<?php

class HDVController {
    
    public function __construct() {
        // requireRole('HDV');
    }
    
    public function lichLamViec() {
        require 'views/hdv/lich_lam_viec.php';
    }
    
    public function nhatKyTour() {
        require 'views/hdv/nhat_ky_tour.php';
    }
    
    public function danhSachKhach() {
        require 'views/hdv/danh_sach_khach.php';
    }
    
    public function phanHoi() {
        require 'views/hdv/phan_hoi.php';
    }
}
