<?php
namespace app\Models;

use PDO;
use PDOException;

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Регистрация нового пользователя
     */
    public function register(string $name, string $phone, string $email, string $password): bool
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO user (name, phone, email, password, role) 
                VALUES (:name, :phone, :email, :password, 'user')
            ");
            
            return $stmt->execute([
                ':name' => htmlspecialchars($name),
                ':phone' => htmlspecialchars($phone),
                ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
                ':password' => $hashedPassword
            ]);
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Авторизация пользователя
     */
    public function login(string $email, string $password): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
            $stmt->execute([':email' => filter_var($email, FILTER_SANITIZE_EMAIL)]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
            return null;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Получение пользователя по ID
     */
    public function getUserById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id_user = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("Get user error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Проверка существования email
     */
    public function emailExists(string $email): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id_user FROM user WHERE email = :email");
            $stmt->execute([':email' => filter_var($email, FILTER_SANITIZE_EMAIL)]);
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Обновление данных пользователя
     */
    public function updateUser(int $id, string $name, string $phone, string $email): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE user 
                SET name = :name, phone = :phone, email = :email 
                WHERE id_user = :id
            ");
            
            return $stmt->execute([
                ':id' => $id,
                ':name' => htmlspecialchars($name),
                ':phone' => htmlspecialchars($phone),
                ':email' => filter_var($email, FILTER_SANITIZE_EMAIL)
            ]);
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Удаление пользователя
     */
    public function deleteUser(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user WHERE id_user = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Получение всех пользователей
     */
    public function getAllUsers(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM user");
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return [];
        }
    }
}