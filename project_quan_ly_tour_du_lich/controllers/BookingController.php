<?php
require_once 'models/Booking.php';
require_once 'models/Tour.php';
require_once 'models/KhachHang.php';

class BookingController {
    private $bookingModel;
    private $tourModel;
    private $khachHangModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
        $this->khachHangModel = new KhachHang();
    }
    
    public function create() {
        requireLogin();
        
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
        requireLogin();
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
        requireLogin();
        $conditions = [];
        
        if ($_SESSION['role'] === 'KhachHang') {
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
        
        $bookings = $this->bookingModel->find($conditions);
        require 'views/admin/quan_ly_booking.php';
    }
}
