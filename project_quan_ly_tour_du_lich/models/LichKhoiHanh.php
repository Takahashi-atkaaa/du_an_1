<?php
// Model cho LichKhoiHanh - Quản lý lịch khởi hành chi tiết
class LichKhoiHanh 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Tự động cập nhật trạng thái lịch khởi hành theo thời gian hiện tại
    public function autoUpdateTrangThai() {
        // Hoàn thành: đã kết thúc (ngày_ket_thuc < hôm nay hoặc = hôm nay và giờ_ket_thuc <= hiện tại)
        $sqlHoanThanh = "UPDATE lich_khoi_hanh
                         SET trang_thai = 'HoanThanh'
                         WHERE trang_thai IN ('SapKhoiHanh','DangChay')
                           AND (
                               ngay_ket_thuc < CURDATE()
                               OR (ngay_ket_thuc = CURDATE() AND gio_ket_thuc IS NOT NULL AND gio_ket_thuc <= CURTIME())
                           )";
        $stmt1 = $this->conn->prepare($sqlHoanThanh);
        $stmt1->execute();

        // Đang chạy: đã bắt đầu nhưng chưa kết thúc
        $sqlDangChay = "UPDATE lich_khoi_hanh
                        SET trang_thai = 'DangChay'
                        WHERE trang_thai = 'SapKhoiHanh'
                          AND ngay_khoi_hanh <= CURDATE()
                          AND (ngay_ket_thuc IS NULL OR ngay_ket_thuc >= CURDATE())";
        $stmt2 = $this->conn->prepare($sqlDangChay);
        $stmt2->execute();
    }

    // Lấy tất cả lịch khởi hành
    public function getAll() {
        $sql = "SELECT 
                    lk.*, 
                    t.ten_tour, 
                    t.loai_tour,
                    COUNT(DISTINCT pbn.id) AS so_nhan_su,
                    COUNT(DISTINCT pbd.id) AS so_dich_vu,
                    lk.hdv_id,
                    GROUP_CONCAT(DISTINCT CASE WHEN pbn.vai_tro = 'HDV' THEN pbn.nhan_su_id END) AS hdv_ids
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                LEFT JOIN phan_bo_nhan_su pbn ON pbn.lich_khoi_hanh_id = lk.id
                LEFT JOIN phan_bo_dich_vu pbd ON pbd.lich_khoi_hanh_id = lk.id
                GROUP BY lk.id
                ORDER BY lk.ngay_khoi_hanh DESC, lk.gio_xuat_phat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
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

    // Tìm lịch khởi hành theo tour và ngày khởi hành (dùng để map từ booking)
    public function findByTourAndNgayKhoiHanh($tourId, $ngayKhoiHanh) {
        $sql = "SELECT * FROM lich_khoi_hanh 
                WHERE tour_id = ? AND ngay_khoi_hanh = ?
                ORDER BY id ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId, $ngayKhoiHanh]);
        return $stmt->fetch();
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

    // Gán HDV chính cho lịch khởi hành
    public function assignHDV($lichKhoiHanhId, $nhanSuId) {
        $sql = "UPDATE lich_khoi_hanh SET hdv_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $nhanSuId !== null ? (int)$nhanSuId : null,
            (int)$lichKhoiHanhId
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

    // Lấy các lịch khởi hành mà HDV đã được phân công chính
    public function getByHdvId($hdvId) {
        $sql = "SELECT lk.*, 
                       t.ten_tour, 
                       t.loai_tour,
                       t.gia_co_ban
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                WHERE lk.hdv_id = ?
                ORDER BY lk.ngay_khoi_hanh DESC, lk.gio_xuat_phat DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$hdvId]);
        return $stmt->fetchAll();
    }
}

