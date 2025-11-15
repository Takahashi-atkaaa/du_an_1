<?php
require_once 'models/LichKhoiHanh.php';
require_once 'models/PhanBoNhanSu.php';
require_once 'models/PhanBoDichVu.php';
require_once 'models/Tour.php';
require_once 'models/NhanSu.php';
require_once 'models/NhaCungCap.php';

class LichKhoiHanhController {
    private $lichKhoiHanhModel;
    private $phanBoNhanSuModel;
    private $phanBoDichVuModel;
    private $tourModel;
    private $nhanSuModel;
    private $nhaCungCapModel;
    
    public function __construct() {
        $this->lichKhoiHanhModel = new LichKhoiHanh();
        $this->phanBoNhanSuModel = new PhanBoNhanSu();
        $this->phanBoDichVuModel = new PhanBoDichVu();
        $this->tourModel = new Tour();
        $this->nhanSuModel = new NhanSu();
        $this->nhaCungCapModel = new NhaCungCap();
    }

    // Danh sách lịch khởi hành
    public function index() {
        $lichKhoiHanhList = $this->lichKhoiHanhModel->getAll();
        require 'views/admin/quan_ly_lich_khoi_hanh.php';
    }

    // Chi tiết lịch khởi hành
    public function chiTiet() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ.';
            header('Location: index.php?act=lichKhoiHanh/index');
            exit();
        }
        
        $lichKhoiHanh = $this->lichKhoiHanhModel->getWithDetails($id);
        if (!$lichKhoiHanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại.';
            header('Location: index.php?act=lichKhoiHanh/index');
            exit();
        }
        
        // Lấy phân bổ nhân sự
        $phanBoNhanSu = $this->phanBoNhanSuModel->getByLichKhoiHanh($id);
        
        // Lấy phân bổ dịch vụ
        $phanBoDichVu = $this->phanBoDichVuModel->getByLichKhoiHanh($id);
        
        // Lấy danh sách nhân sự để chọn
        $nhanSuList = $this->nhanSuModel->getAll();
        
        // Lấy danh sách nhà cung cấp
        $nhaCungCapList = $this->nhaCungCapModel->getAll();
        
        // Tính tổng chi phí
        $tongChiPhi = $this->phanBoDichVuModel->getTongChiPhi($id);
        
        require 'views/admin/chi_tiet_lich_khoi_hanh.php';
    }

    // Tạo lịch khởi hành mới
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : null,
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'] ?? null,
                'gio_xuat_phat' => $_POST['gio_xuat_phat'] ?? null,
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'] ?? null,
                'gio_ket_thuc' => $_POST['gio_ket_thuc'] ?? null,
                'diem_tap_trung' => $_POST['diem_tap_trung'] ?? '',
                'so_cho' => isset($_POST['so_cho']) ? (int)$_POST['so_cho'] : 50,
                'hdv_id' => isset($_POST['hdv_id']) && $_POST['hdv_id'] !== '' ? (int)$_POST['hdv_id'] : null,
                'trang_thai' => $_POST['trang_thai'] ?? 'SapKhoiHanh',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $id = $this->lichKhoiHanhModel->insert($data);
            if ($id) {
                $_SESSION['success'] = 'Tạo lịch khởi hành thành công.';
                header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $id);
                exit();
            } else {
                $_SESSION['error'] = 'Không thể tạo lịch khởi hành.';
            }
        }
        
        $tours = $this->tourModel->getAll();
        require 'views/admin/tao_lich_khoi_hanh.php';
    }

    // Cập nhật lịch khởi hành
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            
            if ($id <= 0) {
                $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ.';
                header('Location: index.php?act=lichKhoiHanh/index');
                exit();
            }
            
            $data = [
                'tour_id' => isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : null,
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'] ?? null,
                'gio_xuat_phat' => $_POST['gio_xuat_phat'] ?? null,
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'] ?? null,
                'gio_ket_thuc' => $_POST['gio_ket_thuc'] ?? null,
                'diem_tap_trung' => $_POST['diem_tap_trung'] ?? '',
                'so_cho' => isset($_POST['so_cho']) ? (int)$_POST['so_cho'] : 50,
                'hdv_id' => isset($_POST['hdv_id']) && $_POST['hdv_id'] !== '' ? (int)$_POST['hdv_id'] : null,
                'trang_thai' => $_POST['trang_thai'] ?? 'SapKhoiHanh',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $result = $this->lichKhoiHanhModel->update($id, $data);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật lịch khởi hành thành công.';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật lịch khởi hành.';
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $id);
            exit();
        }
    }

    // Phân bổ nhân sự
    public function phanBoNhanSu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            $nhanSuId = isset($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : 0;
            $vaiTro = $_POST['vai_tro'] ?? 'Khac';
            $ghiChu = $_POST['ghi_chu'] ?? null;
            
            if ($lichKhoiHanhId > 0 && $nhanSuId > 0) {
                $data = [
                    'lich_khoi_hanh_id' => $lichKhoiHanhId,
                    'nhan_su_id' => $nhanSuId,
                    'vai_tro' => $vaiTro,
                    'ghi_chu' => $ghiChu
                ];
                
                $result = $this->phanBoNhanSuModel->insert($data);
                if ($result) {
                    $_SESSION['success'] = 'Phân bổ nhân sự thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể phân bổ nhân sự.';
                }
            } else {
                $_SESSION['error'] = 'Thông tin không hợp lệ.';
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
    }

    // Cập nhật trạng thái phân bổ nhân sự
    public function updateTrangThaiNhanSu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $trangThai = $_POST['trang_thai'] ?? 'ChoXacNhan';
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            
            if ($id > 0) {
                $result = $this->phanBoNhanSuModel->updateTrangThai($id, $trangThai);
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật trạng thái thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật trạng thái.';
                }
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
    }

    // Phân bổ dịch vụ
    public function phanBoDichVu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            
            $data = [
                'lich_khoi_hanh_id' => $lichKhoiHanhId,
                'nha_cung_cap_id' => isset($_POST['nha_cung_cap_id']) && $_POST['nha_cung_cap_id'] !== '' ? (int)$_POST['nha_cung_cap_id'] : null,
                'loai_dich_vu' => $_POST['loai_dich_vu'] ?? 'Khac',
                'ten_dich_vu' => $_POST['ten_dich_vu'] ?? '',
                'so_luong' => isset($_POST['so_luong']) ? (int)$_POST['so_luong'] : 1,
                'don_vi' => $_POST['don_vi'] ?? null,
                'ngay_bat_dau' => $_POST['ngay_bat_dau'] ?? null,
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'] ?? null,
                'gio_bat_dau' => $_POST['gio_bat_dau'] ?? null,
                'gio_ket_thuc' => $_POST['gio_ket_thuc'] ?? null,
                'dia_diem' => $_POST['dia_diem'] ?? null,
                'gia_tien' => isset($_POST['gia_tien']) ? (float)$_POST['gia_tien'] : null,
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            if ($lichKhoiHanhId > 0) {
                $result = $this->phanBoDichVuModel->insert($data);
                if ($result) {
                    $_SESSION['success'] = 'Phân bổ dịch vụ thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể phân bổ dịch vụ.';
                }
            } else {
                $_SESSION['error'] = 'Thông tin không hợp lệ.';
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
    }

    // Cập nhật trạng thái phân bổ dịch vụ
    public function updateTrangThaiDichVu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $trangThai = $_POST['trang_thai'] ?? 'ChoXacNhan';
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            
            if ($id > 0) {
                $result = $this->phanBoDichVuModel->updateTrangThai($id, $trangThai);
                if ($result) {
                    $_SESSION['success'] = 'Cập nhật trạng thái thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật trạng thái.';
                }
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
    }

    // Xóa phân bổ nhân sự
    public function deleteNhanSu() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        if ($id > 0) {
            $result = $this->phanBoNhanSuModel->delete($id);
            if ($result) {
                $_SESSION['success'] = 'Xóa phân bổ nhân sự thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa phân bổ nhân sự.';
            }
        }
        
        header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
        exit();
    }

    // Xóa phân bổ dịch vụ
    public function deleteDichVu() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        if ($id > 0) {
            $result = $this->phanBoDichVuModel->delete($id);
            if ($result) {
                $_SESSION['success'] = 'Xóa phân bổ dịch vụ thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa phân bổ dịch vụ.';
            }
        }
        
        header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
        exit();
    }
}

