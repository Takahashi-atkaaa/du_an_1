<?php
// Model cho Tour - tương tác với cơ sở dữ liệu
class Tour 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour
    public function getAll() {
        $sql = "SELECT * FROM tour ORDER BY tour_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tour theo ID
    public function findById($id) {
        $sql = "SELECT * FROM tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm tour theo điều kiện
    public function find($conditions = []) {
        $sql = "SELECT * FROM tour";
        $params = [];
        
        if (isset($conditions) && count($conditions) > 0) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Thêm tour mới
    public function insert($data) {
        $sql = "INSERT INTO tour (ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, id_nha_cung_cap, tao_boi, trang_thai) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten_tour'] ?? '',
            $data['loai_tour'] ?? 'TrongNuoc',
            $data['mo_ta'] ?? '',
            $data['gia_co_ban'] ?? 0,
            $data['chinh_sach'] ?? null,
            $data['id_nha_cung_cap'] ?? null,
            $data['tao_boi'] ?? null,
            $data['trang_thai'] ?? 'HoatDong'
        ]);
    }

    // Cập nhật tour
    public function update($id, $data) {
        $sql = "UPDATE tour SET ten_tour = ?, loai_tour = ?, mo_ta = ?, gia_co_ban = ?, chinh_sach = ?, 
                id_nha_cung_cap = ?, trang_thai = ? WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten_tour'] ?? '',
            $data['loai_tour'] ?? 'TrongNuoc',
            $data['mo_ta'] ?? '',
            $data['gia_co_ban'] ?? 0,
            $data['chinh_sach'] ?? null,
            $data['id_nha_cung_cap'] ?? null,
            $data['trang_thai'] ?? 'HoatDong',
            $id
        ]);
    }

    // Xóa tour
    public function delete($id) {
        $sql = "DELETE FROM tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Lấy danh sách lịch trình theo tour_id
    public function getLichTrinhByTourId($tourId) {
        $sql = "SELECT ngay_thu, dia_diem, hoat_dong 
                FROM lich_trinh_tour 
                WHERE tour_id = ? 
                ORDER BY ngay_thu ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách lịch khởi hành theo tour_id
    public function getLichKhoiHanhByTourId($tourId) {
        $sql = "SELECT ngay_khoi_hanh, ngay_ket_thuc, diem_tap_trung, trang_thai 
                FROM lich_khoi_hanh 
                WHERE tour_id = ? 
                ORDER BY ngay_khoi_hanh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }

    // Lấy thông tin hướng dẫn viên từ lịch khởi hành theo tour_id
    public function getHDVByTourId($tourId) {
        $sql = "SELECT 
                    lk.id as lich_khoi_hanh_id,
                    lk.ngay_khoi_hanh,
                    lk.ngay_ket_thuc,
                    lk.diem_tap_trung,
                    lk.trang_thai as lich_trang_thai,
                    ns.nhan_su_id,
                    ns.vai_tro as ns_vai_tro,
                    ns.chung_chi,
                    ns.ngon_ngu,
                    ns.kinh_nghiem,
                    ns.suc_khoe,
                    nd.id as nguoi_dung_id,
                    nd.ho_ten,
                    nd.email,
                    nd.so_dien_thoai
                FROM lich_khoi_hanh lk
                LEFT JOIN nhan_su ns ON lk.hdv_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE lk.tour_id = ? 
                ORDER BY lk.ngay_khoi_hanh ASC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetch();
    }

    // Lấy danh sách hình ảnh theo tour_id
    public function getHinhAnhByTourId($tourId) {
        $sql = "SELECT url_anh, mo_ta 
                FROM hinh_anh_tour 
                WHERE tour_id = ? 
                ORDER BY id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }

    // Lấy danh sách yêu cầu đặc biệt theo tour_id
    public function getYeuCauDacBietByTourId($tourId) {
        $sql = "SELECT khach_hang_id, noi_dung 
                FROM yeu_cau_dac_biet 
                WHERE tour_id = ? 
                ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }

    // Lấy nhật ký tour theo tour_id
    public function getNhatKyTourByTourId($tourId) {
        $sql = "SELECT nhan_su_id, noi_dung, ngay_ghi 
                FROM nhat_ky_tour 
                WHERE tour_id = ? 
                ORDER BY ngay_ghi DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$tourId]);
        return $stmt->fetchAll();
    }
}
