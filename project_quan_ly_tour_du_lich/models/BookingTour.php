<?php
class BookingTour {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }
    public function getByTour($tourId) {
        $sql = "SELECT * FROM booking WHERE tour_id = ? ORDER BY ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }
}