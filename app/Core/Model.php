<?php

namespace app\Core;

use PDO;
use PDOException;

abstract class Model
{
    protected static ?PDO $db = null;
    protected static string $table = ''; // Теперь свойство определено

    protected static function init(): void
    {
        if (self::$db === null) {
            try {
                self::$db = Database::connect();
                
                // Проверка соединения
                self::$db->query('SELECT 1')->fetch();
            } catch (PDOException $e) {
                error_log('Model initialization failed: ' . $e->getMessage());
                throw new \RuntimeException('Database connection failed');
            }
        }
    }

    public static function db(): PDO
{
    if (!self::$db) {
        $config = require __DIR__ . '/../Config/db.php';
        
        try {
            self::$db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                $config['user'],
                $config['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("DB connection failed: " . $e->getMessage());
        }
    }
    
    return self::$db;
}

    public static function all(): array
    {
        if (!isset(static::$table)) {
            throw new \LogicException('Table name not defined in model');
        }

        $stmt = self::db()->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    
}