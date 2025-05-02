<?php

class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllRoomTypes() {
        $stmt = $this->db->query("SELECT * FROM room_types");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableRooms($typeId, $checkIn, $checkOut) {
        $stmt = $this->db->prepare("
            SELECT r.*, rt.type_name, rt.base_price 
            FROM rooms r
            JOIN room_types rt ON r.id_type = rt.id_type
            WHERE r.id_type = ? AND r.id_room NOT IN (
                SELECT b.id_room FROM bookings b
                WHERE b.status = 'confirmed' 
                AND (
                    (b.check_in <= ? AND b.check_out >= ?) OR
                    (b.check_in <= ? AND b.check_out >= ?) OR
                    (b.check_in >= ? AND b.check_out <= ?)
                )
        ");
        $stmt->execute([$typeId, $checkOut, $checkIn, $checkIn, $checkOut, $checkIn, $checkOut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRoomDetails($roomId) {
        $stmt = $this->db->prepare("
            SELECT r.*, rt.type_name, rt.description, rt.base_price 
            FROM rooms r
            JOIN room_types rt ON r.id_type = rt.id_type
            WHERE r.id_room = ?
        ");
        $stmt->execute([$roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}