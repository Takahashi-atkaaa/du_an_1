<?php
require_once 'models/Booking.php';
require_once 'models/Tour.php';

class BookingController {
    private $bookingModel;
    private $tourModel;
    
    public function __construct() {
        $this->bookingModel = new Booking();
        $this->tourModel = new Tour();
    }
    
    public function create() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'tour_id' => $_POST['tour_id'] ?? 0,
                'khach_hang_id' => $_SESSION['user_id'],
                'so_luong_nguoi' => $_POST['so_luong_nguoi'] ?? 1,
                'ngay_khoi_hanh' => $_POST['ngay_khoi_hanh'] ?? '',
                'tong_tien' => $_POST['tong_tien'] ?? 0,
                'trang_thai' => 'cho_xac_nhan',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $bookingId = $this->bookingModel->insert($data);
            redirect("index.php?act=booking/show&id=$bookingId");
        } else {
            $tourId = $_GET['tour_id'] ?? 0;
            $tour = $this->tourModel->findById($tourId);
            require 'views/khach_hang/dat_tour.php';
        }
    }
    
    public function show() {
        requireLogin();
            $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->findById($id);
        
        if (!$booking || $booking['khach_hang_id'] != $_SESSION['user_id']) {
            redirect('index.php?act=tour/index');
            return;
        }
        
        require 'views/khach_hang/hoa_don.php';
    }
    
    public function index() {
        requireLogin();
        $conditions = [];
        
        if ($_SESSION['role'] === 'khach_hang') {
            $conditions['khach_hang_id'] = $_SESSION['user_id'];
        }
        
        $bookings = $this->bookingModel->find($conditions);
        require 'views/admin/quan_ly_booking.php';
    }
}
