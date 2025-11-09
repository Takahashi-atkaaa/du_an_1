<?php
require_once 'models/Tour.php';

class TourController {
    private $model;

    public function __construct() {
        $this->model = new Tour();
    }

    public function index() {
        $tours = $this->model->getAll();
        require 'views/khach_hang/danh_sach_tour.php';
    }

    public function show() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect('index.php?act=tour/index');
            return;
        }
        $tour = $this->model->findById($id);
        if (!$tour) {
            redirect('index.php?act=tour/index');
            return;
        }
        require 'views/khach_hang/chi_tiet_tour.php';
    }

    public function create() {
        requireRole('admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_tour' => $_POST['ten_tour'] ?? '',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'gia' => $_POST['gia'] ?? 0,
                'so_ngay' => $_POST['so_ngay'] ?? 1,
                'diem_khoi_hanh' => $_POST['diem_khoi_hanh'] ?? '',
                'diem_den' => $_POST['diem_den'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->model->insert($data);
            redirect('index.php?act=admin/quanLyTour');
        } else {
            require 'views/admin/quan_ly_tour.php';
        }
    }

    public function update() {
        requireRole('admin');
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $data = [
                'ten_tour' => $_POST['ten_tour'] ?? '',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'gia' => $_POST['gia'] ?? 0,
                'so_ngay' => $_POST['so_ngay'] ?? 1,
                'diem_khoi_hanh' => $_POST['diem_khoi_hanh'] ?? '',
                'diem_den' => $_POST['diem_den'] ?? ''
            ];
            
            $this->model->update($id, $data);
            redirect('index.php?act=admin/quanLyTour');
        }
    }

    public function delete() {
        requireRole('admin');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->model->delete($id);
        }
        redirect('index.php?act=admin/quanLyTour');
    }
}
