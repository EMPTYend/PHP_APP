<?php
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Инициализация базы данных
$dbConfig = include __DIR__ . '/src/config/database.php';

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $db = new PDO(
        $dsn, 
        $dbConfig['user'], 
        $dbConfig['password'],
        $dbConfig['options'] ?? []
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Маршрутизация
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Обработка маршрутов
if ($request === '/' && $method === 'GET') {
    header('Location: /frontend/public/index.php');
    exit;
} 
elseif ($request === '/api/login' && $method === 'POST') {
    $controller = new App\Controllers\AuthController($db);
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($controller->login($data['email'], $data['password']));
} 
elseif ($request === '/api/register' && $method === 'POST') {
    $controller = new App\Controllers\AuthController($db);
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($controller->register($data));
} 
elseif ($request === '/api/rooms' && $method === 'GET') {
    $controller = new App\Controllers\RoomController($db);
    echo json_encode($controller->getAllRooms());
} 
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}