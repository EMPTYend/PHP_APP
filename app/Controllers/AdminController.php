<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\View;
use app\Models\User;
use app\Core\AuthMiddleware;

class AdminController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
        $this->checkAdmin();
    }

    private function checkAdmin()
    {
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /account');
            exit();
        }
    }

    public function userManagement()
    {
        $users = User::getAllUsers();
        
        View::render('admin/users', [
            'title' => 'User Management',
            'users' => $users
        ]);
    }

    public function editUserForm($email)
{
    $user = User::findByEmail($email);
    
    if (!$user) {
        $_SESSION['error'] = "User not found";
        header('Location: /admin/users');
        exit();
    }
    
    View::render('admin/edit_user', [
        'title' => 'Edit User',
        'user' => $user
    ]);
}

    public function updateUser($email)
    {   
        $newEmail = $_POST['email'];
    
        // Проверяем, не занят ли новый email другим пользователем
        if ($newEmail !== $email) {
            $existingUser = User::findByEmail($newEmail);
            if ($existingUser) {
                $_SESSION['error'] = "Email already exists";
                header("Location: /admin/users/edit/" . urlencode($email));
                exit();
            }
        }
        
        $data = [
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'], // Новый email (может быть таким же)
            'role' => $_POST['role']
        ];
        
        // Сначала найдем пользователя по старому email
        $user = User::findByEmail($email);
        if (!$user) {
            $_SESSION['error'] = "User not found";
            header('Location: /admin/users');
            exit();
        }
        
        // Обновляем данные
        User::updateUser($user['id_user'], $data); // Обновляем по ID, но ищем по email
        
        header('Location: /admin/users');
    }

    public function deleteUser($id)
    {
        User::deleteUser($id);
        header('Location: /admin/users');
        if ($id == $_SESSION['user']['id_user']) {
            $_SESSION['error'] = "You cannot delete yourself!";
            header('Location: /admin/users');
            exit();
        }
    }
    
}