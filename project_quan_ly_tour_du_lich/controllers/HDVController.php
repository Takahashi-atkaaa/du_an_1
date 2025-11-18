<?php

require_once 'models/NhanSu.php';
require_once 'models/HDVManagement.php';
require_once 'models/LichKhoiHanh.php';
require_once 'models/PhanBoNhanSu.php';

class HDVController {
    private $nhanSuModel;
    private $hdvMgmtModel;
    private $lichKhoiHanhModel;
    private $phanBoNhanSuModel;
    
    public function __construct() {
        requireRole('HDV');
        $this->nhanSuModel = new NhanSu();
        $this->hdvMgmtModel = new HDVManagement();
        $this->lichKhoiHanhModel = new LichKhoiHanh();
        $this->phanBoNhanSuModel = new PhanBoNhanSu();
    }
    
    public function lichLamViec() {
        // Lấy nhan_su_id của HDV đang đăng nhập
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        // Tìm nhân sự theo user_id
        $sql = "SELECT nhan_su_id FROM nhan_su WHERE nguoi_dung_id = ? AND vai_tro = 'HDV' LIMIT 1";
        $stmt = $this->nhanSuModel->conn->prepare($sql);
        $stmt->execute([$userId]);
        $nhanSu = $stmt->fetch();
        
        if (!$nhanSu) {
            $_SESSION['error'] = 'Không tìm thấy thông tin HDV.';
            header('Location: index.php?act=tour/index');
            exit();
        }
        
        $nhanSuId = $nhanSu['nhan_su_id'];
        
        // Lấy lịch khởi hành mà HDV được phân công (từ bảng lich_khoi_hanh)
        $lichKhoiHanhList = $this->hdvMgmtModel->getLichLamViec($nhanSuId);
        
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
