<?php

require_once 'models/NhanSu.php';
require_once 'models/HDVManagement.php';
require_once 'models/LichKhoiHanh.php';
require_once 'models/PhanBoNhanSu.php';

class HDVController {
    private $nhanSuModel;
    private $hdvMgmtModel;
    private $lichKhoiHanhModel;
    private $phanBoNhanSuModel;
    
    public function __construct() {
        requireRole('HDV');
        $this->nhanSuModel = new NhanSu();
        $this->hdvMgmtModel = new HDVManagement();
        $this->lichKhoiHanhModel = new LichKhoiHanh();
        $this->phanBoNhanSuModel = new PhanBoNhanSu();
    }
    
    public function lichLamViec() {
        // Lấy nhan_su_id của HDV đang đăng nhập
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        // Tìm nhân sự theo user_id
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=tour/index');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        // Lấy lịch khởi hành mà HDV được phân công (từ bảng lich_khoi_hanh)
        $lichKhoiHanhList = $this->hdvMgmtModel->getLichLamViec($nhanSuId);
        
        // Lấy phân bổ nhân sự (nếu HDV được phân công qua phan_bo_nhan_su)
        $sql = "SELECT pbn.*, lkh.tour_id, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, lkh.trang_thai as lkh_trang_thai,
                t.ten_tour
                FROM phan_bo_nhan_su pbn
                LEFT JOIN lich_khoi_hanh lkh ON pbn.lich_khoi_hanh_id = lkh.id
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id
                WHERE pbn.nhan_su_id = ?
                ORDER BY lkh.ngay_khoi_hanh DESC";
        $stmt = $this->phanBoNhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $phanBoNhanSuList = $stmt->fetchAll();
        
        require 'views/hdv/lich_lam_viec.php';
    }
    
    public function nhatKyTour() {
        require 'views/hdv/nhat_ky_tour.php';
    }
    
    public function danhSachKhach() {
        require 'views/hdv/danh_sach_khach.php';
    }
    
    /**
     * Trang chủ HDV Dashboard
     */
    public function dashboard() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        // Lấy thông tin nhân sự HDV
        $sql = "SELECT ns.*, nd.ho_ten, nd.email, nd.so_dien_thoai 
                FROM nhan_su ns 
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id 
                WHERE ns.nguoi_dung_id = ? AND ns.vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $hdv_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$hdv_info) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=tour/index');
            exit();
        }
        
        $nhanSuId = $hdv_info['nhan_su_id'];
        
        // Thống kê tour
        $stats = [
            'upcoming_tours' => 0,
            'ongoing_tours' => 0,
            'completed_tours' => 0,
            'rating' => 0
        ];
        
        // Tour sắp tới (trạng thái SapKhoiHanh)
        $sql = "SELECT COUNT(*) as count FROM lich_khoi_hanh WHERE hdv_id = ? AND trang_thai = 'SapKhoiHanh'";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $stats['upcoming_tours'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Tour đang chạy (trạng thái DangChay)
        $sql = "SELECT COUNT(*) as count FROM lich_khoi_hanh WHERE hdv_id = ? AND trang_thai = 'DangChay'";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $stats['ongoing_tours'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Tour hoàn thành (trạng thái HoanThanh)
        $sql = "SELECT COUNT(*) as count FROM lich_khoi_hanh WHERE hdv_id = ? AND trang_thai = 'HoanThanh'";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $stats['completed_tours'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Tour hôm nay
        $sql = "SELECT lkh.*, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND DATE(lkh.ngay_khoi_hanh) = CURDATE()";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $today_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Tour sắp tới (7 ngày tới)
        $sql = "SELECT lkh.*, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.ngay_khoi_hanh BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                ORDER BY lkh.ngay_khoi_hanh ASC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $upcoming_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thông báo mới
        $recent_notifications = $this->hdvMgmtModel->getThongBao($nhanSuId, 5);
        
        // Đếm thông báo chưa đọc
        $sql = "SELECT COUNT(*) as count FROM thong_bao_hdv WHERE nhan_su_id = ? AND da_xem = 0";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $notifications_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        require 'views/hdv/dashboard.php';
    }
    
    /**
     * Xem lịch trình tour
     */
    public function tours() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=tour/index');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $filter_status = $_GET['status'] ?? 'all';
        
        // Lấy danh sách tour
        $sql = "SELECT lkh.*, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ?";
        
        if ($filter_status !== 'all') {
            $sql .= " AND lkh.trang_thai = ?";
        }
        
        $sql .= " ORDER BY lkh.ngay_khoi_hanh DESC";
        
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        if ($filter_status !== 'all') {
            $stmt->execute([$nhanSuId, $filter_status]);
        } else {
            $stmt->execute([$nhanSuId]);
        }
        $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/hdv/tours.php';
    }
    
    /**
     * Chi tiết tour
     */
    public function tourDetail() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['id'] ?? 0;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        // Lấy chi tiết tour và kiểm tra quyền
        $sql = "SELECT lkh.*, t.* 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.id = ? AND lkh.hdv_id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$tour_id, $nhanSu['nhan_su_id']]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tour) {
            $_SESSION['error'] = 'Không tìm thấy tour hoặc bạn không có quyền truy cập';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        require 'views/hdv/tour_detail.php';
    }
    
    /**
     * Xem danh sách khách
     */
    public function khach() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $khach_list = [];
        $tour = null;
        
        if ($tour_id > 0) {
            // Kiểm tra quyền
            $sql = "SELECT lkh.*, t.ten_tour 
                    FROM lich_khoi_hanh lkh 
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                    WHERE lkh.id = ? AND lkh.hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id, $nhanSuId]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($tour) {
                // Lấy danh sách khách
                $sql = "SELECT b.*, kh.*, nd.ho_ten, nd.email, nd.so_dien_thoai 
                        FROM booking b 
                        LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id 
                        LEFT JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
                        WHERE b.tour_id = ? AND DATE(b.ngay_khoi_hanh) = DATE(?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$tour['tour_id'], $tour['ngay_khoi_hanh']]);
                $khach_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        
        // Lấy danh sách tour đang chạy
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.trang_thai IN ('DangChay', 'SapKhoiHanh')
                ORDER BY lkh.ngay_khoi_hanh ASC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/hdv/khach.php';
    }
    
    /**
     * Xem/thêm/cập nhật nhật ký tour
     */
    public function nhatKy() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? 0;
        $edit_id = $_GET['edit_id'] ?? 0;
        
        // Xóa thông báo lỗi cũ về quyền
        if (isset($_SESSION['error']) && strpos($_SESSION['error'], 'quyền') !== false) {
            unset($_SESSION['error']);
        }
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        // Lấy nhan_su_id - không kiểm tra vai_tro
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhân sự.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $nhat_ky_list = [];
        $tour = null;
        $edit_entry = null;
        
        if ($tour_id > 0) {
            // Lấy tour - không kiểm tra quyền
            $sql = "SELECT lkh.*, t.ten_tour, t.tour_id as tour_table_id
                    FROM lich_khoi_hanh lkh 
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                    WHERE lkh.id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$tour) {
                $_SESSION['error'] = 'Tour không tồn tại (ID: ' . $tour_id . ')';
                $tour_id = 0;
            } else {
                // Lấy nhật ký tour - sử dụng tour_id từ bảng tour
                $sql = "SELECT * FROM nhat_ky_tour 
                        WHERE tour_id = ? AND nhan_su_id = ?
                        ORDER BY ngay_ghi DESC, id DESC";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$tour['tour_table_id'], $nhanSuId]);
                $nhat_ky_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Nếu có edit_id, lấy dữ liệu để sửa
                if ($edit_id > 0) {
                    $sql = "SELECT * FROM nhat_ky_tour WHERE id = ? AND nhan_su_id = ?";
                    $stmt = $this->nhanSuModel->conn->prepare($sql);
                    $stmt->execute([$edit_id, $nhanSuId]);
                    $edit_entry = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        }
        
        // Lấy danh sách tour - không kiểm tra quyền
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                ORDER BY lkh.ngay_khoi_hanh DESC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute();
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/hdv/nhat_ky.php';
    }
    
    /**
     * Check-in và điểm danh
     */
    public function checkin() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? 0;
        $diem_id = $_GET['diem_id'] ?? 0;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $tour = null;
        $diem_checkin_list = [];
        $diem_hien_tai = null;
        $khach_list = [];
        
        if ($tour_id > 0) {
            // Kiểm tra quyền
            $sql = "SELECT lkh.*, t.ten_tour, t.tour_id 
                    FROM lich_khoi_hanh lkh 
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                    WHERE lkh.id = ? AND lkh.hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id, $nhanSuId]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($tour) {
                // Lấy danh sách điểm check-in
                $sql = "SELECT * FROM diem_checkin 
                        WHERE tour_id = ?
                        ORDER BY thu_tu ASC, thoi_gian_du_kien ASC";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$tour['tour_id']]);
                $diem_checkin_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Nếu có điểm check-in được chọn
                if ($diem_id > 0) {
                    $sql = "SELECT * FROM diem_checkin WHERE id = ? AND tour_id = ?";
                    $stmt = $this->nhanSuModel->conn->prepare($sql);
                    $stmt->execute([$diem_id, $tour['tour_id']]);
                    $diem_hien_tai = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($diem_hien_tai) {
                        // Lấy danh sách khách với trạng thái check-in
                        $sql = "SELECT b.*, kh.*, nd.ho_ten, nd.email, nd.so_dien_thoai,
                                ck.trang_thai as checkin_status, ck.thoi_gian_checkin, ck.ghi_chu as checkin_note
                                FROM booking b 
                                LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id 
                                LEFT JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
                                LEFT JOIN checkin_khach ck ON (ck.booking_id = b.booking_id AND ck.diem_checkin_id = ?)
                                WHERE b.tour_id = ? AND DATE(b.ngay_khoi_hanh) = DATE(?) AND b.trang_thai = 'DaXacNhan'
                                ORDER BY nd.ho_ten ASC";
                        $stmt = $this->nhanSuModel->conn->prepare($sql);
                        $stmt->execute([$diem_id, $tour['tour_id'], $tour['ngay_khoi_hanh']]);
                        $khach_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    }
                }
            }
        }
        
        // Lấy danh sách tour đang chạy
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.trang_thai IN ('DangChay', 'SapKhoiHanh')
                ORDER BY lkh.ngay_khoi_hanh ASC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/hdv/checkin.php';
    }
    
    /**
     * Lưu điểm check-in
     */
    public function saveDiemCheckin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            $_SESSION['error'] = 'Không có quyền';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $tour_id = $_POST['tour_id'] ?? 0;
        $diem_id = $_POST['diem_id'] ?? 0;
        
        $data = [
            'tour_id' => $tour_id,
            'ten_diem' => $_POST['ten_diem'] ?? '',
            'loai_diem' => $_POST['loai_diem'] ?? 'tap_trung',
            'thoi_gian_du_kien' => $_POST['thoi_gian_du_kien'] ?? null,
            'ghi_chu' => $_POST['ghi_chu'] ?? null,
            'thu_tu' => $_POST['thu_tu'] ?? 1
        ];
        
        try {
            if ($diem_id > 0) {
                // Update
                $sql = "UPDATE diem_checkin SET 
                        ten_diem = ?, loai_diem = ?, thoi_gian_du_kien = ?, 
                        ghi_chu = ?, thu_tu = ?
                        WHERE id = ?";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $data['ten_diem'], $data['loai_diem'], $data['thoi_gian_du_kien'],
                    $data['ghi_chu'], $data['thu_tu'], $diem_id
                ]);
                $_SESSION['success'] = 'Cập nhật điểm check-in thành công';
            } else {
                // Insert
                $sql = "INSERT INTO diem_checkin (tour_id, ten_diem, loai_diem, thoi_gian_du_kien, ghi_chu, thu_tu) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $data['tour_id'], $data['ten_diem'], $data['loai_diem'],
                    $data['thoi_gian_du_kien'], $data['ghi_chu'], $data['thu_tu']
                ]);
                $_SESSION['success'] = 'Thêm điểm check-in thành công';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/checkin&tour_id=' . $tour_id);
        exit;
    }
    
    /**
     * Xóa điểm check-in
     */
    public function deleteDiemCheckin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            $_SESSION['error'] = 'Không có quyền';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $diem_id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        try {
            $sql = "DELETE FROM diem_checkin WHERE id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$diem_id]);
            $_SESSION['success'] = 'Xóa điểm check-in thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/checkin&tour_id=' . $tour_id);
        exit;
    }
    
    /**
     * Lưu trạng thái check-in của khách
     */
    public function saveCheckinKhach() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Lấy nhan_su_id
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy thông tin HDV']);
            exit;
        }
        
        $diem_checkin_id = $_POST['diem_checkin_id'] ?? 0;
        $booking_id = $_POST['booking_id'] ?? 0;
        $trang_thai = $_POST['trang_thai'] ?? 'da_checkin';
        $ghi_chu = $_POST['ghi_chu'] ?? null;
        
        try {
            // Kiểm tra xem đã có bản ghi chưa
            $sql = "SELECT id FROM checkin_khach WHERE diem_checkin_id = ? AND booking_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$diem_checkin_id, $booking_id]);
            $existing = $stmt->fetch();
            
            if ($existing) {
                // Update
                $sql = "UPDATE checkin_khach SET 
                        trang_thai = ?, thoi_gian_checkin = NOW(), ghi_chu = ?, nguoi_checkin_id = ?
                        WHERE id = ?";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$trang_thai, $ghi_chu, $nhanSu['nhan_su_id'], $existing['id']]);
            } else {
                // Insert
                $sql = "INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id) 
                        VALUES (?, ?, ?, NOW(), ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$diem_checkin_id, $booking_id, $trang_thai, $ghi_chu, $nhanSu['nhan_su_id']]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Yêu cầu đặc biệt
     */
    /**
     * Yêu cầu đặc biệt
     */
    public function yeuCauDacBiet() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $tour = null;
        $yeu_cau_list = [];
        
        if ($tour_id > 0) {
            // Kiểm tra quyền
            $sql = "SELECT lkh.*, t.ten_tour, t.tour_id 
                    FROM lich_khoi_hanh lkh 
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                    WHERE lkh.id = ? AND lkh.hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id, $nhanSuId]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($tour) {
                // Lấy danh sách yêu cầu đặc biệt của khách trong tour
                $sql = "SELECT yc.*, b.booking_id, b.so_nguoi, nd.ho_ten, nd.email, nd.so_dien_thoai
                        FROM yeu_cau_dac_biet yc
                        INNER JOIN booking b ON yc.booking_id = b.booking_id
                        LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
                        LEFT JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
                        WHERE b.tour_id = ? AND DATE(b.ngay_khoi_hanh) = DATE(?) AND b.trang_thai IN ('DaCoc', 'HoanTat')
                        ORDER BY yc.muc_do_uu_tien DESC, yc.ngay_tao DESC";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([$tour['tour_id'], $tour['ngay_khoi_hanh']]);
                $yeu_cau_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        
        // Lấy danh sách tour
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.trang_thai IN ('DangChay', 'SapKhoiHanh')
                ORDER BY lkh.ngay_khoi_hanh ASC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Lấy danh sách booking để thêm yêu cầu
        $bookings_list = [];
        if ($tour) {
            $sql = "SELECT b.booking_id, nd.ho_ten, b.so_nguoi
                    FROM booking b
                    LEFT JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
                    LEFT JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
                    WHERE b.tour_id = ? AND DATE(b.ngay_khoi_hanh) = DATE(?) AND b.trang_thai IN ('DaCoc', 'HoanTat')
                    ORDER BY nd.ho_ten ASC";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour['tour_id'], $tour['ngay_khoi_hanh']]);
            $bookings_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        require 'views/hdv/yeu_cau_dac_biet.php';
    }
    
    /**
     * Lưu yêu cầu đặc biệt
     */
    public function saveYeuCauDacBiet() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            $_SESSION['error'] = 'Không có quyền';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $tour_id = $_POST['tour_id'] ?? 0;
        $yeu_cau_id = $_POST['yeu_cau_id'] ?? 0;
        
        // Lấy nhan_su_id
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $data = [
            'booking_id' => $_POST['booking_id'] ?? 0,
            'loai_yeu_cau' => $_POST['loai_yeu_cau'] ?? 'khac',
            'tieu_de' => $_POST['tieu_de'] ?? '',
            'mo_ta' => $_POST['mo_ta'] ?? null,
            'muc_do_uu_tien' => $_POST['muc_do_uu_tien'] ?? 'trung_binh',
            'trang_thai' => $_POST['trang_thai'] ?? 'moi',
            'ghi_chu_hdv' => $_POST['ghi_chu_hdv'] ?? null
        ];
        
        try {
            if ($yeu_cau_id > 0) {
                // Update
                $sql = "UPDATE yeu_cau_dac_biet SET 
                        loai_yeu_cau = ?, tieu_de = ?, mo_ta = ?, muc_do_uu_tien = ?, 
                        trang_thai = ?, ghi_chu_hdv = ?, nguoi_xu_ly_id = ?
                        WHERE id = ?";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $data['loai_yeu_cau'], $data['tieu_de'], $data['mo_ta'], 
                    $data['muc_do_uu_tien'], $data['trang_thai'], $data['ghi_chu_hdv'],
                    $nhanSu['nhan_su_id'], $yeu_cau_id
                ]);
                
                // Lưu lịch sử
                $sql = "INSERT INTO lich_su_yeu_cau (yeu_cau_id, hanh_dong, noi_dung, nguoi_thuc_hien_id) 
                        VALUES (?, 'cap_nhat', ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $yeu_cau_id, 
                    'Cập nhật trạng thái: ' . $data['trang_thai'], 
                    $userId
                ]);
                
                $_SESSION['success'] = 'Cập nhật yêu cầu thành công';
            } else {
                // Insert
                $sql = "INSERT INTO yeu_cau_dac_biet 
                        (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien, trang_thai, ghi_chu_hdv, nguoi_tao_id, nguoi_xu_ly_id) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $data['booking_id'], $data['loai_yeu_cau'], $data['tieu_de'], 
                    $data['mo_ta'], $data['muc_do_uu_tien'], $data['trang_thai'],
                    $data['ghi_chu_hdv'], $userId, $nhanSu['nhan_su_id']
                ]);
                
                $new_id = $this->nhanSuModel->conn->lastInsertId();
                
                // Lưu lịch sử
                $sql = "INSERT INTO lich_su_yeu_cau (yeu_cau_id, hanh_dong, noi_dung, nguoi_thuc_hien_id) 
                        VALUES (?, 'tao_moi', ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $new_id, 
                    'Tạo yêu cầu mới: ' . $data['tieu_de'], 
                    $userId
                ]);
                
                $_SESSION['success'] = 'Thêm yêu cầu đặc biệt thành công';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/yeu_cau_dac_biet&tour_id=' . $tour_id);
        exit;
    }
    
    /**
     * Xóa yêu cầu đặc biệt
     */
    public function deleteYeuCauDacBiet() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            $_SESSION['error'] = 'Không có quyền';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $yeu_cau_id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        try {
            $sql = "DELETE FROM yeu_cau_dac_biet WHERE id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$yeu_cau_id]);
            $_SESSION['success'] = 'Xóa yêu cầu thành công';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/yeu_cau_dac_biet&tour_id=' . $tour_id);
        exit;
    }
    
    /**
     * Đánh giá và phản hồi
     */
    public function danhGia() {
        $userId = $_SESSION['user_id'] ?? null;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/tours');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $danh_gia_list = [];
        $tour = null;
        
        if ($tour_id > 0) {
            // Kiểm tra quyền
            $sql = "SELECT lkh.*, t.ten_tour 
                    FROM lich_khoi_hanh lkh 
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                    WHERE lkh.id = ? AND lkh.hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id, $nhanSuId]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Lấy danh sách tour đã hoàn thành
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.trang_thai = 'HoanThanh'
                ORDER BY lkh.ngay_khoi_hanh DESC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require 'views/hdv/danh_gia.php';
    }
    
    /**
     * Thông báo
     */
    public function notifications() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=tour/index');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        // Lấy tất cả thông báo
        $notifications = $this->hdvMgmtModel->getThongBao($nhanSuId, 50);
        
        // Đánh dấu tất cả là đã đọc
        $sql = "UPDATE thong_bao_hdv SET da_xem = 1 WHERE nhan_su_id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        
        require 'views/hdv/notifications.php';
    }
    
    /**
     * Lưu nhật ký tour
     */
    public function saveNhatKy() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Lấy nhan_su_id - không kiểm tra vai_tro
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhân sự';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $tour_id = $_POST['tour_id'] ?? 0;
        $entry_id = $_POST['entry_id'] ?? 0;
        
        if ($tour_id <= 0) {
            $_SESSION['error'] = 'Thiếu thông tin tour';
            header('Location: index.php?act=hdv/nhat_ky');
            exit;
        }
        
        // Lấy tour - không kiểm tra quyền
        $sql = "SELECT lkh.*, t.tour_id as tour_table_id, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$tour) {
            $_SESSION['error'] = 'Tour không tồn tại (ID: ' . $tour_id . ')';
            header('Location: index.php?act=hdv/nhat_ky&tour_id=' . $tour_id);
            exit;
        }
        
        // Xử lý upload hình ảnh
        $imageUrls = [];
        if (isset($_FILES['hinh_anh']) && !empty($_FILES['hinh_anh']['name'][0])) {
            $uploadDir = 'uploads/nhat_ky/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $maxFiles = min(count($_FILES['hinh_anh']['name']), 5);
            for ($i = 0; $i < $maxFiles; $i++) {
                if ($_FILES['hinh_anh']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . $i . '_' . basename($_FILES['hinh_anh']['name'][$i]);
                    $targetFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'][$i], $targetFile)) {
                        $imageUrls[] = $targetFile;
                    }
                }
            }
        }
        
        $data = [
            'tour_id' => $tour['tour_table_id'], // Sử dụng tour_id từ bảng tour
            'nhan_su_id' => $nhanSuId,
            'loai_nhat_ky' => $_POST['loai_nhat_ky'] ?? 'hanh_trinh',
            'tieu_de' => $_POST['tieu_de'] ?? '',
            'noi_dung' => $_POST['noi_dung'] ?? '',
            'ngay_ghi' => $_POST['ngay_ghi'] ?? date('Y-m-d H:i:s'),
            'cach_xu_ly' => $_POST['cach_xu_ly'] ?? null,
            'hinh_anh' => !empty($imageUrls) ? json_encode($imageUrls) : null
        ];
        
        try {
            if ($entry_id > 0) {
                // Update - chỉ update hinh_anh nếu có upload mới
                if (!empty($imageUrls)) {
                    $sql = "UPDATE nhat_ky_tour SET 
                            loai_nhat_ky = ?, tieu_de = ?, noi_dung = ?, ngay_ghi = ?, 
                            cach_xu_ly = ?, hinh_anh = ?
                            WHERE id = ? AND nhan_su_id = ?";
                    $stmt = $this->nhanSuModel->conn->prepare($sql);
                    $result = $stmt->execute([
                        $data['loai_nhat_ky'], $data['tieu_de'], $data['noi_dung'], 
                        $data['ngay_ghi'], $data['cach_xu_ly'], $data['hinh_anh'],
                        $entry_id, $nhanSuId
                    ]);
                } else {
                    $sql = "UPDATE nhat_ky_tour SET 
                            loai_nhat_ky = ?, tieu_de = ?, noi_dung = ?, ngay_ghi = ?, 
                            cach_xu_ly = ?
                            WHERE id = ? AND nhan_su_id = ?";
                    $stmt = $this->nhanSuModel->conn->prepare($sql);
                    $result = $stmt->execute([
                        $data['loai_nhat_ky'], $data['tieu_de'], $data['noi_dung'], 
                        $data['ngay_ghi'], $data['cach_xu_ly'],
                        $entry_id, $nhanSuId
                    ]);
                }
                $_SESSION['success'] = 'Cập nhật nhật ký thành công';
            } else {
                // Insert new entry
                $sql = "INSERT INTO nhat_ky_tour 
                        (tour_id, nhan_su_id, loai_nhat_ky, tieu_de, noi_dung, ngay_ghi, cach_xu_ly, hinh_anh) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $result = $stmt->execute([
                    $data['tour_id'], $data['nhan_su_id'], $data['loai_nhat_ky'], 
                    $data['tieu_de'], $data['noi_dung'], $data['ngay_ghi'], 
                    $data['cach_xu_ly'], $data['hinh_anh']
                ]);
                $_SESSION['success'] = 'Thêm nhật ký thành công';
            }
            
            if (!$result) {
                $_SESSION['error'] = 'Lỗi khi lưu nhật ký';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/nhat_ky&tour_id=' . $tour_id);
        exit;
    }
    
    /**
     * Xóa nhật ký tour
     */
    public function deleteNhatKy() {
        if (!isset($_SESSION['user_id']) || $_SESSION['vai_tro'] !== 'HDV') {
            $_SESSION['error'] = 'Không có quyền';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $entry_id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        // Lấy nhan_su_id
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV';
            header('Location: index.php?act=hdv/tours');
            exit;
        }
        
        try {
            // Lấy thông tin nhật ký để xóa hình ảnh
            $sql = "SELECT hinh_anh FROM nhat_ky_tour WHERE id = ? AND nhan_su_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$entry_id, $nhanSu['nhan_su_id']]);
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($entry) {
                // Xóa hình ảnh
                if (!empty($entry['hinh_anh'])) {
                    $images = json_decode($entry['hinh_anh'], true);
                    if ($images && is_array($images)) {
                        foreach ($images as $img) {
                            if (file_exists($img)) {
                                unlink($img);
                            }
                        }
                    }
                }
                
                // Xóa nhật ký
                $sql = "DELETE FROM nhat_ky_tour WHERE id = ? AND nhan_su_id = ?";
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $result = $stmt->execute([$entry_id, $nhanSu['nhan_su_id']]);
                
                if ($result) {
                    $_SESSION['success'] = 'Xóa nhật ký thành công';
                } else {
                    $_SESSION['error'] = 'Lỗi khi xóa nhật ký';
                }
            } else {
                $_SESSION['error'] = 'Không tìm thấy nhật ký';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/nhat_ky&tour_id=' . $tour_id);
        exit;
    }

    // ==================== PHẢN HỒI ĐÁNH GIÁ ====================
    public function phanHoi() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/dashboard');
            exit;
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        // Lấy tour được chọn
        $tour_id = $_GET['tour_id'] ?? 0;
        $loai_filter = $_GET['loai'] ?? '';
        
        $tour = null;
        if ($tour_id) {
            $sql = "SELECT lkh.id, lkh.tour_id, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, t.ten_tour, t.tour_id
                    FROM lich_khoi_hanh lkh
                    LEFT JOIN tour t ON lkh.tour_id = t.tour_id
                    WHERE lkh.id = ? AND lkh.hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour_id, $nhanSuId]);
            $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Lấy danh sách phản hồi
        $phan_hoi_list = [];
        if ($tour) {
            $sql = "SELECT ph.*, t.ten_tour, ns.ho_ten as ten_nguoi_xu_ly
                    FROM phan_hoi_hdv ph
                    LEFT JOIN tour t ON ph.tour_id = t.tour_id
                    LEFT JOIN nhan_su ns ON ph.nguoi_xu_ly_id = ns.nhan_su_id
                    WHERE ph.tour_id = ? AND ph.hdv_id = ?";
            
            $params = [$tour['tour_id'], $nhanSuId];
            
            if ($loai_filter) {
                $sql .= " AND ph.loai_danh_gia = ?";
                $params[] = $loai_filter;
            }
            
            $sql .= " ORDER BY ph.ngay_tao DESC";
            
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute($params);
            $phan_hoi_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // Lấy danh sách tour đã hoàn thành
        $sql = "SELECT lkh.id, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc, t.ten_tour 
                FROM lich_khoi_hanh lkh 
                LEFT JOIN tour t ON lkh.tour_id = t.tour_id 
                WHERE lkh.hdv_id = ? AND lkh.trang_thai = 'DaHoanThanh'
                ORDER BY lkh.ngay_khoi_hanh DESC";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$nhanSuId]);
        $tours_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thống kê
        $stats = ['tong' => 0, 'moi' => 0, 'da_xem' => 0, 'da_xu_ly' => 0, 'diem_tb' => 0];
        if ($tour) {
            $sql = "SELECT 
                    COUNT(*) as tong,
                    SUM(CASE WHEN trang_thai = 'moi' THEN 1 ELSE 0 END) as moi,
                    SUM(CASE WHEN trang_thai = 'da_xem' THEN 1 ELSE 0 END) as da_xem,
                    SUM(CASE WHEN trang_thai = 'da_xu_ly' THEN 1 ELSE 0 END) as da_xu_ly,
                    AVG(diem_danh_gia) as diem_tb
                    FROM phan_hoi_hdv 
                    WHERE tour_id = ? AND hdv_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([$tour['tour_id'], $nhanSuId]);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        require 'views/hdv/phan_hoi.php';
    }
    
    public function savePhanHoi() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/dashboard');
            exit;
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $id = $_POST['id'] ?? 0;
        $tour_id = $_POST['tour_id'] ?? 0;
        $loai_danh_gia = $_POST['loai_danh_gia'] ?? '';
        $ten_doi_tuong = $_POST['ten_doi_tuong'] ?? '';
        $diem_danh_gia = $_POST['diem_danh_gia'] ?? 0;
        $tieu_de = $_POST['tieu_de'] ?? '';
        $noi_dung = $_POST['noi_dung'] ?? '';
        $diem_manh = $_POST['diem_manh'] ?? '';
        $diem_yeu = $_POST['diem_yeu'] ?? '';
        $de_xuat = $_POST['de_xuat'] ?? '';
        
        // Xử lý upload ảnh
        $hinh_anh = [];
        if (isset($_FILES['hinh_anh']) && is_array($_FILES['hinh_anh']['name'])) {
            $upload_dir = 'uploads/phan_hoi/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            foreach ($_FILES['hinh_anh']['name'] as $key => $name) {
                if ($_FILES['hinh_anh']['error'][$key] == 0) {
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $filename = uniqid() . '_' . time() . '.' . $ext;
                    $target = $upload_dir . $filename;
                    
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'][$key], $target)) {
                        $hinh_anh[] = $target;
                    }
                }
            }
        }
        
        // Lấy tour_id thực tế từ lich_khoi_hanh
        $sql = "SELECT tour_id FROM lich_khoi_hanh WHERE id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$tour_id]);
        $tour_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $actual_tour_id = $tour_data['tour_id'];
        
        try {
            if ($id > 0) {
                // Update
                $sql = "UPDATE phan_hoi_hdv SET 
                        loai_danh_gia = ?, ten_doi_tuong = ?, diem_danh_gia = ?,
                        tieu_de = ?, noi_dung = ?, diem_manh = ?, diem_yeu = ?, de_xuat = ?";
                
                $params = [$loai_danh_gia, $ten_doi_tuong, $diem_danh_gia, 
                          $tieu_de, $noi_dung, $diem_manh, $diem_yeu, $de_xuat];
                
                if (!empty($hinh_anh)) {
                    $sql .= ", hinh_anh = ?";
                    $params[] = json_encode($hinh_anh);
                }
                
                $sql .= " WHERE id = ? AND hdv_id = ?";
                $params[] = $id;
                $params[] = $nhanSuId;
                
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute($params);
                
                $_SESSION['success'] = 'Cập nhật phản hồi thành công!';
            } else {
                // Insert
                $sql = "INSERT INTO phan_hoi_hdv 
                        (tour_id, hdv_id, loai_danh_gia, ten_doi_tuong, diem_danh_gia, 
                         tieu_de, noi_dung, diem_manh, diem_yeu, de_xuat, hinh_anh) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $this->nhanSuModel->conn->prepare($sql);
                $stmt->execute([
                    $actual_tour_id, $nhanSuId, $loai_danh_gia, $ten_doi_tuong, $diem_danh_gia,
                    $tieu_de, $noi_dung, $diem_manh, $diem_yeu, $de_xuat, json_encode($hinh_anh)
                ]);
                
                $_SESSION['success'] = 'Gửi phản hồi thành công!';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/phan_hoi&tour_id=' . $tour_id);
        exit;
    }
    
    public function deletePhanHoi() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/dashboard');
            exit;
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        $id = $_GET['id'] ?? 0;
        $tour_id = $_GET['tour_id'] ?? 0;
        
        // Xóa ảnh trước
        $sql = "SELECT hinh_anh FROM phan_hoi_hdv WHERE id = ? AND hdv_id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$id, $nhanSuId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row['hinh_anh']) {
            $hinh_anh = json_decode($row['hinh_anh'], true);
            if (is_array($hinh_anh)) {
                foreach ($hinh_anh as $img) {
                    if (file_exists($img)) {
                        unlink($img);
                    }
                }
            }
        }
        
        // Xóa phản hồi
        $sql = "DELETE FROM phan_hoi_hdv WHERE id = ? AND hdv_id = ?";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$id, $nhanSuId]);
        
        $_SESSION['success'] = 'Xóa phản hồi thành công!';
        header('Location: index.php?act=hdv/phan_hoi&tour_id=' . $tour_id);
        exit;
    }
    
    // ==================== HỒ SƠ HDV ====================
    public function profile() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        $sql = "SELECT ns.*, nd.ho_ten, nd.email, nd.so_dien_thoai, nd.avatar
                FROM nhan_su ns
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE ns.nguoi_dung_id = ? AND ns.vai_tro = 'HDV' 
                LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $hdv_info = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$hdv_info) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=hdv/dashboard');
            exit;
        }
        
        require 'views/hdv/profile.php';
    }
    
    public function updateProfile() {
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit;
        }
        
        try {
            // Cập nhật bảng nguoi_dung
            $sql = "UPDATE nguoi_dung SET 
                    email = ?, 
                    so_dien_thoai = ?
                    WHERE id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([
                $_POST['email'] ?? '',
                $_POST['so_dien_thoai'] ?? '',
                $userId
            ]);
            
            // Cập nhật bảng nhan_su
            $sql = "UPDATE nhan_su SET 
                    chung_chi = ?, 
                    ngon_ngu = ?, 
                    kinh_nghiem = ?, 
                    suc_khoe = ?
                    WHERE nguoi_dung_id = ?";
            $stmt = $this->nhanSuModel->conn->prepare($sql);
            $stmt->execute([
                $_POST['chung_chi'] ?? null,
                $_POST['ngon_ngu'] ?? null,
                $_POST['kinh_nghiem'] ?? null,
                $_POST['suc_khoe'] ?? null,
                $userId
            ]);
            
            $_SESSION['success'] = 'Cập nhật hồ sơ thành công!';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=hdv/profile');
        exit;
    }
}

