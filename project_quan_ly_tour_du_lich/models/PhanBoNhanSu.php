<?php
// Model cho PhanBoNhanSu - Phân bổ nhân sự cho lịch khởi hành
class PhanBoNhanSu 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy phân bổ theo ID
    public function findById($id) {
        $sql = "SELECT * FROM phan_bo_nhan_su WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    // Lấy phân bổ nhân sự theo lịch khởi hành
    public function getByLichKhoiHanh($lichKhoiHanhId) {
        $sql = "SELECT pbn.*, 
                ns.nhan_su_id, ns.vai_tro as ns_vai_tro,
                nd.ho_ten, nd.email, nd.so_dien_thoai
                FROM phan_bo_nhan_su pbn
                LEFT JOIN nhan_su ns ON pbn.nhan_su_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE pbn.lich_khoi_hanh_id = ?
                ORDER BY pbn.vai_tro, nd.ho_ten";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$lichKhoiHanhId]);
        return $stmt->fetchAll();
    }

    // Lấy phân bổ nhân sự theo vai trò
    public function getByVaiTro($lichKhoiHanhId, $vaiTro) {
        $sql = "SELECT pbn.*, 
                ns.nhan_su_id,
                nd.ho_ten, nd.email, nd.so_dien_thoai
                FROM phan_bo_nhan_su pbn
                LEFT JOIN nhan_su ns ON pbn.nhan_su_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE pbn.lich_khoi_hanh_id = ? AND pbn.vai_tro = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$lichKhoiHanhId, $vaiTro]);
        return $stmt->fetchAll();
    }

    // Thêm phân bổ nhân sự
    public function insert($data) {
        $sql = "INSERT INTO phan_bo_nhan_su (lich_khoi_hanh_id, nhan_su_id, vai_tro, ghi_chu, trang_thai) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['lich_khoi_hanh_id'] ?? 0,
            $data['nhan_su_id'] ?? 0,
            $data['vai_tro'] ?? 'Khac',
            $data['ghi_chu'] ?? null,
            $data['trang_thai'] ?? 'ChoXacNhan'
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật phân bổ nhân sự
    public function update($id, $data) {
        $sql = "UPDATE phan_bo_nhan_su SET 
                nhan_su_id = ?, vai_tro = ?, ghi_chu = ?, trang_thai = ?,
                thoi_gian_xac_nhan = ?
                WHERE id = ?";
        $thoiGianXacNhan = isset($data['trang_thai']) && $data['trang_thai'] == 'DaXacNhan' 
            ? date('Y-m-d H:i:s') 
            : null;
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nhan_su_id'] ?? 0,
            $data['vai_tro'] ?? 'Khac',
            $data['ghi_chu'] ?? null,
            $data['trang_thai'] ?? 'ChoXacNhan',
            $thoiGianXacNhan,
            $id
        ]);
    }

    // Xóa phân bổ nhân sự
    public function delete($id) {
        $sql = "DELETE FROM phan_bo_nhan_su WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$id]);
    }

    // Cập nhật trạng thái xác nhận
    public function updateTrangThai($id, $trangThai, $nguoiThayDoiId = null) {
        $sql = "UPDATE phan_bo_nhan_su SET 
                trang_thai = ?,
                thoi_gian_xac_nhan = ?
                WHERE id = ?";
        $thoiGian = ($trangThai == 'DaXacNhan' || $trangThai == 'TuChoi') 
            ? date('Y-m-d H:i:s') 
            : null;
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangThai, $thoiGian, (int)$id]);
    }

    /**
     * Tìm các lịch khởi hành khác mà nhân sự (HDV) này đang được phân bổ
     * và bị trùng khoảng ngày với lịch hiện tại.
     * Dùng để cảnh báo trùng lịch, KHÔNG chặn phân bổ.
     *
     * @param int $lichKhoiHanhId
     * @param int $nhanSuId
     * @return array
     */
    public function getScheduleConflictsForStaff($lichKhoiHanhId, $nhanSuId) {
        $lichKhoiHanhId = (int)$lichKhoiHanhId;
        $nhanSuId = (int)$nhanSuId;
        if ($lichKhoiHanhId <= 0 || $nhanSuId <= 0) {
            return [];
        }

        // Lấy khoảng ngày của lịch hiện tại
        $sqlBase = "SELECT ngay_khoi_hanh, COALESCE(ngay_ket_thuc, ngay_khoi_hanh) AS ngay_ket_thuc
                    FROM lich_khoi_hanh
                    WHERE id = ?";
        $stmtBase = $this->conn->prepare($sqlBase);
        $stmtBase->execute([$lichKhoiHanhId]);
        $current = $stmtBase->fetch();
        if (!$current || empty($current['ngay_khoi_hanh'])) {
            return [];
        }

        $start = $current['ngay_khoi_hanh'];
        $end   = $current['ngay_ket_thuc'];

        // Tìm các lịch khác mà HDV này đang dẫn (hdv_id) hoặc đã được phân bổ vai trò HDV
        $sql = "SELECT 
                    lk2.id,
                    lk2.ngay_khoi_hanh,
                    lk2.ngay_ket_thuc,
                    t.ten_tour
                FROM lich_khoi_hanh lk2
                LEFT JOIN tour t ON lk2.tour_id = t.tour_id
                LEFT JOIN phan_bo_nhan_su p2 
                       ON p2.lich_khoi_hanh_id = lk2.id 
                      AND p2.vai_tro = 'HDV'
                WHERE lk2.id <> ?
                  AND lk2.trang_thai IN ('SapKhoiHanh','DangChay')
                  AND (
                        lk2.hdv_id = ?
                        OR p2.nhan_su_id = ?
                  )
                  -- Điều kiện trùng ngày: NOT (end2 < start1 OR end1 < start2)
                  AND NOT (
                        COALESCE(lk2.ngay_ket_thuc, lk2.ngay_khoi_hanh) < ?
                        OR ? < lk2.ngay_khoi_hanh
                  )
                ORDER BY lk2.ngay_khoi_hanh ASC, lk2.id ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $lichKhoiHanhId,
            $nhanSuId,
            $nhanSuId,
            $start,
            $end,
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Tự động phân bổ 1 HDV cho lịch khởi hành nếu chưa có phân bổ
     * - Chỉ chạy khi:
     *   + lich_khoi_hanh chưa có hdv_id
     *   + bảng phan_bo_nhan_su chưa có bản ghi vai_tro = 'HDV' cho lịch này
     * - Ưu tiên HDV đang sẵn sàng, đã dẫn ít tour hơn
     *
     */
    public function autoAssignHDVIfMissing($lichKhoiHanhId) {
        $lichKhoiHanhId = (int)$lichKhoiHanhId;
        if ($lichKhoiHanhId <= 0) {
            return null;
        }

        // 1. Kiểm tra lịch đã có HDV hoặc đã có phân bổ HDV chưa
        $sqlCheck = "SELECT 
                        lk.hdv_id,
                        lk.ngay_khoi_hanh,
                        lk.ngay_ket_thuc,
                        SUM(CASE WHEN pbn.vai_tro = 'HDV' THEN 1 ELSE 0 END) AS so_phan_bo_hdv
                     FROM lich_khoi_hanh lk
                     LEFT JOIN phan_bo_nhan_su pbn ON pbn.lich_khoi_hanh_id = lk.id
                     WHERE lk.id = ?
                     GROUP BY lk.id, lk.hdv_id";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->execute([$lichKhoiHanhId]);
        $rowCheck = $stmtCheck->fetch();

        if (!$rowCheck) {
            // Lịch không tồn tại
            return null;
        }

        if (!empty($rowCheck['hdv_id']) || (int)$rowCheck['so_phan_bo_hdv'] > 0) {
            // Đã có HDV hoặc đã có phân bổ HDV => không tự động
            return null;
        }

        // 2. Xác định khoảng thời gian của lịch khởi hành hiện tại
        $ngayBatDau = $rowCheck['ngay_khoi_hanh'] ?? null;
        $ngayKetThuc = $rowCheck['ngay_ket_thuc'] ?? null;
        if (!$ngayBatDau) {
            // Thiếu thông tin ngày => không tự động để tránh gán sai
            return null;
        }
        if (!$ngayKetThuc) {
            $ngayKetThuc = $ngayBatDau;
        }

        // 3. Chọn 1 HDV đang sẵn sàng, ít tour nhất,
        //    không bị trùng lịch với tour khác (SapKhoiHanh/DangChay)
        $sqlPick = "SELECT ns.nhan_su_id
                    FROM nhan_su ns
                    WHERE ns.vai_tro = 'HDV'
                      AND (ns.trang_thai_lam_viec IS NULL OR ns.trang_thai_lam_viec = 'SanSang')
                      AND NOT EXISTS (
                          SELECT 1
                          FROM lich_khoi_hanh lk2
                          LEFT JOIN phan_bo_nhan_su p2 
                                 ON p2.lich_khoi_hanh_id = lk2.id 
                                AND p2.vai_tro = 'HDV'
                          WHERE lk2.trang_thai IN ('SapKhoiHanh','DangChay')
                            AND lk2.id <> ?
                            AND (
                                lk2.hdv_id = ns.nhan_su_id
                                OR p2.nhan_su_id = ns.nhan_su_id
                            )
                            -- Điều kiện trùng khoảng ngày: NOT (end2 < start1 OR end1 < start2)
                            AND NOT (
                                COALESCE(lk2.ngay_ket_thuc, lk2.ngay_khoi_hanh) < ?
                                OR ? < lk2.ngay_khoi_hanh
                            )
                      )
                    ORDER BY COALESCE(ns.so_tour_da_dan, 0) ASC, ns.nhan_su_id ASC
                    LIMIT 1";
        $stmtPick = $this->conn->prepare($sqlPick);
        $stmtPick->execute([
            $lichKhoiHanhId,
            $ngayBatDau,
            $ngayKetThuc,
        ]);
        $hdv = $stmtPick->fetch();

        if (empty($hdv) || empty($hdv['nhan_su_id'])) {
            // Không có HDV phù hợp
            return null;
        }

        $nhanSuId = (int)$hdv['nhan_su_id'];

        // 3. Tạo bản ghi phân bổ nhân sự cho lịch khởi hành
        $dataInsert = [
            'lich_khoi_hanh_id' => $lichKhoiHanhId,
            'nhan_su_id'        => $nhanSuId,
            'vai_tro'           => 'HDV',
            'ghi_chu'           => 'Tự động phân bổ do chưa có HDV',
            'trang_thai'        => 'DaXacNhan',
        ];

        $idPhanBo = $this->insert($dataInsert);
        if (!$idPhanBo) {
            return null;
        }

        // Cập nhật thời gian xác nhận ngay lập tức
        $sqlTime = "UPDATE phan_bo_nhan_su 
                    SET thoi_gian_xac_nhan = ? 
                    WHERE id = ?";
        $stmtTime = $this->conn->prepare($sqlTime);
        $stmtTime->execute([date('Y-m-d H:i:s'), (int)$idPhanBo]);

        // 4. Gán HDV chính cho lịch khởi hành
        require_once __DIR__ . '/LichKhoiHanh.php';
        $lichKhoiHanhModel = new LichKhoiHanh();
        $lichKhoiHanhModel->assignHDV($lichKhoiHanhId, $nhanSuId);

        return $nhanSuId;
    }
}

