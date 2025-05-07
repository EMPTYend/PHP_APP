<?php
namespace app\Controllers;

use app\Core\View;
<<<<<<< HEAD
use app\Core\Database;
=======
use app\Models\Room; // Подключаем модель Room
use PDO;
>>>>>>> b107a560a2a2cb567434304a42feb2b66947774c

class RoomController
{
    public function search()
    {
        echo "Room Search Results!";
    }

    public function home()
    {
        // Получаем комнаты (без проверки аутентификации)
        $rooms = $this->getAllRooms();
        
        View::render('home', [
            'title' => 'Home Page',
            'rooms' => $rooms,
<<<<<<< HEAD
            'user' => $_SESSION['user'] ?? null
=======
            'user' => $_SESSION['user'] ?? null // Передаем пользователя, если он авторизован
>>>>>>> b107a560a2a2cb567434304a42feb2b66947774c
        ]);
    }

    public function getAllRooms()
    {
<<<<<<< HEAD
        try {
            // Используем статический метод connect() вместо создания нового экземпляра
            $db = Database::connect();
            
            $query = "SELECT r.id_room, r.type, r.description, r.id_pictures, r.price, r.created_at, p.road
                    FROM rooms r
                    LEFT JOIN pictures p ON r.id_pictures = p.id_pictures";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (\PDOException $e) {
            // Логируем ошибку и возвращаем пустой массив
            error_log("Error fetching rooms: " . $e->getMessage());
            return [];
        }
=======
        $db = new \app\Core\Database();
        $query = "SELECT r.id_room, r.type, r.description, r.id_pictures, r.price, r.created_at, p.road
                FROM rooms r
                LEFT JOIN pictures p ON r.id_pictures = p.id_pictures;"; // SQL-запрос для получения данных с таблицы pictures
        return $db->query($query); // Возвращаем массив данных
    }

    public function createRoom()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            // Проверяем, был ли загружен файл
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/storage/';
                $fileName = uniqid() . '_' . basename($_FILES['picture']['name']);
                $filePath = $uploadDir . $fileName;

                // Перемещаем загруженный файл в папку public/storage
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $filePath)) {
                $relativePath = 'storage/' . $fileName;

                // Сохраняем путь к картинке в таблицу pictures
                $db = new \app\Core\Database();
                $query = "INSERT INTO pictures (road) VALUES (:road)";
                $db->query($query, ['road' => $relativePath]);

                // Получаем ID только что добавленной записи
                $pictureId = $db->lastInsertId();

                // Сохраняем остальные данные в таблицу rooms
                $query = "INSERT INTO rooms (type, description, id_pictures, price, created_at, updated_at) 
                    VALUES (:type, :description, :id_pictures, :price, :created_at, :updated_at)";
                $db->query($query, [
                    'type' => $data['type'],
                    'description' => $data['description'],
                    'id_pictures' => $pictureId,
                    'price' => $data['price'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at']
                ]);
                } else {
                throw new \Exception('Ошибка при загрузке файла.');
                }
            } else {
                throw new \Exception('Файл не был загружен.');
            }
            header('Location: /account'); // Замените на нужный URL
            exit;

        }
        
        View::render('rooms/rooms', [
            'title' => 'View Room'
        ]);
>>>>>>> b107a560a2a2cb567434304a42feb2b66947774c
    }
}