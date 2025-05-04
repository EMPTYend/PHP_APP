<?php

namespace Core;

use PDO;

class Model
{
    protected static $db;

    public static function db()
    {
        if (!self::$db) {
            $config = require __DIR__ . '/../Config/db.php';
            self::$db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                $config['user'],
                $config['pass']
            );
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$db;
    }

    public static function all()
    {
        $table = static::$table;
        $stmt = self::db()->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
