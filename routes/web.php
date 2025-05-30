<?php

require_once __DIR__ . '/../vendor/autoload.php';


if (!isset($router)) {
    die('Router not initialized.');
}

// Основные маршруты
$router->get('/', ['app\Controllers\RoomController', 'home']);
$router->get('/rooms', ['app\Controllers\RoomController', 'rooms']);
$router->get('/search', ['app\Controllers\RoomController', 'search']);
$router->post('/search_result', ['app\Controllers\RoomController', 'search_result']);

// Аутентификация
$router->get('/login', ['app\Controllers\AuthController', 'showLoginForm']);
$router->post('/login', ['app\Controllers\AuthController', 'login']);
$router->get('/register', ['app\Controllers\AuthController', 'showRegistrationForm']);
$router->post('/register', ['app\Controllers\AuthController', 'register']);
$router->get('/logout', ['app\Controllers\AuthController', 'logout']);

// Личный кабинет (защищенные маршруты)
$router->get('/account', ['app\Controllers\AccountController', 'dashboard']);
$router->get('/account/edit', ['app\Controllers\AccountController', 'editProfile']);
$router->post('/account/update', ['app\Controllers\AccountController', 'updateProfile']);

$router->get('/admin/users', ['app\Controllers\AdminController', 'userManagement']);
$router->get('/admin/users/edit', ['app\Controllers\AdminController', 'editUserForm']);
$router->post('/admin/users/delete', ['app\Controllers\AdminController', 'deleteUser']);
$router->post('/admin/users/update', ['app\Controllers\AdminController', 'updateUser']); 

// Маршруты для управления комнатами
$router->get('/admin/rooms/create', ['app\Controllers\RoomController', 'createRoomForm']);
$router->post('/admin/rooms', ['app\Controllers\RoomController', 'createRoom']);
$router->get('/admin/rooms/created/{id}', ['app\Controllers\RoomController', 'viewCreatedRoom']);

// Маршруты для бронирования
$router->get('/booking', ['app\Controllers\BookingController', 'showForm']);
$router->post('/booking', ['app\Controllers\BookingController', 'createBooking']);

// Маршрут для страницы успеха
$router->get('/booking/success', ['app\Controllers\BookingController', 'successPage']);


