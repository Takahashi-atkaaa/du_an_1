<?php
// Model cho ThongBao - Quản lý thông báo hệ thống
class ThongBao 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Tạo thông báo mới
     */
    public function insert($data) {
        $sql = "INSERT INTO thong_bao (
                    tieu_de, noi_dung, loai_thong_bao, muc_do_uu_tien,
                    nguoi_gui_id, nguoi_nhan_id, vai_tro_nhan,
                    trang_thai, thoi_gian_gui, thoi_gian_hen_gui
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['tieu_de'] ?? '',
            $data['noi_dung'] ?? '',
            $data['loai_thong_bao'] ?? 'ChungChung',
            $data['muc_do_uu_tien'] ?? 'TrungBinh',
            $data['nguoi_gui_id'] ?? null,
            $data['nguoi_nhan_id'] ?? null,
            $data['vai_tro_nhan'] ?? null,
            $data['trang_thai'] ?? 'ChuaGui',
            $data['thoi_gian_gui'] ?? null,
            $data['thoi_gian_hen_gui'] ?? null
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Cập nhật thông báo
     */
    public function update($id, $data) {
        $sql = "UPDATE thong_bao SET 
                tieu_de = ?, noi_dung = ?, loai_thong_bao = ?, muc_do_uu_tien = ?,
                nguoi_nhan_id = ?, vai_tro_nhan = ?, trang_thai = ?,
                thoi_gian_gui = ?, thoi_gian_hen_gui = ?
                WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tieu_de'] ?? '',
            $data['noi_dung'] ?? '',
            $data['loai_thong_bao'] ?? 'ChungChung',
            $data['muc_do_uu_tien'] ?? 'TrungBinh',
            $data['nguoi_nhan_id'] ?? null,
            $data['vai_tro_nhan'] ?? null,
            $data['trang_thai'] ?? 'ChuaGui',
            $data['thoi_gian_gui'] ?? null,
            $data['thoi_gian_hen_gui'] ?? null,
            (int)$id
        ]);
    }

    /**
     * Lấy thông báo theo ID
     */
    public function findById($id) {
        $sql = "SELECT tb.*, 
                nd_gui.ho_ten as nguoi_gui_ten,
                nd_nhan.ho_ten as nguoi_nhan_ten
                FROM thong_bao tb
                LEFT JOIN nguoi_dung nd_gui ON tb.nguoi_gui_id = nd_gui.id
                LEFT JOIN nguoi_dung nd_nhan ON tb.nguoi_nhan_id = nd_nhan.id
                WHERE tb.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    /**
     * Lấy tất cả thông báo
     */
    public function getAll($limit = 100) {
        $sql = "SELECT tb.*, 
                nd_gui.ho_ten as nguoi_gui_ten
                FROM thong_bao tb
                LEFT JOIN nguoi_dung nd_gui ON tb.nguoi_gui_id = nd_gui.id
                ORDER BY tb.created_at DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông báo theo loại
     */
    public function getByLoai($loaiThongBao) {
        $sql = "SELECT tb.*, 
                nd_gui.ho_ten as nguoi_gui_ten
                FROM thong_bao tb
                LEFT JOIN nguoi_dung nd_gui ON tb.nguoi_gui_id = nd_gui.id
                WHERE tb.loai_thong_bao = ?
                ORDER BY tb.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$loaiThongBao]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông báo theo vai trò nhận
     */
    public function getByVaiTro($vaiTro, $limit = 50) {
        $sql = "SELECT tb.*, 
                nd_gui.ho_ten as nguoi_gui_ten
                FROM thong_bao tb
                LEFT JOIN nguoi_dung nd_gui ON tb.nguoi_gui_id = nd_gui.id
                WHERE tb.vai_tro_nhan = ? OR tb.vai_tro_nhan IS NULL
                ORDER BY tb.created_at DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$vaiTro, (int)$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông báo của người dùng cụ thể
     */
    public function getByNguoiDung($nguoiDungId, $limit = 50) {
        $sql = "SELECT tb.*, 
                nd_gui.ho_ten as nguoi_gui_ten,
                tbd.da_doc, tbd.thoi_gian_doc
                FROM thong_bao tb
                LEFT JOIN nguoi_dung nd_gui ON tb.nguoi_gui_id = nd_gui.id
                LEFT JOIN thong_bao_doc tbd ON tb.id = tbd.thong_bao_id AND tbd.nguoi_dung_id = ?
                WHERE (tb.nguoi_nhan_id = ? OR tb.nguoi_nhan_id IS NULL)
                ORDER BY tb.created_at DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$nguoiDungId, (int)$nguoiDungId, (int)$limit]);
        return $stmt->fetchAll();
    }

    /**
     * Đếm thông báo chưa đọc của người dùng
     */
    public function countChuaDoc($nguoiDungId) {
        $sql = "SELECT COUNT(*) as total
                FROM thong_bao tb
                LEFT JOIN thong_bao_doc tbd ON tb.id = tbd.thong_bao_id AND tbd.nguoi_dung_id = ?
                WHERE (tb.nguoi_nhan_id = ? OR tb.nguoi_nhan_id IS NULL)
                  AND (tbd.da_doc IS NULL OR tbd.da_doc = 0)
                  AND tb.trang_thai = 'DaGui'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$nguoiDungId, (int)$nguoiDungId]);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    /**
     * Đánh dấu đã đọc
     */
    public function danhDauDaDoc($thongBaoId, $nguoiDungId) {
        $sql = "INSERT INTO thong_bao_doc (thong_bao_id, nguoi_dung_id, da_doc, thoi_gian_doc)
                VALUES (?, ?, 1, NOW())
                ON DUPLICATE KEY UPDATE da_doc = 1, thoi_gian_doc = NOW()";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$thongBaoId, (int)$nguoiDungId]);
    }

    /**
     * Cập nhật trạng thái thông báo
     */
    public function updateTrangThai($id, $trangThai) {
        $sql = "UPDATE thong_bao SET trang_thai = ?";
        
        if ($trangThai === 'DaGui') {
            $sql .= ", thoi_gian_gui = NOW()";
        }
        
        $sql .= " WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangThai, (int)$id]);
    }

    /**
     * Xóa thông báo
     */
    public function delete($id) {
        $sql = "DELETE FROM thong_bao WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$id]);
    }

    /**
     * Gửi thông báo đến nhiều người theo vai trò
     */
    public function guiTheoVaiTro($data) {
        // Tạo thông báo
        $thongBaoId = $this->insert($data);
        
        if (!$thongBaoId) {
            return false;
        }
        
        // Cập nhật trạng thái đã gửi
        $this->updateTrangThai($thongBaoId, 'DaGui');
        
        return $thongBaoId;
    }

    /**
     * Lấy thống kê thông báo
     */
    public function getThongKe() {
        $sql = "SELECT 
                COUNT(*) as tong_so,
                SUM(CASE WHEN trang_thai = 'DaGui' THEN 1 ELSE 0 END) as da_gui,
                SUM(CASE WHEN trang_thai = 'ChuaGui' THEN 1 ELSE 0 END) as chua_gui,
                SUM(CASE WHEN trang_thai = 'Loi' THEN 1 ELSE 0 END) as loi,
                SUM(CASE WHEN loai_thong_bao = 'HDV' THEN 1 ELSE 0 END) as hdv,
                SUM(CASE WHEN loai_thong_bao = 'KhachHang' THEN 1 ELSE 0 END) as khach_hang,
                SUM(CASE WHEN loai_thong_bao = 'NhanSu' THEN 1 ELSE 0 END) as nhan_su
                FROM thong_bao";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
}
