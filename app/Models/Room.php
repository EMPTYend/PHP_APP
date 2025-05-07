<?php

namespace app\Models;

use app\Core\Model;
use PDO;

class Room extends Model
{
    protected static string $table = 'rooms';

    protected static $primaryKey = 'id_room';

    public static function saveImageAndAssignToRoom(array $image, int $roomId): ?string
    {
        $storagePath = __DIR__ . '/../../public/storage/';
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Проверка типа изображения
        $imageType = mime_content_type($image['tmp_name']);
        if (strpos($imageType, 'image/') !== 0) {
            // Неверный тип файла
            return null;
        }

        $imageName = uniqid() . '_' . basename($image['name']);
        $imagePath = $storagePath . $imageName;

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            return null;
        }

        $relativePath = 'storage/' . $imageName;

        // Вставка данных в таблицу pictures
        $stmt = self::db()->prepare("INSERT INTO pictures (road, created_at) VALUES (:road, :created_at)");
        if (!$stmt->execute(['road' => $relativePath, 'created_at' => date('Y-m-d H:i:s')])) {
            echo "Ошибка вставки в pictures: " . implode(", ", $stmt->errorInfo());
            return null;
        }

        $pictureId = self::db()->lastInsertId();

        // Обновление записи в таблице rooms
        $stmt = self::db()->prepare("UPDATE rooms SET id_pictures = :id_pictures WHERE id_room = :id_room");
        if (!$stmt->execute([
            'id_pictures' => $pictureId,
            'id_room' => $roomId
        ])) {
            echo "Ошибка обновления в rooms: " . implode(", ", $stmt->errorInfo());
            return null;
        }

        return $relativePath;
    }

    
    public static function create(array $data): int
    {
        $stmt = self::db()->prepare("
            INSERT INTO rooms (type, peoples, rooms, bed, price, description, created_at, updated_at) 
            VALUES (:type, :peoples, :rooms, :bed, :price, :description, :created_at, :updated_at)
        ");
        
        $stmt->execute([
            'type' => $data['type'],
            'peoples' => $data['peoples'],
            'rooms' => $data['rooms'],
            'bed' => $data['bed'],
            'price' => $data['price'],
            'description' => $data['description'],
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at']
        ]);

        return self::db()->lastInsertId();
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
