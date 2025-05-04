<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;
use app\Models\Booking;

class AccountController extends Controller
{
    private User $userModel;
    private Booking $bookingModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User($this->db);
        $this->bookingModel = new Booking($this->db);
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    // ... остальные методы без изменений ...
}