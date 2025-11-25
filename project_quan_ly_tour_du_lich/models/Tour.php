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
        // Xóa tất cả các bản ghi liên quan trước khi xóa tour
        // Thứ tự xóa: từ bảng con đến bảng cha để tránh vi phạm foreign key constraint
        
        try {
            // 1. Xóa hình ảnh tour
            $this->deleteHinhAnhByTourId($id);
            
            // 2. Xóa lịch trình tour
            $this->deleteLichTrinhByTourId($id);
            
            // 3. Xóa lịch khởi hành
            $this->deleteLichKhoiHanhByTourId($id);
            
            // 4. Xóa nhật ký tour
            $this->deleteNhatKyByTourId($id);
            
            // 5. Xóa phản hồi đánh giá
            $this->deletePhanHoiDanhGiaByTourId($id);
            
            // 6. Xóa giao dịch tài chính
            $this->deleteGiaoDichTaiChinhByTourId($id);
            
            // 7. Xóa yêu cầu đặc biệt
            $this->deleteYeuCauDacBietByTourId($id);
            
            // 8. Xóa booking (nếu muốn xóa cả booking khi xóa tour)
            // Skip vì có thể cần giữ lịch sử booking
            // $this->deleteBookingByTourId($id);
            
            // 9. Cuối cùng mới xóa tour
            $sql = "DELETE FROM tour WHERE tour_id = ?";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([$id]);
            
            return $result;
        } catch (PDOException $e) {
            // Log error để debug
            error_log("Error deleting tour: " . $e->getMessage());
            throw $e;
        }
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
        $sql = "SELECT yc.*, b.khach_hang_id 
                FROM yeu_cau_dac_biet yc
                INNER JOIN booking b ON yc.booking_id = b.booking_id
                WHERE b.tour_id = ? 
                ORDER BY yc.id DESC";
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

    // Thêm lịch trình tour
    public function insertLichTrinh($tourId, $lichTrinh) {
        $sql = "INSERT INTO lich_trinh_tour (tour_id, ngay_thu, dia_diem, hoat_dong) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$tourId,
            (int)$lichTrinh['ngay_thu'],
            $lichTrinh['dia_diem'] ?? '',
            $lichTrinh['hoat_dong'] ?? ''
        ]);
    }

    // Xóa lịch trình tour theo tour_id
    public function deleteLichTrinhByTourId($tourId) {
        $sql = "DELETE FROM lich_trinh_tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Thêm lịch khởi hành
    public function insertLichKhoiHanh($tourId, $lichKhoiHanh) {
        $sql = "INSERT INTO lich_khoi_hanh (tour_id, ngay_khoi_hanh, ngay_ket_thuc, diem_tap_trung, hdv_id, trang_thai) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$tourId,
            $lichKhoiHanh['ngay_khoi_hanh'] ?? null,
            $lichKhoiHanh['ngay_ket_thuc'] ?? null,
            $lichKhoiHanh['diem_tap_trung'] ?? '',
            isset($lichKhoiHanh['hdv_id']) && $lichKhoiHanh['hdv_id'] !== '' ? (int)$lichKhoiHanh['hdv_id'] : null,
            $lichKhoiHanh['trang_thai'] ?? 'SapKhoiHanh'
        ]);
    }

    // Xóa lịch khởi hành theo tour_id
    public function deleteLichKhoiHanhByTourId($tourId) {
        $sql = "DELETE FROM lich_khoi_hanh WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Thêm hình ảnh tour
    public function insertHinhAnh($tourId, $hinhAnh) {
        $sql = "INSERT INTO hinh_anh_tour (tour_id, url_anh, mo_ta) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$tourId,
            $hinhAnh['url_anh'] ?? '',
            $hinhAnh['mo_ta'] ?? ''
        ]);
    }

    // Xóa hình ảnh tour theo tour_id
    public function deleteHinhAnhByTourId($tourId) {
        $sql = "DELETE FROM hinh_anh_tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Xóa nhật ký tour theo tour_id
    public function deleteNhatKyByTourId($tourId) {
        $sql = "DELETE FROM nhat_ky_tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Xóa phản hồi đánh giá theo tour_id
    public function deletePhanHoiDanhGiaByTourId($tourId) {
        $sql = "DELETE FROM phan_hoi_danh_gia WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Xóa giao dịch tài chính theo tour_id
    public function deleteGiaoDichTaiChinhByTourId($tourId) {
        $sql = "DELETE FROM giao_dich_tai_chinh WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Xóa yêu cầu đặc biệt theo tour_id
    public function deleteYeuCauDacBietByTourId($tourId) {
        // Bảng yeu_cau_dac_biet không có tour_id, chỉ có booking_id
        // Nên ta phải xóa qua bảng booking trước
        $sql = "DELETE ycdb FROM yeu_cau_dac_biet ycdb 
                INNER JOIN booking b ON ycdb.booking_id = b.booking_id 
                WHERE b.tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Thêm yêu cầu đặc biệt
    public function insertYeuCauDacBiet($bookingId, $noiDung, $loaiYeuCau = 'khac', $mucDoUuTien = 'trung_binh') {
        $sql = "INSERT INTO yeu_cau_dac_biet (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$bookingId,
            $loaiYeuCau,
            'Yêu cầu đặc biệt',
            $noiDung,
            $mucDoUuTien
        ]);
    }

    // Xóa booking theo tour_id
    public function deleteBookingByTourId($tourId) {
        $sql = "DELETE FROM booking WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Lấy tour_id vừa insert
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
}