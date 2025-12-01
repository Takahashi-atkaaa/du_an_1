<?php
require_once 'models/Booking.php';
require_once 'models/Tour.php';
require_once 'models/KhachHang.php';
require_once 'models/NguoiDung.php';
require_once 'models/BookingHistory.php';
require_once 'models/BookingDeletionHistory.php';
require_once 'models/LichKhoiHanh.php';

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use Dompdf\Dompdf;
use Dompdf\Options;

class BookingController {
    private $bookingModel;
    private $tourModel;
    private $khachHangModel;
    private $nguoiDungModel;
    private $historyModel;
    private $deletionHistoryModel;
    private $lichKhoiHanhModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
        $this->khachHangModel = new KhachHang();
        $this->nguoiDungModel = new NguoiDung();
        $this->historyModel = new BookingHistory();
        $this->deletionHistoryModel = new BookingDeletionHistory();
        $this->lichKhoiHanhModel = new LichKhoiHanh();
    }
    
    public function create() {
        // requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $khachHangId = $_SESSION['khach_hang_id'] ?? null;
            if (!$khachHangId && isset($_SESSION['user_id'])) {
                $khachHang = $this->khachHangModel->findByNguoiDungId($_SESSION['user_id']);
                if ($khachHang) {
                    $khachHangId = $khachHang['khach_hang_id'];
                    $_SESSION['khach_hang_id'] = $khachHangId;
                }
            }

            if (!$khachHangId) {
                header('Location: index.php?act=tour/index');
                exit();
            }

            $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
            $tour = $tourId > 0 ? $this->tourModel->findById($tourId) : null;
            if ($tourId <= 0 || !$tour) {
                header('Location: index.php?act=tour/index');
                exit();
            }

        $ngayKhoiHanh = $_POST['ngay_khoi_hanh'] ?? '';
        $ngayKetThuc = $_POST['ngay_ket_thuc'] ?? $ngayKhoiHanh;

            $data = [
                'tour_id' => $tourId,
                'khach_hang_id' => $khachHangId,
                'ngay_dat' => date('Y-m-d'),
                'so_nguoi' => isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1,
            'ngay_khoi_hanh' => $ngayKhoiHanh,
            'ngay_ket_thuc' => $ngayKetThuc,
                'tong_tien' => isset($_POST['tong_tien']) ? (float)$_POST['tong_tien'] : (float)($tour['gia_co_ban'] ?? 0) * (isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1),
                'trang_thai' => 'ChoXacNhan',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $bookingId = $this->bookingModel->insert($data);
            if ($bookingId) {
                // Tự động tạo lịch khởi hành nếu chưa có
                if (!empty($ngayKhoiHanh)) {
                    $lichKhoiHanh = $this->lichKhoiHanhModel->findByTourAndNgayKhoiHanh($tourId, $ngayKhoiHanh);
                    if (!$lichKhoiHanh) {
                        // Tạo lịch khởi hành mới
                        $lichKhoiHanhData = [
                            'tour_id' => $tourId,
                            'ngay_khoi_hanh' => $ngayKhoiHanh,
                            'ngay_ket_thuc' => $ngayKetThuc,
                            'gio_xuat_phat' => null,
                            'gio_ket_thuc' => null,
                            'diem_tap_trung' => '',
                            'so_cho' => 50, // Mặc định
                            'hdv_id' => null,
                            'trang_thai' => 'SapKhoiHanh',
                            'ghi_chu' => 'Tạo tự động từ booking #' . $bookingId
                        ];
                        $this->lichKhoiHanhModel->insert($lichKhoiHanhData);
                    }
                }
                
                header("Location: index.php?act=booking/show&id=$bookingId");
                exit();
            } else {
                header('Location: index.php?act=tour/index');
                exit();
            }
        } else {
            $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
            $tour = $this->tourModel->findById($tourId);
            if (!$tour) {
                header('Location: index.php?act=tour/index');
                exit();
            }
            require 'views/khach_hang/dat_tour.php';
        }
    }
    
    public function show() {
        // requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $booking = $this->bookingModel->findById($id);
        
        $khachHangId = $_SESSION['khach_hang_id'] ?? null;
        if (!$khachHangId && isset($_SESSION['user_id'])) {
            $khachHang = $this->khachHangModel->findByNguoiDungId($_SESSION['user_id']);
            if ($khachHang) {
                $khachHangId = $khachHang['khach_hang_id'];
                $_SESSION['khach_hang_id'] = $khachHangId;
            }
        }

        if (!$booking || ($khachHangId && $booking['khach_hang_id'] != $khachHangId)) {
            header('Location: index.php?act=tour/index');
            exit();
        }
        $tour = $this->tourModel->findById($booking['tour_id']);
        require 'views/khach_hang/hoa_don.php';
    }
    
    public function index() {
        // requireLogin();
        $conditions = [];
        
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'KhachHang') {
            $khachHangId = $_SESSION['khach_hang_id'] ?? null;
            if (!$khachHangId && isset($_SESSION['user_id'])) {
                $khachHang = $this->khachHangModel->findByNguoiDungId($_SESSION['user_id']);
                if ($khachHang) {
                    $khachHangId = $khachHang['khach_hang_id'];
                    $_SESSION['khach_hang_id'] = $khachHangId;
                }
            }
            if ($khachHangId) {
                $conditions['khach_hang_id'] = $khachHangId;
            }
        }
        
        // Lọc theo trạng thái nếu có
        if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])) {
            $conditions['trang_thai'] = $_GET['trang_thai'];
        }
        
        if (!empty($conditions)) {
        $bookings = $this->bookingModel->find($conditions);
        } else {
            $bookings = $this->bookingModel->getAllWithDetails();
        }
        
        require 'views/admin/quan_ly_booking.php';
    }

    // Cập nhật trạng thái booking
    public function updateTrangThai() {
        // requireLogin();
        $bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
        $trangThaiMoi = $_POST['trang_thai'] ?? '';
        $ghiChu = trim($_POST['ghi_chu'] ?? '');
        $nguoiThayDoiId = $_SESSION['user_id'] ?? null;
        
        // Kiểm tra quyền
        if (!$this->checkPermissionToUpdate($bookingId)) {
            $_SESSION['error'] = 'Bạn không có quyền cập nhật booking này.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        if ($bookingId <= 0 || empty($trangThaiMoi)) {
            $_SESSION['error'] = 'Thông tin không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        $validStatuses = ['ChoXacNhan', 'DaCoc', 'HoanTat', 'Huy'];
        if (!in_array($trangThaiMoi, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        $result = $this->bookingModel->updateTrangThai($bookingId, $trangThaiMoi, $nguoiThayDoiId, $ghiChu);
        
        if ($result) {
            $_SESSION['success'] = 'Cập nhật trạng thái booking thành công.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái booking.';
        }
        
        header('Location: index.php?act=booking/chiTiet&id=' . $bookingId);
        exit();
    }

    // Xem chi tiết booking
    public function chiTiet() {
        // requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID booking không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        $booking = $this->bookingModel->getBookingWithDetails($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        // Kiểm tra quyền xem
        if (!$this->checkPermissionToView($booking)) {
            $_SESSION['error'] = 'Bạn không có quyền xem booking này.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        // Lấy lịch sử thay đổi
        $history = $this->historyModel->getByBookingId($id);
        
        // Lấy thông tin tour
        $tour = $this->tourModel->findById($booking['tour_id']);
        
        require 'views/admin/chi_tiet_booking.php';
    }

    // Cập nhật thông tin booking
    public function update() {
        // requireLogin();
        $id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID booking không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        // Kiểm tra quyền
        if (!$this->checkPermissionToUpdate($id)) {
            $_SESSION['error'] = 'Bạn không có quyền cập nhật booking này.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        $ngayKhoiHanh = $_POST['ngay_khoi_hanh'] ?? null;
        $ngayKetThuc = $_POST['ngay_ket_thuc'] ?? $ngayKhoiHanh;
        
        $data = [
            'so_nguoi' => isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1,
            'ngay_khoi_hanh' => $ngayKhoiHanh,
            'ngay_ket_thuc' => $ngayKetThuc,
            'tong_tien' => isset($_POST['tong_tien']) ? (float)$_POST['tong_tien'] : 0,
            'trang_thai' => $_POST['trang_thai'] ?? 'ChoXacNhan',
            'ghi_chu' => $_POST['ghi_chu'] ?? null
        ];
        
        // Lấy trạng thái cũ để lưu lịch sử nếu thay đổi
        $booking = $this->bookingModel->findById($id);
        $trangThaiCu = $booking['trang_thai'] ?? '';
        
        $result = $this->bookingModel->update($id, $data);
        
        if ($result) {
            // Nếu trạng thái thay đổi, lưu lịch sử
            if (isset($data['trang_thai']) && $data['trang_thai'] !== $trangThaiCu) {
                $this->bookingModel->updateTrangThai($id, $data['trang_thai'], $_SESSION['user_id'] ?? null, 'Cập nhật thông tin booking');
            }
            $_SESSION['success'] = 'Cập nhật booking thành công.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật booking.';
        }
        
        header('Location: index.php?act=booking/chiTiet&id=' . $id);
        exit();
    }

    // Xóa booking
    public function delete() {
        // requireLogin();
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID booking không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        // Chỉ Admin mới được xóa
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
            $_SESSION['error'] = 'Chỉ Admin mới có quyền xóa booking.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        // GET: Hiển thị form xác nhận mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $booking = $this->bookingModel->getBookingWithDetails($id);
            if (!$booking) {
                $_SESSION['error'] = 'Booking không tồn tại.';
                header('Location: index.php?act=admin/quanLyBooking');
                exit();
            }
            require 'views/admin/xac_nhan_xoa_booking.php';
            exit();
        }
        
        // POST: Xác nhận mật khẩu và xóa
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $matKhau = $_POST['mat_khau'] ?? '';
            $lyDoXoa = $_POST['ly_do_xoa'] ?? '';
            
            // Kiểm tra mật khẩu admin
            $adminId = $_SESSION['user_id'] ?? 0;
            $admin = $this->nguoiDungModel->findById($adminId);
            
            if (!$admin || !password_verify($matKhau, $admin['mat_khau'])) {
                $_SESSION['error'] = 'Mật khẩu không đúng.';
                header('Location: index.php?act=booking/delete&id=' . $id);
                exit();
            }
            
            // Lấy thông tin booking trước khi xóa
            $booking = $this->bookingModel->getBookingWithDetails($id);
            if (!$booking) {
                $_SESSION['error'] = 'Booking không tồn tại.';
                header('Location: index.php?act=admin/quanLyBooking');
                exit();
            }
            
            // Lưu thông tin booking vào JSON trước khi xóa
            $thongTinBooking = json_encode([
                'booking_id' => $booking['booking_id'],
                'tour_id' => $booking['tour_id'],
                'ten_tour' => $booking['ten_tour'] ?? 'N/A',
                'khach_hang_id' => $booking['khach_hang_id'],
                'ten_khach_hang' => $booking['ho_ten'] ?? 'N/A',
                'so_nguoi' => $booking['so_nguoi'] ?? 0,
                'tong_tien' => $booking['tong_tien'] ?? 0,
                'ngay_dat' => $booking['ngay_dat'] ?? null,
                'ngay_khoi_hanh' => $booking['ngay_khoi_hanh'] ?? null,
                'ngay_ket_thuc' => $booking['ngay_ket_thuc'] ?? null,
                'trang_thai' => $booking['trang_thai'] ?? 'N/A',
                'ghi_chu' => $booking['ghi_chu'] ?? null
            ], JSON_UNESCAPED_UNICODE);
            
            // Xóa booking
            $result = $this->bookingModel->delete($id);
            
            if ($result) {
                // Lưu vào lịch sử xóa
                $this->deletionHistoryModel->insert([
                    'booking_id' => $id,
                    'tour_id' => $booking['tour_id'] ?? null,
                    'khach_hang_id' => $booking['khach_hang_id'] ?? null,
                    'nguoi_xoa_id' => $adminId,
                    'ly_do_xoa' => $lyDoXoa,
                    'thong_tin_booking' => $thongTinBooking
                ]);
                
                $_SESSION['success'] = 'Xóa booking thành công.';
            } else {
                $_SESSION['error'] = 'Không thể xóa booking.';
            }
            
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
    }

    // Kiểm tra quyền cập nhật
    private function checkPermissionToUpdate($bookingId) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $role = $_SESSION['role'] ?? '';
        
        // Admin có quyền cập nhật tất cả
        if ($role === 'Admin') {
            return true;
        }
        
        // HDV có thể cập nhật booking của tour mình phụ trách
        if ($role === 'HDV') {
            // Có thể thêm logic kiểm tra tour do HDV phụ trách
            return true; // Tạm thời cho phép
        }
        
        return false;
    }

    // Kiểm tra quyền xem
    private function checkPermissionToView($booking) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $role = $_SESSION['role'] ?? '';
        
        // Admin và HDV có quyền xem tất cả
        if ($role === 'Admin' || $role === 'HDV') {
            return true;
        }
        
        // Khách hàng chỉ xem được booking của mình
        if ($role === 'KhachHang') {
            $khachHangId = $_SESSION['khach_hang_id'] ?? null;
            if (!$khachHangId && isset($_SESSION['user_id'])) {
                $khachHang = $this->khachHangModel->findByNguoiDungId($_SESSION['user_id']);
                if ($khachHang) {
                    $khachHangId = $khachHang['khach_hang_id'];
                }
            }
            return $khachHangId && $booking['khach_hang_id'] == $khachHangId;
        }
        
        return false;
    }

    // Nhân viên đặt tour cho khách hàng
    public function datTourChoKhach() {
        // requireRole('Admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Lấy thông tin từ form
                $tourId = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
                $hoTen = trim($_POST['ho_ten'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
                $diaChi = trim($_POST['dia_chi'] ?? '');
                $gioiTinh = $_POST['gioi_tinh'] ?? null;
                $ngaySinh = $_POST['ngay_sinh'] ?? null;
                $soNguoi = isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1;
                $ngayKhoiHanh = $_POST['ngay_khoi_hanh'] ?? '';
                $loaiKhach = $_POST['loai_khach'] ?? 'le'; // le hoặc doan
                $tenCongTy = trim($_POST['ten_cong_ty'] ?? '');
                $ghiChu = trim($_POST['ghi_chu'] ?? '');
                $yeuCauDacBiet = trim($_POST['yeu_cau_dac_biet'] ?? '');

                // Validation
                if (empty($hoTen)) {
                    throw new Exception('Vui lòng nhập tên khách hàng.');
                }
                if (empty($email) && empty($soDienThoai)) {
                    throw new Exception('Vui lòng nhập email hoặc số điện thoại.');
                }
                if ($tourId <= 0) {
                    throw new Exception('Vui lòng chọn tour.');
                }
                if (empty($ngayKhoiHanh)) {
                    throw new Exception('Vui lòng chọn ngày khởi hành.');
                }
                if ($soNguoi <= 0) {
                    throw new Exception('Số lượng người phải lớn hơn 0.');
                }

                // Kiểm tra tour tồn tại
                $tour = $this->tourModel->findById($tourId);
                if (!$tour) {
                    throw new Exception('Tour không tồn tại.');
                }

                // Kiểm tra chỗ trống
                $kiemTraCho = $this->bookingModel->kiemTraChoTrong($tourId, $ngayKhoiHanh, $soNguoi);
                if (!$kiemTraCho['co_cho']) {
                    throw new Exception("Không đủ chỗ trống. Chỉ còn {$kiemTraCho['cho_trong']} chỗ trống.");
                }

                // Tìm hoặc tạo người dùng
                $nguoiDung = $this->nguoiDungModel->findOrCreate($hoTen, $email, $soDienThoai, 'KhachHang');
                if (!$nguoiDung) {
                    throw new Exception('Không thể tạo tài khoản khách hàng.');
                }

                // Tìm hoặc tạo khách hàng
                $khachHang = $this->khachHangModel->findOrCreateByNguoiDungInfo(
                    $nguoiDung['id'],
                    $diaChi,
                    $gioiTinh,
                    $ngaySinh
                );
                if (!$khachHang) {
                    throw new Exception('Không thể tạo thông tin khách hàng.');
                }

                // Tính tổng tiền
                $giaCoBan = (float)($tour['gia_co_ban'] ?? 0);
                $tongTien = $giaCoBan * $soNguoi;

                // Tạo booking
                $bookingData = [
                    'tour_id' => $tourId,
                    'khach_hang_id' => $khachHang['khach_hang_id'],
                    'ngay_dat' => date('Y-m-d'),
                    'ngay_khoi_hanh' => $ngayKhoiHanh,
                    'ngay_ket_thuc' => !empty($_POST['ngay_ket_thuc']) ? $_POST['ngay_ket_thuc'] : $ngayKhoiHanh,
                    'so_nguoi' => $soNguoi,
                    'tong_tien' => $tongTien,
                    'trang_thai' => 'ChoXacNhan',
                    'ghi_chu' => $ghiChu . ($loaiKhach === 'doan' && !empty($tenCongTy) ? " | Công ty/Tổ chức: {$tenCongTy}" : '')
                ];

                $bookingId = $this->bookingModel->insert($bookingData);
                if (!$bookingId) {
                    throw new Exception('Không thể tạo booking.');
                }

                // Tự động tạo lịch khởi hành nếu chưa có
                if (!empty($ngayKhoiHanh)) {
                    $ngayKetThuc = !empty($_POST['ngay_ket_thuc']) ? $_POST['ngay_ket_thuc'] : $ngayKhoiHanh;
                    $lichKhoiHanh = $this->lichKhoiHanhModel->findByTourAndNgayKhoiHanh($tourId, $ngayKhoiHanh);
                    if (!$lichKhoiHanh) {
                        // Tạo lịch khởi hành mới
                        $lichKhoiHanhData = [
                            'tour_id' => $tourId,
                            'ngay_khoi_hanh' => $ngayKhoiHanh,
                            'ngay_ket_thuc' => $ngayKetThuc,
                            'gio_xuat_phat' => null,
                            'gio_ket_thuc' => null,
                            'diem_tap_trung' => '',
                            'so_cho' => 50, // Mặc định
                            'hdv_id' => null,
                            'trang_thai' => 'SapKhoiHanh',
                            'ghi_chu' => 'Tạo tự động từ booking #' . $bookingId
                        ];
                        $this->lichKhoiHanhModel->insert($lichKhoiHanhData);
                    }
                }

                // Lưu yêu cầu đặc biệt nếu có
                if (!empty($yeuCauDacBiet)) {
                    $this->tourModel->insertYeuCauDacBiet($bookingId, $yeuCauDacBiet);
                }

                $_SESSION['success'] = "Đặt tour thành công! Mã booking: #{$bookingId}";
                header("Location: index.php?act=booking/datTourChoKhach&success=1&booking_id={$bookingId}");
                exit();
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                // Giữ lại dữ liệu form để hiển thị lại
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?act=booking/datTourChoKhach');
                exit();
            }
        } else {
            // Hiển thị form
            $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : null;
            $tours = $this->tourModel->getAll();
            $tour = $tourId ? $this->tourModel->findById($tourId) : null;
            
            // Lấy lịch khởi hành nếu có tour
            $lichKhoiHanhList = [];
            if ($tour) {
                $lichKhoiHanhList = $this->tourModel->getLichKhoiHanhByTourId($tourId);
            }
            
            // Lấy dữ liệu form từ session nếu có (khi có lỗi)
            $formData = $_SESSION['form_data'] ?? [];
            unset($_SESSION['form_data']);
            
            require 'views/admin/dat_tour_cho_khach.php';
        }
    }

    // API: Kiểm tra chỗ trống (AJAX)
    public function kiemTraChoTrong() {
        header('Content-Type: application/json');
        
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
        $ngayKhoiHanh = $_GET['ngay_khoi_hanh'] ?? '';
        $soNguoi = isset($_GET['so_nguoi']) ? (int)$_GET['so_nguoi'] : 1;
        
        if ($tourId <= 0 || empty($ngayKhoiHanh)) {
            echo json_encode(['error' => 'Thiếu thông tin']);
            exit();
        }
        
        $result = $this->bookingModel->kiemTraChoTrong($tourId, $ngayKhoiHanh, $soNguoi);
        echo json_encode($result);
        exit();
    }

    // Trang xuất tài liệu (báo giá, hợp đồng, hóa đơn)
    public function xuatTaiLieu() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'ID booking không hợp lệ.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        $booking = $this->bookingModel->getBookingWithDetails($id);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại.';
            header('Location: index.php?act=admin/quanLyBooking');
            exit();
        }
        
        require 'views/admin/xuat_tai_lieu_booking.php';
    }

    // Xuất file PDF
    public function exportPDF() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $type = $_GET['type'] ?? 'bao-gia'; // bao-gia, hop-dong, hoa-don
        
        if ($id <= 0) {
            die('ID không hợp lệ');
        }
        
        $booking = $this->bookingModel->getBookingWithDetails($id);
        if (!$booking) {
            die('Booking không tồn tại');
        }
        
        // Tạo nội dung HTML
        ob_start();
        switch ($type) {
            case 'hop-dong':
                include 'views/admin/templates/hop_dong_template.php';
                $filename = 'Hop_Dong_' . $booking['booking_id'] . '.pdf';
                break;
            case 'hoa-don':
                include 'views/admin/templates/hoa_don_template.php';
                $filename = 'Hoa_Don_' . $booking['booking_id'] . '.pdf';
                break;
            default:
                include 'views/admin/templates/bao_gia_template.php';
                $filename = 'Bao_Gia_' . $booking['booking_id'] . '.pdf';
        }
        $html = ob_get_clean();
        
        // Sử dụng thư viện dompdf hoặc tương tự để tạo PDF
        // Nếu chưa cài, có thể tạm dùng cách đơn giản hơn
        $this->generateSimplePDF($html, $filename);
    }

    // Generate PDF đơn giản (có thể thay bằng dompdf)
    private function generateSimplePDF($html, $filename) {
        // Cách 1: Sử dụng mPDF hoặc dompdf (cần cài qua composer)
        // require_once 'vendor/autoload.php';
        // $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        // $mpdf->WriteHTML($html);
        // $mpdf->Output($filename, 'D'); // D = download
        
        // Cách 2: Tạm thời xuất HTML để xem trước và in (fallback)
        header('Content-Type: text/html; charset=UTF-8');
        
        // Chuyển HTML sang PDF đơn giản
        echo $this->convertHTMLtoPDF($html, $filename);
    }

    // Convert HTML to PDF đơn giản
    private function convertHTMLtoPDF($html, $filename) {
        // Thêm CSS cho print và auto print
        $pdfHTML = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . $filename . '</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
            <style>
                body { font-family: Arial, sans-serif; font-size: 12pt; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                .info-table td { border: 1px solid #dee2e6; padding: 8px; }
                .info-table td:first-child { font-weight: 600; background: #f8f9fa; width: 30%; }
                .detail-table { border: 1px solid #000; }
                .detail-table th, .detail-table td { border: 1px solid #000; padding: 8px; }
                .detail-table thead { background: #f8f9fa; font-weight: bold; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .text-muted { color: #6c757d; }
                .company-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 20px; }
                .document-title { text-align: center; font-size: 1.75rem; font-weight: bold; margin: 20px 0; text-transform: uppercase; }
                .total-section { border-top: 2px solid #000; padding-top: 15px; margin-top: 20px; }
                .signature-section { margin-top: 40px; }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>' . $html . '
            <div class="no-print text-center mt-4">
                <button onclick="window.print()" class="btn btn-primary btn-lg">
                    <i class="bi bi-printer"></i> In tài liệu
                </button>
                <button onclick="window.close()" class="btn btn-secondary btn-lg ms-2">
                    <i class="bi bi-x-circle"></i> Đóng
                </button>
            </div>
        </body>
        </html>';
        
        return $pdfHTML;
    }

    // Gửi email
    public function sendEmail() {
        header('Content-Type: application/json');
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $type = $_GET['type'] ?? 'bao-gia';
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit();
        }
        
        $booking = $this->bookingModel->getBookingWithDetails($id);
        if (!$booking) {
            echo json_encode(['success' => false, 'message' => 'Booking không tồn tại']);
            exit();
        }
        
        $email = $booking['email'];
        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Không có email khách hàng']);
            exit();
        }
        
        // Tạo nội dung email
        ob_start();
        switch ($type) {
            case 'hop-dong':
                include 'views/admin/templates/hop_dong_template.php';
                $subject = 'Hợp đồng dịch vụ du lịch - Booking #' . $booking['booking_id'];
                break;
            case 'hoa-don':
                include 'views/admin/templates/hoa_don_template.php';
                $subject = 'Hóa đơn thanh toán - Booking #' . $booking['booking_id'];
                break;
            default:
                include 'views/admin/templates/bao_gia_template.php';
                $subject = 'Báo giá tour du lịch - Booking #' . $booking['booking_id'];
        }
        $htmlContent = ob_get_clean();
        
        // Gửi email
        $result = $this->sendHTMLEmail($email, $subject, $htmlContent, $booking['ho_ten']);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã gửi email thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể gửi email']);
        }
        exit();
    }

    // Function gửi HTML email
    private function sendHTMLEmail($to, $subject, $htmlContent, $toName = '') {
        $from = 'info@dulichabc.vn';
        $fromName = 'Công ty Du lịch ABC';
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $fromName <$from>" . "\r\n";
        $headers .= "Reply-To: $from" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Email body
        $message = '
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 800px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; }
                .content { background: #fff; padding: 20px; }
                .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Công ty Du lịch ABC</h2>
                    <p>Cảm ơn quý khách đã tin tưởng sử dụng dịch vụ</p>
                </div>
                <div class="content">
                    <p>Kính gửi: <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                    <p>Chúng tôi xin gửi đến quý khách tài liệu đính kèm.</p>
                    <hr>
                    ' . $htmlContent . '
                    <hr>
                    <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ:</p>
                    <ul>
                        <li>Hotline: 1900 xxxx</li>
                        <li>Email: info@dulichabc.vn</li>
                        <li>Website: www.dulichabc.vn</li>
                    </ul>
                </div>
                <div class="footer">
                    <p>© 2025 Công ty Du lịch ABC. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Gửi email
        return mail($to, $subject, $message, $headers);
    }

}