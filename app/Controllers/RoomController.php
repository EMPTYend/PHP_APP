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
        
        View::render('home', [
            'title' => 'Home Page',
            'rooms' => $rooms
        ]);
    }

    public function getAllRooms()
    {
        // Здесь будет логика получения всех комнат
        echo "All rooms data!";
    }
}
