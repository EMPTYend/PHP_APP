<?php

namespace app\Models;

use app\Core\Model;
use PDO;
use PDOException;
use finfo;
use RuntimeException;

class Room extends Model
{
    protected static string $table = 'rooms';

    protected static $primaryKey = 'id_room';

    public static function saveImageAndAssignToRoom(array $image, int $roomId): ?string
    {
        // Проверка временного файла
        if (!isset($image['tmp_name']) || !file_exists($image['tmp_name'])) {
            error_log('Temporary file error: ' . print_r($image, true));
            return null;
        }

        // Создаем директорию для комнаты
        $uploadDir = __DIR__ . '/../../public/storage/rooms/' . $roomId . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Получаем оригинальное имя и расширение
        $originalName = pathinfo($image['name'], PATHINFO_FILENAME);
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);

        // Генерируем уникальное имя файла
        $filename = self::generateUniqueFilename($uploadDir, $originalName, $extension);
        $destination = $uploadDir . $filename;

        // Перемещаем файл
        if (!move_uploaded_file($image['tmp_name'], $destination)) {
            error_log("Failed to move uploaded file to: $destination");
            return null;
        }

        // Устанавливаем права
        chmod($destination, 0644);

        // Сохраняем в БД
        $relativePath = 'storage/rooms/' . $roomId . '/' . $filename;
        
        try {
            $db = self::db();
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO pictures (path, room_id, original_name, created_at) VALUES (?, ?, ?, ?)");
            $stmt->execute([$relativePath, $roomId, $originalName . '.' . $extension, date('Y-m-d H:i:s')]);
            
            $pictureId = $db->lastInsertId();

            $stmt = $db->prepare("UPDATE rooms SET id_pictures = ? WHERE id_room = ?");
            $stmt->execute([$pictureId, $roomId]);

            $db->commit();
            return $relativePath;

        } catch (PDOException $e) {
            $db->rollBack();
            if (file_exists($destination)) {
                unlink($destination);
            }
            error_log('Database error: ' . $e->getMessage());
            return null;
        }
    }

    private static function generateUniqueFilename(string $directory, string $originalName, string $extension): string
    {
        $counter = 1;
        $filename = $originalName . '.' . $extension;
        $baseName = $originalName;
        
        // Проверяем существование файла и добавляем суффикс при необходимости
        while (file_exists($directory . $filename)) {
            $filename = $baseName . '_' . $counter . '.' . $extension;
            $counter++;
        }
        
        return $filename;
    }

    private static function sanitizeFilename(string $filename): string
    {
        // Удаляем небезопасные символы
        $filename = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $filename);
        // Ограничиваем длину имени
        return substr($filename, 0, 100);
    }

    private static function getExtensionByMime(string $mimeType): string
    {
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp'
        ];
        
        return $map[$mimeType] ?? 'bin';
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

    public static function getImagesByRoomId(int $roomId): array
    {
        $stmt = self::db()->prepare("
            SELECT p.road 
            FROM pictures p 
            INNER JOIN rooms r ON r.id_pictures = p.id_pictures 
            WHERE r.id_room = :roomId
        ");
        
        $stmt->execute(['roomId' => $roomId]);

        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $images ?: [];
    }


    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM rooms WHERE id_room = ?");
        return $stmt->execute([$id]);
    }
    
}
