<?php

class AdminController {
    
    public function __construct() {
        // requireRole('Admin');
        // khi vào gốc dự án sẽ gọi new AdminController(). Trong AdminController::__construct() có requireRole('Admin') → requireLogin() → nếu chưa đăng nhập thì chuyển hướng sang auth/login. Nên luôn thấy trang đăng nhập trước khi có session.
    }
    
    public function dashboard() {
        require 'views/admin/dashboard.php';
    }
    
    public function quanLyTour() {
        require_once 'models/Tour.php';
        $tourModel = new Tour();
        
        // Lọc theo loại tour nếu có
        $loaiTour = isset($_GET['loai_tour']) ? $_GET['loai_tour'] : '';
        if (isset($loaiTour) && $loaiTour !== '') {
            $tours = $tourModel->find(['loai_tour' => $loaiTour]);
        } else {
            $tours = $tourModel->getAll();
        }
        
        require 'views/admin/quan_ly_tour.php';
    }
    
    public function chiTietTour() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $tour = null;
        $lichTrinhList = [];
        $lichKhoiHanhList = [];
        $hinhAnhList = [];
        $yeuCauList = [];
        $nhatKyList = [];
        $hdvInfo = null;
        $anhChinh = null;
        $error = null;

        if ($id <= 0) {
            $error = 'Thiếu mã tour cần xem chi tiết.';
        } else {
            require_once 'models/Tour.php';
            $tourModel = new Tour();
            $tour = $tourModel->findById($id);
            if (!$tour) {
                $error = 'Tour không tồn tại hoặc đã bị xóa.';
            } else {
                $lichTrinhList = $tourModel->getLichTrinhByTourId($id);
                $lichKhoiHanhList = $tourModel->getLichKhoiHanhByTourId($id);
                $hinhAnhList = $tourModel->getHinhAnhByTourId($id);
                $anhChinh = $this->chonAnhChinh($hinhAnhList);
                $yeuCauList = $tourModel->getYeuCauDacBietByTourId($id);
                $nhatKyList = $tourModel->getNhatKyTourByTourId($id);
                $hdvInfo = $tourModel->getHDVByTourId($id);
            }
        }

        require 'views/khach_hang/chi_tiet_tour.php';
    }
    public function quanLyNguoiDung() {
        require 'views/admin/quan_ly_nguoi_dung.php';
    }
    
    public function quanLyBooking() {
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

    private function chonAnhChinh(array $hinhAnhList) {
        foreach ($hinhAnhList as $anh) {
            if (!empty($anh['url_anh'])) {
                return $anh;
            }
        }
        return null;
    }
}
