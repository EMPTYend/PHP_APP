<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Room.php';

class BookingController {
    private $db;
    private $userModel;
    private $roomModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->roomModel = new Room($db);
    }

    public function searchAvailableRooms($data) {
        $checkIn = $data['check_in'];
        $checkOut = $data['check_out'];
        $typeId = $data['room_type'];
        
        return $this->roomModel->getAvailableRooms($typeId, $checkIn, $checkOut);
    }

    public function createBooking($userId, $roomId, $checkIn, $checkOut, $adults, $children) {
        $room = $this->roomModel->getRoomDetails($roomId);
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        $totalPrice = $room['base_price'] * $days;

        $stmt = $this->db->prepare("
            INSERT INTO bookings 
            (id_user, id_room, check_in, check_out, adults, children, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $userId, 
            $roomId, 
            $checkIn, 
            $checkOut, 
            $adults, 
            $children, 
            $totalPrice
        ]);
    }
}