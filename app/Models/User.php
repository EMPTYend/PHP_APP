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

    public static function findById(int $id): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }


    public static function findByEmail(string $email): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getAllUsers(): array
    {
        $stmt = self::db()->query("SELECT * FROM user");
        return $stmt->fetchAll();
    }

    public static function updateUser(int $id, array $data): bool
    {
        $stmt = self::db()->prepare("
            UPDATE user 
            SET name = :name, 
                phone = :phone, 
                email = :email, 
                role = :role 
            WHERE id_user = :id
        ");
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role']
        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM user WHERE id_user = ?");
        return $stmt->execute([$id]);
    }
    
}