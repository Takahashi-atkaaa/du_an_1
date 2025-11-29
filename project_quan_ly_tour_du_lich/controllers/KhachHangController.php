<?php

class KhachHangController {
    
    public function __construct() {
        requireLogin();
    }
    
    // Dashboard khách hàng
    public function dashboard() {
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        require_once 'models/ThongBao.php';
        require_once 'models/Tour.php';
        require_once 'models/DanhGia.php';

        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        $thongBaoModel = new ThongBao();
        $tourModel = new Tour();
        $danhGiaModel = new DanhGia();

        // Lấy thông tin khách hàng
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        if (!$khachHang) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php?act=auth/profile');
            exit();
        }

        // Lấy booking của khách hàng
        $bookings = $bookingModel->getByKhachHangId($khachHang['khach_hang_id']);

        // Lấy thông báo chưa đọc
        $thongBaoChuaDoc = $thongBaoModel->countChuaDoc($_SESSION['user_id']);
        $thongBaoList = $thongBaoModel->getByNguoiDung($_SESSION['user_id'], 5);

        // Lấy tour sắp tới (booking có ngày khởi hành >= hôm nay)
        $tourSapToi = [];
        $today = date('Y-m-d');
        foreach ($bookings as $booking) {
            if (!empty($booking['ngay_khoi_hanh']) && $booking['ngay_khoi_hanh'] >= $today && 
                in_array($booking['trang_thai'], ['ChoXacNhan', 'DaCoc', 'HoanTat'])) {
                $tourSapToi[] = $booking;
            }
        }

        // Thống kê
        $tongBooking = count($bookings);
        $bookingChoXacNhan = count(array_filter($bookings, fn($b) => $b['trang_thai'] === 'ChoXacNhan'));
        $bookingDaCoc = count(array_filter($bookings, fn($b) => $b['trang_thai'] === 'DaCoc'));
        $bookingHoanTat = count(array_filter($bookings, fn($b) => $b['trang_thai'] === 'HoanTat'));

        // Lấy danh sách tour từ DB và phân loại
        $allTours = $tourModel->getAll();
        $tourTrongNuoc = [];
        $tourQuocTe = [];
        $tourTheoYeuCau = [];
        foreach ($allTours as $tour) {
            switch ($tour['loai_tour']) {
                case 'TrongNuoc':
                    $tourTrongNuoc[] = $tour;
                    break;
                case 'QuocTe':
                    $tourQuocTe[] = $tour;
                    break;
                case 'TheoYeuCau':
                    $tourTheoYeuCau[] = $tour;
                    break;
            }
        }

        // Lấy 3 đánh giá tốt nhất từ DB
        // Lấy 3 đánh giá tốt nhất (điểm >= 4 trên thang 5)
        $danhGiaTot = $danhGiaModel->filter([
            'diem_min' => 4
        ]);
        $danhGiaTot = array_slice($danhGiaTot, 0, 3);

        require 'views/khach_hang/dashboard.php';
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
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        
        $tourModel = new Tour();
        $nccModel = new NhaCungCap();
        $nhanSuModel = new NhanSu();
        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        
        // Lấy booking đã hoàn thành của khách hàng để đánh giá
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        $bookingsHoanTat = [];
        if ($khachHang) {
            $allBookings = $bookingModel->getByKhachHangId($khachHang['khach_hang_id']);
            $bookingsHoanTat = array_filter($allBookings, fn($b) => $b['trang_thai'] === 'HoanTat');
        }
        
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

    // Xem hóa đơn và trạng thái thanh toán
    public function hoaDon() {
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        require_once 'models/GiaoDich.php';
        
        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        $giaoDichModel = new GiaoDich();
        
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        if (!$khachHang) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $booking = null;
        $tour = null;
        $giaoDichList = [];
        
        if ($bookingId > 0) {
            $booking = $bookingModel->getBookingWithDetails($bookingId);
            if ($booking && $booking['khach_hang_id'] == $khachHang['khach_hang_id']) {
                $giaoDichList = $giaoDichModel->getByTourId($booking['tour_id']);
                // Tính tổng đã thanh toán
                $tongDaThanhToan = $giaoDichModel->getTongThuByTour($booking['tour_id']);
            } else {
                $_SESSION['error'] = 'Không tìm thấy hóa đơn';
                header('Location: index.php?act=khachHang/dashboard');
                exit();
            }
        } else {
            // Lấy tất cả booking của khách hàng
            $bookings = $bookingModel->getByKhachHangId($khachHang['khach_hang_id']);
        }
        
        require 'views/khach_hang/hoa_don.php';
    }
    
    // Xem lịch trình tour chi tiết
    public function lichTrinhTour() {
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        require_once 'models/Tour.php';
        
        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        $tourModel = new Tour();
        
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        if (!$khachHang) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        $booking = null;
        $tour = null;
        $lichTrinhList = [];
        $lichKhoiHanh = null;
        
        if ($bookingId > 0) {
            $booking = $bookingModel->getBookingWithDetails($bookingId);
            if ($booking && $booking['khach_hang_id'] == $khachHang['khach_hang_id']) {
                $tour = $tourModel->findById($booking['tour_id']);
                if ($tour) {
                    $lichTrinhList = $tourModel->getLichTrinhByTourId($booking['tour_id']);
                    $lichKhoiHanhList = $tourModel->getLichKhoiHanhByTourId($booking['tour_id']);
                    // Tìm lịch khởi hành phù hợp với ngày khởi hành của booking
                    foreach ($lichKhoiHanhList as $lkh) {
                        if ($lkh['ngay_khoi_hanh'] == $booking['ngay_khoi_hanh']) {
                            $lichKhoiHanh = $lkh;
                            break;
                        }
                    }
                }
            } else {
                $_SESSION['error'] = 'Không tìm thấy booking';
                header('Location: index.php?act=khachHang/dashboard');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Thiếu mã booking';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        require 'views/khach_hang/lich_trinh_tour.php';
    }
    
    // Thông báo
    public function thongBao() {
        require_once 'models/ThongBao.php';
        
        $thongBaoModel = new ThongBao();
        
        $thongBaoList = $thongBaoModel->getByNguoiDung($_SESSION['user_id'], 50);
        $thongBaoChuaDoc = $thongBaoModel->countChuaDoc($_SESSION['user_id']);
        
        // Đánh dấu đã đọc nếu có tham số
        if (isset($_GET['mark_read']) && $_GET['mark_read'] > 0) {
            $thongBaoModel->danhDauDaDoc((int)$_GET['mark_read'], $_SESSION['user_id']);
            header('Location: index.php?act=khachHang/thongBao');
            exit();
        }
        
        require 'views/khach_hang/thong_bao.php';
    }
    
    // Cập nhật thông tin cá nhân
    public function capNhatThongTin() {
        require_once 'models/KhachHang.php';
        require_once 'models/NguoiDung.php';
        
        $khachHangModel = new KhachHang();
        $nguoiDungModel = new NguoiDung();
        
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        $nguoiDung = $nguoiDungModel->findById($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cập nhật thông tin người dùng
            $nguoiDungData = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];
            
            if (!empty($_POST['mat_khau_moi']) && $_POST['mat_khau_moi'] === $_POST['xac_nhan_mat_khau']) {
                $nguoiDungData['mat_khau'] = password_hash($_POST['mat_khau_moi'], PASSWORD_DEFAULT);
            }
            
            $nguoiDungModel->update($_SESSION['user_id'], $nguoiDungData);
            
            // Cập nhật thông tin khách hàng
            if ($khachHang) {
                $khachHangData = [
                    'dia_chi' => $_POST['dia_chi'] ?? null,
                    'gioi_tinh' => $_POST['gioi_tinh'] ?? null,
                    'ngay_sinh' => !empty($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : null
                ];
                
                $sql = "UPDATE khach_hang SET dia_chi = ?, gioi_tinh = ?, ngay_sinh = ? WHERE khach_hang_id = ?";
                $stmt = $khachHangModel->conn->prepare($sql);
                $stmt->execute([
                    $khachHangData['dia_chi'],
                    $khachHangData['gioi_tinh'],
                    $khachHangData['ngay_sinh'],
                    $khachHang['khach_hang_id']
                ]);
            }
            
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
            header('Location: index.php?act=khachHang/capNhatThongTin');
            exit();
        }
        
        require 'views/khach_hang/cap_nhat_thong_tin.php';
    }
    
    // Gửi yêu cầu hỗ trợ
    public function guiYeuCauHoTro() {
        require_once 'models/ThongBao.php';
        require_once 'models/KhachHang.php';
        
        $thongBaoModel = new ThongBao();
        $khachHangModel = new KhachHang();
        
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tieu_de' => $_POST['tieu_de'] ?? 'Yêu cầu hỗ trợ',
                'noi_dung' => $_POST['noi_dung'] ?? '',
                'loai_thong_bao' => 'KhachHang',
                'muc_do_uu_tien' => $_POST['muc_do_uu_tien'] ?? 'TrungBinh',
                'nguoi_gui_id' => $_SESSION['user_id'],
                'vai_tro_nhan' => 'Admin',
                'trang_thai' => 'DaGui'
            ];
            
            if ($thongBaoModel->insert($data)) {
                $_SESSION['success'] = 'Yêu cầu hỗ trợ đã được gửi thành công. Chúng tôi sẽ phản hồi sớm nhất có thể.';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
            }
            
            header('Location: index.php?act=khachHang/guiYeuCauHoTro');
            exit();
        }
        
        require 'views/khach_hang/gui_yeu_cau_ho_tro.php';
    }
    
    // Thanh toán online
    public function thanhToan() {
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        require_once 'models/GiaoDich.php';
        
        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        $giaoDichModel = new GiaoDich();
        
        $khachHang = $khachHangModel->findByUserId($_SESSION['user_id']);
        if (!$khachHang) {
            $_SESSION['error'] = 'Không tìm thấy thông tin khách hàng';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        if ($bookingId <= 0) {
            $_SESSION['error'] = 'Thiếu mã booking';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        $booking = $bookingModel->getBookingWithDetails($bookingId);
        if (!$booking || $booking['khach_hang_id'] != $khachHang['khach_hang_id']) {
            $_SESSION['error'] = 'Không tìm thấy booking';
            header('Location: index.php?act=khachHang/dashboard');
            exit();
        }
        
        // Xử lý thanh toán
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $soTien = (float)($_POST['so_tien'] ?? 0);
            $phuongThuc = $_POST['phuong_thuc'] ?? 'Online';
            
            if ($soTien > 0) {
                // Lưu giao dịch
                $giaoDichData = [
                    'tour_id' => $booking['tour_id'],
                    'loai' => 'Thu',
                    'so_tien' => $soTien,
                    'mo_ta' => "Thanh toán booking #{$bookingId} - {$booking['ten_tour']}",
                    'ngay_giao_dich' => date('Y-m-d')
                ];
                
                if ($giaoDichModel->insert($giaoDichData)) {
                    // Cập nhật trạng thái booking nếu đã thanh toán đủ
                    $tongDaThanhToan = $giaoDichModel->getTongThuByTour($booking['tour_id']);
                    if ($tongDaThanhToan >= $booking['tong_tien']) {
                        $bookingModel->updateTrangThai($bookingId, 'DaCoc', $_SESSION['user_id'], 'Đã thanh toán đủ');
                    }
                    
                    $_SESSION['success'] = 'Thanh toán thành công!';
                    header('Location: index.php?act=khachHang/hoaDon&booking_id=' . $bookingId);
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xử lý thanh toán';
                }
            }
        }
        
        // Tính số tiền còn nợ
        $tongDaThanhToan = $giaoDichModel->getTongThuByTour($booking['tour_id']);
        $conNo = max(0, $booking['tong_tien'] - $tongDaThanhToan);
        
        require 'views/khach_hang/thanh_toan.php';
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
