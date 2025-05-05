<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\View;
use app\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        View::render('auth/login', ['title' => 'Login']);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['is_authenticated'] = true;
            
            header('Location: /home');
            exit();
        }

        View::render('auth/login', [
            'title' => 'Login',
            'error' => 'Invalid credentials'
        ]);
    }

    public function showRegistrationForm()
    {
        View::render('auth/register', ['title' => 'Register']);
    }

    public function register()
    {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Валидация
        $errors = [];
        if (empty($name)) $errors['name'] = 'Name is required';
        if (empty($phone)) $errors['phone'] = 'Phone is required';
        if (empty($email)) $errors['email'] = 'Email is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email format';
        if (empty($password)) $errors['password'] = 'Password is required';
        if ($password !== $confirmPassword) $errors['confirm_password'] = 'Passwords do not match';

        if (User::findByEmail($email)) {
            $errors['email'] = 'Email already exists';
        }

        if (!empty($errors)) {
            View::render('auth/register', [
                'title' => 'Register',
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        // Создание пользователя
        $userData = [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'user'
        ];

        User::create($userData);

        // Автоматический вход после регистрации
        $_SESSION['user'] = User::findByEmail($email);
        $_SESSION['is_authenticated'] = true;

        header('Location: /home');
        exit();
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit();
    }
}