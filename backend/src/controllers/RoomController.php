<?php
namespace App\Controllers;

class RoomController {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllRooms(): array {
        $stmt = $this->db->query("
            SELECT r.*, rt.type_name, rt.base_price 
            FROM rooms r
            JOIN room_types rt ON r.id_type = rt.id_type
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}