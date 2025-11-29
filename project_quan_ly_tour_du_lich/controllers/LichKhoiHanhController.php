<?php
require_once 'models/LichKhoiHanh.php';
require_once 'models/PhanBoNhanSu.php';
require_once 'models/PhanBoDichVu.php';
require_once 'models/Tour.php';
require_once 'models/NhanSu.php';
require_once 'models/NhaCungCap.php';
require_once 'models/DichVuNhaCungCap.php';
require_once 'models/Booking.php';

class LichKhoiHanhController {
    private $lichKhoiHanhModel;
    private $phanBoNhanSuModel;
    private $phanBoDichVuModel;
    private $tourModel;
    private $nhanSuModel;
    private $nhaCungCapModel;
    private $bookingModel;
    private $dichVuCatalogModel;
    
    public function __construct() {
        $this->lichKhoiHanhModel = new LichKhoiHanh();
        $this->phanBoNhanSuModel = new PhanBoNhanSu();
        $this->phanBoDichVuModel = new PhanBoDichVu();
        $this->tourModel = new Tour();
        $this->nhanSuModel = new NhanSu();
        $this->nhaCungCapModel = new NhaCungCap();
        $this->bookingModel = new Booking();
        $this->dichVuCatalogModel = new DichVuNhaCungCap();
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

        $catalogServicesMap = [];
        if (!empty($nhaCungCapList)) {
            $supplierIds = array_column($nhaCungCapList, 'id_nha_cung_cap');
            $catalogServices = $this->dichVuCatalogModel->getBySupplierIds($supplierIds);
            foreach ($catalogServices as $service) {
                $catalogServicesMap[$service['nha_cung_cap_id']][] = $service;
            }
        }
        
        // Tính tổng chi phí
        $tongChiPhi = $this->phanBoDichVuModel->getTongChiPhi($id);

        // Lấy yêu cầu đặc biệt của khách cho lịch khởi hành này
        $yeuCauDacBietList = [];
        if (!empty($lichKhoiHanh['tour_id']) && !empty($lichKhoiHanh['ngay_khoi_hanh'])) {
            $yeuCauDacBietList = $this->bookingModel->getSpecialRequestsByLichKhoiHanh(
                $lichKhoiHanh['tour_id'],
                $lichKhoiHanh['ngay_khoi_hanh']
            );
        }
        
        // Lấy danh sách booking và khách chi tiết cho lịch khởi hành
        $bookingList = [];
        $danhSachKhachChiTiet = [];
        if (!empty($lichKhoiHanh['tour_id']) && !empty($lichKhoiHanh['ngay_khoi_hanh'])) {
            $bookingList = $this->bookingModel->getKhachByTourAndNgayKhoiHanh(
                $lichKhoiHanh['tour_id'],
                $lichKhoiHanh['ngay_khoi_hanh']
            );
            
            // Lấy danh sách khách chi tiết từ tour_checkin
            require_once 'models/CheckinKhach.php';
            $checkinModel = new CheckinKhach();
            foreach ($bookingList as $booking) {
                $khachList = $checkinModel->getByBookingId($booking['booking_id']);
                $danhSachKhachChiTiet[$booking['booking_id']] = $khachList;
            }
        }
        
        require 'views/admin/chi_tiet_lich_khoi_hanh.php';
    }

    /**
     * Đi từ booking sang màn chi tiết lịch khởi hành để phân bổ nhân sự & dịch vụ.
     * Nếu chưa có lịch khởi hành cho tour + ngày khởi hành của booking thì tự tạo mới.
     */
    public function chiTietTheoBooking() {
        $bookingId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($bookingId <= 0) {
            $_SESSION['error'] = 'ID booking không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }

        $booking = $this->bookingModel->getBookingWithDetails($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }

        $tourId = (int)($booking['tour_id'] ?? 0);
        $ngayKhoiHanh = $booking['ngay_khoi_hanh'] ?? $booking['ngay_dat'];

        if ($tourId <= 0 || empty($ngayKhoiHanh)) {
            $_SESSION['error'] = 'Booking chưa có thông tin tour hoặc ngày khởi hành.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }

        // Tìm lịch khởi hành tương ứng
        $lichKhoiHanh = $this->lichKhoiHanhModel->findByTourAndNgayKhoiHanh($tourId, $ngayKhoiHanh);

        if (!$lichKhoiHanh) {
            // Tự tạo lịch khởi hành mới dựa trên thông tin booking
            $data = [
                'tour_id' => $tourId,
                'ngay_khoi_hanh' => $ngayKhoiHanh,
                'gio_xuat_phat' => null,
                'ngay_ket_thuc' => $booking['ngay_ket_thuc'] ?? $ngayKhoiHanh,
                'gio_ket_thuc' => null,
                'diem_tap_trung' => '',
                'so_cho' => 50,
                'hdv_id' => null,
                'trang_thai' => 'SapKhoiHanh',
                'ghi_chu' => 'Tạo tự động từ booking #' . $bookingId
            ];

            $lichKhoiHanhId = $this->lichKhoiHanhModel->insert($data);
        } else {
            $lichKhoiHanhId = $lichKhoiHanh['id'];
        }

        header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId . '&from_booking=' . $bookingId);
        exit();
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

    // Hiển thị form sửa lịch khởi hành
    public function edit() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = 'ID lịch khởi hành không hợp lệ.';
            header('Location: index.php?act=lichKhoiHanh/index');
            exit();
        }

        $lichKhoiHanh = $this->lichKhoiHanhModel->findById($id);
        if (!$lichKhoiHanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại.';
            header('Location: index.php?act=lichKhoiHanh/index');
            exit();
        }

        $tours = $this->tourModel->getAll();
        $mode = 'edit';
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

    // Cập nhật trạng thái phân bổ nhân sự (HDV)
    public function updateTrangThaiNhanSu() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
            $trangThai = $_POST['trang_thai'] ?? 'ChoXacNhan';
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            
            if ($id > 0) {
                $phanBo = $this->phanBoNhanSuModel->findById($id);
                $result = $this->phanBoNhanSuModel->updateTrangThai($id, $trangThai);
                if ($result) {
                    if (
                        $phanBo
                        && $trangThai === 'DaXacNhan'
                        && isset($phanBo['vai_tro'])
                        && $phanBo['vai_tro'] === 'HDV'
                        && isset($phanBo['lich_khoi_hanh_id'], $phanBo['nhan_su_id'])
                    ) {
                        $this->lichKhoiHanhModel->assignHDV($phanBo['lich_khoi_hanh_id'], $phanBo['nhan_su_id']);
                    }
                    $_SESSION['success'] = 'Cập nhật trạng thái nhân sự thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể cập nhật trạng thái nhân sự.';
                }
            } else {
                $_SESSION['error'] = 'Thông tin không hợp lệ.';
            }
            
            // Redirect về trang phù hợp với vai trò
            $role = $_SESSION['role'] ?? '';
            if ($role === 'HDV') {
                // HDV quay về trang lịch làm việc của mình
                header('Location: index.php?act=hdv/lichLamViec');
            } else {
                // Admin quay về trang chi tiết lịch khởi hành
                header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            }
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

    // Thêm khách chi tiết vào lịch khởi hành
    public function themKhachChiTiet() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/CheckinKhach.php';
            require_once 'models/Booking.php';
            
            $lichKhoiHanhId = isset($_POST['lich_khoi_hanh_id']) ? (int)$_POST['lich_khoi_hanh_id'] : 0;
            $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
            
            $bookingModel = new Booking();
            $booking = $bookingModel->findById($bookingId);
            
            if (!$booking) {
                $_SESSION['error'] = 'Booking không tồn tại.';
                header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
                exit();
            }
            
            $checkinModel = new CheckinKhach();
            $data = [
                'booking_id' => $bookingId,
                'khach_hang_id' => $booking['khach_hang_id'],
                'lich_khoi_hanh_id' => $lichKhoiHanhId,
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_cmnd' => $_POST['so_cmnd'] ?? null,
                'so_passport' => $_POST['so_passport'] ?? null,
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'gioi_tinh' => $_POST['gioi_tinh'] ?? 'Khac',
                'quoc_tich' => $_POST['quoc_tich'] ?? 'Việt Nam',
                'dia_chi' => $_POST['dia_chi'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'trang_thai' => 'ChuaCheckIn',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $result = $checkinModel->insert($data);
            if ($result) {
                $_SESSION['success'] = 'Thêm khách thành công.';
            } else {
                $_SESSION['error'] = 'Không thể thêm khách.';
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
    }

    // Sửa khách chi tiết
    public function suaKhachChiTiet() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        require_once 'models/CheckinKhach.php';
        $checkinModel = new CheckinKhach();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $khach = $checkinModel->findById($id);
            if (!$khach) {
                $_SESSION['error'] = 'Không tìm thấy khách.';
                header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
                exit();
            }
            
            // Cập nhật thông tin cơ bản (cần mở rộng model để update đầy đủ)
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? $khach['ho_ten'],
                'so_cmnd' => $_POST['so_cmnd'] ?? $khach['so_cmnd'],
                'so_passport' => $_POST['so_passport'] ?? $khach['so_passport'],
                'ngay_sinh' => $_POST['ngay_sinh'] ?? $khach['ngay_sinh'],
                'gioi_tinh' => $_POST['gioi_tinh'] ?? $khach['gioi_tinh'],
                'quoc_tich' => $_POST['quoc_tich'] ?? $khach['quoc_tich'],
                'dia_chi' => $_POST['dia_chi'] ?? $khach['dia_chi'],
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? $khach['so_dien_thoai'],
                'email' => $_POST['email'] ?? $khach['email'],
                'ghi_chu' => $_POST['ghi_chu'] ?? $khach['ghi_chu']
            ];
            
            // Cần mở rộng method update trong CheckinKhach model
            $result = $checkinModel->updateFull($id, $data);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật thông tin khách thành công.';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật thông tin khách.';
            }
            
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
        
        // GET: hiển thị form
        $khach = $checkinModel->findById($id);
        if (!$khach) {
            $_SESSION['error'] = 'Không tìm thấy khách.';
            header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
            exit();
        }
        
        require 'views/admin/sua_khach_chi_tiet.php';
    }

    // Xóa khách chi tiết
    public function xoaKhachChiTiet() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        require_once 'models/CheckinKhach.php';
        $checkinModel = new CheckinKhach();
        
        $khach = $checkinModel->findById($id);
        if (!$khach) {
            $_SESSION['error'] = 'Không tìm thấy khách.';
        } else {
            $result = $checkinModel->delete($id);
            if ($result) {
                $_SESSION['success'] = 'Xóa khách thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa khách.';
            }
        }
        
        header('Location: index.php?act=lichKhoiHanh/chiTiet&id=' . $lichKhoiHanhId);
        exit();
    }
}

