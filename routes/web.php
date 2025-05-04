<?php

require_once __DIR__ . '/../app/Controllers/RoomController.php';


use app\Controllers\RoomController;
use app\Core\Router; // <-- добавляем импорт класса Router

// перед вызовом нужно убедиться, что $router уже существует
if (!isset($router)) {
    die('Router not initialized.');
}

$router->get('/', [RoomController::class, 'index']);
$router->get('/home', [RoomController::class, 'home']);
$router->get('/room', [RoomController::class, 'getAllRooms']);

$router->post('/room/search', [RoomController::class, 'search']);


