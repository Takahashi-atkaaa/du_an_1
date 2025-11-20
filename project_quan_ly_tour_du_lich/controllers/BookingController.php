<?php
require_once 'models/Booking.php';
require_once 'models/Tour.php';
require_once 'models/KhachHang.php';
require_once 'models/NguoiDung.php';
require_once 'models/BookingHistory.php';

class BookingController {
    private $bookingModel;
    private $tourModel;
    private $khachHangModel;
    private $nguoiDungModel;
    private $historyModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
        $this->khachHangModel = new KhachHang();
        $this->nguoiDungModel = new NguoiDung();
        $this->historyModel = new BookingHistory();
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

            $data = [
                'tour_id' => $tourId,
                'khach_hang_id' => $khachHangId,
                'ngay_dat' => date('Y-m-d'),
                'so_nguoi' => isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1,
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'] ?? '',
                'tong_tien' => isset($_POST['tong_tien']) ? (float)$_POST['tong_tien'] : (float)($tour['gia_co_ban'] ?? 0) * (isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1),
                'trang_thai' => 'ChoXacNhan',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $bookingId = $this->bookingModel->insert($data);
            if ($bookingId) {
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
        
        $data = [
            'so_nguoi' => isset($_POST['so_nguoi']) ? (int)$_POST['so_nguoi'] : 1,
            'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'] ?? null,
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
        
        $result = $this->bookingModel->delete($id);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa booking thành công.';
        } else {
            $_SESSION['error'] = 'Không thể xóa booking.';
        }
        
        header('Location: index.php?act=admin/quanLyBooking');
        exit();
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
                    'so_nguoi' => $soNguoi,
                    'tong_tien' => $tongTien,
                    'trang_thai' => 'ChoXacNhan',
                    'ghi_chu' => $ghiChu . ($loaiKhach === 'doan' && !empty($tenCongTy) ? " | Công ty/Tổ chức: {$tenCongTy}" : '')
                ];

                $bookingId = $this->bookingModel->insert($bookingData);
                if (!$bookingId) {
                    throw new Exception('Không thể tạo booking.');
                }

                // Lưu yêu cầu đặc biệt nếu có
                if (!empty($yeuCauDacBiet)) {
                    $this->tourModel->insertYeuCauDacBiet($khachHang['khach_hang_id'], $tourId, $yeuCauDacBiet);
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

}