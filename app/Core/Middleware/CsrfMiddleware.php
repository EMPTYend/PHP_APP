<?php

namespace app\Core\Middleware;

class CsrfMiddleware
{
    public function handle()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $_SESSION['error'] = "Недействительный CSRF-токен";
                header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '/');
                exit();
            }
        }
    }
}