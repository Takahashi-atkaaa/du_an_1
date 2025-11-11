<?php

class KhachHangController {
    
    public function __construct() {
        requireLogin();
    }
    
    // Tra cứu bằng mã tour và mã khách hàng (ID)
    public function traCuu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tourId = isset($_POST['ma_tour']) ? (int)$_POST['ma_tour'] : 0;
            $khachHangId = isset($_POST['ma_khach_hang']) ? (int)$_POST['ma_khach_hang'] : 0;
            if ($tourId <= 0 || $khachHangId <= 0) {
                $error = "Mã tour và mã khách hàng không hợp lệ.";
                require 'views/khach_hang/tra_cuu.php';
                return;
            }
            $bookingModel = new Booking();
            $tourModel = new Tour();
            $booking = $bookingModel->findByTourAndCustomer($tourId, $khachHangId);
            if (!$booking) {
                $error = "Không tìm thấy đặt tour phù hợp.";
                require 'views/khach_hang/tra_cuu.php';
                return;
            }
            $tour = $tourModel->findById($booking['tour_id']);
            require 'views/khach_hang/chi_tiet_lich_trinh.php';
            return;
        }
        require 'views/khach_hang/tra_cuu.php';
    }
    
    public function danhSachTour() {
        require 'views/khach_hang/danh_sach_tour.php';
    }
    
    public function chiTietTour() {
        require 'views/khach_hang/chi_tiet_tour.php';
    }
    
    public function datTour() {
        require 'views/khach_hang/dat_tour.php';
    }
    
    public function danhGia() {
        require 'views/khach_hang/danh_gia.php';
    }
}
