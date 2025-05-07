<?php
namespace app\Models;

use app\Core\Model;
use PDOException;

class QueryModel extends Model
{
    protected static string $table = 'query';

    public static function create(array $data): bool
    {
        self::init();
        
        try {
            $sql = "INSERT INTO query 
                    (id_user, name, phone, email, type, peoples, check_in, check_out, status, comments, created_at) 
                    VALUES (:id_user, :name, :phone, :email, :type, :peoples, :check_in, :check_out, :status, :comments, :created_at)";
            
            $stmt = self::$db->prepare($sql);
            $result = $stmt->execute([
                ':id_user' => $data['id_user'], // Может быть NULL
                ':name' => $data['name'],
                ':phone' => $data['phone'],
                ':email' => $data['email'],
                ':type' => $data['type'],
                ':peoples' => $data['peoples'],
                ':check_in' => $data['check_in'],
                ':check_out' => $data['check_out'],
                ':status' => $data['status'],
                ':comments' => $data['comments'],
                ':created_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$result) {
                $errorInfo = self::$db->errorInfo();
                error_log("Booking error: " . print_r($errorInfo, true));
                throw new \RuntimeException("Ошибка при сохранении бронирования");
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            throw new \RuntimeException("Ошибка базы данных при бронировании");
        }
    }
}