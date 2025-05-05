<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;
use app\Models\Booking;
use InvalidArgumentException;

class AccountController extends Controller
{
    private User $userModel;
    private Booking $bookingModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User($this->db);
        $this->bookingModel = new Booking($this->db);
        
        $this->checkAuthentication();
    }

    private function checkAuthentication(): void
    {
        if (empty($_SESSION['user_id'])) {
            $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
            $this->redirect('/login');
        }
    }


    public function index()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $bookings = $this->bookingModel->getUserBookings($userId);

        $getBookingStatus = function(string $status): string {
            return match($status) {
                'pending' => 'Ожидание',
                'confirmed' => 'Подтверждено',
                'cancelled' => 'Отменено',
                default => $status
            };
        };

        $this->view('account/index', [
            'user' => $user,
            'bookings' => $bookings,
            'getBookingStatus' => $getBookingStatus
        ]);
    }

    public function profile()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        $this->view('account/profile', [
            'user' => $user,
            'errors' => $_SESSION['profile_errors'] ?? [],
            'success' => $_SESSION['success'] ?? null
        ]);
        
        unset($_SESSION['profile_errors'], $_SESSION['success']);
    }

    public function updateProfile()
    {
        $userId = $_SESSION['user_id'];
        $data = $this->sanitizeProfileData($_POST);

        try {
            if ($this->userModel->updateProfile($userId, $data)) {
                $_SESSION['success'] = 'Профиль успешно обновлен';
            }
        } catch (InvalidArgumentException $e) {
            $_SESSION['profile_errors'] = [$e->getMessage()];
        }
        
        $this->redirect('/account/profile');
    }

    private function sanitizeProfileData(array $data): array
    {
        return [
            'name' => htmlspecialchars($data['name'] ?? ''),
            'phone' => htmlspecialchars($data['phone'] ?? ''),
            'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL)
        ];
    }

    public function bookings()
    {
        $bookings = $this->bookingModel->getUserBookings($_SESSION['user_id']);
        $getBookingStatus = fn($s) => $this->bookingModel::getStatusText($s);
        
        $this->view('account/bookings', [
            'bookings' => $bookings,
            'getBookingStatus' => $getBookingStatus
        ]);
    }

    public function security()
    {
        $this->view('account/security', [
            'errors' => $_SESSION['security_errors'] ?? [],
            'success' => $_SESSION['success'] ?? null
        ]);
        
        unset($_SESSION['security_errors'], $_SESSION['success']);
    }

    public function changePassword()
    {
        $userId = $_SESSION['user_id'];
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        
        try {
            if ($this->userModel->changePassword($userId, $current, $new)) {
                $_SESSION['success'] = 'Пароль успешно изменен';
            }
        } catch (InvalidArgumentException $e) {
            $_SESSION['security_errors'] = [$e->getMessage()];
        }
        
        $this->redirect('/account/security');
    }
}