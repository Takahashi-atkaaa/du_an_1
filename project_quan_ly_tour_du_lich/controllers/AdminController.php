<?php

class AdminController {
    
    public function __construct() {
        requireRole('admin');
    }
    
    public function dashboard() {
        require 'views/admin/dashboard.php';
    }
    
    public function quanLyTour() {
        require 'views/admin/quan_ly_tour.php';
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
    
    public function danhGia() {
        require 'views/admin/danh_gia.php';
    }
}
