<?php
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/vendor/autoload.php';

session_start();

// Инициализация базы данных
$dbConfig = include __DIR__ . '/src/config/database.php';
try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $db = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Маршрутизация
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case '/':
        header('Location: /frontend/public/index.php');
        break;
        
    case '/api/login' && $method === 'POST':
        require __DIR__ . '/src/controllers/AuthController.php';
        $controller = new AuthController($db);
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($controller->login($data['email'], $data['password']));
        break;
        
    case '/api/register' && $method === 'POST':
        require __DIR__ . '/src/controllers/AuthController.php';
        $controller = new AuthController($db);
        $data = json_decode(file_get_contents('php://input'), true);
        echo json_encode($controller->register($data));
        break;
        
    case '/api/rooms' && $method === 'GET':
        require __DIR__ . '/src/controllers/RoomController.php';
        $controller = new RoomController($db);
        echo json_encode($controller->getAllRooms());
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}