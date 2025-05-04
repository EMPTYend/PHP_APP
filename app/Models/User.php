<?php

class User
{
    // Подключение к базе данных
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Регистрация нового пользователя
    public function register($name, $phone, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare("
            INSERT INTO user (name, phone, email, password, role) 
            VALUES (:name, :phone, :email, :password, 'user')
        ");
        
        return $stmt->execute([
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }

    // Авторизация пользователя
    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    // Получение пользователя по ID
    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id_user = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Проверка существования email
    public function emailExists($email)
    {
        $stmt = $this->pdo->prepare("SELECT id_user FROM user WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() !== false;
    }

    // Обновление данных пользователя
    public function updateUser($id, $name, $phone, $email)
    {
        $stmt = $this->pdo->prepare("
            UPDATE user 
            SET name = :name, phone = :phone, email = :email 
            WHERE id_user = :id
        ");
        
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':phone' => $phone,
            ':email' => $email
        ]);
    }

    // Удаление пользователя
    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE id_user = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Получение всех пользователей (для админки)
    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM user");
        return $stmt->fetchAll();
    }
}