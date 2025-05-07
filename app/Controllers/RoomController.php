<?php
namespace app\Controllers;

use app\Core\View;
use app\Core\Database;
use app\Models\Room;
use PDO;
use PDOException;

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

    public function createRoom()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::render('rooms/create', ['title' => 'Create Room']);
            return;
        }

        try {
            $data = $this->validateRoomData($_POST);
            $pictureId = $this->handleFileUpload($_FILES['picture'] ?? null);
            
            $db = Database::connect();
            $db->beginTransaction();

            // Вставляем данные комнаты
            $query = "INSERT INTO rooms (type, description, id_pictures, price, created_at, updated_at) 
                     VALUES (:type, :description, :id_pictures, :price, :created_at, :updated_at)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'type' => $data['type'],
                'description' => $data['description'],
                'id_pictures' => $pictureId,
                'price' => $data['price'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $db->commit();
            $_SESSION['success'] = 'Комната успешно создана';
            header('Location: /rooms');
            exit;

        } catch (\Exception $e) {
            if (isset($db)) {
                $db->rollBack();
            }
            $_SESSION['error'] = $e->getMessage();
            header('Location: /rooms/create');
            exit;
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

    private function handleFileUpload(?array $file): ?int
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/storage/';
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \RuntimeException('Ошибка при загрузке файла');
        }

        $relativePath = 'storage/' . $fileName;
        $db = Database::connect();
        
        $stmt = $db->prepare("INSERT INTO pictures (road) VALUES (:road)");
        $stmt->execute(['road' => $relativePath]);
        
        return $db->lastInsertId();
    }
}