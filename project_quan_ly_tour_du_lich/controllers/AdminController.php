<?php

class AdminController {
    // Hiển thị so sánh chi tiết chi phí thực tế và dự toán
    public function soSanhChiTietChiPhi() {
        require_once __DIR__ . '/../models/DuToanTour.php';
        require_once __DIR__ . '/../models/ChiPhiThucTe.php';
        $tour_id = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 1;
        $duToanModel = new DuToanTour();
        $chiPhiModel = new ChiPhiThucTe();
        $duToan = $duToanModel->findByTourId($tour_id);
        $chiPhis = $chiPhiModel->findByTourId($tour_id);
        $tongDuToan = $duToan ? $duToan['tong_du_toan'] : 0;
        $tongChiPhiThucTe = 0;
        $chiPhiSoSanh = [];
        if ($duToan && $chiPhis) {
            // Gom nhóm theo loại chi phí
            $loaiChiPhiArr = [];
            foreach ($chiPhis as $cp) {
                $loai = $cp['loai_chi_phi'];
                if (!isset($loaiChiPhiArr[$loai])) $loaiChiPhiArr[$loai] = 0;
                $loaiChiPhiArr[$loai] += $cp['so_tien'];
                $tongChiPhiThucTe += $cp['so_tien'];
            }
            foreach ($loaiChiPhiArr as $loai => $thucTe) {
                $duToanLoai = $duToanModel->getDuToanLoai($tour_id, $loai);
                $chiPhiSoSanh[] = [
                    'loai_chi_phi' => $loai,
                    'du_toan' => $duToanLoai,
                    'thuc_te' => $thucTe,
                    'chenh_lech' => $thucTe - $duToanLoai,
                    'ghi_chu' => ''
                ];
            }
        }
        $tour = ['ten_tour' => $duToan ? $duToan['ten_tour'] : ''];
        require 'views/admin/bao_cao_tai_chinh/so_sanh_chi_tiet.php';
    }
    // ...existing code...
    public function xemChiTietNguoiDung() {
        require_once 'models/NguoiDung.php';
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nguoiDungModel = new NguoiDung();
        $nguoiDung = $nguoiDungModel->findById($id);
        require 'views/admin/chi_tiet_nguoi_dung.php';
    }
    // ...existing code...
                    // Xóa khách khỏi booking
                    public function xoaKhachBooking() {
                        require_once 'models/Booking.php';
                        require_once 'models/KhachHang.php';
                        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
                        $khachHangId = isset($_GET['khach_id']) ? (int)$_GET['khach_id'] : 0;
                        if ($bookingId > 0 && $khachHangId > 0) {
                            $bookingModel = new Booking();
                            $sql = "DELETE FROM booking_khach_hang WHERE booking_id = ? AND khach_hang_id = ?";
                            $stmt = $bookingModel->conn->prepare($sql);
                            $stmt->execute([$bookingId, $khachHangId]);
                        }
                        // Nếu là AJAX thì trả về JSON, nếu không thì reload lại trang hiện tại
                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                            echo json_encode(['success' => true]);
                            exit;
                        } else {
                            // Reload lại trang hiện tại
                            echo '<script>window.location.reload();</script>';
                            exit;
                        }
                    }

                    // Sửa thông tin khách hàng của booking
                    public function suaKhachBooking() {
                        require_once 'models/Booking.php';
                        require_once 'models/KhachHang.php';
                        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
                        $khachHangId = isset($_GET['khach_id']) ? (int)$_GET['khach_id'] : 0;
                        $khachHangModel = new KhachHang();
                        $bookingModel = new Booking();
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $khachHangId > 0) {
                            $tenKhachHang = $_POST['ho_ten'] ?? '';
                            $gmail = $_POST['email'] ?? '';
                            $soDienThoai = $_POST['so_dien_thoai'] ?? '';
                            $diaChi = $_POST['dia_chi'] ?? '';
                            $ngaySinh = $_POST['ngay_sinh'] ?? null;
                            $gioiTinh = $_POST['gioi_tinh'] ?? null;
                            if ($gioiTinh !== 'Nam' && $gioiTinh !== 'Nữ' && $gioiTinh !== 'Khác') {
                                $gioiTinh = null;
                            }
                            $sql = "UPDATE khach_hang SET ten_khach_hang = ?, gmail = ?, so_dien_thoai = ?, dia_chi = ?, gioi_tinh = ?, ngay_sinh = ? WHERE khach_hang_id = ?";
                            $stmt = $khachHangModel->conn->prepare($sql);
                            $stmt->execute([$tenKhachHang, $gmail, $soDienThoai, $diaChi, $gioiTinh, $ngaySinh, $khachHangId]);
                            echo '<script>window.location.href = "index.php?act=admin/danhSachKhachBooking&booking_id=' . $bookingId . '";</script>';
                            exit;
                        }
                        // Lấy thông tin khách để hiển thị form sửa
                        $khach = $khachHangModel->findById($khachHangId);
                        require 'views/admin/sua_khach_booking.php';
                    }
                // Xử lý thêm khách hàng vào booking
                public function themKhachBooking() {
                    require_once 'models/Booking.php';
                    require_once 'models/KhachHang.php';
                    $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
                    $hoTen = $_POST['ho_ten'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $soDienThoai = $_POST['so_dien_thoai'] ?? '';
                    $diaChi = $_POST['dia_chi'] ?? '';
                    $ngaySinh = $_POST['ngay_sinh'] ?? null;
                    $gioiTinh = $_POST['gioi_tinh'] ?? null;
                    if ($gioiTinh !== 'Nam' && $gioiTinh !== 'Nữ' && $gioiTinh !== 'Khác') {
                        $gioiTinh = null;
                    }
                    $diemDanh = $_POST['diem_danh'] ?? 'co_mat';
                    if ($bookingId > 0 && $hoTen && $email && $soDienThoai) {
                        $khachHangModel = new KhachHang();
                        // Chèn trực tiếp vào bảng khach_hang
                        $sqlInsertKH = "INSERT INTO khach_hang (ten_khach_hang, gmail, so_dien_thoai, dia_chi, gioi_tinh, ngay_sinh) VALUES (?, ?, ?, ?, ?, ?)";
                        $stmtInsertKH = $khachHangModel->conn->prepare($sqlInsertKH);
                        $stmtInsertKH->execute([$hoTen, $email, $soDienThoai, $diaChi, $gioiTinh, $ngaySinh]);
                        $khachHangId = $khachHangModel->conn->lastInsertId();
                        // Thêm khách vào bảng booking_khach_hang
                        $sqlInsertBK = "INSERT INTO booking_khach_hang (booking_id, khach_hang_id, diem_danh) VALUES (?, ?, ?)";
                        $stmtInsertBK = $khachHangModel->conn->prepare($sqlInsertBK);
                        $stmtInsertBK->execute([$bookingId, $khachHangId, $diemDanh]);
                    }
                    // Quay lại trang danh sách khách booking
                    header('Location: index.php?act=admin/danhSachKhachBooking&booking_id=' . $bookingId);
                    exit;
                }
            // Hiển thị danh sách booking của tour
    // Hiển thị danh sách khách hàng của booking
    public function danhSachKhachBooking() {
        require_once 'models/Booking.php';
        require_once 'models/KhachHang.php';
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $bookingModel = new Booking();
        $khachHangModel = new KhachHang();
        $booking = null;
        $khachList = [];
        if ($bookingId > 0) {
            $booking = $bookingModel->findById($bookingId);
            // Lấy danh sách khách của booking từ bảng booking_khach_hang và khach_hang
            $sql = "SELECT kh.*, bk.diem_danh, bk.id as booking_khach_id FROM booking_khach_hang bk JOIN khach_hang kh ON bk.khach_hang_id = kh.khach_hang_id WHERE bk.booking_id = ?";
            $stmt = $bookingModel->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            $khachList = $stmt->fetchAll();
        }
        require 'views/admin/danh_sach_khach_booking.php';
    }
        // Route: admin/danhSachKhachTour
        public function danhSachKhachTour() {
            $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
            $tourModel = new Tour();
            $lichKhoiHanhModel = new LichKhoiHanh();
            $bookingModel = new Booking();
            $checkinModel = new TourCheckin();
            $roomModel = new HotelRoomAssignment();
            $tour = null;
            $bookingList = [];
            if ($tourId > 0) {
                $tour = $tourModel->findById($tourId);
                // Lấy tất cả booking của tour này
                $sql = "SELECT b.*, nd.ho_ten as khach_ho_ten, nd.email, nd.so_dien_thoai, tc.id as checkin_id, tc.trang_thai as checkin_status FROM booking b LEFT JOIN khach_hang k ON b.khach_hang_id = k.khach_hang_id LEFT JOIN nguoi_dung nd ON k.nguoi_dung_id = nd.id LEFT JOIN tour_checkin tc ON b.booking_id = tc.booking_id WHERE b.tour_id = ? ORDER BY b.ngay_dat DESC";
                $stmt = $bookingModel->conn->prepare($sql);
                $stmt->execute([$tourId]);
                $bookingList = $stmt->fetchAll();
            }
            require 'views/admin/danh_sach_khach.php';
        }
    
    public function __construct() {
        requireRole('Admin');
        // khi vào gốc dự án sẽ gọi new AdminController(). Trong AdminController::__construct() có requireRole('Admin') → requireLogin() → nếu chưa đăng nhập thì chuyển hướng sang auth/login. Nên luôn thấy trang đăng nhập trước khi có session.
    }
    
    public function dashboard() {
        require 'views/admin/dashboard.php';
    }
    
    public function quanLyTour() {
        $tourModel = new Tour();
        
        // Lọc theo loại tour
        $loaiTour = $_GET['loai_tour'] ?? '';
        $trangThai = $_GET['trang_thai'] ?? '';
        $search = trim($_GET['search'] ?? '');
        
        if (!empty($loaiTour) || !empty($trangThai) || !empty($search)) {
            $conditions = [];
            if (!empty($loaiTour)) {
                $conditions['loai_tour'] = $loaiTour;
            }
            if (!empty($trangThai)) {
                $conditions['trang_thai'] = $trangThai;
            }
            $tours = $tourModel->find($conditions);
            
            // Lọc theo tìm kiếm nếu có
            if (!empty($search)) {
                $tours = array_filter($tours, function($tour) use ($search) {
                    return stripos($tour['ten_tour'] ?? '', $search) !== false;
                });
            }
        } else {
        $tours = $tourModel->getAll();
        }
        
        require 'views/admin/quan_ly_tour.php';
    }
    
    public function chiTietTour() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $tour = null;
        $lichTrinhList = [];
        $lichKhoiHanhList = [];
        $hinhAnhList = [];
        $error = null;

        if ($id <= 0) {
            $error = 'Thiếu mã tour cần xem chi tiết.';
        } else {
            $tourModel = new Tour();
            $lichKhoiHanhModel = new LichKhoiHanh();
            $tour = $tourModel->findById($id);
            if (!$tour) {
                $error = 'Tour không tồn tại hoặc đã bị xóa.';
            } else {
                $lichTrinhList = $tourModel->getLichTrinhByTourId($id);
                $lichKhoiHanhList = $lichKhoiHanhModel->getByTourId($id);
                $hinhAnhList = $tourModel->getHinhAnhByTourId($id);
            }
        }

        require 'views/admin/chi_tiet_tour_admin.php';
    }
    public function quanLyNguoiDung() {
        require_once 'models/NguoiDung.php';
        $nguoiDungModel = new NguoiDung();
        $search = $_GET['search'] ?? '';
        $searchMonth = $_GET['search_month'] ?? '';
        $searchDate = $_GET['search_date'] ?? '';
        $searchRole = $_GET['search_role'] ?? '';
        $nguoiDungList = $nguoiDungModel->getAll();
        $highlightedUser = null;
        // Tìm kiếm theo tên/email/sđt
        if ($search !== '') {
            foreach ($nguoiDungList as $key => $nd) {
                if (
                    stripos($nd['ho_ten'], $search) !== false ||
                    stripos($nd['email'], $search) !== false ||
                    stripos($nd['so_dien_thoai'], $search) !== false
                ) {
                    $highlightedUser = $nd;
                    unset($nguoiDungList[$key]);
                    $nguoiDungList = array_merge([$highlightedUser], $nguoiDungList);
                    break;
                }
            }
        }
        // Lọc chính xác theo tháng tạo: chỉ hiển thị đúng tháng được chọn
        if ($searchMonth !== '') {
            $filteredList = [];
            foreach ($nguoiDungList as $nd) {
                if (strpos($nd['ngay_tao'], $searchMonth) === 0) {
                    $filteredList[] = $nd;
                }
            }
            $nguoiDungList = $filteredList;
        }
        // Lọc chính xác theo ngày tạo
        if ($searchDate !== '') {
            $filteredList = [];
            foreach ($nguoiDungList as $nd) {
                if (strpos($nd['ngay_tao'], $searchDate) === 0) {
                    $filteredList[] = $nd;
                }
            }
            $nguoiDungList = $filteredList;
        }
        // Lọc theo vai trò
        if ($searchRole !== '') {
            $filteredList = [];
            foreach ($nguoiDungList as $nd) {
                if ($nd['vai_tro'] === $searchRole) {
                    $filteredList[] = $nd;
                }
            }
            $nguoiDungList = $filteredList;
        }
        require 'views/admin/quan_ly_nguoi_dung.php';
    }
    
    public function quanLyBooking() {
        $bookingModel = new Booking();
        $conditions = [];
        
        // Lọc theo trạng thái nếu có
        if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])) {
            $conditions['trang_thai'] = $_GET['trang_thai'];
        }
        
        if (!empty($conditions)) {
            $bookings = $bookingModel->find($conditions);
        } else {
            $bookings = $bookingModel->getAllWithDetails();
        }
        
        require 'views/admin/quan_ly_booking.php';
    }

    public function yeuCauDacBiet() {
        require_once 'models/YeuCauDacBiet.php';
        require_once 'models/Tour.php';

        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'tour_id' => isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0,
            'muc_do_uu_tien' => $_GET['muc_do_uu_tien'] ?? '',
            'trang_thai' => $_GET['trang_thai'] ?? '',
            'loai_yeu_cau' => $_GET['loai_yeu_cau'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? '',
        ];

        $yeuCauModel = new YeuCauDacBiet();
        $requests = $yeuCauModel->getAllForAdmin($filters);
        $stats = $yeuCauModel->getSummaryStats();
        $histories = $yeuCauModel->getHistoriesByRequestIds(array_column($requests, 'id'));

        $tourModel = new Tour();
        $tourList = $tourModel->getAll();

        require 'views/admin/quan_ly_yeu_cau_dac_biet.php';
    }

    public function capNhatYeuCauDacBiet() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=admin/yeuCauDacBiet');
            exit();
        }

        $yeuCauId = isset($_POST['yeu_cau_id']) ? (int)$_POST['yeu_cau_id'] : 0;
        if ($yeuCauId <= 0) {
            $_SESSION['error'] = 'Thiếu mã yêu cầu cần cập nhật.';
            header('Location: index.php?act=admin/yeuCauDacBiet');
            exit();
        }

        require_once 'models/YeuCauDacBiet.php';
        $yeuCauModel = new YeuCauDacBiet();

        $data = [
            'trang_thai' => $_POST['trang_thai'] ?? null,
            'muc_do_uu_tien' => $_POST['muc_do_uu_tien'] ?? null,
            'ghi_chu_hdv' => $_POST['ghi_chu_hdv'] ?? null
        ];

        $nguoiXuLyId = $_SESSION['user_id'] ?? null;
        $result = $yeuCauModel->updateByAdmin($yeuCauId, $data, $nguoiXuLyId);

        $_SESSION[$result ? 'success' : 'error'] = $result ? 'Cập nhật yêu cầu thành công.' : 'Không thể cập nhật yêu cầu.';

        header('Location: index.php?act=admin/yeuCauDacBiet');
        exit();
    }
    
    public function baoCaoTaiChinh() {
        $giaoDichModel = new GiaoDich();
        $tourModel = new Tour();
        
        // Lọc theo thời gian
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
        
        // Thống kê tổng hợp
        $thongKeTongHop = $giaoDichModel->getThongKeTongHop($startDate, $endDate);
        
        // Thống kê theo từng tour
        $thongKeTheoTour = $giaoDichModel->getThongKeTheoTour($startDate, $endDate);
        
        // Chi tiết giao dịch
        $giaoDichList = [];
        if ($tourId > 0) {
            $giaoDichList = $giaoDichModel->getByTourId($tourId);
            $thongKeTour = $giaoDichModel->getThongKeByTour($tourId);
        } else {
            $giaoDichList = $giaoDichModel->getAll();
        }
        
        // Danh sách tour để filter
        $tours = $tourModel->getAll();
        
        require 'views/admin/bao_cao_tai_chinh.php';
    }
    public function addNhacungcap() {
        $nhaCungCapModel = new NhaCungCap();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenDonVi = trim($_POST['ten_don_vi'] ?? '');
            $loaiDichVu = $_POST['loai_dich_vu'] ?? null;
            $diaChi = $_POST['dia_chi'] ?? null;
            $lienHe = $_POST['lien_he'] ?? null;
            $moTa = $_POST['mo_ta'] ?? null;
            
            if ($tenDonVi === '') {
                $_SESSION['error'] = 'Tên đơn vị không được để trống';
            } else {
                try {
                    $data = [
                        'ten_don_vi' => $tenDonVi,
                        'loai_dich_vu' => $loaiDichVu,
                        'dia_chi' => $diaChi,
                        'lien_he' => $lienHe,
                        'mo_ta' => $moTa
                    ];
                    $nhaCungCapModel->create($data);
                    $_SESSION['success'] = 'Thêm nhà cung cấp thành công';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Không thể thêm nhà cung cấp: ' . $e->getMessage();
                }
            }
        }
        
        header('Location: index.php?act=admin/nhaCungCap');
        exit();
    }
    
    public function nhaCungCap() {
        $nhaCungCapModel = new NhaCungCap();
        $nhaCungCapList = $nhaCungCapModel->getAll();
        
        $selectedId = $_GET['id'] ?? $_GET['ncc_id'] ?? ($nhaCungCapList[0]['id_nha_cung_cap'] ?? null);
        $selectedLoai = $_GET['loai'] ?? null;
        $selectedSupplier = null;
        $serviceTypeSummary = [];
        $supplierStats = [];
        $supplierServices = [];
        $serviceTypes = [];
        
        if ($selectedId) {
            $selectedSupplier = $nhaCungCapModel->findById($selectedId);
            if ($selectedSupplier) {
                $serviceTypeSummary = $nhaCungCapModel->getServiceTypeSummary($selectedId);
                $supplierStats = $nhaCungCapModel->getSupplierStats($selectedId);
                $serviceTypes = $nhaCungCapModel->getDistinctServiceTypes($selectedId);
                $supplierServices = $nhaCungCapModel->getSupplierServices($selectedId, $selectedLoai ?: null, 100);
            }
        }
        
        require 'views/admin/nha_cung_cap.php';
    }
    
    public function updateNhaCungCap() {
        $nhaCungCapModel = new NhaCungCap();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_nha_cung_cap'] ?? 0;
            $tenDonVi = $_POST['ten_don_vi'] ?? '';
            $loaiDichVu = $_POST['loai_dich_vu'] ?? null;
            $diaChi = $_POST['dia_chi'] ?? null;
            $lienHe = $_POST['lien_he'] ?? null;
            $moTa = $_POST['mo_ta'] ?? null;
            
            if ($id <= 0) {
                $_SESSION['error'] = 'ID nhà cung cấp không hợp lệ';
            } elseif (empty($tenDonVi)) {
                $_SESSION['error'] = 'Tên đơn vị không được để trống';
            } else {
                try {
                    $data = [
                        'ten_don_vi' => $tenDonVi,
                        'loai_dich_vu' => $loaiDichVu,
                        'dia_chi' => $diaChi,
                        'lien_he' => $lienHe,
                        'mo_ta' => $moTa
                    ];
                    $nhaCungCapModel->update($id, $data);
                    $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công';
                } catch (Exception $e) {
                    $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
                }
            }
        }
        
        header('Location: index.php?act=admin/nhaCungCap');
        exit();
    }
    
    // Xem chi tiết dịch vụ
    public function chiTietDichVu() {
        $nhaCungCapModel = new NhaCungCap();
        $dichVuId = $_GET['id'] ?? 0;
        $nccId = $_GET['ncc_id'] ?? null;
        
        if ($dichVuId <= 0) {
            $_SESSION['error'] = 'Không tìm thấy dịch vụ';
            header('Location: index.php?act=admin/nhaCungCap' . ($nccId ? '&id=' . $nccId : ''));
            exit();
        }
        
        // Admin có thể xem tất cả dịch vụ, không cần kiểm tra nhaCungCapId
        $dichVu = $nhaCungCapModel->getDichVuById($dichVuId);
        
        if (!$dichVu) {
            $_SESSION['error'] = 'Không tìm thấy dịch vụ';
            header('Location: index.php?act=admin/nhaCungCap' . ($nccId ? '&id=' . $nccId : ''));
            exit();
        }
        
        // Lấy thông tin nhà cung cấp nếu chưa có
        if ($dichVu['nha_cung_cap_id'] && !$nccId) {
            $nccId = $dichVu['nha_cung_cap_id'];
        }
        
        require 'views/admin/chi_tiet_dich_vu.php';
    }

    public function supplierServiceAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?act=admin/nhaCungCap');
            exit();
        }

        $serviceId = (int)($_POST['dich_vu_id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $nccId = (int)($_POST['ncc_id'] ?? 0);
        $redirect = 'index.php?act=admin/nhaCungCap';
        if ($nccId) {
            $redirect .= '&id=' . $nccId;
        }

        if ($serviceId <= 0 || $action === '') {
            $_SESSION['error'] = 'Dịch vụ hoặc hành động không hợp lệ';
            header('Location: ' . $redirect);
            exit();
        }

        $nhaCungCapModel = new NhaCungCap();

        try {
            switch ($action) {
                case 'approve':
                    $giaTien = (int)($_POST['gia_tien'] ?? 0);
                    if ($giaTien <= 0) {
                        throw new Exception('Giá tiền phải lớn hơn 0');
                    }
                    $nhaCungCapModel->xacNhanDichVu($serviceId, $giaTien);
                    $_SESSION['success'] = 'Đã xác nhận dịch vụ';
                    break;
                case 'reject':
                    $ghiChu = trim($_POST['ghi_chu'] ?? '');
                    $nhaCungCapModel->tuChoiDichVu($serviceId, $ghiChu ?: null);
                    $_SESSION['success'] = 'Đã từ chối dịch vụ';
                    break;
                case 'update_price':
                    $giaTien = (int)($_POST['gia_tien'] ?? 0);
                    if ($giaTien <= 0) {
                        throw new Exception('Giá tiền phải lớn hơn 0');
                    }
                    $nhaCungCapModel->capNhatGiaDichVu($serviceId, $giaTien);
                    $_SESSION['success'] = 'Đã cập nhật giá dịch vụ';
                    break;
                default:
                    $_SESSION['error'] = 'Hành động không được hỗ trợ';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Không thể xử lý: ' . $e->getMessage();
        }

        header('Location: ' . $redirect);
        exit();
    }
    public function danhGia() {
        require 'views/admin/danh_gia.php';
    }
    public function nhanSu() {
        $nhanSuModel = new NhanSu();
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        // load available roles for tabs
        $roles = $nhanSuModel->getRoles();
        
        // build data grouped by role (for tabs)
        $data_by_role = [];
        if (!empty($roles)) {
            foreach ($roles as $r) {
                $data_by_role[$r] = $nhanSuModel->getByRole($r);
            }
        }
        
        // apply filters: if search query, search across all; if role filter, use that role's data
        if ($q !== '') {
            $nhan_su_list = $nhanSuModel->search($q);
            $active_role = null;
        } elseif ($role !== '' && isset($data_by_role[$role])) {
            $nhan_su_list = $data_by_role[$role];
            $active_role = $role;
        } else {
            $nhan_su_list = $nhanSuModel->getAll();
            $active_role = null;
        }
        
        require 'views/admin/quan_ly_nhan_su.php';
    }

    // Admin: quản lý HDV (danh sách + CRUD cơ bản)
    public function quanLyHDV() {
        $hdvModel = new HDV();
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if ($q !== '') {
            // sử dụng search trên nhan_su (tạm gọi chung)
            $ns = new NhanSu();
            $hdv_list = $ns->search($q);
        } else {
            $hdv_list = $hdvModel->getAll($groupId);
        }
        // load groups
        $groups = [];
        try {
            $stmt = $hdvModel->conn->prepare('SELECT * FROM hdv_groups ORDER BY name ASC');
            $stmt->execute();
            $groups = $stmt->fetchAll();
        } catch (Exception $e) {
            // ignore if table not exists
        }
        require 'views/admin/quan_ly_hdv.php';
    }

    public function quanLyHDVCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new HDV();
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'anh' => $_POST['anh'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'dia_chi' => $_POST['dia_chi'] ?? null,
                'chung_chi' => $_POST['chung_chi'] ?? null,
                'ngon_ngu' => $_POST['ngon_ngu'] ?? null,
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? null,
                'suc_khoe' => $_POST['suc_khoe'] ?? null,
                'group_id' => $_POST['group_id'] ?? null,
                'note' => $_POST['note'] ?? null,
            ];
            $ok = $model->insert($data);
            $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Thêm HDV thành công'] : ['type'=>'danger','message'=>'Thêm HDV thất bại'];
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    public function quanLyHDVUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new HDV();
            $id = isset($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : 0;
            if ($id > 0) {
                $data = [
                    'ho_ten' => $_POST['ho_ten'] ?? '',
                    'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                    'anh' => $_POST['anh'] ?? null,
                    'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                    'email' => $_POST['email'] ?? null,
                    'dia_chi' => $_POST['dia_chi'] ?? null,
                    'chung_chi' => $_POST['chung_chi'] ?? null,
                    'ngon_ngu' => $_POST['ngon_ngu'] ?? null,
                    'kinh_nghiem' => $_POST['kinh_nghiem'] ?? null,
                    'suc_khoe' => $_POST['suc_khoe'] ?? null,
                    'group_id' => $_POST['group_id'] ?? null,
                    'is_available' => isset($_POST['is_available']) ? 1 : 0,
                    'note' => $_POST['note'] ?? null,
                ];
                $ok = $model->update($id, $data);
                $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Cập nhật HDV thành công'] : ['type'=>'danger','message'=>'Cập nhật HDV thất bại'];
            }
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    public function quanLyHDVDelete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            $model = new HDV();
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? ['type'=>'success','message'=>'Xóa HDV thành công'] : ['type'=>'danger','message'=>'Xóa HDV thất bại'];
        }
        header('Location: index.php?act=admin/quanLyHDV'); exit;
    }

    // Hiển thị lịch phân công HDV (calendar)
    public function hdvSchedule() {
        $hdvModel = new HDV();
        // load hdv list
        $hdv_list = $hdvModel->getAll();
        require 'views/admin/hdv_schedule.php';
    }

    // Trang hồ sơ HDV
    public function hdvProfile() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $hdvModel = new HDV();
        $hdv = $hdvModel->findById($id);
        $history = [];
        if ($hdv) {
            $history = $hdvModel->getTourHistory($id, 100);
        }
        require 'views/admin/hdv_profile.php';
    }

    // API: trả về lịch của HDV (JSON)
    public function hdvApiGetSchedule() {
        header('Content-Type: application/json');
        $hdvId = isset($_GET['hdv_id']) ? (int)$_GET['hdv_id'] : 0;
        $hdvModel = new HDV();
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
        $events = [];
        if ($hdvId > 0) {
            $rows = $hdvModel->getSchedule($hdvId, $from, $to);
            foreach ($rows as $r) {
                $events[] = [
                    'id' => $r['id'],
                    'title' => 'Tour ' . ($r['tour_id'] ?? ''),
                    'start' => $r['start_time'],
                    'end' => $r['end_time'],
                    'extendedProps' => ['note' => $r['note'] ?? '']
                ];
            }
        }
        echo json_encode($events);
        exit;
    }

    // API: kiểm tra khả dụng
    public function hdvApiCheck() {
        header('Content-Type: application/json');
        $hdvId = isset($_GET['hdv_id']) ? (int)$_GET['hdv_id'] : 0;
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        $hdvModel = new HDV();
        $ok = false;
        if ($hdvId && $start && $end) {
            $ok = $hdvModel->isAvailable($hdvId, $start, $end);
        }
        echo json_encode(['available' => $ok]);
        exit;
    }

    // API: phân công (thêm schedule) — POST JSON {hdv_id, tour_id, start, end, note}
    public function hdvApiAssign() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['ok'=>false,'msg'=>'Method not allowed']); exit; }
        $payload = $_POST;
        $hdvId = isset($payload['hdv_id']) ? (int)$payload['hdv_id'] : 0;
        $tourId = isset($payload['tour_id']) ? (int)$payload['tour_id'] : null;
        $start = $payload['start'] ?? null;
        $end = $payload['end'] ?? null;
        $note = $payload['note'] ?? null;
        $hdvModel = new HDV();
        if (!$hdvId || !$start || !$end) { echo json_encode(['ok'=>false,'msg'=>'Thiếu dữ liệu']); exit; }
        if (!$hdvModel->isAvailable($hdvId, $start, $end)) {
            echo json_encode(['ok'=>false,'msg'=>'HDV không rảnh trong khung thời gian này']); exit;
        }
        $ok = $hdvModel->addSchedule($hdvId, $tourId, $start, $end, $note);
        echo json_encode(['ok'=>$ok]); exit;
    }

    // API: đề xuất HDV rảnh cho khoảng thời gian (trả danh sách hdv_id)
    public function hdvApiSuggest() {
        header('Content-Type: application/json');
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        $groupId = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;
        $hdvModel = new HDV();
        $candidates = $hdvModel->getAll($groupId, true);
        $available = [];
        foreach ($candidates as $c) {
            if ($hdvModel->isAvailable($c['nhan_su_id'], $start, $end)) {
                $available[] = ['id'=>$c['nhan_su_id'],'ho_ten'=>$c['ho_ten']];
            }
        }
        echo json_encode(['available'=>$available]); exit;
    }

    public function nhanSuCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new NhanSu();
            $data = [
                    'nguoi_dung_id' => $_POST['nguoi_dung_id'] ?? null,
                    'vai_tro' => $_POST['vai_tro'] ?? 'Khac',
                'chung_chi' => $_POST['chung_chi'] ?? '',
                'ngon_ngu' => $_POST['ngon_ngu'] ?? '',
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? '',
                'suc_khoe' => $_POST['suc_khoe'] ?? '',
            ];
            
                // Validate: người dùng phải được chọn
                if (empty($data['nguoi_dung_id'])) {
                    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vui lòng chọn người dùng.'];
                } else {
                    $ok = $model->insert($data);
                    if ($ok) {
                        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Thêm nhân sự thành công. Vai trò người dùng đã được cập nhật.'];
                    } else {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Thêm nhân sự thất bại.'];
                    }
                }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    // API: trả về danh sách người dùng chưa có nhân sự (JSON)
    public function nhanSu_get_users() {
        $model = new NhanSu();
        $users = $model->getAvailableUsers();
        header('Content-Type: application/json');
        echo json_encode(['users' => $users]);
        exit;
    }

    public function nhanSuUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new NhanSu();
            $id = isset($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : 0;
            if ($id <= 0) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID nhân sự không hợp lệ.'];
                header('Location: index.php?act=admin/nhanSu');
                exit;
            }
            $data = [
                'vai_tro' => $_POST['vai_tro'] ?? 'Khac',
                'chung_chi' => $_POST['chung_chi'] ?? '',
                'ngon_ngu' => $_POST['ngon_ngu'] ?? '',
                'kinh_nghiem' => $_POST['kinh_nghiem'] ?? '',
                'suc_khoe' => $_POST['suc_khoe'] ?? '',
            ];
            $ok = $model->update($id, $data);
            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Cập nhật nhân sự thành công.'];
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Cập nhật nhân sự thất bại.'];
            }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    public function nhanSuDelete() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $delete_user = isset($_GET['delete_user']) && $_GET['delete_user'] === '1' ? true : false;
        
        if ($id > 0) {
            $model = new NhanSu();
            if ($delete_user) {
                // kiểm tra blocker quan trọng trước khi xóa (chỉ tour.tao_boi)
                $nhanSu = $model->findById($id);
                if ($nhanSu && !empty($nhanSu['nguoi_dung_id'])) {
                    $blockers = $model->getCriticalDeleteBlockers($nhanSu['nguoi_dung_id']);
                    if (!empty($blockers)) {
                        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không thể xóa tài khoản do: ' . implode(' ', $blockers)];
                        header('Location: index.php?act=admin/nhanSu');
                        exit;
                    }
                }
                $ok = $model->deleteWithUser($id);
                $msg = $ok ? 'Xóa nhân sự, tài khoản và dữ liệu liên quan thành công.' : 'Xóa nhân sú và tài khoản thất bại.';
            } else {
                $ok = $model->delete($id);
                $msg = $ok ? 'Xóa nhân sự thành công. Tài khoản vẫn được giữ.' : 'Xóa nhân sự thất bại.';
            }
            
            if ($ok) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => $msg];
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Xóa thất bại.'];
            }
        }
        header('Location: index.php?act=admin/nhanSu');
        exit;
    }

    // Xem chi tiết sơ yếu lý lịch nhân sự
    public function nhanSuChiTiet() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $nhanSu = null;
        $error = null;

        if ($id <= 0) {
            $error = 'Thiếu mã nhân sự cần xem.';
        } else {
            $model = new NhanSu();
            $nhanSu = $model->findById($id);
            
            if (!$nhanSu) {
                $error = 'Nhân sự không tồn tại hoặc đã bị xóa.';
            } else {
                // Lấy thêm thông tin vai trò người dùng
                if (!empty($nhanSu['nguoi_dung_id'])) {
                    $nguoiDungModel = new NguoiDung();
                    $nguoiDung = $nguoiDungModel->findById($nhanSu['nguoi_dung_id']);
                    if ($nguoiDung) {
                        $nhanSu['vai_tro_nguoi_dung'] = $nguoiDung['vai_tro'];
                        $nhanSu['quyen_cap_cao'] = $nguoiDung['quyen_cap_cao'];
                        $nhanSu['trang_thai'] = $nguoiDung['trang_thai'];
                        $nhanSu['ngay_tao'] = $nguoiDung['ngay_tao'];
                        $nhanSu['avatar'] = $nguoiDung['avatar'];
                    }
                }
            }
        }

        require 'views/admin/nhan_su_chi_tiet.php';
    }

    // ==================== QUẢN LÝ HDV NÂNG CAO (SỬ DỤNG DATABASE HIỆN CÓ) ====================
    
    public function hdvAdvanced() {
        $hdvMgmt = new HDVManagement();
        
        $hdv_list = $hdvMgmt->getAllHDV();
        $stats = $hdvMgmt->getThongKeTongQuan();
        $hieu_suat_list = $hdvMgmt->getBaoCaoHieuSuat();
        $thong_bao_list = $hdvMgmt->getThongBao(null, 20);
        $lich_lam_viec = $hdvMgmt->getAllLichLamViec(); // Lấy tất cả lịch làm việc
        
        require 'views/admin/hdv_quan_ly_nang_cao.php';
    }

    public function hdvAddSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hdvMgmt = new HDVManagement();
            
            $data = [
                'tour_id' => $_POST['tour_id'],
                'hdv_id' => $_POST['hdv_id'],
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'],
                'ngay_ket_thuc' => $_POST['ngay_ket_thuc'],
                'diem_tap_trung' => $_POST['diem_tap_trung'] ?? '',
                'trang_thai' => $_POST['trang_thai'] ?? 'DaXacNhan'
            ];
            
            $result = $hdvMgmt->phanCongHDV($data);
            $_SESSION['flash'] = [
                'type' => $result['success'] ? 'success' : 'danger',
                'message' => $result['message']
            ];
        }
        
        header('Location: index.php?act=admin/hdv_advanced');
        exit;
    }

    public function hdvGetSchedule() {
        $hdvMgmt = new HDVManagement();
        
        $hdv_id = $_GET['hdv_id'] ?? null;
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        
        $events = $hdvMgmt->getLichLamViec($hdv_id, $start, $end);
        
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }

    public function hdvSendNotification() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hdvMgmt = new HDVManagement();
            
            $data = [
                'nhan_su_id' => !empty($_POST['nhan_su_id']) ? (int)$_POST['nhan_su_id'] : null,
                'loai_thong_bao' => $_POST['loai_thong_bao'] ?? 'ThongBao',
                'tieu_de' => $_POST['tieu_de'] ?? '',
                'noi_dung' => $_POST['noi_dung'] ?? '',
                'uu_tien' => $_POST['uu_tien'] ?? 'TrungBinh'
            ];
            
            $result = $hdvMgmt->guiThongBao($data);
            
            $_SESSION['flash'] = [
                'type' => $result ? 'success' : 'danger',
                'message' => $result ? 'Gửi thông báo thành công!' : 'Lỗi khi gửi thông báo!'
            ];
        }
        
        header('Location: index.php?act=admin/hdv_advanced');
        exit;
    }

    public function hdvLichTable() {
        $hdvMgmt = new HDVManagement();
        
        $hdv_list = $hdvMgmt->getAllHDV();
        $lich_lam_viec = $hdvMgmt->getLichLamViec(); // Lấy tất cả lịch
        
        require 'views/admin/hdv_lich_lam_viec_table.php';
    }

    public function hdvDetail() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        $hdvMgmt = new HDVManagement();
        $nhanSuModel = new NhanSu();
        
        $hdv = $nhanSuModel->findById($id);
        $hieu_suat = $hdvMgmt->getHieuSuatTheoThang($id);
        $danh_gia_list = $hdvMgmt->getDanhGiaByHDV($id);
        $lich_lam_viec = $hdvMgmt->getLichLamViec($id);
        $nhat_ky_list = $hdvMgmt->getNhatKyByHDV($id);
        
        require 'views/admin/hdv_chi_tiet.php';
    }
    
    private function chonAnhChinh(array $hinhAnhList) {
        foreach ($hinhAnhList as $anh) {
            if (!empty($anh['url_anh'])) {
                return $anh;
            }
        }
        return null;
    }

    // ========== QUẢN LÝ KHÁCH THEO TOUR ==========
    
    // Danh sách khách theo tour
    public function danhSachKhachTheoTour() {
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        $tourId = isset($_GET['tour_id']) ? (int)$_GET['tour_id'] : 0;
        
        $tourModel = new Tour();
        $lichKhoiHanhModel = new LichKhoiHanh();
        $bookingModel = new Booking();
        $checkinModel = new TourCheckin();
        $roomModel = new HotelRoomAssignment();
        
        $tour = null;
        $lichKhoiHanh = null;
        $bookingList = [];
        $lichKhoiHanhList = [];
        $checkinStats = null;
        $roomStats = null;
        
        if ($lichKhoiHanhId > 0) {
            $lichKhoiHanh = $lichKhoiHanhModel->findById($lichKhoiHanhId);
            if ($lichKhoiHanh) {
                $tourId = $lichKhoiHanh['tour_id'];
                $tour = $tourModel->findById($tourId);
                
                // Lấy danh sách booking theo lịch khởi hành
                $sql = "SELECT b.*, 
                               nd.ho_ten as khach_ho_ten, 
                               nd.email, 
                               nd.so_dien_thoai,
                               tc.id as checkin_id, 
                               tc.trang_thai as checkin_status
                        FROM booking b
                        LEFT JOIN khach_hang k ON b.khach_hang_id = k.khach_hang_id
                        LEFT JOIN nguoi_dung nd ON k.nguoi_dung_id = nd.id
                        LEFT JOIN tour_checkin tc ON b.booking_id = tc.booking_id
                        WHERE b.tour_id = ? 
                        AND b.ngay_khoi_hanh = (SELECT ngay_khoi_hanh FROM lich_khoi_hanh WHERE id = ?)
                        ORDER BY b.ngay_dat DESC";
                $stmt = $bookingModel->conn->prepare($sql);
                $stmt->execute([$tourId, $lichKhoiHanhId]);
                $bookingList = $stmt->fetchAll();
                
                // Lấy thống kê
                $checkinStats = $checkinModel->getStatsByLichKhoiHanh($lichKhoiHanhId);
                $roomStats = $roomModel->getStatsByLichKhoiHanh($lichKhoiHanhId);
            }
        } else if ($tourId > 0) {
            $tour = $tourModel->findById($tourId);
            $lichKhoiHanhList = $lichKhoiHanhModel->getByTourId($tourId);
        }
        
        require 'views/admin/danh_sach_khach.php';
    }
    
    // Check-in khách
    public function checkInKhach() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkinModel = new TourCheckin();
            
            $data = [
                'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? 0,
                'booking_id' => $_POST['booking_id'] ?? 0,
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_cmnd' => $_POST['so_cmnd'] ?? null,
                'so_passport' => $_POST['so_passport'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            if ($checkinModel->insert($data)) {
                $_SESSION['success'] = 'Check-in khách thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi check-in!';
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $data['lich_khoi_hanh_id']);
            exit;
        }
        
        // GET: hiển thị form check-in
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        $bookingModel = new Booking();
        $checkinModel = new TourCheckin();
        
        $booking = $bookingModel->findById($bookingId);
        $checkin = $checkinModel->getByBookingId($bookingId);
        
        require 'views/admin/check_in.php';
    }
    
    // Cập nhật check-in
    public function updateCheckIn() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkinModel = new TourCheckin();
            
            $id = $_POST['id'] ?? 0;
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'so_cmnd' => $_POST['so_cmnd'] ?? null,
                'so_passport' => $_POST['so_passport'] ?? null,
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? null,
                'email' => $_POST['email'] ?? null,
                'trang_thai' => $_POST['trang_thai'] ?? 'DaCheckIn',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            if ($checkinModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật check-in thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
            }
            
            $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? 0;
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit;
        }
    }
    
    // Phân phòng khách sạn
    public function phanPhongKhachSan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomModel = new HotelRoomAssignment();
            
            $action = $_POST['action'] ?? 'add';
            $lichKhoiHanhId = $_POST['lich_khoi_hanh_id'] ?? 0;
            
            if ($action === 'add') {
                $data = [
                    'lich_khoi_hanh_id' => $lichKhoiHanhId,
                    'booking_id' => $_POST['booking_id'] ?? 0,
                    'checkin_id' => $_POST['checkin_id'] ?? null,
                    'ten_khach_san' => $_POST['ten_khach_san'] ?? '',
                    'so_phong' => $_POST['so_phong'] ?? '',
                    'loai_phong' => $_POST['loai_phong'] ?? 'Standard',
                    'so_giuong' => $_POST['so_giuong'] ?? 1,
                    'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? null,
                    'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? null,
                    'gia_phong' => $_POST['gia_phong'] ?? 0,
                    'trang_thai' => $_POST['trang_thai'] ?? 'DaDatPhong',
                    'ghi_chu' => $_POST['ghi_chu'] ?? null
                ];
                
                if ($roomModel->insert($data)) {
                    $_SESSION['success'] = 'Phân phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi phân phòng!';
                }
            } else if ($action === 'update') {
                $id = $_POST['id'] ?? 0;
                $data = [
                    'ten_khach_san' => $_POST['ten_khach_san'] ?? '',
                    'so_phong' => $_POST['so_phong'] ?? '',
                    'loai_phong' => $_POST['loai_phong'] ?? 'Standard',
                    'so_giuong' => $_POST['so_giuong'] ?? 1,
                    'ngay_nhan_phong' => $_POST['ngay_nhan_phong'] ?? null,
                    'ngay_tra_phong' => $_POST['ngay_tra_phong'] ?? null,
                    'gia_phong' => $_POST['gia_phong'] ?? 0,
                    'trang_thai' => $_POST['trang_thai'] ?? 'DaDatPhong',
                    'ghi_chu' => $_POST['ghi_chu'] ?? null
                ];
                
                if ($roomModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật!';
                }
            } else if ($action === 'delete') {
                $id = $_POST['id'] ?? 0;
                if ($roomModel->delete($id)) {
                    $_SESSION['success'] = 'Xóa phân phòng thành công!';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi xóa!';
                }
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit;
        }
        
        // GET: hiển thị form phân phòng
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        
        $bookingModel = new Booking();
        $roomModel = new HotelRoomAssignment();
        $checkinModel = new TourCheckin();
        
        $booking = null;
        $roomList = [];
        $hotelList = [];
        $checkin = null;
        
        if ($bookingId > 0) {
            // Lấy thông tin booking với thông tin khách hàng
            $sql = "SELECT b.*, 
                           nd.ho_ten, 
                           nd.email, 
                           nd.so_dien_thoai
                    FROM booking b
                    LEFT JOIN khach_hang k ON b.khach_hang_id = k.khach_hang_id
                    LEFT JOIN nguoi_dung nd ON k.nguoi_dung_id = nd.id
                    WHERE b.booking_id = ?";
            $stmt = $bookingModel->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            $booking = $stmt->fetch();
            
            $roomList = $roomModel->getByBookingId($bookingId);
            $checkin = $checkinModel->getByBookingId($bookingId);
        }
        
        if ($lichKhoiHanhId > 0) {
            $hotelList = $roomModel->getHotelList();
        }
        
        require 'views/admin/phan_phong.php';
    }
    
    /**
     * Quản lý nhật ký tour - Admin
     */
    public function quanLyNhatKyTour() {
        $conn = connectDB();
        
        // Lấy filter
        $filter_tour = $_GET['tour_id'] ?? '';
        $filter_hdv = $_GET['hdv_id'] ?? '';
        $filter_loai = $_GET['loai_nhat_ky'] ?? '';
        $filter_tu_ngay = $_GET['tu_ngay'] ?? '';
        $filter_den_ngay = $_GET['den_ngay'] ?? '';
        
        // Build query
        $sql = "SELECT nkt.*, t.ten_tour, nd.ho_ten as hdv_ten
                FROM nhat_ky_tour nkt
                LEFT JOIN tour t ON nkt.tour_id = t.tour_id
                LEFT JOIN nhan_su ns ON nkt.nhan_su_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE 1=1";
        $params = [];
        
        if ($filter_tour) {
            $sql .= " AND nkt.tour_id = ?";
            $params[] = $filter_tour;
        }
        if ($filter_hdv) {
            $sql .= " AND nkt.nhan_su_id = ?";
            $params[] = $filter_hdv;
        }
        if ($filter_loai) {
            $sql .= " AND nkt.loai_nhat_ky = ?";
            $params[] = $filter_loai;
        }
        if ($filter_tu_ngay) {
            $sql .= " AND DATE(nkt.ngay_ghi) >= ?";
            $params[] = $filter_tu_ngay;
        }
        if ($filter_den_ngay) {
            $sql .= " AND DATE(nkt.ngay_ghi) <= ?";
            $params[] = $filter_den_ngay;
        }
        
        $sql .= " ORDER BY nkt.ngay_ghi DESC, nkt.id DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $nhatKyList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Thống kê
        $stats = [
            'tong' => 0,
            'hanh_trinh' => 0,
            'su_co' => 0,
            'phan_hoi' => 0,
            'hoat_dong' => 0
        ];
        
        $sqlStats = "SELECT 
                        COUNT(*) as tong,
                        SUM(CASE WHEN loai_nhat_ky = 'hanh_trinh' THEN 1 ELSE 0 END) as hanh_trinh,
                        SUM(CASE WHEN loai_nhat_ky = 'su_co' THEN 1 ELSE 0 END) as su_co,
                        SUM(CASE WHEN loai_nhat_ky = 'phan_hoi' THEN 1 ELSE 0 END) as phan_hoi,
                        SUM(CASE WHEN loai_nhat_ky = 'hoat_dong' THEN 1 ELSE 0 END) as hoat_dong
                     FROM nhat_ky_tour";
        $stmtStats = $conn->prepare($sqlStats);
        $stmtStats->execute();
        $statsResult = $stmtStats->fetch(PDO::FETCH_ASSOC);
        if ($statsResult) {
            $stats = array_merge($stats, $statsResult);
        }
        
        // Lấy danh sách tour cho filter
        $tourModel = new Tour();
        $tours = $tourModel->getAll();
        
        // Lấy danh sách HDV cho filter
        $hdvModel = new HDV();
        $hdvList = $hdvModel->getAll();
        
        require 'views/admin/quan_ly_nhat_ky_tour.php';
    }
    
    /**
     * Form thêm/sửa nhật ký tour - Admin
     */
    public function formNhatKyTour() {
        $conn = connectDB();
        $id = $_GET['id'] ?? 0;
        $entry = null;
        
        if ($id > 0) {
            $sql = "SELECT nkt.*, t.ten_tour, nd.ho_ten as hdv_ten
                    FROM nhat_ky_tour nkt
                    LEFT JOIN tour t ON nkt.tour_id = t.tour_id
                    LEFT JOIN nhan_su ns ON nkt.nhan_su_id = ns.nhan_su_id
                    LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                    WHERE nkt.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        // Lấy danh sách tour
        $tourModel = new Tour();
        $tours = $tourModel->getAll();
        
        // Lấy danh sách HDV
        $hdvModel = new HDV();
        $hdvList = $hdvModel->getAll();
        
        require 'views/admin/form_nhat_ky_tour.php';
    }
    
    /**
     * Lưu nhật ký tour - Admin
     */
    public function saveNhatKyTour() {
        $conn = connectDB();
        $id = $_POST['id'] ?? 0;
        
        // Xử lý upload hình ảnh
        $imageUrls = [];
        if (isset($_FILES['hinh_anh']) && !empty($_FILES['hinh_anh']['name'][0])) {
            $uploadDir = 'uploads/nhat_ky/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $maxFiles = min(count($_FILES['hinh_anh']['name']), 5);
            for ($i = 0; $i < $maxFiles; $i++) {
                if ($_FILES['hinh_anh']['error'][$i] === UPLOAD_ERR_OK) {
                    $fileName = time() . '_' . $i . '_' . basename($_FILES['hinh_anh']['name'][$i]);
                    $targetFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['hinh_anh']['tmp_name'][$i], $targetFile)) {
                        $imageUrls[] = $targetFile;
                    }
                }
            }
        }
        
        $data = [
            'tour_id' => $_POST['tour_id'] ?? 0,
            'nhan_su_id' => $_POST['nhan_su_id'] ?? 0,
            'loai_nhat_ky' => $_POST['loai_nhat_ky'] ?? 'hanh_trinh',
            'tieu_de' => $_POST['tieu_de'] ?? '',
            'noi_dung' => $_POST['noi_dung'] ?? '',
            'ngay_ghi' => $_POST['ngay_ghi'] ?? date('Y-m-d H:i:s'),
            'cach_xu_ly' => $_POST['cach_xu_ly'] ?? null,
            'hinh_anh' => !empty($imageUrls) ? json_encode($imageUrls) : null
        ];
        
        try {
            if ($id > 0) {
                // Update
                if (!empty($imageUrls)) {
                    // Xóa hình ảnh cũ nếu có
                    $sqlOld = "SELECT hinh_anh FROM nhat_ky_tour WHERE id = ?";
                    $stmtOld = $conn->prepare($sqlOld);
                    $stmtOld->execute([$id]);
                    $oldEntry = $stmtOld->fetch(PDO::FETCH_ASSOC);
                    if ($oldEntry && !empty($oldEntry['hinh_anh'])) {
                        $oldImages = json_decode($oldEntry['hinh_anh'], true);
                        if ($oldImages && is_array($oldImages)) {
                            foreach ($oldImages as $img) {
                                if (file_exists($img)) {
                                    unlink($img);
                                }
                            }
                        }
                    }
                    
                    $sql = "UPDATE nhat_ky_tour SET 
                            tour_id = ?, nhan_su_id = ?, loai_nhat_ky = ?, tieu_de = ?, 
                            noi_dung = ?, ngay_ghi = ?, cach_xu_ly = ?, hinh_anh = ?
                            WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $result = $stmt->execute([
                        $data['tour_id'], $data['nhan_su_id'], $data['loai_nhat_ky'], 
                        $data['tieu_de'], $data['noi_dung'], $data['ngay_ghi'], 
                        $data['cach_xu_ly'], $data['hinh_anh'], $id
                    ]);
                } else {
                    $sql = "UPDATE nhat_ky_tour SET 
                            tour_id = ?, nhan_su_id = ?, loai_nhat_ky = ?, tieu_de = ?, 
                            noi_dung = ?, ngay_ghi = ?, cach_xu_ly = ?
                            WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $result = $stmt->execute([
                        $data['tour_id'], $data['nhan_su_id'], $data['loai_nhat_ky'], 
                        $data['tieu_de'], $data['noi_dung'], $data['ngay_ghi'], 
                        $data['cach_xu_ly'], $id
                    ]);
                }
                $_SESSION['success'] = 'Cập nhật nhật ký thành công';
            } else {
                // Insert
                $sql = "INSERT INTO nhat_ky_tour 
                        (tour_id, nhan_su_id, loai_nhat_ky, tieu_de, noi_dung, ngay_ghi, cach_xu_ly, hinh_anh) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([
                    $data['tour_id'], $data['nhan_su_id'], $data['loai_nhat_ky'], 
                    $data['tieu_de'], $data['noi_dung'], $data['ngay_ghi'], 
                    $data['cach_xu_ly'], $data['hinh_anh']
                ]);
                $_SESSION['success'] = 'Thêm nhật ký thành công';
            }
            
            if (!$result) {
                $_SESSION['error'] = 'Lỗi khi lưu nhật ký';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=admin/quanLyNhatKyTour');
        exit;
    }
    
    /**
     * Xóa nhật ký tour - Admin
     */
    public function deleteNhatKyTour() {
        $conn = connectDB();
        $id = $_GET['id'] ?? 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = 'Thiếu ID nhật ký';
            header('Location: index.php?act=admin/quanLyNhatKyTour');
            exit;
        }
        
        try {
            // Lấy thông tin nhật ký để xóa hình ảnh
            $sql = "SELECT hinh_anh FROM nhat_ky_tour WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($entry) {
                // Xóa hình ảnh
                if (!empty($entry['hinh_anh'])) {
                    $images = json_decode($entry['hinh_anh'], true);
                    if ($images && is_array($images)) {
                        foreach ($images as $img) {
                            if (file_exists($img)) {
                                unlink($img);
                            }
                        }
                    }
                }
                
                // Xóa nhật ký
                $sql = "DELETE FROM nhat_ky_tour WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $result = $stmt->execute([$id]);
                
                if ($result) {
                    $_SESSION['success'] = 'Xóa nhật ký thành công';
                } else {
                    $_SESSION['error'] = 'Lỗi khi xóa nhật ký';
                }
            } else {
                $_SESSION['error'] = 'Không tìm thấy nhật ký';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
        }
        
        header('Location: index.php?act=admin/quanLyNhatKyTour');
        exit;
    }
    
    // Thêm khách vào lịch khởi hành
    public function themKhachLichKhoiHanh() {
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lichKhoiHanhModel = new LichKhoiHanh();
            $bookingModel = new Booking();
            $khachHangModel = new KhachHang();
            $nguoiDungModel = new NguoiDung();
            
            $lichKhoiHanh = $lichKhoiHanhModel->findById($lichKhoiHanhId);
            if (!$lichKhoiHanh) {
                $_SESSION['error'] = 'Lịch khởi hành không tồn tại.';
                header('Location: index.php?act=admin/danhSachKhachTheoTour');
                exit();
            }
            
            // Tìm hoặc tạo người dùng
            $email = trim($_POST['email'] ?? '');
            $hoTen = trim($_POST['ho_ten'] ?? '');
            $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
            
            if (empty($email) || empty($hoTen)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin khách hàng.';
                header('Location: index.php?act=admin/themKhachLichKhoiHanh&lich_khoi_hanh_id=' . $lichKhoiHanhId);
                exit();
            }
            
            // Tìm người dùng theo email
            $nguoiDung = $nguoiDungModel->findByEmail($email);
            if (!$nguoiDung) {
                // Tạo người dùng mới
                $nguoiDungId = $nguoiDungModel->insert([
                    'ho_ten' => $hoTen,
                    'email' => $email,
                    'so_dien_thoai' => $soDienThoai,
                    'vai_tro' => 'KhachHang',
                    'mat_khau' => password_hash('123456', PASSWORD_DEFAULT) // Mật khẩu mặc định
                ]);
                $nguoiDung = $nguoiDungModel->findById($nguoiDungId);
            }
            
            // Tìm hoặc tạo khách hàng
            $khachHang = $khachHangModel->findOrCreateByNguoiDungInfo(
                $nguoiDung['id'],
                $_POST['dia_chi'] ?? null,
                $_POST['gioi_tinh'] ?? null,
                $_POST['ngay_sinh'] ?? null
            );
            
            // Tạo booking
            $bookingData = [
                'tour_id' => $lichKhoiHanh['tour_id'],
                'khach_hang_id' => $khachHang['khach_hang_id'],
                'ngay_dat' => date('Y-m-d'),
                'ngay_khoi_hanh' => $lichKhoiHanh['ngay_khoi_hanh'],
                'ngay_ket_thuc' => $lichKhoiHanh['ngay_ket_thuc'],
                'so_nguoi' => (int)($_POST['so_nguoi'] ?? 1),
                'tong_tien' => (float)($_POST['tong_tien'] ?? 0),
                'trang_thai' => $_POST['trang_thai'] ?? 'ChoXacNhan',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $bookingId = $bookingModel->insert($bookingData);
            if ($bookingId) {
                $_SESSION['success'] = 'Thêm khách vào lịch khởi hành thành công.';
            } else {
                $_SESSION['error'] = 'Không thể thêm booking.';
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit();
        }
        
        // GET: hiển thị form
        $lichKhoiHanhModel = new LichKhoiHanh();
        $tourModel = new Tour();
        $nguoiDungModel = new NguoiDung();
        
        $lichKhoiHanh = $lichKhoiHanhModel->findById($lichKhoiHanhId);
        if (!$lichKhoiHanh) {
            $_SESSION['error'] = 'Lịch khởi hành không tồn tại.';
            header('Location: index.php?act=admin/danhSachKhachTheoTour');
            exit();
        }
        
        $tour = $tourModel->findById($lichKhoiHanh['tour_id']);
        $khachHangList = $nguoiDungModel->getAll(); // Lấy danh sách khách hàng để chọn
        
        require 'views/admin/them_khach_lich_khoi_hanh.php';
    }
    
    // Sửa khách trong lịch khởi hành
    public function suaKhachLichKhoiHanh() {
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingModel = new Booking();
            
            $booking = $bookingModel->findById($bookingId);
            if (!$booking) {
                $_SESSION['error'] = 'Booking không tồn tại.';
                header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
                exit();
            }
            
            $data = [
                'so_nguoi' => (int)($_POST['so_nguoi'] ?? 1),
                'tong_tien' => (float)($_POST['tong_tien'] ?? 0),
                'trang_thai' => $_POST['trang_thai'] ?? 'ChoXacNhan',
                'ghi_chu' => $_POST['ghi_chu'] ?? null
            ];
            
            $result = $bookingModel->update($bookingId, $data);
            if ($result) {
                $_SESSION['success'] = 'Cập nhật thông tin booking thành công.';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật booking.';
            }
            
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit();
        }
        
        // GET: hiển thị form
        $bookingModel = new Booking();
        $lichKhoiHanhModel = new LichKhoiHanh();
        $tourModel = new Tour();
        
        $booking = $bookingModel->getBookingWithDetails($bookingId);
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại.';
            header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
            exit();
        }
        
        $lichKhoiHanh = $lichKhoiHanhModel->findById($lichKhoiHanhId);
        $tour = $tourModel->findById($booking['tour_id']);
        
        require 'views/admin/sua_khach_lich_khoi_hanh.php';
    }
    
    // Xóa khách khỏi lịch khởi hành
    public function xoaKhachLichKhoiHanh() {
        $bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
        $lichKhoiHanhId = isset($_GET['lich_khoi_hanh_id']) ? (int)$_GET['lich_khoi_hanh_id'] : 0;
        
        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);
        
        if (!$booking) {
            $_SESSION['error'] = 'Booking không tồn tại.';
        } else {
            // Chỉ xóa nếu chưa check-in
            $checkinModel = new TourCheckin();
            $checkin = $checkinModel->getByBookingId($bookingId);
            
            if ($checkin) {
                $_SESSION['error'] = 'Không thể xóa booking đã check-in. Vui lòng hủy booking thay vì xóa.';
            } else {
                $result = $bookingModel->delete($bookingId);
                if ($result) {
                    $_SESSION['success'] = 'Xóa booking thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể xóa booking.';
                }
            }
        }
        
        header('Location: index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=' . $lichKhoiHanhId);
        exit();
    }

    // Hiển thị lịch sử xóa booking
    public function lichSuXoaBooking() {
        require_once 'models/BookingDeletionHistory.php';
        $deletionHistoryModel = new BookingDeletionHistory();
        
        $lichSuXoa = $deletionHistoryModel->getAll();
        
        require 'views/admin/lich_su_xoa_booking.php';
    }
}
