<?php
$host = 'db';        // Имя сервиса в docker-compose.yml
$db   = 'hotel_db';  // Название БД
$user = 'user';      // Пользователь БД
$pass = 'secret';    // Пароль (должен совпадать с MYSQL_PASSWORD в docker-compose)
$charset = 'utf8mb4'; // Рекомендуемая кодировка для MySQL

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Логируем ошибку и выводим user-friendly сообщение
    error_log("Connection failed: " . $e->getMessage());
    throw new PDOException("Database connection error. Please try again later.");
}