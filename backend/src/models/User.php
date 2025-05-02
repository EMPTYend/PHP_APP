<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register($name, $telefon, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, telefon, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $telefon, $email, $hashedPassword]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($userId) {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id_user = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user && $user['role'] === 'admin';
    }
}