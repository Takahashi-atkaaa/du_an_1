<?php

class KhachHangController {
    
    public function __construct() {
        requireLogin();
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
