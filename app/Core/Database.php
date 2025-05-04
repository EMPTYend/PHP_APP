<?php

namespace Core;

use PDO;

class Database
{
    private static $pdo;

    public static function connect()
    {
        if (!self::$pdo) {
            $config = require __DIR__ . '/../Config/db.php';
            self::$pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                $config['user'],
                $config['pass']
            );
        }
        return self::$pdo;
    }
}
