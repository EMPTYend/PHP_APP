<?php

namespace Core;

use PDO;
use PDOException; // Добавьте этот импорт

class Database
{
    private static $pdo;

    public static function connect() {
        if (!self::$pdo) {
            $config = require __DIR__ . '/../Config/db.php';
            
            try {
                self::$pdo = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                    $config['user'],
                    $config['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Исправлена опечатка (EXCEPTION → EXCEPTION)
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}