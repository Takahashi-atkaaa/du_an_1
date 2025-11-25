<?php

class KhachHangController {
    
    public function __construct() {
        requireLogin();
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
        // Lấy danh sách để khách hàng chọn
        require_once 'models/Tour.php';
        require_once 'models/NhaCungCap.php';
        require_once 'models/NhanSu.php';
        
        $tourModel = new Tour();
        $nccModel = new NhaCungCap();
        $nhanSuModel = new NhanSu();
        
        $tourList = $tourModel->getAll();
        $nccList = $nccModel->getAll();
        $nhanSuList = $nhanSuModel->getAll();
        
        require 'views/khach_hang/danh_gia.php';
    }
    
    public function guiDanhGia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=khachHang/danhGia');
            exit();
        }
        
        require_once 'models/DanhGia.php';
        require_once 'models/KhachHang.php';
        
        // Lấy khach_hang_id từ session
        $khachHangModel = new KhachHang();
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        
        if (!$khachHang) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php?act=khachHang/danhGia');
            exit();
        }
        
        $data = [
            'khach_hang_id' => $khachHang['khach_hang_id'],
            'tour_id' => !empty($_POST['tour_id']) ? (int)$_POST['tour_id'] : null,
            'nha_cung_cap_id' => !empty($_POST['nha_cung_cap_id']) ? (int)$_POST['nha_cung_cap_id'] : null,
            'nhan_su_id' => !empty($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : null,
            'loai_danh_gia' => $_POST['loai_danh_gia'],
            'tieu_chi' => $_POST['tieu_chi'] ?? null,
            'loai_dich_vu' => $_POST['loai_dich_vu'] ?? null,
            'diem' => (int)$_POST['diem'],
            'noi_dung' => $_POST['noi_dung']
        ];
        
        $danhGiaModel = new DanhGia();
        if ($danhGiaModel->create($data)) {
            $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá! Ý kiến của bạn rất quan trọng với chúng tôi.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
        }
        
        header('Location: index.php?act=khachHang/danhGia');
        exit();
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
