<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/RoomController.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Core/View.php';


// Получаем подключение к БД
$pdo = \app\Core\Database::connect();

// Инициализация моделей (исправлен namespace)
$userModel = new \app\Models\User($pdo);

// Создание маршрутизатора
$router = new \app\Core\Router();

// Подключаем маршруты
require_once __DIR__ . '/../routes/web.php';

// Обрабатываем запрос (исправленный вызов dispatch)
$router->dispatch($_SERVER['REQUEST_URI']);