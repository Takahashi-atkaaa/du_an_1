<?php

class NhaCungCapController {
    
    public function __construct() {
        requireRole('NhaCungCap');
    }
    
    public function baoGia() {
        require 'views/nha_cung_cap/bao_gia.php';
    }
    
    public function dichVu() {
        require 'views/nha_cung_cap/dich_vu.php';
    }
    
    public function congNo() {
        require 'views/nha_cung_cap/cong_no.php';
    }
    
    public function hopDong() {
        require 'views/nha_cung_cap/hop_dong.php';
    }
}
