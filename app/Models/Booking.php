<?php
namespace app\Models;

use PDO;
use PDOException;

class Booking
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserBookings(int $userId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM query 
                WHERE id_user = :user_id 
                ORDER BY created_at DESC
                LIMIT 5
            ");
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log("Booking error: " . $e->getMessage());
            return [];
        }
    }

    public static function getStatusText(string $status): string
    {
        return match($status) {
            'pending' => 'Ожидание',
            'confirmed' => 'Подтверждено',
            'cancelled' => 'Отменено',
            default => $status
        };
    }
}