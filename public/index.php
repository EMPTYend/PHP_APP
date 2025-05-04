<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/Database.php';  // Подключаем класс Database
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Controllers/RoomController.php';
require_once __DIR__ . '/../app/Models/User.php';

// Получаем подключение к БД через Singleton
$pdo = \Core\Database::connect();

// Инициализация моделей
$userModel = new app\Models\User($pdo);

// Создание и настройка маршрутизатора
$router = new app\Core\Router();

// Подключаем маршруты
require_once __DIR__ . '/../routes/web.php';

// Обрабатываем запрос
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);