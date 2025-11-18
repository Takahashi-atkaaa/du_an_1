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
        $this->deleteBookingByTourId($id);
        
        // 9. Cuối cùng mới xóa tour
        $sql = "DELETE FROM tour WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$id]);
        
        return $result;
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
        $sql = "DELETE FROM yeu_cau_dac_biet WHERE tour_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$tourId]);
    }

    // Thêm yêu cầu đặc biệt
    public function insertYeuCauDacBiet($khachHangId, $tourId, $noiDung) {
        $sql = "INSERT INTO yeu_cau_dac_biet (khach_hang_id, tour_id, noi_dung) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$khachHangId,
            (int)$tourId,
            $noiDung
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

    // Lấy URL đặt tour online
    public function getBookingUrl($tourId) {
        return BASE_URL . 'index.php?act=tour/bookOnline&tour_id=' . $tourId;
    }

    // Tạo QR Code cho tour
    public function generateQRCode($tourId) {
        $tour = $this->findById($tourId);
        if (!$tour) {
            return false;
        }

        // URL đặt tour
        $bookingUrl = $this->getBookingUrl($tourId);
        
        try {
            // Tạo QR Code bằng PHP thuần (không cần thư viện ngoài)
            $size = 300;
            $image = imagecreatetruecolor($size, $size);
            
            // Màu nền trắng
            $white = imagecolorallocate($image, 255, 255, 255);
            $black = imagecolorallocate($image, 0, 0, 0);
            $blue = imagecolorallocate($image, 0, 102, 204);
            
            // Fill background
            imagefill($image, 0, 0, $white);
            
            // Vẽ border
            imagerectangle($image, 10, 10, $size-11, $size-11, $black);
            imagerectangle($image, 11, 11, $size-12, $size-12, $black);
            
            // Tiêu đề
            $title = "TOUR #" . $tourId;
            imagestring($image, 5, 100, 40, $title, $blue);
            
            // QR Code placeholder (vẽ pattern đơn giản)
            $startX = 50;
            $startY = 80;
            $qrSize = 200;
            $cellSize = 10;
            
            // Vẽ grid QR Code giả lập
            for ($i = 0; $i < 20; $i++) {
                for ($j = 0; $j < 20; $j++) {
                    // Tạo pattern ngẫu nhiên dựa trên tour_id
                    if (($i + $j + $tourId) % 3 == 0 || $i == 0 || $i == 19 || $j == 0 || $j == 19) {
                        imagefilledrectangle(
                            $image, 
                            $startX + $i * $cellSize, 
                            $startY + $j * $cellSize,
                            $startX + ($i + 1) * $cellSize - 1,
                            $startY + ($j + 1) * $cellSize - 1,
                            $black
                        );
                    }
                }
            }
            
            // Text hướng dẫn
            imagestring($image, 3, 50, 35, "Scan QR Code de dat tour", $black);
            imagestring($image, 2, 30, 260, "Hoac truy cap:", $black);
            
            // URL (rút ngắn để hiển thị)
            $shortUrl = substr($bookingUrl, 0, 35) . "...";
            imagestring($image, 2, 30, 275, $shortUrl, $blue);
            
            // Lưu file
            $fileName = 'tour_' . $tourId . '_' . time() . '.png';
            $filePath = PATH_ROOT . 'public/uploads/qr_codes/' . $fileName;
            
            // Kiểm tra quyền ghi
            $dir = dirname($filePath);
            if (!is_writable($dir)) {
                throw new Exception('Không có quyền ghi vào thư mục: ' . $dir);
            }
            
            $result = imagepng($image, $filePath);
            imagedestroy($image);
            
            if (!$result) {
                throw new Exception('Không thể lưu file QR Code');
            }
            
            // Cập nhật database
            $qrCodePath = 'public/uploads/qr_codes/' . $fileName;
            $sql = "UPDATE tour SET qr_code_path = ? WHERE tour_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$qrCodePath, $tourId]);
            
            return $qrCodePath;
        } catch (Exception $e) {
            error_log("Error generating QR code: " . $e->getMessage());
            throw $e;
        }
    }

    // Tạo QR Code đơn giản (fallback) - XÓA hàm này vì đã gộp vào trên
    private function generateQRCodeSimple($tourId, $url) {
        // Hàm này không còn cần thiết
        return false;
    }

    // Lấy đường dẫn QR Code
    public function getQRCodePath($tourId) {
        $tour = $this->findById($tourId);
        return $tour['qr_code_path'] ?? null;
    }
}
