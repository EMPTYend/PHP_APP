<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/RoomController.php';
require_once __DIR__ . '/../app/Config/db.php';
require_once __DIR__ . '/../app/Models/User.php';

$pdo = new PDO(...); // из db.php
$userModel = new User($pdo);

use app\Core\Router;

$router = new Router();  // Создаем объект маршрутизатора

require_once __DIR__ . '/../routes/web.php';  // Подключаем маршруты

// Регистрируем маршруты
$router->get('/', [app\Controllers\RoomController::class, 'index']);
$router->post('/room/search', [app\Controllers\RoomController::class, 'search']);



// Отправляем запрос на обработку

$router->dispatch($_SERVER['REQUEST_URI']);
