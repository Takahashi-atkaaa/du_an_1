<?php
class LichSuThanhToanNCC {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }
    // Tạo mới lịch sử thanh toán NCC
    public function create($data) {
        $sql = "INSERT INTO lich_su_thanh_toan_ncc (cong_no_ncc_id, so_tien_thanh_toan, ngay_thanh_toan, ghi_chu) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['cong_no_ncc_id'],
            $data['so_tien_thanh_toan'],
            $data['ngay_thanh_toan'] ?? date('Y-m-d'),
            $data['ghi_chu'] ?? null
        ]);
    }
    // Lấy lịch sử thanh toán theo công nợ NCC
    public function getByCongNoNCC($cong_no_ncc_id) {
        $sql = "SELECT * FROM lich_su_thanh_toan_ncc WHERE cong_no_ncc_id = ? ORDER BY ngay_thanh_toan ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cong_no_ncc_id]);
        return $stmt->fetchAll();
    }
    // Lấy chi tiết lịch sử thanh toán
    public function findById($id) {
        $sql = "SELECT * FROM lich_su_thanh_toan_ncc WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Xóa lịch sử thanh toán
    public function delete($id) {
        $sql = "DELETE FROM lich_su_thanh_toan_ncc WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
