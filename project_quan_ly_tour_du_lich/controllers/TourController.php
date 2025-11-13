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
        $lichTrinhList = $this->model->getLichTrinhByTourId($id);
        $lichKhoiHanhList = $this->model->getLichKhoiHanhByTourId($id);
        $hinhAnhList = $this->model->getHinhAnhByTourId($id);
        $anhChinh = $this->chonAnhChinh($hinhAnhList);
        $yeuCauList = $this->model->getYeuCauDacBietByTourId($id);
        $nhatKyList = $this->model->getNhatKyTourByTourId($id);
        $hdvInfo = $this->model->getHDVByTourId($id);
        require 'views/khach_hang/chi_tiet_tour.php';
    }
    
    public function create() {
        // requireRole('Admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hanhDong = $_POST['hanh_dong'] ?? 'create';
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

            $lichTrinhPost = isset($_POST['lich_trinh']) && is_array($_POST['lich_trinh']) ? array_values($_POST['lich_trinh']) : [];
            $lichKhoiHanhPost = isset($_POST['lich_khoi_hanh']) && is_array($_POST['lich_khoi_hanh']) ? $_POST['lich_khoi_hanh'] : [];
            $hinhAnhPost = isset($_POST['hinh_anh']) && is_array($_POST['hinh_anh']) ? array_values($_POST['hinh_anh']) : [];

            $this->xuLyUploadHinhAnh($hinhAnhPost, 'hinh_anh_file');
            $hasImageError = false;
            if (isset($_SESSION['image_upload_error'])) {
                $hasImageError = true;
                unset($_SESSION['image_upload_error']);
            }

            if ($hanhDong === 'preview') {
                $tour = $data;
                $lichTrinhList = $lichTrinhPost;
            $lichKhoiHanhList = !empty($lichKhoiHanhPost) ? [$lichKhoiHanhPost] : [];
            $hinhAnhList = $hinhAnhPost;
            $anhChinh = $this->chonAnhChinh($hinhAnhList);
                require 'views/admin/tao_tour.php';
                return;
            }
            if ($hasImageError) {
                $tour = $data;
                $lichTrinhList = $lichTrinhPost;
                $lichKhoiHanhList = !empty($lichKhoiHanhPost) ? [$lichKhoiHanhPost] : [];
                $hinhAnhList = $hinhAnhPost;
                $anhChinh = $this->chonAnhChinh($hinhAnhList);
                require 'views/admin/tao_tour.php';
                return;
            }

            try {
                if (method_exists($this->model->conn, 'beginTransaction')) {
                    $this->model->conn->beginTransaction();
                }

                $inserted = $this->model->insert($data);
                if (!$inserted) {
                    throw new Exception('Không thể tạo tour mới.');
                }

                $tourId = (int)$this->model->getLastInsertId();

                if (!empty($lichTrinhPost)) {
                    foreach ($lichTrinhPost as $index => $lichTrinh) {
                        $lichTrinh['ngay_thu'] = isset($lichTrinh['ngay_thu']) && $lichTrinh['ngay_thu'] !== '' ? (int)$lichTrinh['ngay_thu'] : ($index + 1);
                        if (!empty($lichTrinh['dia_diem'])) {
                            $this->model->insertLichTrinh($tourId, $lichTrinh);
                        }
                    }
                }

                if (!empty($lichKhoiHanhPost) && is_array($lichKhoiHanhPost)) {
                    if (!empty($lichKhoiHanhPost['ngay_khoi_hanh'])) {
                        $this->model->insertLichKhoiHanh($tourId, $lichKhoiHanhPost);
                    }
                }

                if (!empty($hinhAnhPost)) {
                    foreach ($hinhAnhPost as $hinhAnh) {
                        if (!empty($hinhAnh['url_anh'])) {
                            $this->model->insertHinhAnh($tourId, $hinhAnh);
                        }
                    }
                }

                if (method_exists($this->model->conn, 'commit')) {
                    $this->model->conn->commit();
                }

                header('Location: index.php?act=admin/quanLyTour');
                exit();
            } catch (Exception $e) {
                if (method_exists($this->model->conn, 'rollBack') && $this->model->conn->inTransaction()) {
                    $this->model->conn->rollBack();
                }

                $_SESSION['error'] = $e->getMessage();
                header('Location: index.php?act=tour/create');
                exit();
            }
        } else {
            $tour = null;
            $lichTrinhList = [];
            $lichKhoiHanhList = [];
            $hinhAnhList = [];
            $anhChinh = null;
            require 'views/admin/tao_tour.php';
        }
    }
    
    public function update() {
        // requireRole('Admin');
        $id = $_POST['id'] ?? $_GET['id'] ?? null;
        $id = $id !== null ? (int)$id : null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $hanhDong = $_POST['hanh_dong'] ?? 'update';
            $data = [
                'ten_tour' => $_POST['ten_tour'] ?? '',
                'loai_tour' => $_POST['loai_tour'] ?? 'TrongNuoc',
                'mo_ta' => $_POST['mo_ta'] ?? '',
                'gia_co_ban' => isset($_POST['gia_co_ban']) ? (float)$_POST['gia_co_ban'] : 0,
                'chinh_sach' => $_POST['chinh_sach'] ?? null,
                'id_nha_cung_cap' => isset($_POST['id_nha_cung_cap']) && $_POST['id_nha_cung_cap'] !== '' ? (int)$_POST['id_nha_cung_cap'] : null,
                'trang_thai' => $_POST['trang_thai'] ?? 'HoatDong'
            ];
            
            $lichTrinhPost = isset($_POST['lich_trinh']) && is_array($_POST['lich_trinh']) ? array_values($_POST['lich_trinh']) : [];
            $lichKhoiHanhPost = isset($_POST['lich_khoi_hanh']) && is_array($_POST['lich_khoi_hanh']) ? $_POST['lich_khoi_hanh'] : [];
            $hinhAnhPost = isset($_POST['hinh_anh']) && is_array($_POST['hinh_anh']) ? array_values($_POST['hinh_anh']) : [];

            $this->xuLyUploadHinhAnh($hinhAnhPost, 'hinh_anh_file');
            $hasImageError = false;
            if (isset($_SESSION['image_upload_error'])) {
                $hasImageError = true;
                unset($_SESSION['image_upload_error']);
            }

            if ($hanhDong === 'preview') {
                $tour = array_merge($this->model->findById($id) ?: [], $data);
                $lichTrinhList = $lichTrinhPost;
                $lichKhoiHanhList = !empty($lichKhoiHanhPost) ? [$lichKhoiHanhPost] : $this->model->getLichKhoiHanhByTourId($id);
                $hinhAnhList = $hinhAnhPost;
                $anhChinh = $this->chonAnhChinh($hinhAnhList);
                require 'views/admin/tao_tour.php';
                return;
            }
            if ($hasImageError) {
                $tour = array_merge($this->model->findById($id) ?: [], $data);
                $lichTrinhList = $lichTrinhPost;
                $lichKhoiHanhList = !empty($lichKhoiHanhPost) ? [$lichKhoiHanhPost] : $this->model->getLichKhoiHanhByTourId($id);
                $hinhAnhList = $hinhAnhPost;
                $anhChinh = $this->chonAnhChinh($hinhAnhList);
                require 'views/admin/tao_tour.php';
                return;
            }

            $this->model->update($id, $data);
            
            // Xóa và thêm lại lịch trình
            $this->model->deleteLichTrinhByTourId($id);
            if (!empty($lichTrinhPost)) {
                foreach ($lichTrinhPost as $index => $lichTrinh) {
                    $lichTrinh['ngay_thu'] = isset($lichTrinh['ngay_thu']) && $lichTrinh['ngay_thu'] !== '' ? (int)$lichTrinh['ngay_thu'] : ($index + 1);
                    if (!empty($lichTrinh['dia_diem'])) {
                        $this->model->insertLichTrinh($id, $lichTrinh);
                    }
                }
            }
            
            // Xóa và thêm lại lịch khởi hành
            $this->model->deleteLichKhoiHanhByTourId($id);
            if (!empty($lichKhoiHanhPost) && is_array($lichKhoiHanhPost)) {
                if (!empty($lichKhoiHanhPost['ngay_khoi_hanh'])) {
                    $this->model->insertLichKhoiHanh($id, $lichKhoiHanhPost);
                }
            }
            
            // Xóa và thêm lại hình ảnh
            $this->model->deleteHinhAnhByTourId($id);
            if (!empty($hinhAnhPost)) {
                foreach ($hinhAnhPost as $hinhAnh) {
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
            $anhChinh = $this->chonAnhChinh($hinhAnhList);
            require 'views/admin/tao_tour.php';
        }
    }
    
    public function delete() {
        // requireRole('Admin');
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            try {
                $result = $this->model->delete($id);
                if ($result) {
                    $_SESSION['success'] = 'Xóa tour thành công.';
                } else {
                    $_SESSION['error'] = 'Không thể xóa tour.';
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Lỗi khi xóa tour: ' . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = 'ID tour không hợp lệ.';
        }
        header('Location: index.php?act=admin/quanLyTour');
        exit();
    }

    private function xuLyUploadHinhAnh(array &$hinhAnhPost, string $inputName): void {
        if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName]['name'])) {
            return;
        }

        $tepTin = $_FILES[$inputName];
        $thuMucUpload = dirname(__DIR__) . '/public/uploads/tour_images/';
        if (!is_dir($thuMucUpload)) {
            if (!mkdir($thuMucUpload, 0777, true) && !is_dir($thuMucUpload)) {
                $_SESSION['image_upload_error'] = 'Không thể tạo thư mục lưu ảnh.';
                return;
            }
        }

        $dinhDangChoPhep = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        foreach ($hinhAnhPost as $index => &$anh) {
            if (!isset($tepTin['error'][$index]) || $tepTin['error'][$index] !== UPLOAD_ERR_OK) {
                if (isset($tepTin['error'][$index]) && $tepTin['error'][$index] !== UPLOAD_ERR_NO_FILE) {
                    $_SESSION['image_upload_error'] = 'Tải ảnh thất bại. Vui lòng thử lại.';
                }
                continue;
            }

            $extension = strtolower(pathinfo($tepTin['name'][$index], PATHINFO_EXTENSION));
            if (!in_array($extension, $dinhDangChoPhep, true)) {
                $_SESSION['image_upload_error'] = 'Định dạng ảnh không hợp lệ.';
                continue;
            }

            $tenMoi = uniqid('tour_', true) . '.' . $extension;
            $duongDanDayDu = $thuMucUpload . $tenMoi;

            if (move_uploaded_file($tepTin['tmp_name'][$index], $duongDanDayDu)) {
                $anh['url_anh'] = 'public/uploads/tour_images/' . $tenMoi;
            } else {
                $_SESSION['image_upload_error'] = 'Không thể lưu ảnh lên máy chủ.';
            }
        }
        unset($anh);
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
