<?php

namespace app\Models;

use app\Core\Model;

class User extends Model
{
    protected static string $table = 'user';

    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

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
}