<?php
// Model cho LichKhoiHanh - Quản lý lịch khởi hành chi tiết
class LichKhoiHanh 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả lịch khởi hành
    public function getAll() {
        $sql = "SELECT lk.*, t.ten_tour, t.loai_tour
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                ORDER BY lk.ngay_khoi_hanh DESC, lk.gio_xuat_phat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lọc lịch khởi hành theo các tiêu chí
    public function filter($filters) {
        $sql = "SELECT lk.*, t.ten_tour, t.loai_tour
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                WHERE 1=1";
        
        $params = [];
        
        // Tìm kiếm theo tên tour hoặc điểm tập trung
        if (!empty($filters['search'])) {
            $sql .= " AND (t.ten_tour LIKE ? OR lk.diem_tap_trung LIKE ?)";
            $searchParam = '%' . $filters['search'] . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Lọc theo trạng thái
        if (!empty($filters['trang_thai'])) {
            $sql .= " AND lk.trang_thai = ?";
            $params[] = $filters['trang_thai'];
        }
        
        // Lọc theo ngày khởi hành từ
        if (!empty($filters['tu_ngay'])) {
            $sql .= " AND lk.ngay_khoi_hanh >= ?";
            $params[] = $filters['tu_ngay'];
        }
        
        // Lọc theo ngày khởi hành đến
        if (!empty($filters['den_ngay'])) {
            $sql .= " AND lk.ngay_khoi_hanh <= ?";
            $params[] = $filters['den_ngay'];
        }
        
        $sql .= " ORDER BY lk.ngay_khoi_hanh DESC, lk.gio_xuat_phat DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Lấy lịch khởi hành theo ID
    public function findById($id) {
        $sql = "SELECT lk.*, t.ten_tour, t.loai_tour, t.gia_co_ban
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                WHERE lk.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    // Lấy lịch khởi hành theo tour_id
    public function getByTourId($tourId) {
        $sql = "SELECT lk.*, t.ten_tour, t.gia_co_ban
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                WHERE lk.tour_id = ?
                ORDER BY lk.ngay_khoi_hanh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }

    // Thêm lịch khởi hành mới
    public function insert($data) {
        $sql = "INSERT INTO lich_khoi_hanh (tour_id, ngay_khoi_hanh, gio_xuat_phat, ngay_ket_thuc, gio_ket_thuc, diem_tap_trung, so_cho, hdv_id, trang_thai, ghi_chu) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['tour_id'] ?? null,
            $data['ngay_khoi_hanh'] ?? null,
            $data['gio_xuat_phat'] ?? null,
            $data['ngay_ket_thuc'] ?? null,
            $data['gio_ket_thuc'] ?? null,
            $data['diem_tap_trung'] ?? '',
            $data['so_cho'] ?? 50,
            $data['hdv_id'] ?? null,
            $data['trang_thai'] ?? 'SapKhoiHanh',
            $data['ghi_chu'] ?? null
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật lịch khởi hành
    public function update($id, $data) {
        $sql = "UPDATE lich_khoi_hanh SET 
                tour_id = ?, ngay_khoi_hanh = ?, gio_xuat_phat = ?, 
                ngay_ket_thuc = ?, gio_ket_thuc = ?, diem_tap_trung = ?, 
                so_cho = ?, hdv_id = ?, trang_thai = ?, ghi_chu = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'] ?? null,
            $data['ngay_khoi_hanh'] ?? null,
            $data['gio_xuat_phat'] ?? null,
            $data['ngay_ket_thuc'] ?? null,
            $data['gio_ket_thuc'] ?? null,
            $data['diem_tap_trung'] ?? '',
            $data['so_cho'] ?? 50,
            $data['hdv_id'] ?? null,
            $data['trang_thai'] ?? 'SapKhoiHanh',
            $data['ghi_chu'] ?? null,
            $id
        ]);
    }

    // Xóa lịch khởi hành
    public function delete($id) {
        $sql = "DELETE FROM lich_khoi_hanh WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$id]);
    }

    // Lấy lịch khởi hành với đầy đủ thông tin
    public function getWithDetails($id) {
        $sql = "SELECT lk.*, 
                t.ten_tour, t.loai_tour, t.gia_co_ban,
                COUNT(DISTINCT b.booking_id) as so_booking,
                COALESCE(SUM(b.so_nguoi), 0) as tong_nguoi_dat
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                LEFT JOIN booking b ON lk.tour_id = b.tour_id 
                    AND b.ngay_khoi_hanh = lk.ngay_khoi_hanh
                    AND b.trang_thai IN ('ChoXacNhan', 'DaCoc', 'HoanTat')
                WHERE lk.id = ?
                GROUP BY lk.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }
}

