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
        echo "Room Search Results!";
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return View::render('admin/create_room', ['title' => 'Create Room']);
        }

        // Валидация CSRF токена
        if (!$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            return $this->showFormWithError('Invalid CSRF token', $_POST);
        }

        try {
            $data = $this->prepareRoomData($_POST);
            $errors = $this->validateRoomData($data);
            
            if (!empty($errors)) {
                return $this->showFormWithErrors($errors, $_POST, $_FILES);
            }

            $uploadedFiles = $_FILES;

                return Database::transaction(function() use ($data, $uploadedFiles) {
                    $roomId = Room::create($data);
                    $uploadedImages = $this->processUploadedImages($uploadedFiles['images'] ?? [], $roomId);
                    
                    return View::render('admin/room_created', [
                        'title' => 'Room Created',
                        'success' => 'Room created successfully!',
                        'roomId' => $roomId,
                        'roomData' => $data,
                        'uploadedImages' => $uploadedImages
                    ]);
                });

        } catch (Exception $e) {
            return $this->showFormWithError($e->getMessage(), $_POST, $_FILES);
        }
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

        View::render('admin/room_created', [
            'title' => 'Room #' . $roomId,
            'roomId' => $roomId,
            'roomData' => $room,
            'uploadedImages' => $this->getRoomImages($roomId)
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
        $required = ['type', 'description', 'price'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new \InvalidArgumentException("Поле $field обязательно для заполнения");
            }
        }

        return [
            'type' => htmlspecialchars(trim($data['type'])),
            'description' => htmlspecialchars(trim($data['description'])),
            'price' => (float)$data['price']
        ];
    }

    
}