<?php
// Model cho Booking - tương tác với cơ sở dữ liệu
class Booking 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả booking
    public function getAll() {
        $sql = "SELECT * FROM booking ORDER BY ngay_dat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy booking theo ID
    public function findById($id) {
        $sql = "SELECT * FROM booking WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm booking theo điều kiện
    public function find($conditions = []) {
        $sql = "SELECT * FROM booking";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY ngay_dat DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Thêm booking mới
    public function insert($data) {
        $sql = "INSERT INTO booking (tour_id, khach_hang_id, ngay_dat, ngay_khoi_hanh, so_nguoi, tong_tien, trang_thai, ghi_chu) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['tour_id'] ?? 0,
            $data['khach_hang_id'] ?? 0,
            $data['ngay_dat'] ?? date('Y-m-d'),
            $data['ngay_khoi_hanh'] ?? null,
            $data['so_nguoi'] ?? 1,
            $data['tong_tien'] ?? 0,
            $data['trang_thai'] ?? 'ChoXacNhan',
            $data['ghi_chu'] ?? null
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật booking
    public function update($id, $data) {
        $sql = "UPDATE booking SET so_nguoi = ?, ngay_khoi_hanh = ?, tong_tien = ?, trang_thai = ?, ghi_chu = ? WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['so_nguoi'] ?? 1,
            $data['ngay_khoi_hanh'] ?? null,
            $data['tong_tien'] ?? 0,
            $data['trang_thai'] ?? 'ChoXacNhan',
            $data['ghi_chu'] ?? null,
            $id
        ]);
}

    // Xóa booking
    public function delete($id) {
        $sql = "DELETE FROM booking WHERE booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
