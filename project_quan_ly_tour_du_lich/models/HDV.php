<?php
class HDV 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM hdv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM hdv WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
}
}
