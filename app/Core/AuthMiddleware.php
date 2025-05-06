<?php

namespace app\Core;

class AuthMiddleware
{
    public static function handle(): void
    {
        if (!isset($_SESSION['is_authenticated']) || !$_SESSION['is_authenticated']) {
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