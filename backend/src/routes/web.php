<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/BookingController.php';

session_start();

$dbConfig = include __DIR__ . '/../config/database.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$db = new PDO($dsn, $dbConfig['user'], $dbConfig['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$authController = new AuthController($db);
$bookingController = new BookingController($db);

// Маршруты аутентификации
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/register') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($authController->register($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($authController->login($data['email'], $data['password']));
}

// Маршруты для бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/search-rooms') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($bookingController->searchAvailableRooms($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/book-room') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode($bookingController->createBooking(
        $_SESSION['user_id'],
        $data['room_id'],
        $data['check_in'],
        $data['check_out'],
        $data['adults'],
        $data['children']
    ));
}