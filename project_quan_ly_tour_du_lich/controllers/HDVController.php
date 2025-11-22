<?php

require_once 'models/NhanSu.php';
require_once 'models/HDVManagement.php';
require_once 'models/LichKhoiHanh.php';
require_once 'models/PhanBoNhanSu.php';
require_once 'models/Tour.php';
require_once 'models/Booking.php';
require_once 'models/NhatKyTour.php';
require_once 'models/KhachHang.php';
require_once 'models/CheckinKhach.php';
require_once 'models/YeuCauDacBiet.php';

class HDVController {
    private $nhanSuModel;
    private $hdvMgmtModel;
    private $lichKhoiHanhModel;
    private $phanBoNhanSuModel;
    private $tourModel;
    private $bookingModel;
    private $nhatKyTourModel;
    private $khachHangModel;
    private $yeuCauDacBietModel;
    private $checkinKhachModel;
    
    public function __construct() {
        requireRole('HDV');
        $this->nhanSuModel = new NhanSu();
        $this->hdvMgmtModel = new HDVManagement();
        $this->lichKhoiHanhModel = new LichKhoiHanh();
        $this->phanBoNhanSuModel = new PhanBoNhanSu();
        $this->tourModel = new Tour();
        $this->bookingModel = new Booking();
        $this->nhatKyTourModel = new NhatKyTour();
        $this->khachHangModel = new KhachHang();
        $this->yeuCauDacBietModel = new YeuCauDacBiet();
        $this->checkinKhachModel = new CheckinKhach();
    }
    
    public function lichLamViec() {
        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        $lichKhoiHanhList = $this->getLichKhoiHanhByHDV($nhanSuId);
        $lichTrinhTheoTour = [];
        foreach ($lichKhoiHanhList as $lich) {
            $tourId = $lich['tour_id'] ?? null;
            if ($tourId && !isset($lichTrinhTheoTour[$tourId])) {
                $lichTrinhTheoTour[$tourId] = $this->tourModel->getLichTrinhByTourId($tourId);
            }
        }
        
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

        $nhiemVuTheoLich = [];
        foreach ($phanBoNhanSuList as $pb) {
            if (($pb['nhan_su_id'] ?? null) == $nhanSuId) {
                $nhiemVuTheoLich[$pb['lich_khoi_hanh_id']] = $pb;
            }
        }

        // Lấy danh sách khách và yêu cầu đặc biệt cho từng lịch khởi hành
        $yeuCauDacBietTheoLich = [];
        foreach ($lichKhoiHanhList as $lich) {
            $lichId = (int)($lich['id'] ?? 0);
            if ($lichId > 0) {
                $danhSachKhach = $this->bookingModel->getKhachByTourAndNgayKhoiHanh(
                    $lich['tour_id'],
                    $lich['ngay_khoi_hanh']
                );
                $yeuCauDacBietTheoLich[$lichId] = $danhSachKhach;
            }
        }
        
        require 'views/hdv/lich_lam_viec.php';
    }
    
    public function nhatKyTour() {
        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];

        $lichKhoiHanhList = $this->getLichKhoiHanhByHDV($nhanSuId);
        $allowedTourIds = array_map(fn($lich) => (int)$lich['tour_id'], $lichKhoiHanhList);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleNhatKyPost($nhanSuId, $allowedTourIds);
            return;
        }

        $selectedTourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : null;
        if (!$selectedTourId && !empty($allowedTourIds)) {
            $selectedTourId = $allowedTourIds[0];
        }

        $nhatKyList = $this->nhatKyTourModel->getByHDVAndTour($nhanSuId, $selectedTourId ?: null);

        $entryEditing = null;
        $entryId = isset($_GET['entry_id']) ? (int)$_GET['entry_id'] : null;
        if ($entryId) {
            $entryEditing = $this->nhatKyTourModel->getById($entryId, $nhanSuId);
            if ($entryEditing) {
                $parsed = $this->parseNhatKyContent($entryEditing['noi_dung'] ?? '');
                $entryEditing = array_merge($parsed, $entryEditing);
            }
        }

        require 'views/hdv/nhat_ky_tour.php';
    }
    
    public function danhSachKhach() {
        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];

        $lichKhoiHanhList = $this->getLichKhoiHanhByHDV($nhanSuId);

        $selectedLichId = isset($_GET['lich_id']) ? (int)$_GET['lich_id'] : null;
        if (!$selectedLichId && !empty($lichKhoiHanhList)) {
            $selectedLichId = (int)$lichKhoiHanhList[0]['id'];
        }

        $selectedLich = null;
        foreach ($lichKhoiHanhList as $lich) {
            if ((int)$lich['id'] === $selectedLichId) {
                $selectedLich = $lich;
                break;
            }
        }

        $danhSachKhach = [];
        if ($selectedLich) {
            $danhSachKhach = $this->bookingModel->getKhachByTourAndNgayKhoiHanh(
                $selectedLich['tour_id'],
                $selectedLich['ngay_khoi_hanh']
            );
        }

        require 'views/hdv/danh_sach_khach.php';
    }

    public function checkInKhach() {
        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];

        $lichKhoiHanhList = $this->getLichKhoiHanhByHDV($nhanSuId);

        $selectedLichId = isset($_GET['lich_id']) ? (int)$_GET['lich_id'] : null;
        if (!$selectedLichId && !empty($lichKhoiHanhList)) {
            $selectedLichId = (int)$lichKhoiHanhList[0]['id'];
        }

        $selectedLich = null;
        foreach ($lichKhoiHanhList as $lich) {
            if ((int)$lich['id'] === $selectedLichId) {
                $selectedLich = $lich;
                break;
            }
        }

        $danhSachKhach = [];
        $checkinMap = [];

        if ($selectedLich) {
            $danhSachKhach = $this->bookingModel->getKhachByTourAndNgayKhoiHanh(
                $selectedLich['tour_id'],
                $selectedLich['ngay_khoi_hanh']
            );

            $checkinList = $this->checkinKhachModel->getByLichKhoiHanh($selectedLich['id']);
            foreach ($checkinList as $item) {
                $checkinMap[$item['khach_hang_id']] = $item;
            }
        }

        require 'views/hdv/checkin_khach.php';
    }

    public function updateCheckInKhach() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=hdv/checkInKhach');
            exit();
        }

        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];

        $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
        $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
        $khachHangId = isset($_POST['khach_hang_id']) ? (int)$_POST['khach_hang_id'] : 0;
        $trangThai = $_POST['trang_thai'] ?? 'ChuaCheckIn';
        $ghiChu = trim($_POST['ghi_chu'] ?? '');

        if ($lichKhoiHanhId <= 0 || $bookingId <= 0 || $khachHangId <= 0) {
            $_SESSION['error'] = 'Thiếu thông tin cần thiết.';
            header('Location: index.php?act=hdv/checkInKhach');
            exit();
        }

        $assignedLichList = $this->getLichKhoiHanhByHDV($nhanSuId);
        $allowedLichIds = array_map(fn($item) => (int)$item['id'], $assignedLichList);
        if (!in_array($lichKhoiHanhId, $allowedLichIds, true)) {
            $_SESSION['error'] = 'Bạn không được phép cập nhật lịch này.';
            header('Location: index.php?act=hdv/checkInKhach');
            exit();
        }

        $khachInfo = $this->khachHangModel->getKhachHangWithNguoiDung($khachHangId);
        if (!$khachInfo) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng.';
            header('Location: index.php?act=hdv/checkInKhach&lich_id=' . $lichKhoiHanhId);
            exit();
        }

        $gioiTinh = $khachInfo['gioi_tinh'] ?? 'Khac';
        if ($gioiTinh === 'Nữ') {
            $gioiTinh = 'Nu';
        } elseif ($gioiTinh === 'Khác') {
            $gioiTinh = 'Khac';
        }

        $existing = $this->checkinKhachModel->findOne($lichKhoiHanhId, $bookingId, $khachHangId);
        $checkinTime = null;
        $checkoutTime = null;

        if ($trangThai === 'DaCheckIn') {
            $checkinTime = $existing['checkin_time'] ?? date('Y-m-d H:i:s');
        } elseif ($trangThai === 'DaCheckOut') {
            $checkinTime = $existing['checkin_time'] ?? date('Y-m-d H:i:s');
            $checkoutTime = $existing['checkout_time'] ?? date('Y-m-d H:i:s');
        }

        $data = [
            'booking_id' => $bookingId,
            'khach_hang_id' => $khachHangId,
            'lich_khoi_hanh_id' => $lichKhoiHanhId,
            'ho_ten' => $khachInfo['ho_ten'] ?? '',
            'ngay_sinh' => $khachInfo['ngay_sinh'] ?? null,
            'gioi_tinh' => $gioiTinh ?: 'Khac',
            'quoc_tich' => 'Việt Nam',
            'dia_chi' => $khachInfo['dia_chi'] ?? null,
            'so_dien_thoai' => $khachInfo['so_dien_thoai'] ?? null,
            'email' => $khachInfo['email'] ?? null,
            'trang_thai' => $trangThai,
            'ghi_chu' => $ghiChu,
            'checkin_time' => $checkinTime,
            'checkout_time' => $checkoutTime
        ];

        $result = false;
        if ($existing) {
            $result = $this->checkinKhachModel->update($existing['id'], $data);
        } else {
            $result = $this->checkinKhachModel->insert($data);
        }

        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Cập nhật điểm danh thành công.' : 'Không thể cập nhật điểm danh.';

        header('Location: index.php?act=hdv/checkInKhach&lich_id=' . $lichKhoiHanhId);
        exit();
    }

    public function quanLyYeuCauDacBiet() {
        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        $lichKhoiHanhList = $this->getLichKhoiHanhByHDV($nhanSuId);
        
        // Lấy danh sách khách và yêu cầu đặc biệt cho từng lịch khởi hành
        $yeuCauDacBietTheoLich = [];
        foreach ($lichKhoiHanhList as $lich) {
            $lichId = (int)($lich['id'] ?? 0);
            if ($lichId > 0) {
                $danhSachKhach = $this->bookingModel->getKhachByTourAndNgayKhoiHanh(
                    $lich['tour_id'],
                    $lich['ngay_khoi_hanh']
                );
                $yeuCauDacBietTheoLich[$lichId] = $danhSachKhach;
            }
        }
        
        require 'views/hdv/quan_ly_yeu_cau_dac_biet.php';
    }

    public function updateYeuCauDacBiet() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=hdv/quanLyYeuCauDacBiet');
            exit();
        }

        $nhanSu = $this->getCurrentHDV();
        $nhanSuId = $nhanSu['nhan_su_id'];

        $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
        $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
        $khachHangId = isset($_POST['khach_hang_id']) ? (int)$_POST['khach_hang_id'] : 0;
        $noiDung = trim($_POST['noi_dung'] ?? '');

        if ($lichKhoiHanhId <= 0 || $tourId <= 0 || $khachHangId <= 0) {
            $_SESSION['error'] = 'Thiếu thông tin yêu cầu đặc biệt.';
            header('Location: index.php?act=hdv/checkInKhach');
            exit();
        }

        $assigned = $this->getLichKhoiHanhByHDV($nhanSuId);
        $allowed = [];
        foreach ($assigned as $lich) {
            $allowed[(int)$lich['id']] = (int)$lich['tour_id'];
        }

        if (!isset($allowed[$lichKhoiHanhId]) || $allowed[$lichKhoiHanhId] !== $tourId) {
            $_SESSION['error'] = 'Bạn không được phép cập nhật yêu cầu cho tour này.';
            header('Location: index.php?act=hdv/checkInKhach');
            exit();
        }

        if ($noiDung === '') {
            $_SESSION['error'] = 'Vui lòng nhập nội dung yêu cầu đặc biệt.';
            header('Location: index.php?act=hdv/checkInKhach&lich_id=' . $lichKhoiHanhId);
            exit();
        }

        $result = $this->yeuCauDacBietModel->upsert($khachHangId, $tourId, $noiDung);
        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Đã lưu yêu cầu đặc biệt.' : 'Không thể lưu yêu cầu.';

        $redirectTo = $_POST['redirect_to'] ?? 'hdv/quanLyYeuCauDacBiet';
        if ($redirectTo === 'hdv/checkInKhach') {
            header('Location: index.php?act=hdv/checkInKhach&lich_id=' . $lichKhoiHanhId);
        } elseif ($redirectTo === 'hdv/lichLamViec') {
            header('Location: index.php?act=hdv/lichLamViec');
        } else {
            header('Location: index.php?act=hdv/quanLyYeuCauDacBiet');
        }
        exit();
    }
    
    public function phanHoi() {
        require 'views/hdv/phan_hoi.php';

        
    }

    private function getCurrentHDV() {
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

        return $nhanSu;
    }

    private function getLichKhoiHanhByHDV($nhanSuId) {
        $sql = "SELECT lk.*, t.ten_tour, t.loai_tour, t.gia_co_ban
                FROM lich_khoi_hanh lk
                LEFT JOIN tour t ON lk.tour_id = t.tour_id
                WHERE lk.hdv_id = ?
                ORDER BY lk.ngay_khoi_hanh DESC, lk.gio_xuat_phat DESC";
        $stmt = $this->lichKhoiHanhModel->conn->prepare($sql);
        $stmt->execute([(int)$nhanSuId]);
        return $stmt->fetchAll();
    }

    private function handleNhatKyPost($nhanSuId, $allowedTourIds) {
        $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
        if ($tourId <= 0 || (!empty($allowedTourIds) && !in_array($tourId, $allowedTourIds, true))) {
            $_SESSION['error'] = 'Tour không hợp lệ hoặc bạn không được phân công.';
            header('Location: index.php?act=hdv/nhatKyTour');
            exit();
        }

        $ngayGhi = !empty($_POST['ngay_ghi']) ? $_POST['ngay_ghi'] : date('Y-m-d');
        $noiDung = $this->buildNhatKyContent($_POST);

        if (empty(trim($noiDung))) {
            $_SESSION['error'] = 'Vui lòng nhập nội dung nhật ký.';
            header('Location: index.php?act=hdv/nhatKyTour&tour_id=' . $tourId);
            exit();
        }

        $action = $_POST['journal_action'] ?? 'create';
        if ($action === 'update') {
            $entryId = isset($_POST['entry_id']) ? (int)$_POST['entry_id'] : 0;
            if ($entryId <= 0) {
                $_SESSION['error'] = 'Nhật ký không hợp lệ.';
            } else {
                $result = $this->nhatKyTourModel->update($entryId, $nhanSuId, [
                    'tour_id' => $tourId,
                    'noi_dung' => $noiDung,
                    'ngay_ghi' => $ngayGhi
                ]);
                $_SESSION[$result ? 'success' : 'error'] = $result ? 'Đã cập nhật nhật ký.' : 'Không thể cập nhật nhật ký.';
            }
        } else {
            $result = $this->nhatKyTourModel->insert([
                'tour_id' => $tourId,
                'nhan_su_id' => $nhanSuId,
                'noi_dung' => $noiDung,
                'ngay_ghi' => $ngayGhi
            ]);
            $_SESSION[$result ? 'success' : 'error'] = $result ? 'Đã thêm nhật ký mới.' : 'Không thể thêm nhật ký.';
        }

        header('Location: index.php?act=hdv/nhatKyTour&tour_id=' . $tourId);
        exit();
    }

    private function buildNhatKyContent($input) {
        $sections = [
            'Tiêu đề' => $input['tieu_de'] ?? '',
            'Hoạt động nổi bật' => $input['hoat_dong'] ?? '',
            'Sự kiện / Sự cố' => $input['su_kien'] ?? '',
            'Cách xử lý' => $input['cach_xu_ly'] ?? '',
            'Phản hồi khách hàng' => $input['phan_hoi'] ?? '',
            'Ảnh minh họa' => $input['anh_minh_hoa'] ?? '',
            'Ghi chú thêm' => $input['ghi_chu_them'] ?? ''
        ];

        $lines = [];
        foreach ($sections as $label => $value) {
            $value = trim($value);
            if ($value !== '') {
                $lines[] = "{$label}: {$value}";
            }
        }

        return implode("\n", $lines);
    }

    private function parseNhatKyContent($text) {
        $result = [
            'tieu_de' => '',
            'hoat_dong' => '',
            'su_kien' => '',
            'cach_xu_ly' => '',
            'phan_hoi' => '',
            'anh_minh_hoa' => '',
            'ghi_chu_them' => ''
        ];

        if (empty($text)) {
            return $result;
        }

        $mapping = [
            'tiêu đề' => 'tieu_de',
            'hoạt động nổi bật' => 'hoat_dong',
            'sự kiện / sự cố' => 'su_kien',
            'sự cố / sự kiện' => 'su_kien',
            'cách xử lý' => 'cach_xu_ly',
            'phản hồi khách hàng' => 'phan_hoi',
            'ảnh minh họa' => 'anh_minh_hoa',
            'ghi chú thêm' => 'ghi_chu_them'
        ];

        $lines = preg_split("/\r\n|\n|\r/", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            foreach ($mapping as $label => $key) {
                $prefix = $label . ':';
                if (stripos($line, $prefix) === 0) {
                    $result[$key] = trim(substr($line, strlen($prefix)));
                    break;
                }
            }
        }

        return $result;
    }
}
