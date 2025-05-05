<?php

namespace app\Controllers;

use app\Core\View;

class RoomController
{
    public function index()
    {
        echo "Welcome to the Room Index!";
    }

    public function search()
    {
        echo "Room Search Results!";
    }

    public function home()
    {
        // Инициализируем переменную перед использованием
        $rooms = []; // Пустой массив или данные из модели
        
        // Пример с mock-данными:
        $rooms = [
            ['id' => 1, 'name' => 'Deluxe Room'],
            ['id' => 2, 'name' => 'Standard Room']
        ];
        
        // Или получаем данные из модели:
        // $rooms = $this->getAllRooms();
        
        View::render('home', [
            'title' => 'Home Page',
            'rooms' => $rooms // Теперь переменная определена
        ]);
    }

    public function getAllRooms()
    {
        // Здесь будет логика получения всех комнат
        // Например, обращение к модели Room
        return [
            ['id' => 1, 'name' => 'Deluxe Room'],
            ['id' => 2, 'name' => 'Standard Room']
        ];
    }

    
}