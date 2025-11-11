<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/AuthController.php';
require_once './controllers/AdminController.php';
require_once './controllers/TourController.php';
require_once './controllers/BookingController.php';
require_once './controllers/HDVController.php';
require_once './controllers/KhachHangController.php';
require_once './controllers/NhaCungCapController.php';

// Require toàn bộ file Models
require_once './models/NguoiDung.php';
require_once './models/Tour.php';
require_once './models/Booking.php';
require_once './models/KhachHang.php';
require_once './models/HDV.php';
require_once './models/NhaCungCap.php';
require_once './models/GiaoDich.php';
require_once './models/DanhGia.php';

// Route
$act = $_GET['act'] ?? 'admin/dashboard';

// Để đảm bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match
match ($act) {
    // Trang chủ - Tour
    'tour/index' => (new TourController())->index(),
    'tour/show' => (new TourController())->show(),
    'tour/create' => (new TourController())->create(),
    'tour/update' => (new TourController())->update(),
    'tour/delete' => (new TourController())->delete(),
    
    // Auth
    'auth/login' => (new AuthController())->login(),
    'auth/register' => (new AuthController())->register(),
    'auth/logout' => (new AuthController())->logout(),
    'auth/forgotPassword' => (new AuthController())->forgotPassword(),
    'auth/profile' => (new AuthController())->profile(),

    
    // Booking
    'booking/index' => (new BookingController())->index(),
    'booking/create' => (new BookingController())->create(),
    'booking/show' => (new BookingController())->show(),
    
    // Admin
    'admin/dashboard' => (new AdminController())->dashboard(),
    'admin/quanLyTour' => (new AdminController())->quanLyTour(),
    'admin/quanLyNguoiDung' => (new AdminController())->quanLyNguoiDung(),
    'admin/quanLyBooking' => (new AdminController())->quanLyBooking(),
    'admin/baoCaoTaiChinh' => (new AdminController())->baoCaoTaiChinh(),
    'admin/danhGia' => (new AdminController())->danhGia(),
    
    // HDV
    'hdv/lichLamViec' => (new HDVController())->lichLamViec(),
    'hdv/nhatKyTour' => (new HDVController())->nhatKyTour(),
    'hdv/danhSachKhach' => (new HDVController())->danhSachKhach(),
    'hdv/phanHoi' => (new HDVController())->phanHoi(),
    
    // Nhà cung cấp
    'nhaCungCap/baoGia' => (new NhaCungCapController())->baoGia(),
    'nhaCungCap/dichVu' => (new NhaCungCapController())->dichVu(),
    'nhaCungCap/congNo' => (new NhaCungCapController())->congNo(),
    'nhaCungCap/hopDong' => (new NhaCungCapController())->hopDong(),
    
    // Khách hàng
    'khachHang/danhSachTour' => (new KhachHangController())->danhSachTour(),
    'khachHang/chiTietTour' => (new KhachHangController())->chiTietTour(),
    'khachHang/datTour' => (new KhachHangController())->datTour(),
    'khachHang/danhGia' => (new KhachHangController())->danhGia(),
    'khachHang/traCuu' => (new KhachHangController())->traCuu(),
    
    // Default
    default => die("Route không tồn tại: $act")
};

