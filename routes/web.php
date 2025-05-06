<?php

require_once __DIR__ . '/../vendor/autoload.php';


if (!isset($router)) {
    die('Router not initialized.');
}

// Основные маршруты
$router->get('/', ['app\Controllers\RoomController', 'home']);
$router->get('/rooms', ['app\Controllers\RoomController', 'rooms']);
$router->post('/room/search', ['app\Controllers\RoomController', 'search']);

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

$router->get('/users/edit/([^/]+)', ['app\Controllers\AdminController', 'editUserForm']); 
$router->post('/users/update/([^/]+)', ['app\Controllers\AdminController', 'updateUser']);

$router->get('/test-db', function() {
    try {
        $user = app\Models\User::findByEmail('admin@hotel.com');
        echo '<pre>';
        print_r($user);
        echo '</pre>';
    } catch (Exception $e) {
        echo 'DB Error: ' . $e->getMessage();
    }
});

$router->get('/test-session', function() {
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
});