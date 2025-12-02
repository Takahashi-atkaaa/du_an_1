<?php
class DuToanTour {
    public $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy dự toán theo tour
    public function getByTour($tourId) {
        $sql = "SELECT dt.*, nd.ho_ten as nguoi_tao
                FROM du_toan_tour dt
                LEFT JOIN nguoi_dung nd ON dt.nguoi_tao_id = nd.id
                WHERE dt.tour_id = ?
                ORDER BY dt.ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll();
    }

    // Cập nhật dự toán
    public function update($id, $data) {
        $sql = "UPDATE du_toan_tour SET
                    cp_phuong_tien = ?,
                    mo_ta_phuong_tien = ?,
                    cp_luu_tru = ?,
                    mo_ta_luu_tru = ?,
                    cp_ve_tham_quan = ?,
                    mo_ta_ve_tham_quan = ?,
                    cp_an_uong = ?,
                    mo_ta_an_uong = ?,
                    cp_huong_dan_vien = ?,
                    cp_dich_vu_bo_sung = ?,
                    mo_ta_dich_vu = ?,
                    cp_phat_sinh_du_kien = ?,
                    mo_ta_phat_sinh = ?
                WHERE du_toan_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['cp_phuong_tien'] ?? 0,
            $data['mo_ta_phuong_tien'] ?? '',
            $data['cp_luu_tru'] ?? 0,
            $data['mo_ta_luu_tru'] ?? '',
            $data['cp_ve_tham_quan'] ?? 0,
            $data['mo_ta_ve_tham_quan'] ?? '',
            $data['cp_an_uong'] ?? 0,
            $data['mo_ta_an_uong'] ?? '',
            $data['cp_huong_dan_vien'] ?? 0,
            $data['cp_dich_vu_bo_sung'] ?? 0,
            $data['mo_ta_dich_vu'] ?? '',
            $data['cp_phat_sinh_du_kien'] ?? 0,
            $data['mo_ta_phat_sinh'] ?? '',
            $id
        ]);
    }

    // Tạo dự toán mới
    public function create($data) {
        $sql = "INSERT INTO du_toan_tour (
                    tour_id, lich_khoi_hanh_id,
                    cp_phuong_tien, mo_ta_phuong_tien,
                    cp_luu_tru, mo_ta_luu_tru,
                    cp_ve_tham_quan, mo_ta_ve_tham_quan,
                    cp_an_uong, mo_ta_an_uong,
                    cp_huong_dan_vien,
                    cp_dich_vu_bo_sung, mo_ta_dich_vu,
                    cp_phat_sinh_du_kien, mo_ta_phat_sinh,
                    nguoi_tao_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            $data['lich_khoi_hanh_id'] ?? null,
            $data['cp_phuong_tien'] ?? 0,
            $data['mo_ta_phuong_tien'] ?? '',
            $data['cp_luu_tru'] ?? 0,
            $data['mo_ta_luu_tru'] ?? '',
            $data['cp_ve_tham_quan'] ?? 0,
            $data['mo_ta_ve_tham_quan'] ?? '',
            $data['cp_an_uong'] ?? 0,
            $data['mo_ta_an_uong'] ?? '',
            $data['cp_huong_dan_vien'] ?? 0,
            $data['cp_dich_vu_bo_sung'] ?? 0,
            $data['mo_ta_dich_vu'] ?? '',
            $data['cp_phat_sinh_du_kien'] ?? 0,
            $data['mo_ta_phat_sinh'] ?? '',
            $data['nguoi_tao_id']
        ]);
    }

    // Lấy tất cả dự toán
    public function getAll() {
        $sql = "SELECT dt.*, t.ten_tour, nd.ho_ten as nguoi_tao
                FROM du_toan_tour dt
                LEFT JOIN tour t ON dt.tour_id = t.tour_id
                LEFT JOIN nguoi_dung nd ON dt.nguoi_tao_id = nd.id
                ORDER BY dt.ngay_tao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy dự toán theo id
    public function findById($duToanId) {
        $sql = "SELECT dt.*, t.ten_tour, nd.ho_ten as nguoi_tao
                FROM du_toan_tour dt
                LEFT JOIN tour t ON dt.tour_id = t.tour_id
                LEFT JOIN nguoi_dung nd ON dt.nguoi_tao_id = nd.id
                WHERE dt.du_toan_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$duToanId]);
        return $stmt->fetch();
    }
    public function getDuToanCanhBao() {
        $sql = "SELECT * FROM v_so_sanh_du_toan_thuc_te 
                WHERE canh_bao IN ('VuotDuToan', 'GanVuot')
                ORDER BY 
                    CASE canh_bao 
                        WHEN 'VuotDuToan' THEN 1
                        WHEN 'GanVuot' THEN 2
                    END";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy dự toán từng loại chi phí
    public function getDuToanLoai($tourId, $loaiChiPhi) {
        $sql = "SELECT SUM(so_tien) as tong FROM du_toan_chi_tiet WHERE tour_id = ? AND loai_chi_phi = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId, $loaiChiPhi]);
        $row = $stmt->fetch();
        return $row && $row['tong'] !== null ? $row['tong'] : 0;
    }

    // Lấy dự toán theo tour_id (lấy bản mới nhất)
    public function findByTourId($tourId) {
        $sql = "SELECT * FROM du_toan_tour WHERE tour_id = ? ORDER BY ngay_tao DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetch();
    }
}