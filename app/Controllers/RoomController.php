<?php
namespace app\Controllers;

use app\Core\View;
use app\Core\Database;
use app\Models\Room;
use PDO;
use PDOException;
use Exception;

class RoomController
{
    public function search()
    {
        View::render('search', [
            'title' => 'Search Page',
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function search_result($params)
    {
        // Проверка, если хотя бы один параметр передан
        if (
            empty($params['type']) &&
            empty($params['peoples']) &&
            empty($params['min_price']) &&
            empty($params['max_price']) &&
            empty($params['rooms']) &&
            empty($params['beds'])
        ) {
            echo "At least one search parameter must be provided";
            return;
        }

        $queryParts = [];
        $queryParams = [];

        if (!empty($params['type'])) {
            $queryParts[] = "type = :type";
            $queryParams['type'] = htmlspecialchars($params['type']);
        }
        if (!empty($params['peoples'])) {
            $queryParts[] = "peoples >= :peoples";
            $queryParams['peoples'] = (int)$params['peoples'];
        }
        if (!empty($params['min_price'])) {
            $queryParts[] = "price >= :min_price";
            $queryParams['min_price'] = (float)$params['min_price'];
        }
        if (!empty($params['max_price'])) {
            $queryParts[] = "price <= :max_price";
            $queryParams['max_price'] = (float)$params['max_price'];
        }
        if (!empty($params['rooms'])) {
            $queryParts[] = "rooms >= :rooms";
            $queryParams['rooms'] = (int)$params['rooms'];
        }
        if (!empty($params['beds'])) {
            $queryParts[] = "bed = :beds";
            $queryParams['beds'] = htmlspecialchars($params['beds']);
        }

        $queryCondition = implode(' AND ', $queryParts);

        $pdo = new \PDO("mysql:host=localhost;dbname=hotel_db;charset=utf8", "user", "root");

        $sql = "SELECT * FROM rooms
            WHERE $queryCondition";
            // Removed unnecessary check-in and check-out parameters

        $stmt = $pdo->prepare($sql);
        $stmt->execute($queryParams);

        $rooms = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        View::render('search_result', [
            'title' => 'Search Result',
            'rooms' => $rooms,
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function home()
    {
        $rooms = $this->getAllRooms();
        
        View::render('home', [
            'title' => 'Home Page',
            'rooms' => $rooms,
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function getAllRooms()
    {
        try {
            $db = Database::connect();
            
            $query = "SELECT r.id_room, r.type, r.description, r.id_pictures, r.price, r.created_at, p.road
                    FROM rooms r
                    LEFT JOIN pictures p ON r.id_pictures = p.id_pictures";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error fetching rooms: " . $e->getMessage());
            return [];
        }
    }

    public function createRoomForm()
    {
        // Проверка прав администратора
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }
        
        View::render('admin/create_room', ['title' => 'Create Room']);
    }
    
    public function createRoom()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'type' => $_POST['type'] ?? null,
                'peoples' => $_POST['peoples'] ?? null,
                'rooms' => $_POST['rooms'] ?? null,
                'bed' => $_POST['bed'] ?? null,
                'price' => $_POST['price'] ?? null,
                'description' => $_POST['description'] ?? null,
            ];

            $errors = $this->validateRoomData($data);

            if (empty($errors)) {
                $roomId = Room::create($data);

                if ($roomId) {
                    $storagePath = realpath(dirname(__FILE__) . '/../Views/storage');

                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0777, true);
                    }

                    if (!empty($_FILES['images'])) {
                        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                            $file = [
                                'tmp_name' => $tmpName,
                                'name' => $_FILES['images']['name'][$key]
                            ];

                            if ($_FILES['images']['error'][$key] != UPLOAD_ERR_OK) {
                                echo "Ошибка при загрузке файла: " . $_FILES['images']['error'][$key];
                                continue;
                            }

                            // Генерация уникального имени с использованием `sanitizeFilename()`
                            $originalName = self::sanitizeFilename(pathinfo($file['name'], PATHINFO_FILENAME));
                            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $newFileName = $originalName . '_' . date('Ymd_His') . '.' . $extension;
                            
                            $filePath = $storagePath . '/' . $newFileName;

                            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                                echo "Изображение загружено успешно!";
                                $fileData = [
                                    'name' => $newFileName,
                                    'original_name' => $file['name'],
                                    'path' => $filePath
                                ];
                                
                                Room::saveImageAndAssignToRoom($fileData, $roomId);
                            } else {
                                echo "Ошибка при сохранении изображения!";
                            }
                        }
                    }

                    View::render('admin/room_created', [
                        'success' => 'Room successfully created!',
                        'roomId' => $roomId,
                        'roomData' => $data,
                        'uploadedImages' => Room::getImagesByRoomId($roomId)
                    ]);
                    return;
                }
            }

            View::render('admin/room_create', [
                'errors' => $errors,
                'formData' => $data
            ]);
        }
    }


    private static function sanitizeFilename($filename)
    {
        // Удаляем небезопасные символы
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
        // Ограничиваем длину имени файла
        return substr($sanitized, 0, 100);
    }
    


    private function redirectWithError(string $url, string $error): void
    {
        $_SESSION['error'] = $error;
        header("Location: $url");
        exit;
    }

    private function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    private function prepareRoomData(array $postData): array
    {
        return [
            'type' => trim($postData['type'] ?? ''),
            'peoples' => (int)($postData['peoples'] ?? 1),
            'rooms' => (int)($postData['rooms'] ?? 1),
            'bed' => trim($postData['bed'] ?? ''),
            'price' => (float)($postData['price'] ?? 0),
            'description' => trim($postData['description'] ?? ''),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    private function processUploadedImages(array $files, int $roomId): array
    {
        $uploadedImages = [];
        
        if (!empty($files['tmp_name'])) {
            foreach ($files['tmp_name'] as $key => $tmpName) {
                if ($files['error'][$key] === UPLOAD_ERR_OK) {
                    $image = [
                        'name' => $files['name'][$key],
                        'tmp_name' => $tmpName,
                        'error' => $files['error'][$key]
                    ];
                    
                    if ($path = Room::saveImageAndAssignToRoom($image, $roomId)) {
                        $uploadedImages[] = $path;
                    }
                }
            }
        }
        
        return $uploadedImages;
    }

    private function showFormWithError(string $error, array $formData = [], array $files = [])
    {
        return View::render('admin/create_room', [
            'title' => 'Create Room - Error',
            'error' => $error,
            'formData' => $formData,
            'uploadErrors' => $files['images']['error'] ?? []
        ]);
    }

    private function showFormWithErrors(array $errors, array $formData = [], array $files = [])
    {
        return View::render('admin/create_room', [
            'title' => 'Create Room - Error',
            'errors' => $errors,
            'formData' => $formData,
            'uploadErrors' => $files['images']['error'] ?? []
        ]);
    }

    private function showErrorForm(Exception $e, array $formData, array $uploadErrors)
    {
        return View::render('admin/create_room', [
            'title' => 'Create Room - Error',
            'error' => $e->getMessage(),
            'formData' => $formData,
            'uploadErrors' => $uploadErrors
        ]);
    }

    public function viewCreatedRoom($params)
    {
        $roomId = $params['id'];
        $room = Room::findById($roomId);

        if (!$room) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        // Получение пути для хранения изображений через View
        $storagePath = __DIR__ . '/../Views/storage';  // Укажите нужный путь

        // Передаем данные в шаблон
        View::render('admin/room_created', [
            'title' => 'Room #' . $roomId,
            'roomId' => $roomId,
            'roomData' => $room,
            'uploadedImages' => $this->getRoomImages($roomId),
            'storagePath' => $storagePath  // Путь для хранения изображений
        ]);
    }


    private function getRoomImages($roomId): array
    {
        try {
            // Используем метод query из Database класса
            return Database::query(
                "SELECT p.path 
                FROM pictures p
                JOIN rooms r ON r.id_pictures = p.id_pictures
                WHERE r.id_room = :room_id",
                ['room_id' => $roomId]
            );
        } catch (Exception $e) {
            error_log('Error fetching room images: ' . $e->getMessage());
            return [];
        }
    }

    private function validateRoomData(array $data): array
    {
        $errors = [];

        if (empty($data['type'])) {
            $errors['type'] = 'Room type is required.';
        }

        if (empty($data['peoples']) || !is_numeric($data['peoples'])) {
            $errors['peoples'] = 'Number of people is required.';
        }

        if (empty($data['rooms']) || !is_numeric($data['rooms'])) {
            $errors['rooms'] = 'Number of rooms is required.';
        }

        if (empty($data['bed'])) {
            $errors['bed'] = 'Bed type is required.';
        }

        if (empty($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Price must be a valid number.';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required.';
        }

        return $errors;
    }

    
}