<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\View;
use app\Models\User;
use app\Core\AuthMiddleware;

class AccountController extends Controller
{
    public function dashboard()
{
    AuthMiddleware::handle();
    
    View::render('account/dashboard', [
        'title' => 'Личный кабинет',
        'user' => $_SESSION['user']
    ]);
}

    public function editProfile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        View::render('account/edit', [
            'title' => 'Редактирование профиля',
            'user' => $_SESSION['user']
        ]);
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        // Обработка данных формы
        $userId = $_SESSION['user']['id_user'];
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Валидация
        if (empty($name) || empty($phone)) {
            $_SESSION['error'] = 'Все поля обязательны для заполнения';
            header('Location: /account/edit');
            exit();
        }

        // Обновление данных
        $stmt = $this->db->prepare("UPDATE user SET name = ?, phone = ? WHERE id_user = ?");
        $stmt->execute([$name, $phone, $userId]);

        // Обновляем данные в сессии
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['phone'] = $phone;

        $_SESSION['success'] = 'Профиль успешно обновлен';
        header('Location: /account');
        exit();
    }
}