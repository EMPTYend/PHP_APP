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
            'error' => $_SESSION['error'] ?? null
        ]);
        unset($_SESSION['error']);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $user = User::findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                $_SESSION['is_authenticated'] = true;
                header('Location: /account');
                exit();
            }
            
            throw new Exception('Invalid credentials');
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /login');
            exit();
        }
    }

    public function showRegistrationForm()
    {
        View::render('auth/register', ['title' => 'Register']);
    }

    public function register()
{
    // Получаем данные из формы
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Хешируем пароль
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Пробуем сохранить пользователя
    try {
        $result = User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $hashedPassword
        ]);
        
        if ($result) {
            $_SESSION['user'] = User::findByEmail($email);
            header('Location: /account');
            exit();
        } else {
            throw new Exception('Ошибка при сохранении пользователя');
        }
    } catch (Exception $e) {
        // Логируем ошибку и показываем пользователю
        error_log($e->getMessage());
        $_SESSION['error'] = 'Ошибка регистрации: ' . $e->getMessage();
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