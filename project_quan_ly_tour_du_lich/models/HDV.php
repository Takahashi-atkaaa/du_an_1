<?php
class HDV 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM nhan_su WHERE vai_tro = 'HDV'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM nhan_su WHERE nhan_su_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
