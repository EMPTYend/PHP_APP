<?php
namespace App\Controllers;

class AuthController {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function login(string $email, string $password): array {
        // Реализация входа
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'Invalid credentials'];
    }

    public function register(array $data): array {
        // Валидация данных
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
        );
        
        if ($stmt->execute([$data['name'], $data['email'], $hashedPassword])) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'Registration failed'];
    }
}