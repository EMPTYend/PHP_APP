<?php

namespace App\Models;

use app\Core\Model;
use PDO;

class Room extends Model
{
    protected static $table = 'rooms';

    
    public static function create(array $data): bool
    {
        $stmt = self::db()->prepare("
            INSERT INTO user (name, people, type, description, id_pictires, price, created_at, updated_at) 
            VALUES (:type, :peoples, :description, :bed, :id_pictures, :price, :created_at, :updated_at)
        ");
        
        return $stmt->execute([
            'type' => $data['type'],
            'peoples' => $data['peoples'],
            'description' => $data['description'],
            'bed' => $data['bed'],
            'id_pictures' => $data['id_pictures'],
            'price' => $data['price'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],

        ]);
    }

    public static function findById(int $id): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM rooms WHERE id_room = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function findByType(string $type): ?array
    {
        $stmt = self::db()->prepare("SELECT * FROM rooms WHERE type = ?");
        $stmt->execute([$type]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getAllRooms(): array
    {
        $stmt = self::db()->query("SELECT * FROM rooms");
        return $stmt->fetchAll();
    }

    public static function updateRoom(int $id, array $data): bool
    {
        $stmt = self::db()->prepare("
            UPDATE rooms
            SET type = :type, 
                peoples = :peoples, 
                description = :description, 
                bed = :bed, 
                id_pictures = :id_pictures, 
                price = :price, 
                created_at = :created_at, 
                updated_at = :updated_at
            WHERE id_room = :id 
            SET 
                type = :type, 
                peoples = :peoples, 
                description = :description, 
                bed = :bed, 
                id_pictures = :id_pictures, 
                price = :price, 
                created_at = :created_at, 
                updated_at = :updated_at
            WHERE id_room = :id
           
        ");
        
        return $stmt->execute([
            'id' => $id,
            'type' => $data['type'],
            'peoples' => $data['peoples'],
            'description' => $data['description'],
            'bed' => $data['bed'],
            'id_pictures' => $data['id_pictures'],
            'price' => $data['price'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],

        ]);
    }

    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM rooms WHERE id_room = ?");
        return $stmt->execute([$id]);
    }
    
}
