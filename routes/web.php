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

// Маршруты аутентификации
$router->get('/login', ['app\Controllers\AuthController', 'login']);
$router->post('/login', ['app\Controllers\AuthController', 'authenticate']);
$router->get('/logout', ['app\Controllers\AuthController', 'logout']);

// Маршруты аккаунта
$router->get('/account', ['app\Controllers\AccountController', 'index']);
$router->get('/account/profile', ['app\Controllers\AccountController', 'profile']);
$router->post('/account/update-profile', ['app\Controllers\AccountController', 'updateProfile']);
$router->get('/account/bookings', ['app\Controllers\AccountController', 'bookings']);
$router->get('/account/security', ['app\Controllers\AccountController', 'security']);
$router->post('/account/change-password', ['app\Controllers\AccountController', 'changePassword']);