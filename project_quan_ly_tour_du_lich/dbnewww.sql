-- --------------------------------------------------------
-- Máy chủ:                      127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Phiên bản:           12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for quan_ly_tour_du_lich
CREATE DATABASE IF NOT EXISTS `quan_ly_tour_du_lich` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `quan_ly_tour_du_lich`;

-- Dumping structure for table quan_ly_tour_du_lich.booking
CREATE TABLE IF NOT EXISTS `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `khach_hang_id` int DEFAULT NULL,
  `ngay_dat` date DEFAULT NULL,
  `ngay_khoi_hanh` date DEFAULT NULL,
  `so_nguoi` int DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaCoc','HoanTat','Huy') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`booking_id`),
  KEY `tour_id` (`tour_id`),
  KEY `khach_hang_id` (`khach_hang_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.booking_history
CREATE TABLE IF NOT EXISTS `booking_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `trang_thai_cu` enum('ChoXacNhan','DaCoc','HoanTat','Huy') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trang_thai_moi` enum('ChoXacNhan','DaCoc','HoanTat','Huy') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguoi_thay_doi_id` int DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `thoi_gian` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nguoi_thay_doi_id` (`nguoi_thay_doi_id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_thoi_gian` (`thoi_gian`),
  CONSTRAINT `booking_history_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_history_ibfk_2` FOREIGN KEY (`nguoi_thay_doi_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.checkin_khach
CREATE TABLE IF NOT EXISTS `checkin_khach` (
  `id` int NOT NULL AUTO_INCREMENT,
  `diem_checkin_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `trang_thai` enum('chua_checkin','da_checkin','vang_mat','re_gio') COLLATE utf8mb4_unicode_ci DEFAULT 'chua_checkin',
  `thoi_gian_checkin` datetime DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `nguoi_checkin_id` int DEFAULT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_checkin` (`diem_checkin_id`,`booking_id`),
  KEY `nguoi_checkin_id` (`nguoi_checkin_id`),
  KEY `idx_checkin_khach_diem` (`diem_checkin_id`,`trang_thai`),
  KEY `idx_checkin_khach_booking` (`booking_id`),
  CONSTRAINT `checkin_khach_ibfk_1` FOREIGN KEY (`diem_checkin_id`) REFERENCES `diem_checkin` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checkin_khach_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `checkin_khach_ibfk_3` FOREIGN KEY (`nguoi_checkin_id`) REFERENCES `nhan_su` (`nhan_su_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.chung_chi_hdv
CREATE TABLE IF NOT EXISTS `chung_chi_hdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nhan_su_id` int NOT NULL,
  `ten_chung_chi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chứng chỉ/bằng cấp',
  `loai_chung_chi` enum('HDV','NgoaiNgu','KyNang','AnToan','Khac') COLLATE utf8mb4_unicode_ci NOT NULL,
  `co_quan_cap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nơi cấp',
  `ngay_cap` date DEFAULT NULL,
  `ngay_het_han` date DEFAULT NULL COMMENT 'NULL nếu vô thời hạn',
  `so_chung_chi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_dinh_kem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Link file scan chứng chỉ',
  `trang_thai` enum('ConHan','SapHetHan','HetHan') COLLATE utf8mb4_unicode_ci DEFAULT 'ConHan',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `ngay_tao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nhan_su` (`nhan_su_id`),
  KEY `idx_het_han` (`ngay_het_han`),
  CONSTRAINT `chung_chi_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý chi tiết chứng chỉ HDV';

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.danh_gia_hdv
CREATE TABLE IF NOT EXISTS `danh_gia_hdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `nhan_su_id` int NOT NULL COMMENT 'HDV được đánh giá',
  `khach_hang_id` int DEFAULT NULL COMMENT 'Khách hàng đánh giá',
  `diem_chuyen_mon` tinyint DEFAULT NULL COMMENT 'Điểm chuyên môn 1-5',
  `diem_thai_do` tinyint DEFAULT NULL COMMENT 'Điểm thái độ 1-5',
  `diem_giao_tiep` tinyint DEFAULT NULL COMMENT 'Điểm giao tiếp 1-5',
  `diem_tong` decimal(3,2) DEFAULT NULL COMMENT 'Điểm tổng = TB 3 tiêu chí',
  `noi_dung_danh_gia` text COLLATE utf8mb4_unicode_ci COMMENT 'Nhận xét chi tiết',
  `ngay_danh_gia` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `khach_hang_id` (`khach_hang_id`),
  KEY `idx_nhan_su` (`nhan_su_id`),
  KEY `idx_tour` (`tour_id`),
  CONSTRAINT `danh_gia_hdv_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_hdv_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE,
  CONSTRAINT `danh_gia_hdv_ibfk_3` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Đánh giá HDV từ khách hàng';

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.diem_checkin
CREATE TABLE IF NOT EXISTS `diem_checkin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `ten_diem` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_diem` enum('tap_trung','tham_quan','an_uong','nghi_ngoi','khac') COLLATE utf8mb4_unicode_ci DEFAULT 'tap_trung',
  `thoi_gian_du_kien` datetime DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `thu_tu` int DEFAULT '1',
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_diem_checkin_tour` (`tour_id`,`thu_tu`),
  CONSTRAINT `diem_checkin_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.giao_dich_tai_chinh
CREATE TABLE IF NOT EXISTS `giao_dich_tai_chinh` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `loai` enum('Thu','Chi') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_tien` decimal(15,2) DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `ngay_giao_dich` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `giao_dich_tai_chinh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.hieu_suat_hdv
CREATE TABLE IF NOT EXISTS `hieu_suat_hdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nhan_su_id` int NOT NULL,
  `thang` int NOT NULL COMMENT 'Tháng 1-12',
  `nam` int NOT NULL COMMENT 'Năm',
  `so_tour_thang` int DEFAULT '0' COMMENT 'Số tour trong tháng',
  `so_ngay_lam_viec` int DEFAULT '0' COMMENT 'Số ngày làm việc',
  `doanh_thu_mang_lai` decimal(15,2) DEFAULT '0.00' COMMENT 'Doanh thu tour đã dẫn',
  `diem_danh_gia_tb` decimal(3,2) DEFAULT '0.00' COMMENT 'Điểm TB từ khách hàng',
  `so_khieu_nai` int DEFAULT '0' COMMENT 'Số khiếu nại trong tháng',
  `so_khen_thuong` int DEFAULT '0' COMMENT 'Số lần được khen thưởng',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `ngay_tao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_thang_nam` (`nhan_su_id`,`thang`,`nam`),
  KEY `idx_thang_nam` (`thang`,`nam`),
  CONSTRAINT `hieu_suat_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Báo cáo hiệu suất HDV theo tháng';

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.hinh_anh_tour
CREATE TABLE IF NOT EXISTS `hinh_anh_tour` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `url_anh` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `hinh_anh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.hotel_room_assignment
CREATE TABLE IF NOT EXISTS `hotel_room_assignment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lich_khoi_hanh_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `checkin_id` int DEFAULT NULL,
  `ten_khach_san` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_phong` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loai_phong` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Standard',
  `so_giuong` int DEFAULT '1',
  `ngay_nhan_phong` date NOT NULL,
  `ngay_tra_phong` date NOT NULL,
  `gia_phong` decimal(15,2) DEFAULT '0.00',
  `trang_thai` enum('DaDatPhong','DaNhanPhong','DaTraPhong','Huy') COLLATE utf8mb4_unicode_ci DEFAULT 'DaDatPhong',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `checkin_id` (`checkin_id`),
  KEY `idx_room_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  KEY `idx_room_booking` (`booking_id`),
  KEY `idx_room_status` (`trang_thai`),
  CONSTRAINT `hotel_room_assignment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `hotel_room_assignment_ibfk_2` FOREIGN KEY (`checkin_id`) REFERENCES `tour_checkin` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.khach_hang
CREATE TABLE IF NOT EXISTS `khach_hang` (
  `khach_hang_id` int NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` int DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  PRIMARY KEY (`khach_hang_id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  CONSTRAINT `khach_hang_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.lich_khoi_hanh
CREATE TABLE IF NOT EXISTS `lich_khoi_hanh` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `ngay_khoi_hanh` date DEFAULT NULL,
  `gio_xuat_phat` time DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gio_ket_thuc` time DEFAULT NULL,
  `diem_tap_trung` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_cho` int DEFAULT '50',
  `hdv_id` int DEFAULT NULL,
  `trang_thai` enum('SapKhoiHanh','DangChay','HoanThanh') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `hdv_id` (`hdv_id`),
  CONSTRAINT `lich_khoi_hanh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  CONSTRAINT `lich_khoi_hanh_ibfk_2` FOREIGN KEY (`hdv_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.lich_lam_viec_hdv
CREATE TABLE IF NOT EXISTS `lich_lam_viec_hdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nhan_su_id` int NOT NULL,
  `tour_id` int DEFAULT NULL COMMENT 'NULL nếu là ngày nghỉ/bận',
  `loai_lich` enum('Tour','NghiPhep','Ban','DatTruoc') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại lịch làm việc',
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` enum('DuKien','XacNhan','HoanThanh','Huy') COLLATE utf8mb4_unicode_ci DEFAULT 'DuKien',
  `nguoi_tao_id` int DEFAULT NULL COMMENT 'Người tạo lịch (admin)',
  `ngay_tao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  KEY `idx_nhan_su` (`nhan_su_id`),
  KEY `idx_ngay` (`ngay_bat_dau`,`ngay_ket_thuc`),
  KEY `idx_lich_hdv_trang_thai` (`nhan_su_id`,`trang_thai`,`ngay_bat_dau`),
  CONSTRAINT `lich_lam_viec_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE,
  CONSTRAINT `lich_lam_viec_hdv_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE SET NULL,
  CONSTRAINT `lich_lam_viec_hdv_ibfk_3` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch làm việc HDV: tour, nghỉ phép, bận';

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.lich_su_yeu_cau
CREATE TABLE IF NOT EXISTS `lich_su_yeu_cau` (
  `id` int NOT NULL AUTO_INCREMENT,
  `yeu_cau_id` int NOT NULL,
  `hanh_dong` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noi_dung` text COLLATE utf8mb4_unicode_ci,
  `nguoi_thuc_hien_id` int DEFAULT NULL,
  `ngay_thuc_hien` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lich_su_yeu_cau` (`yeu_cau_id`,`ngay_thuc_hien`),
  KEY `nguoi_thuc_hien_id` (`nguoi_thuc_hien_id`),
  CONSTRAINT `lich_su_yeu_cau_ibfk_1` FOREIGN KEY (`yeu_cau_id`) REFERENCES `yeu_cau_dac_biet` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lich_su_yeu_cau_ibfk_2` FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.lich_trinh_tour
CREATE TABLE IF NOT EXISTS `lich_trinh_tour` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `ngay_thu` int DEFAULT NULL,
  `dia_diem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hoat_dong` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `lich_trinh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.nguoi_dung
CREATE TABLE IF NOT EXISTS `nguoi_dung` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ten_dang_nhap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mat_khau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ho_ten` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vai_tro` enum('Admin','HDV','KhachHang','NhaCungCap') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quyen_cap_cao` tinyint(1) DEFAULT '0',
  `trang_thai` enum('HoatDong','BiKhoa') COLLATE utf8mb4_unicode_ci DEFAULT 'HoatDong',
  `ngay_tao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.nhan_su
CREATE TABLE IF NOT EXISTS `nhan_su` (
  `nhan_su_id` int NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` int DEFAULT NULL,
  `vai_tro` enum('HDV','DieuHanh','TaiXe','Khac') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loai_hdv` enum('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') COLLATE utf8mb4_unicode_ci DEFAULT 'TongHop' COMMENT 'Loại HDV',
  `chuyen_tuyen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Các tuyến chuyên: Miền Bắc, Miền Trung, Miền Nam, Đông Nam Á...',
  `danh_gia_tb` decimal(3,2) DEFAULT '0.00' COMMENT 'Điểm đánh giá trung bình 0-5',
  `so_tour_da_dan` int DEFAULT '0' COMMENT 'Tổng số tour đã dẫn',
  `trang_thai_lam_viec` enum('SanSang','DangBan','NghiPhep','TamNghi') COLLATE utf8mb4_unicode_ci DEFAULT 'SanSang' COMMENT 'Trạng thái làm việc',
  `chung_chi` text COLLATE utf8mb4_unicode_ci,
  `ngon_ngu` text COLLATE utf8mb4_unicode_ci,
  `kinh_nghiem` text COLLATE utf8mb4_unicode_ci,
  `suc_khoe` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`nhan_su_id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  KEY `idx_loai_hdv` (`loai_hdv`,`trang_thai_lam_viec`),
  CONSTRAINT `nhan_su_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.nhat_ky_tour
CREATE TABLE IF NOT EXISTS `nhat_ky_tour` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `nhan_su_id` int DEFAULT NULL,
  `loai_nhat_ky` enum('hanh_trinh','su_co','phan_hoi','hoat_dong') COLLATE utf8mb4_unicode_ci DEFAULT 'hanh_trinh' COMMENT 'Loại nhật ký: hành trình, sự cố, phản hồi khách, hoạt động',
  `tieu_de` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề nhật ký',
  `noi_dung` text COLLATE utf8mb4_unicode_ci,
  `cach_xu_ly` text COLLATE utf8mb4_unicode_ci COMMENT 'Cách xử lý sự cố',
  `hinh_anh` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON array chứa đường dẫn hình ảnh',
  `ngay_ghi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `nhan_su_id` (`nhan_su_id`),
  CONSTRAINT `nhat_ky_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  CONSTRAINT `nhat_ky_tour_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.nha_cung_cap
CREATE TABLE IF NOT EXISTS `nha_cung_cap` (
  `id_nha_cung_cap` int NOT NULL AUTO_INCREMENT,
  `nguoi_dung_id` int DEFAULT NULL,
  `ten_don_vi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loai_dich_vu` enum('KhachSan','NhaHang','Xe','Ve','Visa','BaoHiem','Khac') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dia_chi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lien_he` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `danh_gia_tb` float DEFAULT NULL,
  PRIMARY KEY (`id_nha_cung_cap`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  CONSTRAINT `nha_cung_cap_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.phan_bo_dich_vu
CREATE TABLE IF NOT EXISTS `phan_bo_dich_vu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lich_khoi_hanh_id` int NOT NULL,
  `nha_cung_cap_id` int DEFAULT NULL,
  `loai_dich_vu` enum('Xe','KhachSan','VeMayBay','NhaHang','DiemThamQuan','Visa','BaoHiem','Khac') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ten_dich_vu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_luong` int DEFAULT '1',
  `don_vi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gio_bat_dau` time DEFAULT NULL,
  `gio_ket_thuc` time DEFAULT NULL,
  `dia_diem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gia_tien` decimal(15,2) DEFAULT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` enum('ChoXacNhan','DaXacNhan','TuChoi','Huy','HoanTat') COLLATE utf8mb4_unicode_ci DEFAULT 'ChoXacNhan',
  `thoi_gian_xac_nhan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  KEY `idx_nha_cung_cap` (`nha_cung_cap_id`),
  KEY `idx_loai_dich_vu` (`loai_dich_vu`),
  CONSTRAINT `phan_bo_dich_vu_ibfk_1` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phan_bo_dich_vu_ibfk_2` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.dich_vu_nha_cung_cap
CREATE TABLE IF NOT EXISTS `dich_vu_nha_cung_cap` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nha_cung_cap_id` int NOT NULL,
  `ten_dich_vu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `loai_dich_vu` enum('KhachSan','NhaHang','Xe','Ve','VeMayBay','DiemThamQuan','Visa','BaoHiem','Khac') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Khac',
  `gia_tham_khao` decimal(15,2) DEFAULT NULL,
  `don_vi_tinh` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cong_suat_toi_da` int DEFAULT NULL,
  `thoi_gian_xu_ly` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tai_lieu_dinh_kem` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` enum('HoatDong','TamDung','NgungHopTac') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'HoatDong',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_dich_vu_ncc` (`nha_cung_cap_id`),
  CONSTRAINT `dich_vu_nha_cung_cap_ibfk_1` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.phan_bo_history
CREATE TABLE IF NOT EXISTS `phan_bo_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `phan_bo_id` int NOT NULL,
  `loai_phan_bo` enum('NhanSu','DichVu') COLLATE utf8mb4_unicode_ci NOT NULL,
  `thay_doi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nguoi_thay_doi_id` int DEFAULT NULL,
  `thoi_gian` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `nguoi_thay_doi_id` (`nguoi_thay_doi_id`),
  KEY `idx_phan_bo` (`phan_bo_id`,`loai_phan_bo`),
  KEY `idx_thoi_gian` (`thoi_gian`),
  CONSTRAINT `phan_bo_history_ibfk_1` FOREIGN KEY (`nguoi_thay_doi_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.phan_bo_nhan_su
CREATE TABLE IF NOT EXISTS `phan_bo_nhan_su` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lich_khoi_hanh_id` int NOT NULL,
  `nhan_su_id` int NOT NULL,
  `vai_tro` enum('HDV','TaiXe','HauCan','DieuHanh','Khac') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `trang_thai` enum('ChoXacNhan','DaXacNhan','TuChoi','Huy') COLLATE utf8mb4_unicode_ci DEFAULT 'ChoXacNhan',
  `thoi_gian_xac_nhan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  KEY `idx_nhan_su` (`nhan_su_id`),
  CONSTRAINT `phan_bo_nhan_su_ibfk_1` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phan_bo_nhan_su_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.phan_hoi_danh_gia
CREATE TABLE IF NOT EXISTS `phan_hoi_danh_gia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int DEFAULT NULL,
  `nguoi_dung_id` int DEFAULT NULL,
  `loai` enum('Tour','DichVu','NhaCungCap') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diem` int DEFAULT NULL COMMENT 'Điểm đánh giá từ 1-5',
  `noi_dung` text COLLATE utf8mb4_unicode_ci,
  `ngay_danh_gia` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tour_id` (`tour_id`),
  KEY `nguoi_dung_id` (`nguoi_dung_id`),
  CONSTRAINT `phan_hoi_danh_gia_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  CONSTRAINT `phan_hoi_danh_gia_ibfk_2` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.thong_bao_hdv
CREATE TABLE IF NOT EXISTS `thong_bao_hdv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nhan_su_id` int DEFAULT NULL COMMENT 'NULL = thông báo chung cho tất cả HDV',
  `loai_thong_bao` enum('LichTour','NhacNho','CanhBao','ThongBao') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tieu_de` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `noi_dung` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `uu_tien` enum('Thap','TrungBinh','Cao','KhanCap') COLLATE utf8mb4_unicode_ci DEFAULT 'TrungBinh',
  `da_xem` tinyint(1) DEFAULT '0',
  `ngay_gui` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ngay_xem` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_nhan_su_chua_xem` (`nhan_su_id`,`da_xem`),
  CONSTRAINT `thong_bao_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo và nhắc nhở cho HDV';

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.tour
CREATE TABLE IF NOT EXISTS `tour` (
  `tour_id` int NOT NULL AUTO_INCREMENT,
  `ten_tour` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loai_tour` enum('TrongNuoc','QuocTe','TheoYeuCau') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `gia_co_ban` decimal(15,2) DEFAULT NULL,
  `chinh_sach` text COLLATE utf8mb4_unicode_ci,
  `id_nha_cung_cap` int DEFAULT NULL,
  `tao_boi` int DEFAULT NULL,
  `trang_thai` enum('HoatDong','TamDung','HetHan') COLLATE utf8mb4_unicode_ci DEFAULT 'HoatDong',
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn file QR code',
  PRIMARY KEY (`tour_id`),
  KEY `id_nha_cung_cap` (`id_nha_cung_cap`),
  KEY `tao_boi` (`tao_boi`),
  CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`id_nha_cung_cap`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE SET NULL,
  CONSTRAINT `tour_ibfk_2` FOREIGN KEY (`tao_boi`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table quan_ly_tour_du_lich.tour_checkin
CREATE TABLE IF NOT EXISTS `tour_checkin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `khach_hang_id` int NOT NULL,
  `lich_khoi_hanh_id` int DEFAULT NULL,
  `ho_ten` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `so_cmnd` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `so_passport` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nu','Khac') COLLATE utf8mb4_unicode_ci DEFAULT 'Khac',
  `quoc_tich` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Viß╗çt Nam',
  `dia_chi` text COLLATE utf8mb4_unicode_ci,
  `so_dien_thoai` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkin_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `checkout_time` datetime DEFAULT NULL,
  `trang_thai` enum('DaCheckIn','ChuaCheckIn','DaCheckOut') COLLATE utf8mb4_unicode_ci DEFAULT 'ChuaCheckIn',
  `ghi_chu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_khach_hang_id` (`khach_hang_id`),
  KEY `idx_checkin_status` (`trang_thai`),
  CONSTRAINT `tour_checkin_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `tour_checkin_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for view quan_ly_tour_du_lich.v_hdv_san_sang
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_hdv_san_sang` (
	`nhan_su_id` INT NOT NULL,
	`ho_ten` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`so_dien_thoai` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`loai_hdv` ENUM('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') NULL COMMENT 'Loại HDV' COLLATE 'utf8mb4_unicode_ci',
	`chuyen_tuyen` VARCHAR(1) NULL COMMENT 'Các tuyến chuyên: Miền Bắc, Miền Trung, Miền Nam, Đông Nam Á...' COLLATE 'utf8mb4_unicode_ci',
	`danh_gia_tb` DECIMAL(3,2) NULL COMMENT 'Điểm đánh giá trung bình 0-5',
	`so_tour_da_dan` INT NULL COMMENT 'Tổng số tour đã dẫn',
	`ngon_ngu` TEXT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view quan_ly_tour_du_lich.v_thong_ke_hieu_suat_hdv
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_thong_ke_hieu_suat_hdv` (
	`nhan_su_id` INT NOT NULL,
	`ho_ten` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`loai_hdv` ENUM('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') NULL COMMENT 'Loại HDV' COLLATE 'utf8mb4_unicode_ci',
	`tong_tour` BIGINT NOT NULL,
	`diem_tb` DECIMAL(7,6) NULL,
	`tour_hoan_thanh` DECIMAL(23,0) NULL,
	`tour_gan_nhat` DATE NULL
) ENGINE=MyISAM;

-- Dumping structure for table quan_ly_tour_du_lich.yeu_cau_dac_biet
CREATE TABLE IF NOT EXISTS `yeu_cau_dac_biet` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `loai_yeu_cau` enum('an_uong','suc_khoe','di_chuyen','phong_o','hoat_dong','khac') COLLATE utf8mb4_unicode_ci DEFAULT 'khac',
  `tieu_de` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mo_ta` text COLLATE utf8mb4_unicode_ci,
  `muc_do_uu_tien` enum('thap','trung_binh','cao','khan_cap') COLLATE utf8mb4_unicode_ci DEFAULT 'trung_binh',
  `trang_thai` enum('moi','dang_xu_ly','da_giai_quyet','khong_the_thuc_hien') COLLATE utf8mb4_unicode_ci DEFAULT 'moi',
  `ghi_chu_hdv` text COLLATE utf8mb4_unicode_ci,
  `nguoi_tao_id` int DEFAULT NULL,
  `nguoi_xu_ly_id` int DEFAULT NULL,
  `ngay_tao` datetime DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_yeu_cau_booking` (`booking_id`,`trang_thai`),
  KEY `idx_yeu_cau_loai` (`loai_yeu_cau`,`muc_do_uu_tien`),
  KEY `nguoi_tao_id` (`nguoi_tao_id`),
  KEY `nguoi_xu_ly_id` (`nguoi_xu_ly_id`),
  CONSTRAINT `yeu_cau_dac_biet_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `yeu_cau_dac_biet_ibfk_2` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`),
  CONSTRAINT `yeu_cau_dac_biet_ibfk_3` FOREIGN KEY (`nguoi_xu_ly_id`) REFERENCES `nhan_su` (`nhan_su_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for trigger quan_ly_tour_du_lich.after_insert_chung_chi_hdv
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_chung_chi_hdv` AFTER INSERT ON `chung_chi_hdv` FOR EACH ROW BEGIN
    IF NEW.ngay_het_han IS NOT NULL AND DATEDIFF(NEW.ngay_het_han, CURDATE()) <= 30 THEN
        UPDATE chung_chi_hdv
        SET trang_thai = 'SapHetHan'
        WHERE id = NEW.id;
        
        -- Tạo thông báo nhắc nhở
        INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
        VALUES (
            NEW.nhan_su_id,
            'CanhBao',
            CONCAT('Chứng chỉ ', NEW.ten_chung_chi, ' sắp hết hạn'),
            CONCAT('Chứng chỉ của bạn sẽ hết hạn vào ', DATE_FORMAT(NEW.ngay_het_han, '%d/%m/%Y'), '. Vui lòng gia hạn kịp thời.'),
            'Cao'
        );
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger quan_ly_tour_du_lich.after_insert_danh_gia_hdv
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_danh_gia_hdv` AFTER INSERT ON `danh_gia_hdv` FOR EACH ROW BEGIN
    DECLARE avg_score DECIMAL(3,2);
    DECLARE tour_count INT;
    
    -- Tính điểm TB
    SELECT AVG(diem_tong) INTO avg_score
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Đếm số tour
    SELECT COUNT(DISTINCT tour_id) INTO tour_count
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Cập nhật vào bảng nhan_su
    UPDATE nhan_su
    SET danh_gia_tb = IFNULL(avg_score, 0),
        so_tour_da_dan = tour_count
    WHERE nhan_su_id = NEW.nhan_su_id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger quan_ly_tour_du_lich.before_insert_danh_gia_hdv
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `before_insert_danh_gia_hdv` BEFORE INSERT ON `danh_gia_hdv` FOR EACH ROW BEGIN
    IF NEW.diem_chuyen_mon IS NOT NULL AND NEW.diem_thai_do IS NOT NULL AND NEW.diem_giao_tiep IS NOT NULL THEN
        SET NEW.diem_tong = (NEW.diem_chuyen_mon + NEW.diem_thai_do + NEW.diem_giao_tiep) / 3;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_hdv_san_sang`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_hdv_san_sang` AS select `ns`.`nhan_su_id` AS `nhan_su_id`,`nd`.`ho_ten` AS `ho_ten`,`nd`.`email` AS `email`,`nd`.`so_dien_thoai` AS `so_dien_thoai`,`ns`.`loai_hdv` AS `loai_hdv`,`ns`.`chuyen_tuyen` AS `chuyen_tuyen`,`ns`.`danh_gia_tb` AS `danh_gia_tb`,`ns`.`so_tour_da_dan` AS `so_tour_da_dan`,`ns`.`ngon_ngu` AS `ngon_ngu` from (`nhan_su` `ns` join `nguoi_dung` `nd` on((`ns`.`nguoi_dung_id` = `nd`.`id`))) where ((`ns`.`vai_tro` = 'HDV') and (`ns`.`trang_thai_lam_viec` = 'SanSang') and `ns`.`nhan_su_id` in (select `lich_lam_viec_hdv`.`nhan_su_id` from `lich_lam_viec_hdv` where ((`lich_lam_viec_hdv`.`trang_thai` in ('DuKien','XacNhan')) and (curdate() between `lich_lam_viec_hdv`.`ngay_bat_dau` and `lich_lam_viec_hdv`.`ngay_ket_thuc`))) is false);

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_thong_ke_hieu_suat_hdv`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_thong_ke_hieu_suat_hdv` AS select `ns`.`nhan_su_id` AS `nhan_su_id`,`nd`.`ho_ten` AS `ho_ten`,`ns`.`loai_hdv` AS `loai_hdv`,count(distinct `llv`.`tour_id`) AS `tong_tour`,avg(`dg`.`diem_tong`) AS `diem_tb`,sum((case when (`llv`.`trang_thai` = 'HoanThanh') then 1 else 0 end)) AS `tour_hoan_thanh`,max(`llv`.`ngay_ket_thuc`) AS `tour_gan_nhat` from (((`nhan_su` `ns` join `nguoi_dung` `nd` on((`ns`.`nguoi_dung_id` = `nd`.`id`))) left join `lich_lam_viec_hdv` `llv` on(((`ns`.`nhan_su_id` = `llv`.`nhan_su_id`) and (`llv`.`loai_lich` = 'Tour')))) left join `danh_gia_hdv` `dg` on((`ns`.`nhan_su_id` = `dg`.`nhan_su_id`))) where (`ns`.`vai_tro` = 'HDV') group by `ns`.`nhan_su_id`,`nd`.`ho_ten`,`ns`.`loai_hdv`;

-- ============================================
-- PHẦN DỮ LIỆU MẪU
-- ============================================

-- 1. Dữ liệu mẫu cho bảng nguoi_dung
INSERT INTO `nguoi_dung` (`ten_dang_nhap`, `mat_khau`, `ho_ten`, `email`, `so_dien_thoai`, `vai_tro`, `quyen_cap_cao`, `trang_thai`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Quản trị viên hệ thống', 'admin@tour.com', '0901234567', 'Admin', 1, 'HoatDong'),
('hdv01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nguyễn Văn Hướng', 'hdv01@tour.com', '0912345678', 'HDV', 0, 'HoatDong'),
('hdv02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị Lan', 'hdv02@tour.com', '0923456789', 'HDV', 0, 'HoatDong'),
('khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Văn An', 'khach01@email.com', '0934567890', 'KhachHang', 0, 'HoatDong'),
('khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Phạm Thị Bình', 'khach02@email.com', '0945678901', 'KhachHang', 0, 'HoatDong'),
('ncc01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Công ty ABC Travel', 'ncc01@tour.com', '0956789012', 'NhaCungCap', 0, 'HoatDong'),
('ncc02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Khách sạn XYZ', 'ncc02@tour.com', '0967890123', 'NhaCungCap', 0, 'HoatDong');

-- 2. Dữ liệu mẫu cho bảng khach_hang
INSERT INTO `khach_hang` (`nguoi_dung_id`, `dia_chi`, `gioi_tinh`, `ngay_sinh`) VALUES
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01'), '123 Đường Lê Lợi, Quận 1, TP.HCM', 'Nam', '1990-05-15'),
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach02'), '456 Đường Nguyễn Huệ, Quận 3, TP.HCM', 'Nữ', '1985-08-20');

-- 3. Dữ liệu mẫu cho bảng nhan_su
INSERT INTO `nhan_su` (`nguoi_dung_id`, `vai_tro`, `loai_hdv`, `chuyen_tuyen`, `danh_gia_tb`, `so_tour_da_dan`, `trang_thai_lam_viec`, `chung_chi`, `ngon_ngu`, `kinh_nghiem`, `suc_khoe`) VALUES
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01'), 'HDV', 'NoiDia', 'Miền Bắc, Miền Trung', 4.50, 15, 'SanSang', 'Chứng chỉ nghiệp vụ hướng dẫn viên du lịch', 'Tiếng Việt, Tiếng Anh', '5 năm kinh nghiệm dẫn tour nội địa', 'Tốt'),
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv02'), 'HDV', 'QuocTe', 'Đông Nam Á, Châu Âu', 4.75, 20, 'SanSang', 'Chứng chỉ HDV quốc tế, IELTS 7.0', 'Tiếng Việt, Tiếng Anh, Tiếng Thái', '7 năm kinh nghiệm dẫn tour quốc tế', 'Tốt');

-- 4. Dữ liệu mẫu cho bảng nha_cung_cap
INSERT INTO `nha_cung_cap` (`nguoi_dung_id`, `ten_don_vi`, `loai_dich_vu`, `dia_chi`, `lien_he`, `mo_ta`, `danh_gia_tb`) VALUES
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'ncc01'), 'ABC Travel Services', 'KhachSan', '789 Đường Trần Hưng Đạo, Quận 5, TP.HCM', '0281234567', 'Đối tác cung cấp khách sạn 3-4 sao tại các điểm du lịch', 4.5),
((SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'ncc02'), 'Khách sạn XYZ', 'KhachSan', '321 Đường Lý Tự Trọng, Quận 1, TP.HCM', '0287654321', 'Khách sạn 5 sao tại trung tâm thành phố', 4.8);

-- 5. Dữ liệu mẫu cho bảng tour
INSERT INTO `tour` (`ten_tour`, `loai_tour`, `mo_ta`, `gia_co_ban`, `chinh_sach`, `id_nha_cung_cap`, `tao_boi`, `trang_thai`) VALUES
('Hà Nội - Hạ Long 3N2Đ', 'TrongNuoc', 'Khám phá Vịnh Hạ Long kỳ quan thiên nhiên thế giới, tham quan hang động, nghỉ đêm trên du thuyền', 3500000.00, 'Hủy trước 7 ngày: hoàn 80% tiền cọc. Hủy trước 3 ngày: hoàn 50%', (SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'), 'HoatDong'),
('Sài Gòn - Đà Lạt 4N3Đ', 'TrongNuoc', 'Tham quan thành phố ngàn hoa, vườn hoa, thác nước, đồi chè', 4200000.00, 'Hủy trước 10 ngày: hoàn 90%. Hủy trước 5 ngày: hoàn 70%', (SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'), 'HoatDong'),
('Bangkok - Pattaya 5N4Đ', 'QuocTe', 'Khám phá thủ đô Thái Lan, tham quan cung điện, chùa vàng, vui chơi tại Pattaya', 8500000.00, 'Hủy trước 14 ngày: hoàn 80%. Hủy trước 7 ngày: hoàn 50%', (SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'), 'HoatDong');

-- 6. Dữ liệu mẫu cho bảng lich_trinh_tour
INSERT INTO `lich_trinh_tour` (`tour_id`, `ngay_thu`, `dia_diem`, `hoat_dong`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 1, 'Hà Nội', 'Đón khách tại sân bay - Tham quan phố cổ Hà Nội - Ăn tối tại nhà hàng địa phương'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 2, 'Hạ Long', 'Tham quan Vịnh Hạ Long - Nghỉ đêm trên du thuyền - Tham quan hang Sửng Sốt'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 3, 'Hạ Long - Hà Nội', 'Tham quan hang Luồn - Trở về Hà Nội - Tiễn khách'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 1, 'Sài Gòn - Đà Lạt', 'Khởi hành từ Sài Gòn - Đến Đà Lạt - Tham quan vườn hoa thành phố'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 2, 'Đà Lạt', 'Tham quan thác Datanla - Vườn hoa - Chợ đêm Đà Lạt'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 3, 'Đà Lạt', 'Tham quan đồi chè Cầu Đất - Làng hoa Vạn Thành - Nghỉ ngơi'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 4, 'Đà Lạt - Sài Gòn', 'Tham quan chợ Đà Lạt - Trở về Sài Gòn - Kết thúc tour');

-- 7. Dữ liệu mẫu cho bảng hinh_anh_tour
INSERT INTO `hinh_anh_tour` (`tour_id`, `url_anh`, `mo_ta`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'images/halong1.jpg', 'Toàn cảnh Vịnh Hạ Long'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'images/halong2.jpg', 'Du thuyền trên Vịnh'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 'images/dalat1.jpg', 'Vườn hoa Đà Lạt'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), 'images/dalat2.jpg', 'Thác Datanla');

-- 8. Dữ liệu mẫu cho bảng lich_khoi_hanh
INSERT INTO `lich_khoi_hanh` (`tour_id`, `ngay_khoi_hanh`, `gio_xuat_phat`, `ngay_ket_thuc`, `gio_ket_thuc`, `diem_tap_trung`, `so_cho`, `hdv_id`, `trang_thai`, `ghi_chu`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), DATE_ADD(CURDATE(), INTERVAL 10 DAY), '06:00:00', DATE_ADD(CURDATE(), INTERVAL 12 DAY), '18:00:00', 'Sân bay Nội Bài - Cổng A', 50, (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'SapKhoiHanh', 'Lịch khởi hành mẫu cho tour Hạ Long'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), DATE_ADD(CURDATE(), INTERVAL 15 DAY), '07:00:00', DATE_ADD(CURDATE(), INTERVAL 18 DAY), '19:00:00', 'Bến xe Miền Đông', 45, (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'SapKhoiHanh', 'Lịch khởi hành tour Đà Lạt');

-- 9. Dữ liệu mẫu cho bảng booking
INSERT INTO `booking` (`tour_id`, `khach_hang_id`, `ngay_dat`, `ngay_khoi_hanh`, `so_nguoi`, `tong_tien`, `trang_thai`, `ghi_chu`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), (SELECT `khach_hang_id` FROM `khach_hang` LIMIT 1), CURDATE(), DATE_ADD(CURDATE(), INTERVAL 10 DAY), 2, 7000000.00, 'DaCoc', 'Yêu cầu phòng đôi, ăn chay'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Sài Gòn - Đà Lạt 4N3Đ'), (SELECT `khach_hang_id` FROM `khach_hang` LIMIT 1 OFFSET 1), CURDATE(), DATE_ADD(CURDATE(), INTERVAL 15 DAY), 1, 4200000.00, 'ChoXacNhan', 'Khách đơn, cần hỗ trợ đặc biệt');

-- 10. Dữ liệu mẫu cho bảng booking_history
INSERT INTO `booking_history` (`booking_id`, `trang_thai_cu`, `trang_thai_moi`, `nguoi_thay_doi_id`, `ghi_chu`) VALUES
((SELECT `booking_id` FROM `booking` LIMIT 1), NULL, 'ChoXacNhan', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01'), 'Khách đặt tour'),
((SELECT `booking_id` FROM `booking` LIMIT 1), 'ChoXacNhan', 'DaCoc', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'), 'Khách đã đặt cọc 50%');

-- 11. Dữ liệu mẫu cho bảng phan_bo_nhan_su
INSERT INTO `phan_bo_nhan_su` (`lich_khoi_hanh_id`, `nhan_su_id`, `vai_tro`, `ghi_chu`, `trang_thai`, `thoi_gian_xac_nhan`) VALUES
((SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'HDV', 'HDV chính cho tour', 'DaXacNhan', NOW()),
((SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv02')), 'HauCan', 'Hậu cần hỗ trợ', 'ChoXacNhan', NULL);

-- 12. Dữ liệu mẫu cho bảng phan_bo_dich_vu
INSERT INTO `phan_bo_dich_vu` (`lich_khoi_hanh_id`, `nha_cung_cap_id`, `loai_dich_vu`, `ten_dich_vu`, `so_luong`, `don_vi`, `ngay_bat_dau`, `ngay_ket_thuc`, `gia_tien`, `trang_thai`) VALUES
((SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), (SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), 'KhachSan', 'Khách sạn Hạ Long 3 sao', 10, 'phòng', DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 2000000.00, 'DaXacNhan'),
((SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), (SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), 'Xe', 'Xe 45 chỗ', 1, 'xe', DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 5000000.00, 'DaXacNhan');

-- 12b. Dữ liệu mẫu cho bảng dich_vu_nha_cung_cap
INSERT INTO `dich_vu_nha_cung_cap` (`nha_cung_cap_id`, `ten_dich_vu`, `mo_ta`, `loai_dich_vu`, `gia_tham_khao`, `don_vi_tinh`, `cong_suat_toi_da`, `thoi_gian_xu_ly`, `trang_thai`) VALUES
((SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), 'Phòng Deluxe hướng biển', 'Gói phòng khách sạn 4 sao bao gồm buffet sáng', 'KhachSan', 2200000.00, 'phòng/đêm', 30, 'Xác nhận trong 2h', 'HoatDong'),
((SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1), 'Xe đưa đón sân bay', 'Xe 16 chỗ đón khách tại sân bay Nội Bài', 'Xe', 1500000.00, 'chuyến', 5, 'Đặt trước 1 ngày', 'HoatDong'),
((SELECT `id_nha_cung_cap` FROM `nha_cung_cap` LIMIT 1 OFFSET 1), 'Buffet tối Đặc sản Đà Nẵng', 'Set buffet 40 món hải sản và đặc sản miền Trung', 'NhaHang', 350000.00, 'suất', 80, 'Chuẩn bị trong 3h', 'HoatDong');

-- 13. Dữ liệu mẫu cho bảng diem_checkin
INSERT INTO `diem_checkin` (`tour_id`, `ten_diem`, `loai_diem`, `thoi_gian_du_kien`, `ghi_chu`, `thu_tu`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Điểm tập trung sân bay', 'tap_trung', CONCAT(DATE_ADD(CURDATE(), INTERVAL 10 DAY), ' 06:00:00'), 'Điểm tập trung ban đầu', 1),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Bến tàu Hạ Long', 'tap_trung', CONCAT(DATE_ADD(CURDATE(), INTERVAL 11 DAY), ' 08:00:00'), 'Lên tàu tham quan vịnh', 2),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Hang Sửng Sốt', 'tham_quan', CONCAT(DATE_ADD(CURDATE(), INTERVAL 11 DAY), ' 10:00:00'), 'Tham quan hang động', 3);

-- 14. Dữ liệu mẫu cho bảng checkin_khach
INSERT INTO `checkin_khach` (`diem_checkin_id`, `booking_id`, `trang_thai`, `thoi_gian_checkin`, `ghi_chu`, `nguoi_checkin_id`) VALUES
((SELECT `id` FROM `diem_checkin` LIMIT 1), (SELECT `booking_id` FROM `booking` LIMIT 1), 'da_checkin', NOW(), 'Đã có mặt đúng giờ', (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')));

-- 15. Dữ liệu mẫu cho bảng tour_checkin
INSERT INTO `tour_checkin` (`booking_id`, `khach_hang_id`, `lich_khoi_hanh_id`, `ho_ten`, `so_cmnd`, `ngay_sinh`, `gioi_tinh`, `quoc_tich`, `dia_chi`, `so_dien_thoai`, `email`, `trang_thai`, `ghi_chu`) VALUES
((SELECT `booking_id` FROM `booking` LIMIT 1), (SELECT `khach_hang_id` FROM `khach_hang` LIMIT 1), (SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), 'Lê Văn An', '079123456789', '1990-05-15', 'Nam', 'Việt Nam', '123 Đường Lê Lợi, Quận 1, TP.HCM', '0934567890', 'khach01@email.com', 'ChuaCheckIn', 'Chờ check-in');

-- 16. Dữ liệu mẫu cho bảng hotel_room_assignment
INSERT INTO `hotel_room_assignment` (`lich_khoi_hanh_id`, `booking_id`, `ten_khach_san`, `so_phong`, `loai_phong`, `so_giuong`, `ngay_nhan_phong`, `ngay_tra_phong`, `gia_phong`, `trang_thai`, `ghi_chu`) VALUES
((SELECT `id` FROM `lich_khoi_hanh` LIMIT 1), (SELECT `booking_id` FROM `booking` LIMIT 1), 'Khách sạn Hạ Long 3 sao', '301', 'Deluxe', 2, DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 2000000.00, 'DaDatPhong', 'Phòng đôi theo yêu cầu');

-- 17. Dữ liệu mẫu cho bảng yeu_cau_dac_biet
INSERT INTO `yeu_cau_dac_biet` (`booking_id`, `loai_yeu_cau`, `tieu_de`, `mo_ta`, `muc_do_uu_tien`, `trang_thai`, `ghi_chu_hdv`, `nguoi_tao_id`) VALUES
((SELECT `booking_id` FROM `booking` LIMIT 1), 'an_uong', 'Yêu cầu ăn chay', 'Khách yêu cầu chuẩn bị đồ ăn chay cho cả tour', 'cao', 'dang_xu_ly', 'Đã liên hệ nhà hàng chuẩn bị', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01')),
((SELECT `booking_id` FROM `booking` LIMIT 1), 'suc_khoe', 'Dị ứng hải sản', 'Khách bị dị ứng hải sản, cần tránh các món có hải sản', 'khan_cap', 'da_giai_quyet', 'Đã thông báo cho nhà hàng và HDV', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01'));

-- 18. Dữ liệu mẫu cho bảng lich_su_yeu_cau
INSERT INTO `lich_su_yeu_cau` (`yeu_cau_id`, `hanh_dong`, `noi_dung`, `nguoi_thuc_hien_id`) VALUES
((SELECT `id` FROM `yeu_cau_dac_biet` LIMIT 1), 'Tạo yêu cầu', 'Khách tạo yêu cầu ăn chay', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01')),
((SELECT `id` FROM `yeu_cau_dac_biet` LIMIT 1), 'Cập nhật trạng thái', 'HDV đã xử lý yêu cầu', (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')));

-- 19. Dữ liệu mẫu cho bảng nhat_ky_tour
INSERT INTO `nhat_ky_tour` (`tour_id`, `nhan_su_id`, `loai_nhat_ky`, `tieu_de`, `noi_dung`, `cach_xu_ly`, `hinh_anh`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'hanh_trinh', 'Ngày đầu tiên tour', 'Đã đón khách tại sân bay, mọi người đều có mặt đúng giờ. Thời tiết đẹp, khách hàng rất hào hứng.', NULL, '["images/tour_day1_1.jpg", "images/tour_day1_2.jpg"]'),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'su_co', 'Xe bị hỏng nhẹ', 'Xe du lịch bị lốp xẹp trên đường đi Hạ Long', 'Đã gọi cứu hộ, thay lốp dự phòng. Tour tiếp tục sau 30 phút. Không ảnh hưởng đến lịch trình.', NULL);

-- 20. Dữ liệu mẫu cho bảng danh_gia_hdv
-- Tạm thời vô hiệu hóa trigger để tránh xung đột khi insert dữ liệu mẫu
DROP TRIGGER IF EXISTS `after_insert_danh_gia_hdv`;

INSERT INTO `danh_gia_hdv` (`tour_id`, `nhan_su_id`, `khach_hang_id`, `diem_chuyen_mon`, `diem_thai_do`, `diem_giao_tiep`, `noi_dung_danh_gia`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), (SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), (SELECT `khach_hang_id` FROM `khach_hang` LIMIT 1), 5, 5, 5, 'HDV rất nhiệt tình, chuyên nghiệp. Hướng dẫn chi tiết, giải đáp mọi thắc mắc. Tour rất vui và đáng nhớ!');

-- Cập nhật thủ công điểm đánh giá trung bình và số tour đã dẫn cho HDV
UPDATE `nhan_su` 
SET `danh_gia_tb` = (
    SELECT AVG(`diem_tong`) 
    FROM `danh_gia_hdv` 
    WHERE `nhan_su_id` = `nhan_su`.`nhan_su_id`
),
`so_tour_da_dan` = (
    SELECT COUNT(DISTINCT `tour_id`) 
    FROM `danh_gia_hdv` 
    WHERE `nhan_su_id` = `nhan_su`.`nhan_su_id`
)
WHERE `nhan_su_id` IN (SELECT `nhan_su_id` FROM `danh_gia_hdv`);

-- Tạo lại trigger sau khi đã insert dữ liệu
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_danh_gia_hdv` AFTER INSERT ON `danh_gia_hdv` FOR EACH ROW BEGIN
    DECLARE avg_score DECIMAL(3,2);
    DECLARE tour_count INT;
    
    -- Tính điểm TB
    SELECT AVG(diem_tong) INTO avg_score
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Đếm số tour
    SELECT COUNT(DISTINCT tour_id) INTO tour_count
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Cập nhật vào bảng nhan_su
    UPDATE nhan_su
    SET danh_gia_tb = IFNULL(avg_score, 0),
        so_tour_da_dan = tour_count
    WHERE nhan_su_id = NEW.nhan_su_id;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- 21. Dữ liệu mẫu cho bảng chung_chi_hdv
-- Tạm thời vô hiệu hóa trigger để tránh xung đột khi insert dữ liệu mẫu
DROP TRIGGER IF EXISTS `after_insert_chung_chi_hdv`;

INSERT INTO `chung_chi_hdv` (`nhan_su_id`, `ten_chung_chi`, `loai_chung_chi`, `co_quan_cap`, `ngay_cap`, `ngay_het_han`, `so_chung_chi`, `trang_thai`, `ghi_chu`) VALUES
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'Chứng chỉ nghiệp vụ HDV du lịch', 'HDV', 'Tổng cục Du lịch', '2020-01-15', '2025-01-15', 'HDV-2020-001', 'ConHan', 'Chứng chỉ chính thức'),
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'IELTS Academic', 'NgoaiNgu', 'British Council', '2019-06-20', NULL, 'IELTS-2019-12345', 'ConHan', 'Điểm 7.0, không có thời hạn'),
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv02')), 'Chứng chỉ HDV quốc tế', 'HDV', 'Hiệp hội Du lịch Quốc tế', '2018-03-10', '2026-03-10', 'ITG-2018-456', 'ConHan', 'Chứng chỉ quốc tế');

-- Cập nhật thủ công trạng thái chứng chỉ sắp hết hạn
UPDATE `chung_chi_hdv`
SET `trang_thai` = CASE 
    WHEN `ngay_het_han` IS NOT NULL AND DATEDIFF(`ngay_het_han`, CURDATE()) <= 30 THEN 'SapHetHan'
    WHEN `ngay_het_han` IS NOT NULL AND DATEDIFF(`ngay_het_han`, CURDATE()) < 0 THEN 'HetHan'
    ELSE 'ConHan'
END;

-- Tạo lại trigger sau khi đã insert dữ liệu
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `after_insert_chung_chi_hdv` AFTER INSERT ON `chung_chi_hdv` FOR EACH ROW BEGIN
    IF NEW.ngay_het_han IS NOT NULL AND DATEDIFF(NEW.ngay_het_han, CURDATE()) <= 30 THEN
        UPDATE chung_chi_hdv
        SET trang_thai = 'SapHetHan'
        WHERE id = NEW.id;
        
        -- Tạo thông báo nhắc nhở
        INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
        VALUES (
            NEW.nhan_su_id,
            'CanhBao',
            CONCAT('Chứng chỉ ', NEW.ten_chung_chi, ' sắp hết hạn'),
            CONCAT('Chứng chỉ của bạn sẽ hết hạn vào ', DATE_FORMAT(NEW.ngay_het_han, '%d/%m/%Y'), '. Vui lòng gia hạn kịp thời.'),
            'Cao'
        );
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- 22. Dữ liệu mẫu cho bảng hieu_suat_hdv
INSERT INTO `hieu_suat_hdv` (`nhan_su_id`, `thang`, `nam`, `so_tour_thang`, `so_ngay_lam_viec`, `doanh_thu_mang_lai`, `diem_danh_gia_tb`, `so_khieu_nai`, `so_khen_thuong`, `ghi_chu`) VALUES
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), MONTH(CURDATE()), YEAR(CURDATE()), 3, 12, 21000000.00, 4.50, 0, 2, 'Hiệu suất tốt trong tháng'),
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv02')), MONTH(CURDATE()), YEAR(CURDATE()), 4, 18, 34000000.00, 4.75, 0, 3, 'Hiệu suất xuất sắc');

-- 23. Dữ liệu mẫu cho bảng lich_lam_viec_hdv
INSERT INTO `lich_lam_viec_hdv` (`nhan_su_id`, `tour_id`, `loai_lich`, `ngay_bat_dau`, `ngay_ket_thuc`, `ghi_chu`, `trang_thai`, `nguoi_tao_id`) VALUES
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), (SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Tour', DATE_ADD(CURDATE(), INTERVAL 10 DAY), DATE_ADD(CURDATE(), INTERVAL 12 DAY), 'Tour Hạ Long', 'XacNhan', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin')),
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), NULL, 'NghiPhep', DATE_ADD(CURDATE(), INTERVAL 25 DAY), DATE_ADD(CURDATE(), INTERVAL 27 DAY), 'Nghỉ phép năm', 'DuKien', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'));

-- 24. Dữ liệu mẫu cho bảng thong_bao_hdv
INSERT INTO `thong_bao_hdv` (`nhan_su_id`, `loai_thong_bao`, `tieu_de`, `noi_dung`, `uu_tien`, `da_xem`) VALUES
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'LichTour', 'Chuẩn bị tour tuần sau', CONCAT('Tour Hà Nội - Hạ Long sẽ khởi hành vào ', DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 10 DAY), '%d/%m/%Y'), '. Vui lòng chuẩn bị tài liệu và thiết bị.'), 'Cao', 0),
((SELECT `nhan_su_id` FROM `nhan_su` WHERE `nguoi_dung_id` = (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'hdv01')), 'NhacNho', 'Kiểm tra chứng chỉ', 'Vui lòng kiểm tra lại chứng chỉ HDV của bạn, một số chứng chỉ sắp hết hạn.', 'TrungBinh', 0),
(NULL, 'ThongBao', 'Thông báo chung cho tất cả HDV', 'Hệ thống sẽ bảo trì vào cuối tuần. Vui lòng lưu lại công việc trước khi đăng xuất.', 'Thap', 0);

-- 25. Dữ liệu mẫu cho bảng phan_hoi_danh_gia
INSERT INTO `phan_hoi_danh_gia` (`tour_id`, `nguoi_dung_id`, `loai`, `diem`, `noi_dung`, `ngay_danh_gia`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'khach01'), 'Tour', 5, 'Trải nghiệm tuyệt vời, hướng dẫn viên nhiệt tình, tour được tổ chức rất chuyên nghiệp!', CURDATE());

-- 26. Dữ liệu mẫu cho bảng giao_dich_tai_chinh
INSERT INTO `giao_dich_tai_chinh` (`tour_id`, `loai`, `so_tien`, `mo_ta`, `ngay_giao_dich`) VALUES
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Thu', 7000000.00, 'Khách đặt cọc tour', CURDATE()),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Chi', 2000000.00, 'Đặt cọc khách sạn', CURDATE()),
((SELECT `tour_id` FROM `tour` WHERE `ten_tour` = 'Hà Nội - Hạ Long 3N2Đ'), 'Chi', 5000000.00, 'Thuê xe du lịch', CURDATE());

-- 27. Dữ liệu mẫu cho bảng phan_bo_history
INSERT INTO `phan_bo_history` (`phan_bo_id`, `loai_phan_bo`, `thay_doi`, `nguoi_thay_doi_id`) VALUES
((SELECT `id` FROM `phan_bo_nhan_su` LIMIT 1), 'NhanSu', 'Phân bổ HDV chính cho tour', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin')),
((SELECT `id` FROM `phan_bo_dich_vu` LIMIT 1), 'DichVu', 'Phân bổ khách sạn cho tour', (SELECT `id` FROM `nguoi_dung` WHERE `ten_dang_nhap` = 'admin'));

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
