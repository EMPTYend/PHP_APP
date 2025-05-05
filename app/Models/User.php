<?php

namespace app\Models;

use app\Core\Model;
use PDO;

class User extends Model
{
    protected static string $table = 'user';

    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare("
            INSERT INTO user (name, phone, email, password, role) 
            VALUES (:name, :phone, :email, :password, :role)
        ");
        
        return $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user'
        ]);
    }

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}