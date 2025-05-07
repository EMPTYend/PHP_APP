<?php
namespace app\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../Config/db.php';
            try {
                self::$instance = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
                    $config['user'],
                    $config['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                error_log('Ошибка подключения к базе данных: ' . $e->getMessage());
                throw new Exception('Ошибка подключения к базе данных: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
    
    private function __construct() {}
    private function __clone() {}

    public static function query(string $sql, array $params = []): array
    {
        try {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Логируем запрос и параметры для диагностики
            error_log('Ошибка выполнения запроса: ' . $e->getMessage());
            error_log('SQL Query: ' . $sql);
            error_log('Parameters: ' . json_encode($params));
            throw new Exception('Ошибка выполнения запроса: ' . $e->getMessage());
        }
    }
}