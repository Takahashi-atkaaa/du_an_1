<?php
require_once 'models/Tour.php';

class TourController {
    private $model;
    
    public function __construct() {
        $this->model = new Tour();
    }
    
    public function index() {
        $tours = $this->model->getAll();
        require 'views/auth/login.php';
    }
    
    public function show() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
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
        requireRole('Admin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ten_tour' => $_POST['ten_tour'] ?? '',
                'loai_tour' => $_POST['loai_tour'] ?? 'TrongNuoc',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'gia_co_ban' => isset($_POST['gia_co_ban']) ? (float)$_POST['gia_co_ban'] : 0,
                'chinh_sach' => $_POST['chinh_sach'] ?? null,
                'id_nha_cung_cap' => isset($_POST['id_nha_cung_cap']) && $_POST['id_nha_cung_cap'] !== '' ? (int)$_POST['id_nha_cung_cap'] : null,
                'tao_boi' => $_SESSION['user_id'] ?? null,
                'trang_thai' => $_POST['trang_thai'] ?? 'HoatDong'
            ];
            
            $this->model->insert($data);
            redirect('index.php?act=admin/quanLyTour');
        } else {
            require 'views/admin/quan_ly_tour.php';
        }
    }
    
    public function update() {
        requireRole('Admin');
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $id = $id !== null ? (int)$id : null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $data = [
                'ten_tour' => $_POST['ten_tour'] ?? '',
                'loai_tour' => $_POST['loai_tour'] ?? 'TrongNuoc',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'gia_co_ban' => isset($_POST['gia_co_ban']) ? (float)$_POST['gia_co_ban'] : 0,
                'chinh_sach' => $_POST['chinh_sach'] ?? null,
                'id_nha_cung_cap' => isset($_POST['id_nha_cung_cap']) && $_POST['id_nha_cung_cap'] !== '' ? (int)$_POST['id_nha_cung_cap'] : null,
                'trang_thai' => $_POST['trang_thai'] ?? 'HoatDong'
            ];
            
            $this->model->update($id, $data);
            redirect('index.php?act=admin/quanLyTour');
        }
    }
    
    public function delete() {
        requireRole('Admin');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $this->model->delete($id);
        }
        redirect('index.php?act=admin/quanLyTour');
    }
}
