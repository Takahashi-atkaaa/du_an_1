<?php
require_once 'models/GiaoDich.php';
require_once 'models/Tour.php';
require_once 'models/Booking.php';
require_once 'models/KhachHang.php';
require_once 'models/LichSuKhachHang.php';
require_once 'models/DuToanTour.php';
require_once 'models/ChiPhiThucTe.php';

class BaoCaoTaiChinhController {
    private $giaoDichModel;
    private $tourModel;
    private $bookingModel;
    private $khachHangModel;
    private $lichSuModel;
    private $duToanModel;
    private $chiPhiModel;
    
    public function __construct() {
        $this->giaoDichModel = new GiaoDich();
        $this->tourModel = new Tour();
        $this->bookingModel = new Booking();
        $this->khachHangModel = new KhachHang();
        $this->lichSuModel = new LichSuKhachHang();
        $this->duToanModel = new DuToanTour();
        $this->chiPhiModel = new ChiPhiThucTe();
    }
    
    // Dashboard tổng quan tài chính
    public function dashboard() {
        // Lấy tháng hiện tại
        $thangHienTai = date('Y-m');
        $tuNgay = date('Y-m-01');
        $denNgay = date('Y-m-t');
        
        // Thống kê giao dịch tháng này
        $thongKe = $this->giaoDichModel->thongKeTheoLoai($tuNgay, $denNgay);
        
        // Tính tổng thu, tổng chi, lợi nhuận
        $tongThu = 0;
        $tongChi = 0;
        foreach ($thongKe as $tk) {
            if ($tk['loai'] == 'Thu') {
                $tongThu += $tk['tong_tien'];
            } else {
                $tongChi += $tk['tong_tien'];
            }
        }
        $loiNhuan = $tongThu - $tongChi;
        
        // Lấy top 5 tour có doanh thu cao nhất
        $topTours = $this->getTopToursByRevenue(5);
        
        require 'views/admin/bao_cao_tai_chinh/dashboard.php';
    }
    
    // Lịch sử giao dịch nội bộ
    public function lichSuGiaoDich() {
        // Xử lý bộ lọc
        $filters = [
            'loai' => $_GET['loai'] ?? '',
            'loai_giao_dich' => $_GET['loai_giao_dich'] ?? '',
            'tour_id' => $_GET['tour_id'] ?? '',
            'khach_hang_id' => $_GET['khach_hang_id'] ?? '',
            'tu_ngay' => $_GET['tu_ngay'] ?? '',
            'den_ngay' => $_GET['den_ngay'] ?? '',
            'keyword' => $_GET['keyword'] ?? ''
        ];
        
        // Xóa các filter rỗng
        $filters = array_filter($filters);
        
        // Lấy danh sách giao dịch
        if (empty($filters)) {
            $giaoDichs = $this->giaoDichModel->getAll();
        } else {
            $giaoDichs = $this->giaoDichModel->search($filters);
        }
        
        // Tính tổng thu, tổng chi
        $tongThu = 0;
        $tongChi = 0;
        foreach ($giaoDichs as $gd) {
            if ($gd['loai'] == 'Thu') {
                $tongThu += $gd['so_tien'];
            } else {
                $tongChi += $gd['so_tien'];
            }
        }
        
        // Lấy danh sách tours và khách hàng cho filter
        $tours = $this->tourModel->getAll();
        $khachHangs = $this->khachHangModel->getAll();
        
        require 'views/admin/bao_cao_tai_chinh/lich_su_giao_dich.php';
    }
    
    // Báo cáo thu chi từng tour
    public function thuChiTour() {
        $tourId = $_GET['tour_id'] ?? null;
        if ($tourId) {
            // Lấy thông tin tour
            $tour = $this->tourModel->findById($tourId);
            // Lấy giao dịch của tour
            $giaoDichs = $this->giaoDichModel->getByTour($tourId);
            // Tính tổng thu từ giao dịch
            $tongThu = $this->giaoDichModel->getTongThuByTour($tourId);
            // Tính tổng chi từ giao dịch
            $tongChiGD = $this->giaoDichModel->getTongChiByTour($tourId);
            // Tính tổng chi phí thực tế đã duyệt
            $tongChiThucTe = $this->chiPhiModel->getTongThucTeByDuToan(
                ($this->duToanModel->getByTour($tourId)[0]['du_toan_id'] ?? null)
            );
            // Lấy dự toán tour
            $duToan = $this->duToanModel->getByTour($tourId);
            $tongDuToan = $duToan[0]['tong_du_toan'] ?? 0;
            // Tính lợi nhuận thực tế
            $loiNhuan = $tongThu - $tongChiThucTe;
            // Lấy trạng thái so với dự toán
            $status = 'AnToan';
            if ($tongChiThucTe > $tongDuToan) {
                $status = 'VuotDuToan';
            } elseif ($tongChiThucTe > 0.9 * $tongDuToan) {
                $status = 'GanVuot';
            }
            // Lấy danh sách booking của tour
            $bookings = $this->bookingModel->getByTour($tourId);
            require 'views/admin/bao_cao_tai_chinh/chi_tiet_thu_chi_tour.php';
        } else {
            // Hiển thị danh sách tours với thống kê
            $tours = $this->tourModel->getAll();
            foreach ($tours as &$tour) {
                $tourId = $tour['tour_id'];
                $tour['tong_thu'] = $this->giaoDichModel->getTongThuByTour($tourId);
                $tour['tong_chi_gd'] = $this->giaoDichModel->getTongChiByTour($tourId);
                $duToan = $this->duToanModel->getByTour($tourId);
                $tour['tong_du_toan'] = $duToan[0]['tong_du_toan'] ?? 0;
                $tour['tong_chi_thuc_te'] = $this->chiPhiModel->getTongThucTeByDuToan($duToan[0]['du_toan_id'] ?? null);
                $tour['loi_nhuan'] = $tour['tong_thu'] - $tour['tong_chi_thuc_te'];
                // Trạng thái so với dự toán
                $tour['status'] = 'AnToan';
                if ($tour['tong_chi_thuc_te'] > $tour['tong_du_toan']) {
                    $tour['status'] = 'VuotDuToan';
                } elseif ($tour['tong_chi_thuc_te'] > 0.9 * $tour['tong_du_toan']) {
                    $tour['status'] = 'GanVuot';
                }
            }
            require 'views/admin/bao_cao_tai_chinh/thu_chi_tour.php';
        }
    }
    
    // Báo cáo công nợ
    public function congNo() {
        // Công nợ HDV: tổng chi phí của từng tour gán cho HDV phụ trách
        $congNoHDV = $this->getCongNoHDV();
        require 'views/admin/bao_cao_tai_chinh/cong_no_hdv.php';
    }
    
    // Báo cáo lãi lỗ từng tour
    public function laiLoTour() {
        $tuNgay = $_GET['tu_ngay'] ?? date('Y-m-01');
        $denNgay = $_GET['den_ngay'] ?? date('Y-m-t');
        
        $tours = $this->tourModel->getAll();
        
        $baoCao = [];
        foreach ($tours as $tour) {
            $tongThu = $this->giaoDichModel->getTongThuByTour($tour['tour_id']);
            $tongChi = $this->giaoDichModel->getTongChiByTour($tour['tour_id']);
            $loiNhuan = $tongThu - $tongChi;
            $tyLe = $tongThu > 0 ? ($loiNhuan / $tongThu * 100) : 0;
            
            $baoCao[] = [
                'tour' => $tour,
                'doanh_thu' => $tongThu,
                'chi_phi' => $tongChi,
                'loi_nhuan' => $loiNhuan,
                'ty_suat' => $tyLe
            ];
        }
        
        // Sắp xếp theo lợi nhuận giảm dần
        usort($baoCao, function($a, $b) {
            return $b['loi_nhuan'] - $a['loi_nhuan'];
        });
        
        require 'views/admin/bao_cao_tai_chinh/lai_lo_tour.php';
    }
    
    // Xuất báo cáo Excel/PDF
    public function xuatBaoCao() {
        $loaiBaoCao = $_GET['loai'] ?? 'giao_dich';
        $format = $_GET['format'] ?? 'excel';
        
        // TODO: Implement export functionality
        $_SESSION['info'] = 'Chức năng xuất báo cáo đang được phát triển';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    
    // Helper: Lấy top tours theo doanh thu
    private function getTopToursByRevenue($limit = 5) {
        $tours = $this->tourModel->getAll();
        $result = [];
        
        foreach ($tours as $tour) {
            $doanhThu = $this->giaoDichModel->getTongThuByTour($tour['tour_id']);
            $result[] = [
                'tour' => $tour,
                'doanh_thu' => $doanhThu
            ];
        }
        
        usort($result, function($a, $b) {
            return $b['doanh_thu'] - $a['doanh_thu'];
        });
        
        return array_slice($result, 0, $limit);
    }
    
    // Helper: Tính công nợ HDV
    private function getCongNoHDV() {
        $sql = "SELECT 
                    lkh.hdv_id,
                    nd.ho_ten as ten_hdv,
                    t.tour_id,
                    t.ten_tour,
                    COALESCE(SUM(gd.so_tien), 0) as tong_thu,
                    COALESCE(SUM(cp.so_tien), 0) as tong_chi,
                    (COALESCE(SUM(gd.so_tien), 0) - COALESCE(SUM(cp.so_tien), 0)) as cong_no
                FROM tour t
                JOIN lich_khoi_hanh lkh ON t.tour_id = lkh.tour_id
                JOIN nguoi_dung nd ON lkh.hdv_id = nd.id
                LEFT JOIN giao_dich_tai_chinh gd ON t.tour_id = gd.tour_id AND gd.loai = 'Thu'
                LEFT JOIN chi_phi_thuc_te cp ON t.tour_id = cp.tour_id AND cp.trang_thai = 'DaDuyet'
                GROUP BY lkh.hdv_id, nd.ho_ten, t.tour_id, t.ten_tour
                ORDER BY cong_no DESC";
        $stmt = $this->giaoDichModel->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Helper: Tính công nợ nhà cung cấp
    private function getCongNoNhaCungCap() {
        // TODO: Implement based on your NCC payment tracking
        return [];
    }
    
    // ==================== QUẢN LÝ DỰ TOÁN TOUR ====================
    
    // Danh sách dự toán
    public function duToanTour() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->saveDuToan();
        }
        
        $tourId = $_GET['tour_id'] ?? null;
        
        if ($tourId) {
            // Xem chi tiết dự toán của 1 tour
            $tour = $this->tourModel->findById($tourId);
            $duToans = $this->duToanModel->getByTour($tourId);
            require 'views/admin/bao_cao_tai_chinh/du_toan_chi_tiet.php';
        } else {
            // Danh sách tất cả tour có dự toán
            $duToans = $this->duToanModel->getAll();
            $tours = $this->tourModel->getAll();
            require 'views/admin/bao_cao_tai_chinh/danh_sach_du_toan.php';
        }
    }
    
    // Form tạo/sửa dự toán
    public function formDuToan() {
        $duToanId = $_GET['id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        
        if ($duToanId) {
            $duToan = $this->duToanModel->findById($duToanId);
            $tour = $this->tourModel->findById($duToan['tour_id']);
        } else {
            $duToan = null;
            $tour = $tourId ? $this->tourModel->findById($tourId) : null;
        }
        
        $tours = $this->tourModel->getAll();
        require 'views/admin/bao_cao_tai_chinh/form_du_toan.php';
    }
    
    // Lưu dự toán
    private function saveDuToan() {
        $duToanId = $_POST['du_toan_id'] ?? null;
        
        $data = [
            'tour_id' => $_POST['tour_id'],
            'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? null,
            'cp_phuong_tien' => $_POST['cp_phuong_tien'] ?? 0,
            'mo_ta_phuong_tien' => $_POST['mo_ta_phuong_tien'] ?? '',
            'cp_luu_tru' => $_POST['cp_luu_tru'] ?? 0,
            'mo_ta_luu_tru' => $_POST['mo_ta_luu_tru'] ?? '',
            'cp_ve_tham_quan' => $_POST['cp_ve_tham_quan'] ?? 0,
            'mo_ta_ve_tham_quan' => $_POST['mo_ta_ve_tham_quan'] ?? '',
            'cp_an_uong' => $_POST['cp_an_uong'] ?? 0,
            'mo_ta_an_uong' => $_POST['mo_ta_an_uong'] ?? '',
            'cp_huong_dan_vien' => $_POST['cp_huong_dan_vien'] ?? 0,
            'cp_dich_vu_bo_sung' => $_POST['cp_dich_vu_bo_sung'] ?? 0,
            'mo_ta_dich_vu' => $_POST['mo_ta_dich_vu'] ?? '',
            'cp_phat_sinh_du_kien' => $_POST['cp_phat_sinh_du_kien'] ?? 0,
            'mo_ta_phat_sinh' => $_POST['mo_ta_phat_sinh'] ?? '',
            'nguoi_tao_id' => $_SESSION['user_id']
        ];
        
        if ($duToanId) {
            $result = $this->duToanModel->update($duToanId, $data);
            $message = 'Cập nhật dự toán thành công!';
        } else {
            $result = $this->duToanModel->create($data);
            $message = 'Tạo dự toán thành công!';
        }
        
        if ($result) {
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: index.php?act=admin/duToanTour&tour_id=' . $data['tour_id']);
        exit;
    }
    
    // ==================== QUẢN LÝ CHI PHÍ THỰC TẾ ====================
    
    // Danh sách chi phí thực tế
    public function chiPhiThucTe() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->saveChiPhi();
        }
        
        $duToanId = $_GET['du_toan_id'] ?? null;
        
        if ($duToanId) {
            // Xem chi phí của 1 dự toán
            $duToan = $this->duToanModel->findById($duToanId);
            $chiPhis = $this->chiPhiModel->getByDuToan($duToanId);
            require 'views/admin/bao_cao_tai_chinh/chi_phi_chi_tiet.php';
        } else {
            // Danh sách tất cả chi phí
            $chiPhis = $this->chiPhiModel->getAll();
            require 'views/admin/bao_cao_tai_chinh/danh_sach_chi_phi.php';
        }
    }
    
    // Form ghi nhận chi phí thực tế
    public function formChiPhi() {
        $chiPhiId = $_GET['id'] ?? null;
        $duToanId = $_GET['du_toan_id'] ?? null;
        
        if ($chiPhiId) {
            $chiPhi = $this->chiPhiModel->findById($chiPhiId);
            $duToan = $this->duToanModel->findById($chiPhi['du_toan_id']);
        } else {
            $chiPhi = null;
            $duToan = $duToanId ? $this->duToanModel->findById($duToanId) : null;
        }
        
        require 'views/admin/bao_cao_tai_chinh/form_chi_phi.php';
    }
    
    // Lưu chi phí thực tế
    private function saveChiPhi() {
        $chiPhiId = $_POST['chi_phi_id'] ?? null;
        
        $data = [
            'du_toan_id' => $_POST['du_toan_id'],
            'tour_id' => $_POST['tour_id'],
            'lich_khoi_hanh_id' => $_POST['lich_khoi_hanh_id'] ?? null,
            'loai_chi_phi' => $_POST['loai_chi_phi'],
            'ten_khoan_chi' => $_POST['ten_khoan_chi'],
            'so_tien' => $_POST['so_tien'],
            'ngay_phat_sinh' => $_POST['ngay_phat_sinh'],
            'mo_ta' => $_POST['mo_ta'] ?? '',
            'nguoi_ghi_nhan_id' => $_SESSION['user_id']
        ];
        
        // Xử lý upload chứng từ
        if (isset($_FILES['chung_tu']) && $_FILES['chung_tu']['error'] === 0) {
            $uploadDir = 'uploads/chung_tu/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . $_FILES['chung_tu']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['chung_tu']['tmp_name'], $uploadPath)) {
                $data['chung_tu'] = $uploadPath;
            }
        }
        
        if ($chiPhiId) {
            $result = $this->chiPhiModel->update($chiPhiId, $data);
            $message = 'Cập nhật chi phí thành công!';
        } else {
            $result = $this->chiPhiModel->create($data);
            $message = 'Ghi nhận chi phí thành công!';
            
            // Kiểm tra cảnh báo
            $canhBao = $this->chiPhiModel->kiemTraCanhBao($data['du_toan_id']);
            if ($canhBao['canh_bao'] === 'VuotDuToan') {
                $_SESSION['warning'] = 'CẢNH BÁO: Chi phí thực tế đã vượt dự toán!';
            } elseif ($canhBao['canh_bao'] === 'GanVuot') {
                $_SESSION['warning'] = 'Lưu ý: Chi phí thực tế đã đạt 90% dự toán!';
            }
        }
        
        if ($result) {
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: index.php?act=admin/chiPhiThucTe&du_toan_id=' . $data['du_toan_id']);
        exit;
    }
    
    // Duyệt chi phí
    public function duyetChiPhi() {
        $chiPhiId = $_GET['id'];
        $result = $this->chiPhiModel->approve($chiPhiId, $_SESSION['user_id']);
        
        // Kiểm tra cảnh báo sau khi duyệt
        $chiPhi = $this->chiPhiModel->findById($chiPhiId);
        $canhBao = $this->chiPhiModel->kiemTraCanhBao($chiPhi['du_toan_id']);
        
        if ($result) {
            $_SESSION['success'] = 'Đã duyệt chi phí!';
            
            if ($canhBao['canh_bao'] === 'VuotDuToan') {
                $_SESSION['warning'] = 'CẢNH BÁO: Chi phí thực tế đã vượt dự toán!';
            } elseif ($canhBao['canh_bao'] === 'GanVuot') {
                $_SESSION['warning'] = 'Lưu ý: Chi phí thực tế đã đạt 90% dự toán!';
            }
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // Từ chối chi phí
    public function tuChoiChiPhi() {
        $chiPhiId = $_POST['id'];
        $lyDo = $_POST['ly_do'];
        
        $result = $this->chiPhiModel->reject($chiPhiId, $_SESSION['user_id'], $lyDo);
        
        if ($result) {
            $_SESSION['success'] = 'Đã từ chối chi phí!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // ==================== SO SÁNH DỰ TOÁN VS THỰC TẾ ====================
    
    public function soSanhDuToan() {
        $duToanId = $_GET['du_toan_id'] ?? null;
        
        if ($duToanId) {
            // So sánh chi tiết 1 dự toán
            $duToan = $this->duToanModel->findById($duToanId);
            $chiPhis = $this->chiPhiModel->getByDuToan($duToanId);
            
            // Tính toán theo từng loại
            $soSanh = [
                'PhuongTien' => [
                    'du_toan' => $duToan['cp_phuong_tien'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'PhuongTien')
                ],
                'LuuTru' => [
                    'du_toan' => $duToan['cp_luu_tru'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'LuuTru')
                ],
                'VeThamQuan' => [
                    'du_toan' => $duToan['cp_ve_tham_quan'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'VeThamQuan')
                ],
                'AnUong' => [
                    'du_toan' => $duToan['cp_an_uong'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'AnUong')
                ],
                'HuongDanVien' => [
                    'du_toan' => $duToan['cp_huong_dan_vien'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'HuongDanVien')
                ],
                'DichVuBoSung' => [
                    'du_toan' => $duToan['cp_dich_vu_bo_sung'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'DichVuBoSung')
                ],
                'PhatSinh' => [
                    'du_toan' => $duToan['cp_phat_sinh_du_kien'],
                    'thuc_te' => $this->chiPhiModel->getTongTheoLoai($duToanId, 'PhatSinh')
                ]
            ];
            
            require 'views/admin/bao_cao_tai_chinh/so_sanh_chi_tiet.php';
        } else {
            // Tổng quan các dự toán có cảnh báo
            $canhBaos = $this->duToanModel->getDuToanCanhBao();
            require 'views/admin/bao_cao_tai_chinh/tong_quan_canh_bao.php';
        }
    }
}
