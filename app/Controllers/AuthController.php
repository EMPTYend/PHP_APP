<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\View;
use app\Models\User;
use Exception;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        View::render('auth/login', [
            'title' => 'Login',
            'error' => $_SESSION['error'] ?? null,
            'old' => $_SESSION['old'] ?? []
        ]);
        unset($_SESSION['error'], $_SESSION['old']);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $_SESSION['old'] = ['email' => $email];

        try {
            $user = User::findByEmail($email);
            
            if (!$user) {
                throw new Exception('Пользователь с таким email не найден');
            }
            
            if (!password_verify($password, $user['password'])) {
                throw new Exception('Неверный пароль');
            }
            
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'], // Добавьте это поле
                'role' => $user['role']
            ];
            // $_SESSION['is_authenticated'] можно удалить, так как проверяем по $_SESSION['user']
            
            header('Location: /account');
            exit();
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /login');
            exit();
        }
    }

    public function showRegistrationForm()
    {
        View::render('auth/register', [
            'title' => 'Register',
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? []
        ]);
        unset($_SESSION['errors'], $_SESSION['old']);
    }

    public function register()
    {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        $_SESSION['old'] = compact('name', 'phone', 'email');

        try {
            // Валидация
            $errors = [];
            if (empty($name)) $errors['name'] = 'Имя обязательно';
            if (empty($phone)) $errors['phone'] = 'Телефон обязателен';
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Некорректный email';
            }
            if (empty($password)) $errors['password'] = 'Пароль обязателен';
            if ($password !== $confirmPassword) $errors['confirm_password'] = 'Пароли не совпадают';
            
            if (User::findByEmail($email)) {
                $errors['email'] = 'Email уже занят';
            }
            
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                throw new Exception('Исправьте ошибки в форме');
            }
            
            // Создание пользователя
            $result = User::create([
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => 'user'
            ]);
            
            if (!$result) {
                throw new Exception('Ошибка при создании пользователя');
            }
            
            // Автоматический вход после регистрации
            $user = User::findByEmail($email);
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            $_SESSION['is_authenticated'] = true;
            
            header('Location: /account');
            exit();
            
        } catch (Exception $e) {
            error_log('Registration error: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /register');
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit();
    }
}