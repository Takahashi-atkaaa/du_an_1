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
}

