<?php

namespace app\Controllers;

use app\Core\View;

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
        'user' => $_SESSION['user'] ?? null // Передаем пользователя, если он авторизован
    ]);
}

public function getAllRooms()
{
    $db = new \app\Core\Database();
    $query = "SELECT r.id_room, r.type, r.description, r.id_pictures, r.price, r.created_at, p.road
            FROM rooms r
            LEFT JOIN pictures p ON r.id_pictures = p.id_pictures;"; // SQL-запрос для получения данных с таблицы pictures
    return $db->query($query); // Возвращаем массив данных
}

    
}