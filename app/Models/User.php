<?php
namespace app\Models;

use PDO;
use PDOException;
use InvalidArgumentException;

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Регистрация нового пользователя с валидацией
     */
    public function register(string $name, string $phone, string $email, string $password): array
    {
        // Валидация данных
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Пароль должен содержать минимум 8 символов");
        }

        if ($this->emailExists($email)) {
            throw new InvalidArgumentException("Email уже используется");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO user (name, phone, email, password, role) 
            VALUES (:name, :phone, :email, :password, 'user')
        ");
        
        $stmt->execute([
            ':name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            ':phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
            ':email' => filter_var($email, FILTER_SANITIZE_EMAIL),
            ':password' => $hashedPassword
        ]);

        return [
            'id' => $this->pdo->lastInsertId(),
            'name' => $name,
            'email' => $email
        ];
    }

    /**
     * Авторизация пользователя
     */
    public function login(string $email, string $password): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id_user, name, email, password, role 
            FROM user 
            WHERE email = :email
        ");
        
        $stmt->execute([':email' => filter_var($email, FILTER_SANITIZE_EMAIL)]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Удаляем пароль из результата
            return $user;
        }

        return null;
    }

    /**
     * Смена пароля
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        if (strlen($newPassword) < 8) {
            throw new InvalidArgumentException("Новый пароль слишком короткий");
        }

        $user = $this->getUserById($userId);
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new InvalidArgumentException("Текущий пароль неверен");
        }

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE user SET password = ? WHERE id_user = ?");
        return $stmt->execute([$newHash, $userId]);
    }

    /**
     * Получение пользователя по ID (без пароля)
     */
    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT id_user, name, phone, email, role, created_at 
            FROM user 
            WHERE id_user = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Проверка существования email
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT 1 FROM user WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => filter_var($email, FILTER_SANITIZE_EMAIL)]);
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Обновление данных пользователя
     */
    public function updateProfile(int $id, array $data): bool
    {
        $allowedFields = ['name', 'phone', 'email'];
        $updates = [];
        $params = [':id' => $id];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = :$field";
                $params[":$field"] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }

        if (empty($updates)) {
            return false;
        }

        $query = "UPDATE user SET " . implode(', ', $updates) . " WHERE id_user = :id";
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Получение всех пользователей (без паролей)
     */
    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("
            SELECT id_user, name, email, role, created_at 
            FROM user
        ");
        return $stmt->fetchAll() ?: [];
    }
}