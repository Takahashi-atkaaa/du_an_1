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
            header('Location: index.php?act=tour/index');
            exit();
        }
        $tour = $this->model->findById($id);
        if (!$tour) {
            header('Location: index.php?act=tour/index');
            exit();
        }
        require 'views/khach_hang/chi_tiet_tour.php';
    }
    
    public function create() {
        // requireRole('Admin');
        
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
            $tourId = $this->model->getLastInsertId();
            
            // Lưu lịch trình
            if (isset($_POST['lich_trinh']) && is_array($_POST['lich_trinh'])) {
                foreach ($_POST['lich_trinh'] as $lichTrinh) {
                    if (!empty($lichTrinh['ngay_thu']) && !empty($lichTrinh['dia_diem'])) {
                        $this->model->insertLichTrinh($tourId, $lichTrinh);
                    }
                }
            }
            
            // Lưu lịch khởi hành
            if (isset($_POST['lich_khoi_hanh']) && is_array($_POST['lich_khoi_hanh'])) {
                foreach ($_POST['lich_khoi_hanh'] as $lichKhoiHanh) {
                    if (!empty($lichKhoiHanh['ngay_khoi_hanh'])) {
                        $this->model->insertLichKhoiHanh($tourId, $lichKhoiHanh);
                    }
                }
            }
            
            // Lưu hình ảnh
            if (isset($_POST['hinh_anh']) && is_array($_POST['hinh_anh'])) {
                foreach ($_POST['hinh_anh'] as $hinhAnh) {
                    if (!empty($hinhAnh['url_anh'])) {
                        $this->model->insertHinhAnh($tourId, $hinhAnh);
                    }
                }
            }
            
            header('Location: index.php?act=admin/quanLyTour');
            exit();
        } else {
            $tour = null;
            $lichTrinhList = [];
            $lichKhoiHanhList = [];
            $hinhAnhList = [];
            require 'views/admin/tour_form.php';
        }
    }
    
    public function update() {
        // requireRole('Admin');
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
            
            // Xóa và thêm lại lịch trình
            $this->model->deleteLichTrinhByTourId($id);
            if (isset($_POST['lich_trinh']) && is_array($_POST['lich_trinh'])) {
                foreach ($_POST['lich_trinh'] as $lichTrinh) {
                    if (!empty($lichTrinh['ngay_thu']) && !empty($lichTrinh['dia_diem'])) {
                        $this->model->insertLichTrinh($id, $lichTrinh);
                    }
                }
            }
            
            // Xóa và thêm lại lịch khởi hành
            $this->model->deleteLichKhoiHanhByTourId($id);
            if (isset($_POST['lich_khoi_hanh']) && is_array($_POST['lich_khoi_hanh'])) {
                foreach ($_POST['lich_khoi_hanh'] as $lichKhoiHanh) {
                    if (!empty($lichKhoiHanh['ngay_khoi_hanh'])) {
                        $this->model->insertLichKhoiHanh($id, $lichKhoiHanh);
                    }
                }
            }
            
            // Xóa và thêm lại hình ảnh
            $this->model->deleteHinhAnhByTourId($id);
            if (isset($_POST['hinh_anh']) && is_array($_POST['hinh_anh'])) {
                foreach ($_POST['hinh_anh'] as $hinhAnh) {
                    if (!empty($hinhAnh['url_anh'])) {
                        $this->model->insertHinhAnh($id, $hinhAnh);
                    }
                }
            }
            
            header('Location: index.php?act=admin/quanLyTour');
            exit();
        } else if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id) {
            $tour = $this->model->findById($id);
            if (!$tour) {
                header('Location: index.php?act=admin/quanLyTour');
                exit();
            }
            $lichTrinhList = $this->model->getLichTrinhByTourId($id);
            $lichKhoiHanhList = $this->model->getLichKhoiHanhByTourId($id);
            $hinhAnhList = $this->model->getHinhAnhByTourId($id);
            require 'views/admin/tour_form.php';
        }
    }
    
    public function delete() {
        // requireRole('Admin');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?act=admin/quanLyTour');
        exit();
    }
}
