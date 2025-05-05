<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User($this->db);
    }

    // Показ формы входа
    public function showLogin()
    {
        $this->view('Auth/login', [
            'errors' => $_SESSION['login_errors'] ?? []
        ]);
        unset($_SESSION['login_errors']);
    }

    // Обработка входа
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            $user = $this->userModel->login($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $this->redirect('/account');
            } else {
                throw new \InvalidArgumentException('Неверный email или пароль');
            }
        } catch (\Exception $e) {
            $_SESSION['login_errors'] = [$e->getMessage()];
            $this->redirect('/login');
        }
    }

    // Выход из системы
    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}