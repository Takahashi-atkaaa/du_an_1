<?php

class YeuCauDacBiet
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Lấy booking_id mới nhất ứng với khách + tour
     */
    private function getBookingIdByKhachAndTour($khachHangId, $tourId)
    {
        $sql = "SELECT booking_id 
                FROM booking 
                WHERE khach_hang_id = ? AND tour_id = ?
                ORDER BY ngay_dat DESC 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$khachHangId, (int)$tourId]);
        $booking = $stmt->fetch();
        return $booking['booking_id'] ?? null;
    }

    /**
     * Tìm bản ghi yêu cầu đặc biệt mới nhất của khách trên tour
     */
    public function findOne($khachHangId, $tourId)
    {
        $sql = "SELECT yc.* 
                FROM yeu_cau_dac_biet yc
                INNER JOIN booking b ON yc.booking_id = b.booking_id
                WHERE b.khach_hang_id = ? AND b.tour_id = ?
                ORDER BY yc.id DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$khachHangId, (int)$tourId]);
        return $stmt->fetch();
    }

    /**
     * Ghi nhận/ cập nhật yêu cầu đặc biệt đơn giản (dùng cho HDV)
     */
    public function upsert($khachHangId, $tourId, $noiDung)
    {
        $bookingId = $this->getBookingIdByKhachAndTour($khachHangId, $tourId);
        if (!$bookingId) {
            return false;
        }

        $existing = $this->findOne($khachHangId, $tourId);

        if ($existing) {
            $sql = "UPDATE yeu_cau_dac_biet
                    SET mo_ta = ?, ngay_cap_nhat = NOW()
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$noiDung, (int)$existing['id']]);
        }

        $sql = "INSERT INTO yeu_cau_dac_biet 
                (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien, trang_thai, nguoi_tao_id)
                VALUES (?, 'khac', 'Yêu cầu đặc biệt', ?, 'trung_binh', 'moi', NULL)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$bookingId,
            $noiDung
        ]);
    }

    /**
     * Danh sách yêu cầu dành cho admin với đầy đủ thông tin liên quan
     */
    public function getAllForAdmin(array $filters = [])
    {
        $sql = "SELECT yc.*, 
                       b.booking_id, b.tour_id, b.ngay_khoi_hanh, b.so_nguoi,
                       t.ten_tour, t.loai_tour,
                       nd_khach.ho_ten AS khach_ten, nd_khach.email AS khach_email, nd_khach.so_dien_thoai AS khach_phone,
                       nd_tao.ho_ten AS nguoi_tao_ten,
                       nd_xuly.ho_ten AS nguoi_xu_ly_ten
                FROM yeu_cau_dac_biet yc
                LEFT JOIN booking b ON yc.booking_id = b.booking_id
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
                LEFT JOIN nguoi_dung nd_khach ON kh.nguoi_dung_id = nd_khach.id
                LEFT JOIN nguoi_dung nd_tao ON yc.nguoi_tao_id = nd_tao.id
                LEFT JOIN nguoi_dung nd_xuly ON yc.nguoi_xu_ly_id = nd_xuly.id
                WHERE 1 = 1";

        $params = [];

        if (!empty($filters['trang_thai'])) {
            $sql .= " AND yc.trang_thai = ?";
            $params[] = $filters['trang_thai'];
        }

        if (!empty($filters['muc_do_uu_tien'])) {
            $sql .= " AND yc.muc_do_uu_tien = ?";
            $params[] = $filters['muc_do_uu_tien'];
        }

        if (!empty($filters['loai_yeu_cau'])) {
            $sql .= " AND yc.loai_yeu_cau = ?";
            $params[] = $filters['loai_yeu_cau'];
        }

        if (!empty($filters['tour_id'])) {
            $sql .= " AND b.tour_id = ?";
            $params[] = (int)$filters['tour_id'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(yc.ngay_tao) >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(yc.ngay_tao) <= ?";
            $params[] = $filters['date_to'];
        }

        if (!empty($filters['keyword'])) {
            $sql .= " AND (nd_khach.ho_ten LIKE ? OR nd_khach.so_dien_thoai LIKE ? OR t.ten_tour LIKE ? OR yc.tieu_de LIKE ?)";
            $keyword = '%' . $filters['keyword'] . '%';
            array_push($params, $keyword, $keyword, $keyword, $keyword);
        }

        $sql .= " ORDER BY 
                    FIELD(yc.muc_do_uu_tien, 'khan_cap','cao','trung_binh','thap') ASC,
                    yc.ngay_tao DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Thống kê nhanh theo ưu tiên & trạng thái
     */
    public function getSummaryStats()
    {
        $sql = "SELECT 
                    SUM(CASE WHEN muc_do_uu_tien = 'khan_cap' THEN 1 ELSE 0 END) AS khan_cap,
                    SUM(CASE WHEN muc_do_uu_tien = 'cao' THEN 1 ELSE 0 END) AS cao,
                    SUM(CASE WHEN muc_do_uu_tien = 'trung_binh' THEN 1 ELSE 0 END) AS trung_binh,
                    SUM(CASE WHEN muc_do_uu_tien = 'thap' THEN 1 ELSE 0 END) AS thap,
                    SUM(CASE WHEN trang_thai = 'moi' THEN 1 ELSE 0 END) AS trang_thai_moi,
                    SUM(CASE WHEN trang_thai = 'dang_xu_ly' THEN 1 ELSE 0 END) AS trang_thai_dang_xu_ly,
                    SUM(CASE WHEN trang_thai = 'da_giai_quyet' THEN 1 ELSE 0 END) AS trang_thai_da_giai_quyet,
                    SUM(CASE WHEN trang_thai = 'khong_the_thuc_hien' THEN 1 ELSE 0 END) AS trang_thai_khong_the_thuc_hien
                FROM yeu_cau_dac_biet";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch() ?: [];
    }

    /**
     * Lấy lịch sử thao tác cho danh sách yêu cầu
     */
    public function getHistoriesByRequestIds(array $requestIds)
    {
        if (empty($requestIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($requestIds), '?'));
        $sql = "SELECT ls.*, nd.ho_ten 
                FROM lich_su_yeu_cau ls
                LEFT JOIN nguoi_dung nd ON ls.nguoi_thuc_hien_id = nd.id
                WHERE ls.yeu_cau_id IN ($placeholders)
                ORDER BY ls.ngay_thuc_hien DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($requestIds);
        $rows = $stmt->fetchAll();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['yeu_cau_id']][] = $row;
        }
        return $grouped;
    }

    /**
     * Cập nhật yêu cầu từ phía admin
     */
    public function updateByAdmin($id, array $data, $nguoiXuLyId)
    {
        $fields = [];
        $params = [];

        if (isset($data['trang_thai'])) {
            $fields[] = "trang_thai = ?";
            $params[] = $data['trang_thai'];
        }

        if (isset($data['muc_do_uu_tien'])) {
            $fields[] = "muc_do_uu_tien = ?";
            $params[] = $data['muc_do_uu_tien'];
        }

        if (array_key_exists('ghi_chu_hdv', $data)) {
            $fields[] = "ghi_chu_hdv = ?";
            $params[] = $data['ghi_chu_hdv'];
        }

        $fields[] = "nguoi_xu_ly_id = ?";
        $params[] = $nguoiXuLyId;
        $fields[] = "ngay_cap_nhat = NOW()";

        $params[] = (int)$id;

        $sql = "UPDATE yeu_cau_dac_biet SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $updated = $stmt->execute($params);

        if ($updated) {
            $this->insertHistory($id, 'cap_nhat_admin', 'Admin cập nhật yêu cầu', $nguoiXuLyId);
        }

        return $updated;
    }

    public function findByIdWithRelations($id)
    {
        $sql = "SELECT yc.*, 
                       b.booking_id, b.tour_id, b.ngay_khoi_hanh,
                       t.ten_tour,
                       nd_khach.ho_ten AS khach_ten, nd_khach.email AS khach_email, nd_khach.so_dien_thoai AS khach_phone
                FROM yeu_cau_dac_biet yc
                LEFT JOIN booking b ON yc.booking_id = b.booking_id
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
                LEFT JOIN nguoi_dung nd_khach ON kh.nguoi_dung_id = nd_khach.id
                WHERE yc.id = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    private function insertHistory($yeuCauId, $hanhDong, $noiDung, $nguoiThucHienId)
    {
        $sql = "INSERT INTO lich_su_yeu_cau (yeu_cau_id, hanh_dong, noi_dung, nguoi_thuc_hien_id, ngay_thuc_hien)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$yeuCauId, $hanhDong, $noiDung, $nguoiThucHienId]);
    }
}

