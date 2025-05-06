<?php
// public/index.php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AccountController.php';
require_once __DIR__ . '/../app/Controllers/RoomController.php';
require_once __DIR__ . '/../app/Core/View.php';


// Получаем подключение к БД
$pdo = \app\Core\Database::connect();

// Создание маршрутизатора
$router = new \app\Core\Router();

// Подключаем маршруты
require_once __DIR__ . '/../routes/web.php';

// Обрабатываем запрос (исправленный вызов dispatch)
$router->dispatch($_SERVER['REQUEST_URI']);