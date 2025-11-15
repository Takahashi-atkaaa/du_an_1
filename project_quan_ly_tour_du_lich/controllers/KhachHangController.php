<?php

class KhachHangController {
    
    public function __construct() {
        // requireLogin();
    }
    
    // Tra cứu bằng mã tour và mã khách hàng (ID)
    public function traCuu() {
        
        require 'views/khach_hang/tra_cuu.php';
    }
    
    public function danhSachTour() {
        require 'views/khach_hang/danh_sach_tour.php';
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
    
    public function datTour() {
        require 'views/khach_hang/dat_tour.php';
    }
    
    public function danhGia() {
        require 'views/khach_hang/danh_gia.php';
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
