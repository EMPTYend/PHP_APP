<?php

namespace app\Core\Middleware;

class AuthMiddleware
{
    public static function handle(): void
    {
        // Проверка CSRF для POST запросов
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                die("CSRF token validation failed");
            }
        }

          // Проверка аутентификации через user в сессии
        if (!isset($_SESSION['user'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: /login');
        exit();
        }
    }   
    
    public static function guest(): void
    {
        if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated']) {
            header('Location: /home');
            exit();
        }
    }
    public static function adminOnly()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }
    }
}