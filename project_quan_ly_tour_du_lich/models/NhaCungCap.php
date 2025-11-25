<?php
class NhaCungCap 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM nha_cung_cap";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM nha_cung_cap WHERE id_nha_cung_cap = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByUserId($userId) {
        $sql = "SELECT * FROM nha_cung_cap WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // Lấy danh sách dịch vụ được phân bổ
    public function getDichVu($nhaCungCapId, $trangThai = null) {
        $sql = "SELECT pbdv.*, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, 
                t.ten_tour, t.tour_id
                FROM phan_bo_dich_vu pbdv
                LEFT JOIN lich_khoi_hanh lkh ON pbdv.lich_khoi_hanh_id = lkh.id
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id
                WHERE pbdv.nha_cung_cap_id = ?";
        
        if ($trangThai) {
            $sql .= " AND pbdv.trang_thai = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nhaCungCapId, $trangThai]);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nhaCungCapId]);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái dịch vụ (xác nhận booking)
    public function xacNhanDichVu($dichVuId, $giaTien = null) {
        if ($giaTien !== null) {
            $sql = "UPDATE phan_bo_dich_vu 
                    SET trang_thai = 'DaXacNhan', 
                        thoi_gian_xac_nhan = NOW(),
                        gia_tien = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$giaTien, $dichVuId]);
        } else {
            $sql = "UPDATE phan_bo_dich_vu 
                    SET trang_thai = 'DaXacNhan', 
                        thoi_gian_xac_nhan = NOW()
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$dichVuId]);
        }
        
        return $stmt->rowCount() > 0;
    }

    // Từ chối dịch vụ
    public function tuChoiDichVu($dichVuId, $ghiChu = null) {
        if ($ghiChu) {
            $sql = "UPDATE phan_bo_dich_vu 
                    SET trang_thai = 'TuChoi', 
                        thoi_gian_xac_nhan = NOW(),
                        ghi_chu = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$ghiChu, $dichVuId]);
        } else {
            $sql = "UPDATE phan_bo_dich_vu 
                    SET trang_thai = 'TuChoi', 
                        thoi_gian_xac_nhan = NOW()
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$dichVuId]);
        }
        
        return $stmt->rowCount() > 0;
    }

    // Cập nhật giá dịch vụ
    public function capNhatGiaDichVu($dichVuId, $giaTien) {
        $sql = "UPDATE phan_bo_dich_vu SET gia_tien = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$giaTien, $dichVuId]);
        return $stmt->rowCount() > 0;
    }

    // Tính tổng công nợ
    public function getTongCongNo($nhaCungCapId) {
        $sql = "SELECT 
                    SUM(CASE WHEN trang_thai = 'DaXacNhan' THEN gia_tien ELSE 0 END) as tong_cong_no,
                    COUNT(CASE WHEN trang_thai = 'DaXacNhan' THEN 1 END) as so_dich_vu
                FROM phan_bo_dich_vu
                WHERE nha_cung_cap_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nhaCungCapId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lịch sử hợp tác
    public function getLichSuHopTac($nhaCungCapId, $limit = 50) {
        $sql = "SELECT pbdv.*, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc,
                t.ten_tour, t.tour_id,
                COUNT(DISTINCT b.booking_id) as so_booking
                FROM phan_bo_dich_vu pbdv
                LEFT JOIN lich_khoi_hanh lkh ON pbdv.lich_khoi_hanh_id = lkh.id
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id
                LEFT JOIN booking b ON b.tour_id = t.tour_id
                WHERE pbdv.nha_cung_cap_id = ?
                GROUP BY pbdv.id
                ORDER BY pbdv.created_at DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nhaCungCapId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
