<?php

require_once __DIR__ . '/../app/Controllers/RoomController.php';


use app\Controllers\RoomController;
use app\Controllers\AccountController; // <-- добавляем импорт класса AccountController
use app\Core\Router;    // <-- добавляем импорт класса Router

// перед вызовом нужно убедиться, что $router уже существует
if (!isset($router)) {
    die('Router not initialized.');
}

$router->get('/', [RoomController::class, 'index']);
$router->get('/home', [RoomController::class, 'home']);
$router->get('/rooms', [RoomController::class, 'rooms']);


$router->post('/room/search', [RoomController::class, 'search']);

$router->get('/account', [AccountController::class, 'index']);
$router->get('/account/profile', [AccountController::class, 'profile']);
$router->post('/account/update-profile', [AccountController::class, 'updateProfile']);
$router->get('/account/bookings', [AccountController::class, 'bookings']);
$router->get('/account/security', [AccountController::class, 'security']);
$router->post('/account/change-password', [AccountController::class, 'changePassword']);
