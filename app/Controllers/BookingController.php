<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Core\Middleware\AuthMiddleware;
use app\Models\QueryModel;
use app\Core\View;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(AuthMiddleware::class);
    }

    public function showForm()
    {
        View::render('booking/form', [
            'title' => 'Форма бронирования',
            'csrf_token' => $this->generateCsrfToken(),
            'user' => [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'phone' => $_SESSION['user_phone'] ?? ''
            ],
            'errors' => $_SESSION['booking_errors'] ?? [],
            'old' => $_SESSION['booking_old_data'] ?? []
        ], 'layout');
    }

    public function createBooking()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        try {
            // CSRF проверка
            if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new \Exception('Ошибка безопасности. Пожалуйста, обновите страницу.');
            }

            // Подготовка данных с проверкой id_user
            $data = [
                'id_user' => isset($_SESSION['user']['id_user']) ? (int)$_SESSION['user']['id_user'] : null,
                'name' => htmlspecialchars(trim($_POST['name'])),
                'phone' => preg_replace('/[^0-9+]/', '', trim($_POST['phone'])),
                'email' => filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL),
                'type' => htmlspecialchars(trim($_POST['type'])),
                'peoples' => (int)$_POST['peoples'],
                'check_in' => date('Y-m-d', strtotime($_POST['check_in'])),
                'check_out' => date('Y-m-d', strtotime($_POST['check_out'])),
                'status' => 'pending',
                'comments' => htmlspecialchars(trim($_POST['comments'] ?? ''))
            ];

            // Валидация
            if ($data['email'] === false) {
                throw new \Exception("Некорректный email");
            }

            if ($data['peoples'] <= 0 || $data['peoples'] > 10) {
                throw new \Exception("Количество людей должно быть от 1 до 10");
            }

            // Сохранение
            \app\Models\QueryModel::create($data);
            
            $_SESSION['booking_success'] = true;
            $this->redirect('/booking/success');

        } catch (\Exception $e) {
            $_SESSION['booking_errors'] = [$e->getMessage()];
            $_SESSION['booking_old_data'] = $_POST;
            $this->redirect('/booking');
        }
    }
    private function prepareBookingData(array $post): array
    {
        return [
            'id_user' => $_SESSION['user']['id_user'] ?? null,
            'name' => trim($post['name'] ?? ''),
            'email' => filter_var(trim($post['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'phone' => preg_replace('/[^0-9+]/', '', $post['phone'] ?? ''),
            'type' => trim($post['type'] ?? ''),
            'peoples' => (int)($post['peoples'] ?? 1),
            'check_in' => $post['check_in'] ?? '',
            'check_out' => $post['check_out'] ?? '',
            'comments' => trim($post['comments'] ?? ''),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    private function validateBookingData(array $data): array
    {
        $errors = [];
        
        if (empty($data['name'])) $errors[] = 'Укажите ФИО';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
        if (empty($data['phone'])) $errors[] = 'Укажите телефон';
        if (empty($data['type'])) $errors[] = 'Выберите тип номера';
        if ($data['peoples'] < 1 || $data['peoples'] > 10) $errors[] = 'Некорректное количество гостей';
        if (empty($data['check_in']) || empty($data['check_out'])) $errors[] = 'Укажите даты заезда/выезда';
        elseif ($data['check_in'] >= $data['check_out']) $errors[] = 'Дата выезда должна быть позже даты заезда';
        
        return $errors;
    }

    public function successPage()
    {
        if (empty($_SESSION['booking_success'])) {
            $this->redirect('/booking');
        }

        unset($_SESSION['booking_success']);
        View::render('booking/success', [
            'title' => 'Бронирование успешно создано',
            'message' => 'Ваша заявка принята! Мы свяжемся с вами для подтверждения.'
        ], 'layout');
    }

    private function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private function validateAndSanitizeInput(): array
    {
        return [
            'id_user' => $_SESSION['user']['id_user'],
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL),
            'type' => trim($_POST['type'] ?? ''),
            'peoples' => (int)($_POST['peoples'] ?? 0),
            'check_in' => $_POST['check_in'] ?? '',
            'check_out' => $_POST['check_out'] ?? '',
            'comments' => trim($_POST['comments'] ?? '')
        ];
    }
}