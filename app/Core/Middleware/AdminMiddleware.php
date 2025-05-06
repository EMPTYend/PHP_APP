<?php

namespace app\Core\Middleware;

class AdminMiddleware
{
    public static function handle()
    {
        session_start();

        // Проверка, авторизован ли пользователь
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        // Проверка, является ли пользователь администратором
        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "Доступ запрещён. У вас нет прав администратора.";
            exit();
        }
    }
}
