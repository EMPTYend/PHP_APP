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
    $query = "SELECT id_room, type, description, id_pictures, created_at FROM rooms"; // SQL-запрос для получения данных
    return $db->query($query); // Возвращаем массив данных
}

    
}