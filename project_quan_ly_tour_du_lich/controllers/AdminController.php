<?php

class AdminController {
    
    public function __construct() {
        requireRole('Admin');
        // khi vào gốc dự án sẽ gọi new AdminController(). Trong AdminController::__construct() có requireRole('Admin') → requireLogin() → nếu chưa đăng nhập thì chuyển hướng sang auth/login. Nên luôn thấy trang đăng nhập trước khi có session.
    }
    
    public function dashboard() {
        require 'views/admin/dashboard.php';
    }
    
    public function quanLyTour() {
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        $tours = $tourModel->getAll();
        require 'views/admin/quan_ly_tour.php';
    }
    
    public function chiTietTour() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $tour = null;
        $lichTrinhList = [];
        $lichKhoiHanhList = [];
        $hinhAnhList = [];
        $error = null;

        if ($id <= 0) {
            $error = 'Thiếu mã tour cần xem chi tiết.';
        } else {
            require_once 'models/Tour.php';
            require_once 'models/LichKhoiHanh.php';
            $tourModel = new Tour();
            $lichKhoiHanhModel = new LichKhoiHanh();
            $tour = $tourModel->findById($id);
            if (!$tour) {
                $error = 'Tour không tồn tại hoặc đã bị xóa.';
            } else {
                $lichTrinhList = $tourModel->getLichTrinhByTourId($id);
                $lichKhoiHanhList = $lichKhoiHanhModel->getByTourId($id);
                $hinhAnhList = $tourModel->getHinhAnhByTourId($id);
            }
        }

        require 'views/admin/chi_tiet_tour_admin.php';
    }
    public function quanLyNguoiDung() {
        require 'views/admin/quan_ly_nguoi_dung.php';
    }
    
    public function quanLyBooking() {
        require_once 'models/Booking.php';
        require_once 'models/Tour.php';
        require_once 'models/KhachHang.php';
        
        $bookingModel = new Booking();
        $conditions = [];
        
        // Lọc theo trạng thái nếu có
        if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])) {
            $conditions['trang_thai'] = $_GET['trang_thai'];
        }
        
        if (!empty($conditions)) {
            $bookings = $bookingModel->find($conditions);
        } else {
            $bookings = $bookingModel->getAllWithDetails();
        }
        
        require 'views/admin/quan_ly_booking.php';
    }
    
    public function baoCaoTaiChinh() {
        require 'views/admin/bao_cao_tai_chinh.php';
    }
    public function addNhacungcap() {
        require 'views/admin/nha_cung_cap.php';
    }
    public function danhGia() {
        require 'views/admin/danh_gia.php';
    }
    public function nhanSu() {
        require_once 'models/NhanSu.php';
        $nhanSuModel = new NhanSu();
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        // load available roles for tabs
        $roles = $nhanSuModel->getRoles();
        
        // build data grouped by role (for tabs)
        $data_by_role = [];
        if (!empty($roles)) {
            foreach ($roles as $r) {
                $data_by_role[$r] = $nhanSuModel->getByRole($r);
            }
        }
        
        // apply filters: if search query, search across all; if role filter, use that role's data
        if ($q !== '') {
            $nhan_su_list = $nhanSuModel->search($q);
            $active_role = null;
        } elseif ($role !== '' && isset($data_by_role[$role])) {
            $nhan_su_list = $data_by_role[$role];
            $active_role = $role;
        } else {
            $nhan_su_list = $nhanSuModel->getAll();
            $active_role = null;
        }
        
        require 'views/admin/quan_ly_nhan_su.php';
    }

    // Admin: quản lý HDV (danh sách + CRUD cơ bản)
    public function quanLyHDV() {
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($q !== '') {
            // sử dụng search trên nhan_su (tạm gọi chung)
            require_once 'models/NhanSu.php';
            $ns = new NhanSu();
            $hdv_list = $ns->search($q);
        } else {
            $hdv_list = $hdvModel->getAll($groupId);
        }
        // load groups
        $groups = [];
        try {
            $stmt = $hdvModel->conn->prepare('SELECT * FROM hdv_groups ORDER BY name ASC');
            $stmt->execute();
            $groups = $stmt->fetchAll();
        } catch (Exception $e) {
            // ignore if table not exists
        }
        require 'views/admin/quan_ly_hdv.php';
    }

    public function quanLyHDVCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/HDV.php';
            $model = new HDV();
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'anh' => $_POST['anh'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'dia_chi' => $_POST['dia_chi'] ?? null,
                'chung_chi' => $_POST['chung_chi'] ?? null,
                'ngon_ngu' => $_POST['ngon_ngu'] ?? null,
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? null,
                'suc_khoe' => $_POST['suc_khoe'] ?? null,
                'group_id' => $_POST['group_id'] ?? null,
                'note' => $_POST['note'] ?? null,
            ];
            $ok = $model->insert($data);
            $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Thêm HDV thành công'] : ['type'=>'danger','message'=>'Thêm HDV thất bại'];
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    public function quanLyHDVUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/HDV.php';
            $model = new HDV();
            $id = isset($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : 0;
            if ($id > 0) {
                $data = [
                    'ho_ten' => $_POST['ho_ten'] ?? '',
                    'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                    'anh' => $_POST['anh'] ?? null,
                    'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                    'email' => $_POST['email'] ?? null,
                    'dia_chi' => $_POST['dia_chi'] ?? null,
                    'chung_chi' => $_POST['chung_chi'] ?? null,
                    'ngon_ngu' => $_POST['ngon_ngu'] ?? null,
                    'kinh_nghiem' => $_POST['kinh_nghiem'] ?? null,
                    'suc_khoe' => $_POST['suc_khoe'] ?? null,
                    'group_id' => $_POST['group_id'] ?? null,
                    'is_available' => isset($_POST['is_available']) ? 1 : 0,
                    'note' => $_POST['note'] ?? null,
                ];
                $ok = $model->update($id, $data);
                $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Cập nhật HDV thành công'] : ['type'=>'danger','message'=>'Cập nhật HDV thất bại'];
            }
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    public function quanLyHDVDelete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            require_once 'models/HDV.php';
            $model = new HDV();
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Xóa HDV thành công'] : ['type'=>'danger','message'=>'Xóa HDV thất bại'];
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    // Hiển thị lịch phân công HDV (calendar)
    public function hdvSchedule() {
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        // load hdv list
        $hdv_list = $hdvModel->getAll();
        require 'views/admin/hdv_schedule.php';
    }

    // Trang hồ sơ HDV
    public function hdvProfile() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        $hdv = $hdvModel->findById($id);
        $history = [];
        if ($hdv) {
            $history = $hdvModel->getTourHistory($id, 100);
        }
        require 'views/admin/hdv_profile.php';
    }

    // API: trả về lịch của HDV (JSON)
    public function hdvApiGetSchedule() {
        header('Content-Type: application/json');
        $hdvId = isset($_GET['hdv_id']) ? (int)$_GET['hdv_id'] : 0;
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
        $events = [];
        if ($hdvId > 0) {
            $rows = $hdvModel->getSchedule($hdvId, $from, $to);
            foreach ($rows as $r) {
                $events[] = [
                    'id' => $r['id'],
                    'title' => 'Tour ' . ($r['tour_id'] ?? ''),
                    'start' => $r['start_time'],
                    'end' => $r['end_time'],
                    'extendedProps' => ['note' => $r['note'] ?? '']
                ];
            }
        }
        echo json_encode($events);
        exit;
    }

    // API: kiểm tra khả dụng
    public function hdvApiCheck() {
        header('Content-Type: application/json');
        $hdvId = isset($_GET['hdv_id']) ? (int)$_GET['hdv_id'] : 0;
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        $ok = false;
        if ($hdvId && $start && $end) {
            $ok = $hdvModel->isAvailable($hdvId, $start, $end);
        }
        echo json_encode(['available' => $ok]);
        exit;
    }

    // API: phân công (thêm schedule) — POST JSON {hdv_id, tour_id, start, end, note}
    public function hdvApiAssign() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['ok'=>false,'msg'=>'Method not allowed']); exit; }
        $payload = $_POST;
        $hdvId = isset($payload['hdv_id']) ? (int)$payload['hdv_id'] : 0;
        $tourId = isset($payload['tour_id']) ? (int)$payload['tour_id'] : null;
        $start = $payload['start'] ?? null;
        $end = $payload['end'] ?? null;
        $note = $payload['note'] ?? null;
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        if (!$hdvId || !$start || !$end) { echo json_encode(['ok'=>false,'msg'=>'Thiếu dữ liệu']); exit; }
        if (!$hdvModel->isAvailable($hdvId, $start, $end)) {
            echo json_encode(['ok'=>false,'msg'=>'HDV không rảnh trong khung thời gian này']); exit;
        }
        $ok = $hdvModel->addSchedule($hdvId, $tourId, $start, $end, $note);
        echo json_encode(['ok'=>$ok]); exit;
    }

    // API: đề xuất HDV rảnh cho khoảng thời gian (trả danh sách hdv_id)
    public function hdvApiSuggest() {
        header('Content-Type: application/json');
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;
        require_once 'models/HDV.php';
        $hdvModel = new HDV();
        $candidates = $hdvModel->getAll($groupId, true);
        $available = [];
        foreach ($candidates as $c) {
            if ($hdvModel->isAvailable($c['nhan_su_id'], $start, $end)) {
                $available[] = ['id'=>$c['nhan_su_id'],'ho_ten'=>$c['ho_ten']];
            }
        }
        echo json_encode(['available'=>$available]); exit;
    }

    public function nhanSuCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/NhanSu.php';
            $model = new NhanSu();
            $data = [
                    'nguoi_dung_id' => $_POST['nguoi_dung_id'] ?? null,
                    'vai_tro' => $_POST['vai_tro'] ?? 'Khac',
                'chung_chi' => $_POST['chung_chi'] ?? '',
                'ngon_ngu' => $_POST['ngon_ngu'] ?? '',
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? '',
                'suc_khoe' => $_POST['suc_khoe'] ?? '',
            ];
            
                // Validate: người dùng phải được chọn
                if (empty($data['nguoi_dung_id'])) {
                    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn người dùng.'];
                } else {
                    $ok = $model->insert($data);
                    if ($ok) {
                        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thêm nhân sự thành công. Vai trò người dùng đã được cập nhật.'];
                    } else {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Thêm nhân sự thất bại.'];
                    }
                }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    // API: trả về danh sách người dùng chưa có nhân sự (JSON)
    public function nhanSu_get_users() {
        require_once 'models/NhanSu.php';
        $model = new NhanSu();
        $users = $model->getAvailableUsers();
        header('Content-Type: application/json');
        echo json_encode(['users' => $users]);
        exit;
    }

    public function nhanSuUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/NhanSu.php';
            $model = new NhanSu();
            $id = isset($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : 0;
            if ($id <= 0) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID nhân sự không hợp lệ.'];
                header('Location: index.php?act=admin/nhanSu');
                exit;
            }
            $data = [
                'vai_tro' => $_POST['vai_tro'] ?? 'Khac',
                'chung_chi' => $_POST['chung_chi'] ?? '',
                'ngon_ngu' => $_POST['ngon_ngu'] ?? '',
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? '',
                'suc_khoe' => $_POST['suc_khoe'] ?? '',
            ];
            $ok = $model->update($id, $data);
            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cập nhật nhân sự thành công.'];
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cập nhật nhân sự thất bại.'];
            }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    public function nhanSuDelete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $delete_user = isset($_GET['delete_user']) && $_GET['delete_user'] === '1' ? true : false;
        
        if ($id > 0) {
            require_once 'models/NhanSu.php';
            $model = new NhanSu();
            if ($delete_user) {
                // kiểm tra blocker quan trọng trước khi xóa (chỉ tour.tao_boi)
                $nhanSu = $model->findById($id);
                if ($nhanSu && !empty($nhanSu['nguoi_dung_id'])) {
                    $blockers = $model->getCriticalDeleteBlockers($nhanSu['nguoi_dung_id']);
                    if (!empty($blockers)) {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể xóa tài khoản do: ' . implode(' ', $blockers)];
                        header('Location: index.php?act=admin/nhanSu');
                        exit;
                    }
                }
                $ok = $model->deleteWithUser($id);
                $msg = $ok ? 'Xóa nhân sự, tài khoản và dữ liệu liên quan thành công.' : 'Xóa nhân sú và tài khoản thất bại.';
            } else {
                $ok = $model->delete($id);
                $msg = $ok ? 'Xóa nhân sự thành công. Tài khoản vẫn được giữ.' : 'Xóa nhân sự thất bại.';
            }
            
            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Xóa thất bại.'];
            }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    // Xem chi tiết sơ yếu lý lịch nhân sự
    public function nhanSuChiTiet() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nhanSu = null;
        $error = null;

        if ($id <= 0) {
            $error = 'Thiếu mã nhân sự cần xem.';
        } else {
            require_once 'models/NhanSu.php';
            $model = new NhanSu();
            $nhanSu = $model->findById($id);
            
            if (!$nhanSu) {
                $error = 'Nhân sự không tồn tại hoặc đã bị xóa.';
            } else {
                // Lấy thêm thông tin vai trò người dùng
                if (!empty($nhanSu['nguoi_dung_id'])) {
                    require_once 'models/NguoiDung.php';
                    $nguoiDungModel = new NguoiDung();
                    $nguoiDung = $nguoiDungModel->findById($nhanSu['nguoi_dung_id']);
                    if ($nguoiDung) {
                        $nhanSu['vai_tro_nguoi_dung'] = $nguoiDung['vai_tro'];
                        $nhanSu['quyen_cap_cao'] = $nguoiDung['quyen_cap_cao'];
                        $nhanSu['trang_thai'] = $nguoiDung['trang_thai'];
                        $nhanSu['ngay_tao'] = $nguoiDung['ngay_tao'];
                        $nhanSu['avatar'] = $nguoiDung['avatar'];
                    }
                }
            }
        }

        require 'views/admin/nhan_su_chi_tiet.php';
    }

    // ==================== QUẢN LÝ HDV NÂNG CAO (SỬ DỤNG DATABASE HIỆN CÓ) ====================
    
    public function hdvAdvanced() {
        require_once 'models/HDVManagement.php';
        $hdvMgmt = new HDVManagement();
        
        $hdv_list = $hdvMgmt->getAllHDV();
        $stats = $hdvMgmt->getThongKeTongQuan();
        $hieu_suat_list = $hdvMgmt->getBaoCaoHieuSuat();
        $thong_bao_list = []; // Tạm thời empty vì chưa có bảng thông báo
        
        require 'views/admin/hdv_quan_ly_nang_cao.php';
    }

    public function hdvAddSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/HDVManagement.php';
            $hdvMgmt = new HDVManagement();
            
            $data = [
                'tour_id' => $_POST['tour_id'],
                'hdv_id' => $_POST['hdv_id'],
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'diem_tap_trung' => $_POST['diem_tap_trung'] ?? '',
                'trang_thai' => $_POST['trang_thai'] ?? 'DaXacNhan'
            ];
            
            $result = $hdvMgmt->phanCongHDV($data);
            $_SESSION['flash'] = [
                'type' => $result['success'] ? 'success' : 'danger',
                'message' => $result['message']
            ];
        }
        
        header('Location: index.php?act=admin/hdv_advanced');
        exit;
    }

    public function hdvGetSchedule() {
        require_once 'models/HDVManagement.php';
        $hdvMgmt = new HDVManagement();
        
        $hdv_id = $_GET['hdv_id'] ?? null;
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        
        $events = $hdvMgmt->getLichLamViec($hdv_id, $start, $end);
        
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }

    public function hdvSendNotification() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Tạm thời lưu vào session vì không có bảng thông báo
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Gửi thông báo thành công! (Chức năng demo - chưa lưu database)'
            ];
        }
        
        header('Location: index.php?act=admin/hdv_advanced');
        exit;
    }

    public function hdvDetail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        require_once 'models/HDVManagement.php';
        require_once 'models/NhanSu.php';
        
        $hdvMgmt = new HDVManagement();
        $nhanSuModel = new NhanSu();
        
        $hdv = $nhanSuModel->findById($id);
        $hieu_suat = $hdvMgmt->getHieuSuatTheoThang($id);
        $danh_gia_list = $hdvMgmt->getDanhGiaByHDV($id);
        $lich_lam_viec = $hdvMgmt->getLichLamViec($id);
        $nhat_ky_list = $hdvMgmt->getNhatKyByHDV($id);
        
        require 'views/admin/hdv_chi_tiet.php';
    }
    
    private function chonAnhChinh(array $hinhAnhList) {
        foreach ($hinhAnhList as $anh) {
            if (!empty($anh['url_anh'])) {
                return $anh;
            }
        }
        return null;
    }

    // ========== QUẢN LÝ KHÁCH THEO TOUR ==========
    
    // Danh sách khách theo tour
    public function danhSachKhachTheoTour() {
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
        
        require_once 'models/Tour.php';
        require_once 'models/LichKhoiHanh.php';
        require_once 'models/Booking.php';
        require_once 'models/TourCheckin.php';
        require_once 'models/HotelRoomAssignment.php';
        
        $tourModel = new Tour();
        $lichKhoiHanhModel = new LichKhoiHanh();
        $bookingModel = new Booking();
        $checkinModel = new TourCheckin();
        $roomModel = new HotelRoomAssignment();
        
        $tour = null;
        $lichKhoiHanh = null;
        $bookingList = [];
        $lichKhoiHanhList = [];
        $checkinStats = null;
        $roomStats = null;
        
        if ($lichKhoiHanhId > 0) {
            $lichKhoiHanh = $lichKhoiHanhModel->findById($lichKhoiHanhId);
            if ($lichKhoiHanh) {
                $tourId = $lichKhoiHanh['tour_id'];
                $tour = $tourModel->findById($tourId);
                
                // Lấy danh sách booking theo lịch khởi hành
                $sql = "SELECT b.*, 
                               nd.ho_ten as khach_ho_ten, 
                               nd.email, 
                               nd.so_dien_thoai,
                               tc.id as checkin_id, 
                               tc.trang_thai as checkin_status
                        FROM booking b
                        LEFT JOIN khach_hang k ON b.khach_hang_id = k.khach_hang_id
                        LEFT JOIN nguoi_dung nd ON k.nguoi_dung_id = nd.id
                        LEFT JOIN tour_checkin tc ON b.booking_id = tc.booking_id
                        WHERE b.tour_id = ? 
                        AND b.ngay_khoi_hanh = (SELECT ngay_khoi_hanh FROM lich_khoi_hanh WHERE id = ?)
                        ORDER BY b.ngay_dat DESC";
                $stmt = $bookingModel->conn->prepare($sql);
                $stmt->execute([$tourId, $lichKhoiHanhId]);
                $bookingList = $stmt->fetchAll();
                
                // Lấy thống kê
                $checkinStats = $checkinModel->getStatsByLichKhoiHanh($lichKhoiHanhId);
                $roomStats = $roomModel->getStatsByLichKhoiHanh($lichKhoiHanhId);
            }
        } else if ($tourId > 0) {
            $tour = $tourModel->findById($tourId);
            $lichKhoiHanhList = $lichKhoiHanhModel->getByTourId($tourId);
        }
        
        require 'views/admin/danh_sach_khach.php';
    }
    
    // Check-in khách
    public function checkInKhach() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/TourCheckin.php';
            $checkinModel = new TourCheckin();
            
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? 0,
                'booking_id' => $_POST['booking_id'] ?? 0,
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_cmnd' => $_POST['so_cmnd'] ?? null,
                'so_passport' => $_POST['so_passport'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            if ($checkinModel->insert($data)) {
                $_SESSION['success'] = 'Check-in khách thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi check-in!';
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
            exit;
        }
        
        // GET: hiển thị form check-in
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        require_once 'models/Booking.php';
        require_once 'models/TourCheckin.php';
        
        $bookingModel = new Booking();
        $checkinModel = new TourCheckin();
        
        $booking = $bookingModel->findById($bookingId);
        $checkin = $checkinModel->getByBookingId($bookingId);
        
        require 'views/admin/check_in.php';
    }
    
    // Cập nhật check-in
    public function updateCheckIn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/TourCheckin.php';
            $checkinModel = new TourCheckin();
            
            $id = $_POST['id'] ?? 0;
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_cmnd' => $_POST['so_cmnd'] ?? null,
                'so_passport' => $_POST['so_passport'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'trang_thai' => $_POST['trang_thai'] ?? 'DaCheckIn',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            if ($checkinModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật check-in thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
            }
            
            $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? 0;
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit;
        }
    }
    
    // Phân phòng khách sạn
    public function phanPhongKhachSan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/HotelRoomAssignment.php';
            $roomModel = new HotelRoomAssignment();
            
            $action = $_POST['action'] ?? 'add';
            $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? 0;
            
            if ($action === 'add') {
                $data = [
                    'lich_khoi_hanh_id' => $lichKhoiHanhId,
                    'booking_id' => $_POST['booking_id'] ?? 0,
                    'checkin_id' => $_POST['checkin_id'] ?? null,
                    'ten_khach_san' => $_POST['ten_khach_san'] ?? '',
                    'so_phong' => $_POST['so_phong'] ?? '',
                    'loai_phong' => $_POST['loai_phong'] ?? 'Standard',
                    'so_giuong' => $_POST['so_giuong'] ?? 1,
                    'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? null,
                    'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? null,
                    'gia_phong' => $_POST['gia_phong'] ?? 0,
                    'trang_thai' => $_POST['trang_thai'] ?? 'DaDatPhong',
                    'ghi_chu' => $_POST['ghi_chu'] ?? null
                ];
                
                if ($roomModel->insert($data)) {
                    $_SESSION['success'] = 'Phân phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi phân phòng!';
                }
            } else if ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                $data = [
                    'ten_khach_san' => $_POST['ten_khach_san'] ?? '',
                    'so_phong' => $_POST['so_phong'] ?? '',
                    'loai_phong' => $_POST['loai_phong'] ?? 'Standard',
                    'so_giuong' => $_POST['so_giuong'] ?? 1,
                    'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? null,
                    'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? null,
                    'gia_phong' => $_POST['gia_phong'] ?? 0,
                    'trang_thai' => $_POST['trang_thai'] ?? 'DaDatPhong',
                    'ghi_chu' => $_POST['ghi_chu'] ?? null
                ];
                
                if ($roomModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
                }
            } else if ($action === 'delete') {
                $id = $_POST['id'] ?? 0;
                if ($roomModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa phân phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa!';
                }
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit;
        }
        
        // GET: hiển thị form phân phòng
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        
        require_once 'models/Booking.php';
        require_once 'models/HotelRoomAssignment.php';
        require_once 'models/TourCheckin.php';
        
        $bookingModel = new Booking();
        $roomModel = new HotelRoomAssignment();
        $checkinModel = new TourCheckin();
        
        $booking = null;
        $roomList = [];
        $hotelList = [];
        $checkin = null;
        
        if ($bookingId > 0) {
            // Lấy thông tin booking với thông tin khách hàng
            $sql = "SELECT b.*, 
                           nd.ho_ten, 
                           nd.email, 
                           nd.so_dien_thoai
                    FROM booking b
                    LEFT JOIN khach_hang k ON b.khach_hang_id = k.khach_hang_id
                    LEFT JOIN nguoi_dung nd ON k.nguoi_dung_id = nd.id
                    WHERE b.booking_id = ?";
            $stmt = $bookingModel->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();
            
            $roomList = $roomModel->getByBookingId($bookingId);
            $checkin = $checkinModel->getByBookingId($bookingId);
        }
        
        if ($lichKhoiHanhId > 0) {
            $hotelList = $roomModel->getHotelList();
        }
        
        require 'views/admin/phan_phong.php';
    }
}
