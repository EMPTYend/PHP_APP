<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\Controllers\RoomController;
use app\Controllers\AccountController;
use app\Controllers\AuthController;
use app\Core\Router;

if (!isset($router)) {
    die('Router not initialized.');
}

// Основные маршруты
$router->get('/', ['app\Controllers\RoomController', 'index']);
$router->get('/home', ['app\Controllers\RoomController', 'home']);
$router->get('/rooms', ['app\Controllers\RoomController', 'rooms']);
$router->post('/room/search', ['app\Controllers\RoomController', 'search']);
