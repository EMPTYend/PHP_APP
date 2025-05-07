<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\Database;
use app\Models\Room;
use app\Core\View;
use app\Models\User;
use app\Core\Middleware\AuthMiddleware;
use PDO;
use Exception;

class AdminController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
        
        if ($_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Доступ запрещён";
            header('Location: /account');
            exit();
        }
    }

    public function userManagement()
    {
        $users = User::getAllUsers();
        error_log("Users count: " . count($users)); // Логирование
        
        View::render('admin/users', [
            'title' => 'User Management',
            'users' => $users
        ]);
    }

    public function editUserForm()
    {
        $id = $_GET['id'] ?? 0;
        error_log("Editing user ID: " . $id);
        
        $user = User::findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Пользователь не найден";
            header('Location: /admin/users');
            exit();
        }
        
        View::render('admin/edit_user', [
            'title' => 'Редактирование пользователя', 
            'user' => $user
        ]);
    }

    public function updateUser()
    {
        session_start(); // Добавляем старт сессии
        
        // Получаем ID (лучше из POST, если форма отправляет методом POST)
        $id = $_POST['id'] ?? 0;
        
        // CSRF Protection
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Недействительный CSRF-токен";
            header("Location: /admin/users/edit/{$id}");
            exit();
        }

        // Валидация обязательных полей
        $required = ['name', 'phone', 'email', 'role'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = "Поле " . ucfirst($field) . " обязательно для заполнения";
                header("Location: /admin/users/edit/{$id}");
                exit();
            }
        }
        
        $user = User::findById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Пользователь не найден";
            header('Location: /admin/users');
            exit();
        }
        
        // Санитизация данных
        $data = [
            'name' => htmlspecialchars(trim($_POST['name'])),
            'phone' => htmlspecialchars(trim($_POST['phone'])),
            'email' => filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL),
            'role' => htmlspecialchars(trim($_POST['role']))
        ];
        
        if ($data['email'] === false) {
            $_SESSION['error'] = "Некорректный email";
            header("Location: /admin/users/edit/{$id}");
            exit();
        }
        
        // Проверка уникальности email
        if ($data['email'] !== $user['email'] && User::findByEmail($data['email'])) {
            $_SESSION['error'] = "Email уже используется";
            header("Location: /admin/users/edit/{$id}");
            exit();
        }
        
        // Обновление данных
        if (User::updateUser($id, $data)) {
            $_SESSION['success'] = "Данные обновлены";
        } else {
            $_SESSION['error'] = "Ошибка при обновлении данных";
        }
        
        header('Location: /admin/users');
        exit();
    }

    public function deleteUser()
    {
        session_start();
        
        // CSRF проверка
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Ошибка безопасности";
            header('Location: /admin/users');
            exit();
        }

        $id = $_POST['id'] ?? 0;
        
        if (User::delete($id)) {
            $_SESSION['success'] = "Пользователь удален";
        } else {
            $_SESSION['error'] = "Ошибка при удалении";
        }
        
        header('Location: /admin/users');
        exit();
    }

    private function redirectWithError(string $url, string $error): void
    {
        $_SESSION['error'] = $error;
        header("Location: $url");
        exit;
    }

    private function validateCsrfToken(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}