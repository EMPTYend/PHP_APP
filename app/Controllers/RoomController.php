<?php
namespace app\Controllers;

use app\Core\View;
use app\Core\Database;

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
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function getAllRooms()
    {
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
    }
}