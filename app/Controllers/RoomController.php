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
        // Здесь будет логика получения всех комнат
        // Например, обращение к модели Room
        $roomModel = new \app\Models\Room();
        return $roomModel->getAll();
    }

    
}