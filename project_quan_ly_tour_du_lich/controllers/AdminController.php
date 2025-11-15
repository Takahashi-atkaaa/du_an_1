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
        require_once 'controllers/NhanSuController.php';
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
                // kiểm tra blocker trước khi xóa
                $nhanSu = $model->findById($id);
                if ($nhanSu && !empty($nhanSu['nguoi_dung_id'])) {
                    $blockers = $model->getDeleteBlockers($nhanSu['nguoi_dung_id']);
                    if (!empty($blockers)) {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể xóa tài khoản do: ' . implode(' ', $blockers)];
                        header('Location: index.php?act=admin/nhanSu');
                        exit;
                    }
                }
                $ok = $model->deleteWithUser($id);
                $msg = $ok ? 'Xóa nhân sự và tài khoản thành công.' : 'Xóa nhân sự và tài khoản thất bại.';
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
    
    private function chonAnhChinh(array $hinhAnhList) {
        foreach ($hinhAnhList as $anh) {
            if (!empty($anh['url_anh'])) {
                return $anh;
            }
        }
        return null;
    }
}
