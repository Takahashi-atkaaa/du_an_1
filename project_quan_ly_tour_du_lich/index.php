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
require_once './controllers/LichKhoiHanhController.php';

// Require toàn bộ file Models
require_once './models/NguoiDung.php';
require_once './models/Tour.php';
require_once './models/Booking.php';
require_once './models/BookingHistory.php';
require_once './models/KhachHang.php';
require_once './models/HDV.php';
require_once './models/NhaCungCap.php';
require_once './models/GiaoDich.php';
require_once './models/DanhGia.php';
require_once './models/NhanSu.php';
require_once './models/LichKhoiHanh.php';
require_once './models/PhanBoNhanSu.php';
require_once './models/PhanBoDichVu.php';

// Route
$act = $_GET['act'] ?? 'auth/login';

// Để đảm bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match
match ($act) {
    // Trang chủ - Tour
    'tour/index' => (new TourController())->index(),
    'tour/show' => (new TourController())->show(),
    'tour/create' => (new TourController())->create(),
    'tour/update' => (new TourController())->update(),
    'tour/delete' => (new TourController())->delete(),
    // Lịch khởi hành trong tour
    'tour/taoLichKhoiHanh' => (new TourController())->taoLichKhoiHanh(),
    'tour/chiTietLichKhoiHanh' => (new TourController())->chiTietLichKhoiHanh(),
    'tour/phanBoNhanSuLichKhoiHanh' => (new TourController())->phanBoNhanSuLichKhoiHanh(),
    'tour/updateTrangThaiNhanSuLichKhoiHanh' => (new TourController())->updateTrangThaiNhanSuLichKhoiHanh(),
    'tour/phanBoDichVuLichKhoiHanh' => (new TourController())->phanBoDichVuLichKhoiHanh(),
    'tour/updateTrangThaiDichVuLichKhoiHanh' => (new TourController())->updateTrangThaiDichVuLichKhoiHanh(),
    'tour/deleteNhanSuLichKhoiHanh' => (new TourController())->deleteNhanSuLichKhoiHanh(),
    'tour/deleteDichVuLichKhoiHanh' => (new TourController())->deleteDichVuLichKhoiHanh(),
   
    // Auth
    'auth/login' => (new AuthController())->login(),
    'auth/register' => (new AuthController())->register(),
    'auth/logout' => (new AuthController())->logout(),

    'auth/profile' => (new AuthController())->profile(),

    
    // Booking
    'booking/index' => (new BookingController())->index(),
    'booking/create' => (new BookingController())->create(),
    'booking/show' => (new BookingController())->show(),
    'booking/chiTiet' => (new BookingController())->chiTiet(),
    'booking/update' => (new BookingController())->update(),
    'booking/updateTrangThai' => (new BookingController())->updateTrangThai(),
    'booking/delete' => (new BookingController())->delete(),
    'booking/datTourChoKhach' => (new BookingController())->datTourChoKhach(),
    'booking/kiemTraChoTrong' => (new BookingController())->kiemTraChoTrong(),
    'booking/xuatTaiLieu' => (new BookingController())->xuatTaiLieu(),
    'booking/exportPDF' => (new BookingController())->exportPDF(),
    'booking/sendEmail' => (new BookingController())->sendEmail(),

    
    // Lịch khởi hành
    'lichKhoiHanh/index' => (new LichKhoiHanhController())->index(),
    'lichKhoiHanh/create' => (new LichKhoiHanhController())->create(),
    'lichKhoiHanh/chiTiet' => (new LichKhoiHanhController())->chiTiet(),
    'lichKhoiHanh/update' => (new LichKhoiHanhController())->update(),
    'lichKhoiHanh/phanBoNhanSu' => (new LichKhoiHanhController())->phanBoNhanSu(),
    'lichKhoiHanh/updateTrangThaiNhanSu' => (new LichKhoiHanhController())->updateTrangThaiNhanSu(),
    'lichKhoiHanh/phanBoDichVu' => (new LichKhoiHanhController())->phanBoDichVu(),
    'lichKhoiHanh/updateTrangThaiDichVu' => (new LichKhoiHanhController())->updateTrangThaiDichVu(),
    'lichKhoiHanh/deleteNhanSu' => (new LichKhoiHanhController())->deleteNhanSu(),
    'lichKhoiHanh/deleteDichVu' => (new LichKhoiHanhController())->deleteDichVu(),
    
    // Admin
    'admin/dashboard' => (new AdminController())->dashboard(),
    'admin/quanLyTour' => (new AdminController())->quanLyTour(),
    'admin/quanLyNguoiDung' => (new AdminController())->quanLyNguoiDung(),
    'admin/quanLyBooking' => (new AdminController())->quanLyBooking(),
    'admin/baoCaoTaiChinh' => (new AdminController())->baoCaoTaiChinh(),
    'admin/danhGia' => (new AdminController())->danhGia(),
    'admin/chiTietTour' => (new AdminController())->chiTietTour(),
    'admin/addNhacungcap' => (new AdminController())->addNhacungcap(),
    // HDV
    'hdv/dashboard' => (new HDVController())->dashboard(),
    'hdv/tours' => (new HDVController())->tours(),
    'hdv/tour_detail' => (new HDVController())->tourDetail(),
    'hdv/khach' => (new HDVController())->khach(),
    'hdv/nhat_ky' => (new HDVController())->nhatKy(),
    'hdv/save_nhat_ky' => (new HDVController())->saveNhatKy(),
    'hdv/delete_nhat_ky' => (new HDVController())->deleteNhatKy(),
    'hdv/checkin' => (new HDVController())->checkin(),
    'hdv/save_diem_checkin' => (new HDVController())->saveDiemCheckin(),
    'hdv/delete_diem_checkin' => (new HDVController())->deleteDiemCheckin(),
    'hdv/save_checkin_khach' => (new HDVController())->saveCheckinKhach(),
    'hdv/yeu_cau_dac_biet' => (new HDVController())->yeuCauDacBiet(),
    'hdv/save_yeu_cau' => (new HDVController())->saveYeuCauDacBiet(),
    'hdv/delete_yeu_cau' => (new HDVController())->deleteYeuCauDacBiet(),
    'hdv/phan_hoi' => (new HDVController())->phanHoi(),
    'hdv/save_phan_hoi' => (new HDVController())->savePhanHoi(),
    'hdv/delete_phan_hoi' => (new HDVController())->deletePhanHoi(),
    'hdv/profile' => (new HDVController())->profile(),
    'hdv/update_profile' => (new HDVController())->updateProfile(),
    'hdv/danh_gia' => (new HDVController())->danhGia(),
    'hdv/notifications' => (new HDVController())->notifications(),
    'hdv/lichLamViec' => (new HDVController())->lichLamViec(),
    'hdv/nhatKyTour' => (new HDVController())->nhatKyTour(),
    'hdv/danhSachKhach' => (new HDVController())->danhSachKhach(),
    'hdv/phanHoi' => (new HDVController())->phanHoi(),
    // Admin - quản lý HDV
    'admin/quanLyHDV' => (new AdminController())->quanLyHDV(),
    'admin/quanLyHDV_create' => (new AdminController())->quanLyHDVCreate(),
    'admin/quanLyHDV_update' => (new AdminController())->quanLyHDVUpdate(),
    'admin/quanLyHDV_delete' => (new AdminController())->quanLyHDVDelete(),
    // Admin - HDV schedule & profile
    'admin/hdv_schedule' => (new AdminController())->hdvSchedule(),
    'admin/hdv_profile' => (new AdminController())->hdvProfile(),
    // API endpoints for AJAX
    'admin/hdv_api_get_schedule' => (new AdminController())->hdvApiGetSchedule(),
    'admin/hdv_api_check' => (new AdminController())->hdvApiCheck(),
    'admin/hdv_api_assign' => (new AdminController())->hdvApiAssign(),
    'admin/hdv_api_suggest' => (new AdminController())->hdvApiSuggest(),
    'admin/nhanSu_get_users' => (new AdminController())->nhanSu_get_users(),
    // Admin - Quản lý khách theo tour
    'admin/danhSachKhachTheoTour' => (new AdminController())->danhSachKhachTheoTour(),
    'admin/checkInKhach' => (new AdminController())->checkInKhach(),
    'admin/updateCheckIn' => (new AdminController())->updateCheckIn(),
    'admin/phanPhongKhachSan' => (new AdminController())->phanPhongKhachSan(),
    
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

    // Nhân sự
    'admin/nhanSu' => (new AdminController())->nhanSu(),
    'admin/nhanSuController' => (new AdminController())->nhanSu(),
    'admin/nhanSu_create' => (new AdminController())->nhanSuCreate(),
    'admin/nhanSu_update' => (new AdminController())->nhanSuUpdate(),
    'admin/nhanSu_delete' => (new AdminController())->nhanSuDelete(),
    'admin/nhanSu_chi_tiet' => (new AdminController())->nhanSuChiTiet(),

    // Quản lý HDV nâng cao
    'admin/hdv_advanced' => (new AdminController())->hdvAdvanced(),
    'admin/hdv_lich_table' => (new AdminController())->hdvLichTable(),
    'admin/hdv_add_schedule' => (new AdminController())->hdvAddSchedule(),
    'admin/hdv_get_schedule' => (new AdminController())->hdvGetSchedule(),
    'admin/hdv_send_notification' => (new AdminController())->hdvSendNotification(),
    'admin/hdv_detail' => (new AdminController())->hdvDetail(),
    
    // Default
    default => die("Route không tồn tại: $act")
};

