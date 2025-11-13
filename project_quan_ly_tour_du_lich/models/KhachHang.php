<?php
class KhachHang 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM khach_hang";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM khach_hang WHERE khach_hang_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByNguoiDungId($nguoiDungId) {
        $sql = "SELECT * FROM khach_hang WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nguoiDungId]);
        return $stmt->fetch();
    }

    public function insert($data) {
        $sql = "INSERT INTO khach_hang (nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['nguoi_dung_id'],
            $data['dia_chi'] ?? null,
            $data['gioi_tinh'] ?? null,
            $data['ngay_sinh'] ?? null
        ]);
        return $this->conn->lastInsertId();
    }
}
