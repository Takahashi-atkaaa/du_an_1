<?php

class NhaCungCapController {
    private $nhaCungCapModel;
    
    public function __construct() {
        requireRole('NhaCungCap');
        $this->nhaCungCapModel = new NhaCungCap();
    }
    
    // Dashboard
    public function dashboard() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCapId = $nhaCungCap['id_nha_cung_cap'];
        
        // Thống kê
        $dichVuChoXacNhan = $this->nhaCungCapModel->getDichVu($nhaCungCapId, 'ChoXacNhan');
        $dichVuDaXacNhan = $this->nhaCungCapModel->getDichVu($nhaCungCapId, 'DaXacNhan');
        $congNo = $this->nhaCungCapModel->getTongCongNo($nhaCungCapId);
        $lichSu = $this->nhaCungCapModel->getLichSuHopTac($nhaCungCapId, 10);
        
        require 'views/nha_cung_cap/dashboard.php';
    }
    
    // Danh sách dịch vụ (báo giá)
    public function baoGia() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCapId = $nhaCungCap['id_nha_cung_cap'];
        $trangThai = $_GET['trang_thai'] ?? null;
        
        $dichVu = $this->nhaCungCapModel->getDichVu($nhaCungCapId, $trangThai);
        
        require 'views/nha_cung_cap/bao_gia.php';
    }
    
    // Xác nhận booking/dịch vụ
    public function xacNhanBooking() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=nhaCungCap/baoGia');
            exit();
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $dichVuId = $_POST['dich_vu_id'] ?? 0;
        $giaTien = $_POST['gia_tien'] ?? null;
        $action = $_POST['action'] ?? 'xac_nhan';
        
        if ($action === 'tu_choi') {
            $ghiChu = $_POST['ghi_chu'] ?? null;
            $result = $this->nhaCungCapModel->tuChoiDichVu($dichVuId, $ghiChu);
            if ($result) {
                $_SESSION['success'] = 'Đã từ chối dịch vụ';
            } else {
                $_SESSION['error'] = 'Không thể từ chối dịch vụ';
            }
        } else {
            $result = $this->nhaCungCapModel->xacNhanDichVu($dichVuId, $giaTien);
            if ($result) {
                $_SESSION['success'] = 'Đã xác nhận dịch vụ';
            } else {
                $_SESSION['error'] = 'Không thể xác nhận dịch vụ';
            }
        }
        
        header('Location: index.php?act=nhaCungCap/baoGia');
        exit();
    }
    
    // Cập nhật giá dịch vụ
    public function capNhatGia() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=nhaCungCap/baoGia');
            exit();
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $dichVuId = $_POST['dich_vu_id'] ?? 0;
        $giaTien = $_POST['gia_tien'] ?? 0;
        
        if ($giaTien <= 0) {
            $_SESSION['error'] = 'Giá tiền không hợp lệ';
            header('Location: index.php?act=nhaCungCap/baoGia');
            exit();
        }
        
        $result = $this->nhaCungCapModel->capNhatGiaDichVu($dichVuId, $giaTien);
        if ($result) {
            $_SESSION['success'] = 'Đã cập nhật giá dịch vụ';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật giá dịch vụ';
        }
        
        header('Location: index.php?act=nhaCungCap/baoGia');
        exit();
    }
    
    // Quản lý dịch vụ
    public function dichVu() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCapId = $nhaCungCap['id_nha_cung_cap'];
        $dichVu = $this->nhaCungCapModel->getDichVu($nhaCungCapId);
        
        require 'views/nha_cung_cap/dich_vu.php';
    }
    
    // Công nợ
    public function congNo() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCapId = $nhaCungCap['id_nha_cung_cap'];
        $congNo = $this->nhaCungCapModel->getTongCongNo($nhaCungCapId);
        $dichVuDaXacNhan = $this->nhaCungCapModel->getDichVu($nhaCungCapId, 'DaXacNhan');
        
        require 'views/nha_cung_cap/cong_no.php';
    }
    
    // Lịch sử hợp tác
    public function hopDong() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCap = $this->nhaCungCapModel->findByUserId($userId);
        if (!$nhaCungCap) {
            $_SESSION['error'] = 'Không tìm thấy thông tin nhà cung cấp';
            header('Location: index.php?act=auth/login');
            exit();
        }
        
        $nhaCungCapId = $nhaCungCap['id_nha_cung_cap'];
        $lichSu = $this->nhaCungCapModel->getLichSuHopTac($nhaCungCapId);
        
        require 'views/nha_cung_cap/hop_dong.php';
    }
}
