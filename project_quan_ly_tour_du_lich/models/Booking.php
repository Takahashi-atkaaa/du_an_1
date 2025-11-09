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
        $sql = "SELECT * FROM bookings ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy booking theo ID
    public function findById($id) {
        $sql = "SELECT * FROM bookings WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm booking theo điều kiện
    public function find($conditions = []) {
        $sql = "SELECT * FROM bookings";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Thêm booking mới
    public function insert($data) {
        $sql = "INSERT INTO bookings (tour_id, khach_hang_id, hdv_id, so_luong_nguoi, ngay_khoi_hanh, ngay_ket_thuc, tong_tien, trang_thai, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['tour_id'] ?? 0,
            $data['khach_hang_id'] ?? 0,
            $data['hdv_id'] ?? null,
            $data['so_luong_nguoi'] ?? 1,
            $data['ngay_khoi_hanh'] ?? null,
            $data['ngay_ket_thuc'] ?? null,
            $data['tong_tien'] ?? 0,
            $data['trang_thai'] ?? 'cho_xac_nhan',
            $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật booking
    public function update($id, $data) {
        $sql = "UPDATE bookings SET so_luong_nguoi = ?, ngay_khoi_hanh = ?, ngay_ket_thuc = ?, 
                tong_tien = ?, trang_thai = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['so_luong_nguoi'] ?? 1,
            $data['ngay_khoi_hanh'] ?? null,
            $data['ngay_ket_thuc'] ?? null,
            $data['tong_tien'] ?? 0,
            $data['trang_thai'] ?? 'cho_xac_nhan',
            $id
        ]);
    }

    // Xóa booking
    public function delete($id) {
        $sql = "DELETE FROM bookings WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
