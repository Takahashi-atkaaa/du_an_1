<?php
class CongNoNhaCungCap {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }
    // Tạo mới công nợ NCC
    public function create($data) {
        $sql = "INSERT INTO cong_no_nha_cung_cap (nha_cung_cap_id, so_tien, han_thanh_toan, trang_thai, ghi_chu) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nha_cung_cap_id'],
            $data['so_tien'],
            $data['han_thanh_toan'] ?? null,
            $data['trang_thai'] ?? 'ChuaThanhToan',
            $data['ghi_chu'] ?? null
        ]);
    }
    // Lấy danh sách công nợ theo NCC
    public function getByNhaCungCap($ncc_id) {
        $sql = "SELECT * FROM cong_no_nha_cung_cap WHERE nha_cung_cap_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ncc_id]);
        return $stmt->fetchAll();
    }
    // Lấy chi tiết công nợ
    public function findById($id) {
        $sql = "SELECT * FROM cong_no_nha_cung_cap WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    // Cập nhật công nợ
    public function update($id, $data) {
        $sql = "UPDATE cong_no_nha_cung_cap SET so_tien = ?, han_thanh_toan = ?, trang_thai = ?, ghi_chu = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['so_tien'],
            $data['han_thanh_toan'] ?? null,
            $data['trang_thai'] ?? 'ChuaThanhToan',
            $data['ghi_chu'] ?? null,
            $id
        ]);
    }
    // Xóa công nợ
    public function delete($id) {
        $sql = "DELETE FROM cong_no_nha_cung_cap WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
