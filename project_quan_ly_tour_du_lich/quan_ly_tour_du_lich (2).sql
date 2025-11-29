-- Bảng quản lý danh sách khách cho mỗi booking
CREATE TABLE IF NOT EXISTS booking_khach_hang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  khach_hang_id INT NOT NULL,
  diem_danh ENUM('co_mat', 'vang_mat') DEFAULT 'co_mat',
  FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 25, 2025 at 07:56 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quan_ly_tour_du_lich`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `khach_hang_id` int(11) DEFAULT NULL,
  `ngay_dat` date DEFAULT NULL,
  `ngay_khoi_hanh` date DEFAULT NULL,
  `so_nguoi` int(11) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaCoc','HoanTat','Huy') DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `tour_id`, `khach_hang_id`, `ngay_dat`, `ngay_khoi_hanh`, `so_nguoi`, `tong_tien`, `trang_thai`, `ghi_chu`) VALUES
(3, 3, 3, '2025-11-22', '2025-12-07', 1, 4200000.00, 'ChoXacNhan', 'Khách đơn, cần hỗ trợ đặc biệt'),
(4, 3, 4, '2025-11-22', '2025-12-21', 43, 180600000.00, 'ChoXacNhan', 'cxvxcv | Công ty/Tổ chức: ấds'),
(5, 3, 4, '2025-11-22', '2026-11-21', 15, 63000000.00, 'ChoXacNhan', 'fdgdf | Công ty/Tổ chức: scgsfg'),
(6, 4, 4, '2025-11-22', '2026-03-13', 32, 272000000.00, 'DaCoc', 'cố lên e | Công ty/Tổ chức: fpoly');

-- --------------------------------------------------------

--
-- Table structure for table `booking_history`
--

CREATE TABLE `booking_history` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `trang_thai_cu` enum('ChoXacNhan','DaCoc','HoanTat','Huy') DEFAULT NULL,
  `trang_thai_moi` enum('ChoXacNhan','DaCoc','HoanTat','Huy') NOT NULL,
  `nguoi_thay_doi_id` int(11) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `thoi_gian` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_history`
--

INSERT INTO `booking_history` (`id`, `booking_id`, `trang_thai_cu`, `trang_thai_moi`, `nguoi_thay_doi_id`, `ghi_chu`, `thoi_gian`) VALUES
(4, 6, 'ChoXacNhan', 'DaCoc', 5, '', '2025-11-22 00:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `checkin_khach`
--

CREATE TABLE `checkin_khach` (
  `id` int(11) NOT NULL,
  `diem_checkin_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `trang_thai` enum('chua_checkin','da_checkin','vang_mat','re_gio') DEFAULT 'chua_checkin',
  `thoi_gian_checkin` datetime DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `nguoi_checkin_id` int(11) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chung_chi_hdv`
--

CREATE TABLE `chung_chi_hdv` (
  `id` int(11) NOT NULL,
  `nhan_su_id` int(11) NOT NULL,
  `ten_chung_chi` varchar(255) NOT NULL COMMENT 'Tên chứng chỉ/bằng cấp',
  `loai_chung_chi` enum('HDV','NgoaiNgu','KyNang','AnToan','Khac') NOT NULL,
  `co_quan_cap` varchar(255) DEFAULT NULL COMMENT 'Nơi cấp',
  `ngay_cap` date DEFAULT NULL,
  `ngay_het_han` date DEFAULT NULL COMMENT 'NULL nếu vô thời hạn',
  `so_chung_chi` varchar(100) DEFAULT NULL,
  `file_dinh_kem` varchar(255) DEFAULT NULL COMMENT 'Link file scan chứng chỉ',
  `trang_thai` enum('ConHan','SapHetHan','HetHan') DEFAULT 'ConHan',
  `ghi_chu` text DEFAULT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý chi tiết chứng chỉ HDV';

--
-- Dumping data for table `chung_chi_hdv`
--

INSERT INTO `chung_chi_hdv` (`id`, `nhan_su_id`, `ten_chung_chi`, `loai_chung_chi`, `co_quan_cap`, `ngay_cap`, `ngay_het_han`, `so_chung_chi`, `file_dinh_kem`, `trang_thai`, `ghi_chu`, `ngay_tao`) VALUES
(1, 2, 'Chứng chỉ nghiệp vụ HDV du lịch', 'HDV', 'Tổng cục Du lịch', '2020-01-15', '2025-01-15', 'HDV-2020-001', NULL, 'SapHetHan', 'Chứng chỉ chính thức', '2025-11-22 05:49:46'),
(2, 2, 'IELTS Academic', 'NgoaiNgu', 'British Council', '2019-06-20', NULL, 'IELTS-2019-12345', NULL, 'ConHan', 'Điểm 7.0, không có thời hạn', '2025-11-22 05:49:46'),
(3, 3, 'Chứng chỉ HDV quốc tế', 'HDV', 'Hiệp hội Du lịch Quốc tế', '2018-03-10', '2026-03-10', 'ITG-2018-456', NULL, 'ConHan', 'Chứng chỉ quốc tế', '2025-11-22 05:49:46');

--
-- Triggers `chung_chi_hdv`
--
DELIMITER $$
CREATE TRIGGER `after_insert_chung_chi_hdv` AFTER INSERT ON `chung_chi_hdv` FOR EACH ROW BEGIN
    IF NEW.ngay_het_han IS NOT NULL AND DATEDIFF(NEW.ngay_het_han, CURDATE()) <= 30 THEN
        UPDATE chung_chi_hdv
        SET trang_thai = 'SapHetHan'
        WHERE id = NEW.id;
        
        
        INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
        VALUES (
            NEW.nhan_su_id,
            'CanhBao',
            CONCAT('Chứng chỉ ', NEW.ten_chung_chi, ' sắp hết hạn'),
            CONCAT('Chứng chỉ của bạn sẽ hết hạn vào ', DATE_FORMAT(NEW.ngay_het_han, '%d/%m/%Y'), '. Vui lòng gia hạn kịp thời.'),
            'Cao'
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `danh_gia_id` int(11) NOT NULL,
  `khach_hang_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `nha_cung_cap_id` int(11) DEFAULT NULL,
  `nhan_su_id` int(11) DEFAULT NULL,
  `loai_danh_gia` enum('Tour','NhaCungCap','NhanSu') NOT NULL,
  `tieu_chi` varchar(100) DEFAULT NULL COMMENT 'ChatLuongTour, DichVu, HuongDanVien, GiaCa, etc',
  `loai_dich_vu` varchar(100) DEFAULT NULL COMMENT 'Xe, KhachSan, NhaHang, VanChuyen, etc',
  `diem` int(1) NOT NULL CHECK (`diem` >= 1 and `diem` <= 5),
  `noi_dung` text NOT NULL,
  `phan_hoi_admin` text DEFAULT NULL,
  `ngay_danh_gia` datetime NOT NULL DEFAULT current_timestamp(),
  `ngay_phan_hoi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danh_gia`
--

INSERT INTO `danh_gia` (`danh_gia_id`, `khach_hang_id`, `tour_id`, `nha_cung_cap_id`, `nhan_su_id`, `loai_danh_gia`, `tieu_chi`, `loai_dich_vu`, `diem`, `noi_dung`, `phan_hoi_admin`, `ngay_danh_gia`, `ngay_phan_hoi`) VALUES
(1, 1, 1, NULL, NULL, 'Tour', 'ChatLuongTour', NULL, 5, 'Tour rất tuyệt vời, tổ chức chu đáo, hướng dẫn viên nhiệt tình. Chuyến đi rất đáng nhớ!', NULL, '2024-01-15 10:30:00', NULL),
(2, 2, 1, NULL, NULL, 'Tour', 'DichVu', NULL, 4, 'Dịch vụ tốt, khách sạn sạch sẽ. Tuy nhiên bữa ăn hơi đơn giản.', NULL, '2024-01-16 14:20:00', NULL),
(3, 3, 2, NULL, NULL, 'Tour', 'HuongDanVien', NULL, 5, 'HDV rất nhiệt tình, hiểu biết, giải đáp mọi thắc mắc. Rất hài lòng!', NULL, '2024-01-17 09:15:00', NULL),
(4, 1, 2, NULL, NULL, 'Tour', 'GiaCa', NULL, 3, 'Giá hơi cao so với chất lượng dịch vụ nhận được.', NULL, '2024-01-18 16:45:00', NULL),
(5, 2, NULL, NULL, NULL, 'NhaCungCap', 'DichVu', NULL, 2, 'Xe đưa đón không đúng giờ, gây ảnh hưởng đến lịch trình.', NULL, '2024-01-19 11:30:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia_hdv`
--

CREATE TABLE `danh_gia_hdv` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `nhan_su_id` int(11) NOT NULL COMMENT 'HDV được đánh giá',
  `khach_hang_id` int(11) DEFAULT NULL COMMENT 'Khách hàng đánh giá',
  `diem_chuyen_mon` tinyint(4) DEFAULT NULL COMMENT 'Điểm chuyên môn 1-5',
  `diem_thai_do` tinyint(4) DEFAULT NULL COMMENT 'Điểm thái độ 1-5',
  `diem_giao_tiep` tinyint(4) DEFAULT NULL COMMENT 'Điểm giao tiếp 1-5',
  `diem_tong` decimal(3,2) DEFAULT NULL COMMENT 'Điểm tổng = TB 3 tiêu chí',
  `noi_dung_danh_gia` text DEFAULT NULL COMMENT 'Nhận xét chi tiết',
  `ngay_danh_gia` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Đánh giá HDV từ khách hàng';

--
-- Triggers `danh_gia_hdv`
--
DELIMITER $$
CREATE TRIGGER `after_insert_danh_gia_hdv` AFTER INSERT ON `danh_gia_hdv` FOR EACH ROW BEGIN
    DECLARE avg_score DECIMAL(3,2);
    DECLARE tour_count INT;
    
    
    SELECT AVG(diem_tong) INTO avg_score
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    
    SELECT COUNT(DISTINCT tour_id) INTO tour_count
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    
    UPDATE nhan_su
    SET danh_gia_tb = IFNULL(avg_score, 0),
        so_tour_da_dan = tour_count
    WHERE nhan_su_id = NEW.nhan_su_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_danh_gia_hdv` BEFORE INSERT ON `danh_gia_hdv` FOR EACH ROW BEGIN
    IF NEW.diem_chuyen_mon IS NOT NULL AND NEW.diem_thai_do IS NOT NULL AND NEW.diem_giao_tiep IS NOT NULL THEN
        SET NEW.diem_tong = (NEW.diem_chuyen_mon + NEW.diem_thai_do + NEW.diem_giao_tiep) / 3;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dich_vu_nha_cung_cap`
--

CREATE TABLE `dich_vu_nha_cung_cap` (
  `id` int(11) NOT NULL,
  `nha_cung_cap_id` int(11) NOT NULL,
  `ten_dich_vu` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `loai_dich_vu` enum('KhachSan','NhaHang','Xe','Ve','VeMayBay','DiemThamQuan','Visa','BaoHiem','Khac') NOT NULL DEFAULT 'Khac',
  `gia_tham_khao` decimal(15,2) DEFAULT NULL,
  `don_vi_tinh` varchar(50) DEFAULT NULL,
  `cong_suat_toi_da` int(11) DEFAULT NULL,
  `thoi_gian_xu_ly` varchar(120) DEFAULT NULL,
  `tai_lieu_dinh_kem` text DEFAULT NULL,
  `trang_thai` enum('HoatDong','TamDung','NgungHopTac') NOT NULL DEFAULT 'HoatDong',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dich_vu_nha_cung_cap`
--

INSERT INTO `dich_vu_nha_cung_cap` (`id`, `nha_cung_cap_id`, `ten_dich_vu`, `mo_ta`, `loai_dich_vu`, `gia_tham_khao`, `don_vi_tinh`, `cong_suat_toi_da`, `thoi_gian_xu_ly`, `tai_lieu_dinh_kem`, `trang_thai`, `created_at`, `updated_at`) VALUES
(2, 3, 'dsfsdf', 'dfdgfd', 'KhachSan', 150000.00, '/phòng', 50, '2h', '', 'HoatDong', '2025-11-25 09:06:42', '2025-11-25 09:09:48'),
(3, 3, 'vé máy bay', '', 'VeMayBay', 1500000.00, 'vé', NULL, '2h', '', 'HoatDong', '2025-11-25 13:23:32', '2025-11-25 13:23:53');

-- --------------------------------------------------------

--
-- Table structure for table `diem_checkin`
--

CREATE TABLE `diem_checkin` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `ten_diem` varchar(255) NOT NULL,
  `loai_diem` enum('tap_trung','tham_quan','an_uong','nghi_ngoi','khac') DEFAULT 'tap_trung',
  `thoi_gian_du_kien` datetime DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `thu_tu` int(11) DEFAULT 1,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giao_dich_tai_chinh`
--

CREATE TABLE `giao_dich_tai_chinh` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `loai` enum('Thu','Chi') DEFAULT NULL,
  `so_tien` decimal(15,2) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `ngay_giao_dich` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hieu_suat_hdv`
--

CREATE TABLE `hieu_suat_hdv` (
  `id` int(11) NOT NULL,
  `nhan_su_id` int(11) NOT NULL,
  `thang` int(11) NOT NULL COMMENT 'Tháng 1-12',
  `nam` int(11) NOT NULL COMMENT 'Năm',
  `so_tour_thang` int(11) DEFAULT 0 COMMENT 'Số tour trong tháng',
  `so_ngay_lam_viec` int(11) DEFAULT 0 COMMENT 'Số ngày làm việc',
  `doanh_thu_mang_lai` decimal(15,2) DEFAULT 0.00 COMMENT 'Doanh thu tour đã dẫn',
  `diem_danh_gia_tb` decimal(3,2) DEFAULT 0.00 COMMENT 'Điểm TB từ khách hàng',
  `so_khieu_nai` int(11) DEFAULT 0 COMMENT 'Số khiếu nại trong tháng',
  `so_khen_thuong` int(11) DEFAULT 0 COMMENT 'Số lần được khen thưởng',
  `ghi_chu` text DEFAULT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Báo cáo hiệu suất HDV theo tháng';

--
-- Dumping data for table `hieu_suat_hdv`
--

INSERT INTO `hieu_suat_hdv` (`id`, `nhan_su_id`, `thang`, `nam`, `so_tour_thang`, `so_ngay_lam_viec`, `doanh_thu_mang_lai`, `diem_danh_gia_tb`, `so_khieu_nai`, `so_khen_thuong`, `ghi_chu`, `ngay_tao`) VALUES
(2, 2, 11, 2025, 3, 12, 21000000.00, 4.50, 0, 2, 'Hiệu suất tốt trong tháng', '2025-11-22 05:49:46'),
(3, 3, 11, 2025, 4, 18, 34000000.00, 4.75, 0, 3, 'Hiệu suất xuất sắc', '2025-11-22 05:49:46');

-- --------------------------------------------------------

--
-- Table structure for table `hinh_anh_tour`
--

CREATE TABLE `hinh_anh_tour` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `url_anh` varchar(255) DEFAULT NULL,
  `mo_ta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hinh_anh_tour`
--

INSERT INTO `hinh_anh_tour` (`id`, `tour_id`, `url_anh`, `mo_ta`) VALUES
(5, 3, 'images/dalat1.jpg', 'Vườn hoa Đà Lạt'),
(6, 3, 'images/dalat2.jpg', 'Thác Datanla'),
(7, 5, 'public/uploads/tour_images/tour_6925a4cddadde8.80437085.jpeg', '');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_room_assignment`
--

CREATE TABLE `hotel_room_assignment` (
  `id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `checkin_id` int(11) DEFAULT NULL,
  `ten_khach_san` varchar(255) NOT NULL,
  `so_phong` varchar(50) NOT NULL,
  `loai_phong` varchar(100) DEFAULT 'Standard',
  `so_giuong` int(11) DEFAULT 1,
  `ngay_nhan_phong` date NOT NULL,
  `ngay_tra_phong` date NOT NULL,
  `gia_phong` decimal(15,2) DEFAULT 0.00,
  `trang_thai` enum('DaDatPhong','DaNhanPhong','DaTraPhong','Huy') DEFAULT 'DaDatPhong',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khach_hang`
--

CREATE TABLE `khach_hang` (
  `khach_hang_id` int(11) NOT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nữ','Khác') DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khach_hang`
--

INSERT INTO `khach_hang` (`khach_hang_id`, `nguoi_dung_id`, `dia_chi`, `gioi_tinh`, `ngay_sinh`) VALUES
(2, 8, '123 Đường Lê Lợi, Quận 1, TP.HCM', 'Nam', '1990-05-15'),
(3, 9, '456 Đường Nguyễn Huệ, Quận 3, TP.HCM', 'Nữ', '1985-08-20'),
(4, 12, 'dsfdf', 'Nam', '1999-02-12');

-- --------------------------------------------------------

--
-- Table structure for table `lich_khoi_hanh`
--

CREATE TABLE `lich_khoi_hanh` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `ngay_khoi_hanh` date DEFAULT NULL,
  `gio_xuat_phat` time DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gio_ket_thuc` time DEFAULT NULL,
  `diem_tap_trung` varchar(255) DEFAULT NULL,
  `so_cho` int(11) DEFAULT 50,
  `hdv_id` int(11) DEFAULT NULL,
  `trang_thai` enum('SapKhoiHanh','DangChay','HoanThanh') DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_khoi_hanh`
--

INSERT INTO `lich_khoi_hanh` (`id`, `tour_id`, `ngay_khoi_hanh`, `gio_xuat_phat`, `ngay_ket_thuc`, `gio_ket_thuc`, `diem_tap_trung`, `so_cho`, `hdv_id`, `trang_thai`, `ghi_chu`) VALUES
(3, 3, '2025-12-07', '07:00:00', '2025-12-10', '19:00:00', 'Bến xe Miền Đông', 45, 2, 'SapKhoiHanh', 'Lịch khởi hành tour Đà Lạt'),
(4, 4, '2025-11-24', '07:00:00', '2025-11-26', '17:00:00', '', 50, 2, 'SapKhoiHanh', ''),
(5, 5, '2025-11-25', '21:45:00', '2025-11-30', '20:45:00', 'sân bay nội bài', 50, NULL, 'SapKhoiHanh', ''),
(6, 5, '2025-11-26', '00:41:00', '2025-11-30', '01:41:00', 'sân bay nội bài', 50, 3, 'SapKhoiHanh', '');

-- --------------------------------------------------------

--
-- Table structure for table `lich_lam_viec_hdv`
--

CREATE TABLE `lich_lam_viec_hdv` (
  `id` int(11) NOT NULL,
  `nhan_su_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL COMMENT 'NULL nếu là ngày nghỉ/bận',
  `loai_lich` enum('Tour','NghiPhep','Ban','DatTruoc') NOT NULL COMMENT 'Loại lịch làm việc',
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` enum('DuKien','XacNhan','HoanThanh','Huy') DEFAULT 'DuKien',
  `nguoi_tao_id` int(11) DEFAULT NULL COMMENT 'Người tạo lịch (admin)',
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch làm việc HDV: tour, nghỉ phép, bận';

--
-- Dumping data for table `lich_lam_viec_hdv`
--

INSERT INTO `lich_lam_viec_hdv` (`id`, `nhan_su_id`, `tour_id`, `loai_lich`, `ngay_bat_dau`, `ngay_ket_thuc`, `ghi_chu`, `trang_thai`, `nguoi_tao_id`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(2, 2, NULL, 'Tour', '2025-12-02', '2025-12-04', 'Tour Hạ Long', 'XacNhan', 5, '2025-11-22 05:49:46', '2025-11-22 05:49:46'),
(3, 2, NULL, 'NghiPhep', '2025-12-17', '2025-12-19', 'Nghỉ phép năm', 'DuKien', 5, '2025-11-22 05:49:46', '2025-11-22 05:49:46');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_yeu_cau`
--

CREATE TABLE `lich_su_yeu_cau` (
  `id` int(11) NOT NULL,
  `yeu_cau_id` int(11) NOT NULL,
  `hanh_dong` varchar(100) NOT NULL,
  `noi_dung` text DEFAULT NULL,
  `nguoi_thuc_hien_id` int(11) DEFAULT NULL,
  `ngay_thuc_hien` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lich_trinh_tour`
--

CREATE TABLE `lich_trinh_tour` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `ngay_thu` int(11) DEFAULT NULL,
  `dia_diem` varchar(255) DEFAULT NULL,
  `hoat_dong` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_trinh_tour`
--

INSERT INTO `lich_trinh_tour` (`id`, `tour_id`, `ngay_thu`, `dia_diem`, `hoat_dong`) VALUES
(7, 3, 1, 'Sài Gòn - Đà Lạt', 'Khởi hành từ Sài Gòn - Đến Đà Lạt - Tham quan vườn hoa thành phố'),
(8, 3, 2, 'Đà Lạt', 'Tham quan thác Datanla - Vườn hoa - Chợ đêm Đà Lạt'),
(9, 3, 3, 'Đà Lạt', 'Tham quan đồi chè Cầu Đất - Làng hoa Vạn Thành - Nghỉ ngơi'),
(10, 3, 4, 'Đà Lạt - Sài Gòn', 'Tham quan chợ Đà Lạt - Trở về Sài Gòn - Kết thúc tour'),
(11, 5, 1, '📅 Ngày 1: Hà Nội – Tokyo (Narita)', '\r\n\r\nHướng dẫn viên đón đoàn tại Nội Bài, làm thủ tục bay sang Nhật.\r\n\r\nHạ cánh tại sân bay Narita, về khách sạn nhận phòng.'),
(12, 5, 2, '📅 Ngày 2: Nagoya – Thành phố cảng', 'Hoạt động:\r\n\r\nTham quan Lâu đài Nagoya.\r\n\r\nKhám phá khu phố Sakae, mua sắm.\r\n\r\nThưởng thức món Tebasaki nổi tiếng.'),
(13, 5, 3, '📅 Ngày 3: Nagoya – Núi Phú Sĩ', 'Địa điểm: Phú Sĩ – Kawaguchiko\r\nHoạt động:\r\n\r\nDi chuyển đến khu vực núi Phú Sĩ.\r\n\r\nTham quan trạm 5 (nếu thời tiết cho phép).\r\n\r\nChụp ảnh hồ Kawaguchi.\r\n\r\nTrải nghiệm tắm onsen.'),
(14, 5, 4, '📅 Ngày 4: Làng cổ Oshino Hakkai', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nChùa Vàng Kinkaku-ji.\r\n\r\nChùa Thanh Thủy Kiyomizu-dera.\r\n\r\nDạo phố Gion – nơi geisha sinh sống.'),
(15, 5, 5, '📅 Ngày 6: Kyoto – Trà đạo', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nTham gia trải nghiệm trà đạo.\r\n\r\nTham quan rừng tre Arashiyama.\r\n\r\nMua quà lưu niệm tại Nishiki Market.');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ten_dang_nhap` varchar(100) DEFAULT NULL,
  `mat_khau` varchar(255) DEFAULT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `vai_tro` enum('Admin','HDV','KhachHang','NhaCungCap') DEFAULT NULL,
  `quyen_cap_cao` tinyint(1) DEFAULT 0,
  `trang_thai` enum('HoatDong','BiKhoa') DEFAULT 'HoatDong',
  `ngay_tao` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `mat_khau`, `ho_ten`, `avatar`, `email`, `so_dien_thoai`, `vai_tro`, `quyen_cap_cao`, `trang_thai`, `ngay_tao`) VALUES
(5, 'admin', '$2y$10$h9EsUazPVy/cPZk3LX/sgezIB3PViFeUUBWRjVmyFw2RKKZ4aTxdS', 'Quản trị viên hệ thống', NULL, 'admin@tour.com', '0901234567', 'Admin', 1, 'HoatDong', '2025-11-22 05:49:46'),
(6, 'hdv01', '$2y$10$YPXILUs3Hwv1JZ786l1pOunF/1UEXr1xW6yVP23h7mQ/AwztkCUk.', 'Nguyễn Văn Hướng', NULL, 'hdv@tour.com', '0912345678', 'HDV', 0, 'HoatDong', '2025-11-22 05:49:46'),
(7, 'hdv02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị Lan', NULL, 'hdv02@tour.com', '0923456789', 'HDV', 0, 'HoatDong', '2025-11-22 05:49:46'),
(8, 'khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Văn An', NULL, 'khach01@email.com', '0934567890', 'KhachHang', 0, 'HoatDong', '2025-11-22 05:49:46'),
(9, 'khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Phạm Thị Bình', NULL, 'khach02@email.com', '0945678901', 'KhachHang', 0, 'HoatDong', '2025-11-22 05:49:46'),
(10, 'ncc01', '1\r\n', 'Công ty ABC Travel', NULL, 'ncc01@tour.com', '0956789012', 'NhaCungCap', 0, 'HoatDong', '2025-11-22 05:49:46'),
(11, 'ncc02', '$2y$10$LwBeRXp5X5mPuzoaG1iDsu.zCDObUWlPY5kIHGpFkHf4MoOpdlwoO', 'Khách sạn XYZ', NULL, 'ncc02@tour.com', '0967890123', 'NhaCungCap', 0, 'HoatDong', '2025-11-22 05:49:46'),
(12, 'test100@gmail.com', '$2y$10$C7j97g4U0zJVfSH3jRzmGOqnKRxVQzsAV2D9jwIqz2dYWL.6keI4u', 'hdv33', NULL, 'test100@gmail.com', '12312321', 'KhachHang', 0, 'HoatDong', '2025-11-21 23:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `nhan_su`
--

CREATE TABLE `nhan_su` (
  `nhan_su_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `vai_tro` enum('HDV','DieuHanh','TaiXe','Khac') DEFAULT NULL,
  `loai_hdv` enum('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') DEFAULT 'TongHop' COMMENT 'Loại HDV',
  `chuyen_tuyen` varchar(255) DEFAULT NULL COMMENT 'Các tuyến chuyên: Miền Bắc, Miền Trung, Miền Nam, Đông Nam Á...',
  `danh_gia_tb` decimal(3,2) DEFAULT 0.00 COMMENT 'Điểm đánh giá trung bình 0-5',
  `so_tour_da_dan` int(11) DEFAULT 0 COMMENT 'Tổng số tour đã dẫn',
  `trang_thai_lam_viec` enum('SanSang','DangBan','NghiPhep','TamNghi') DEFAULT 'SanSang' COMMENT 'Trạng thái làm việc',
  `chung_chi` text DEFAULT NULL,
  `ngon_ngu` text DEFAULT NULL,
  `kinh_nghiem` text DEFAULT NULL,
  `suc_khoe` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhan_su`
--

INSERT INTO `nhan_su` (`nhan_su_id`, `nguoi_dung_id`, `vai_tro`, `loai_hdv`, `chuyen_tuyen`, `danh_gia_tb`, `so_tour_da_dan`, `trang_thai_lam_viec`, `chung_chi`, `ngon_ngu`, `kinh_nghiem`, `suc_khoe`) VALUES
(2, 6, 'HDV', 'NoiDia', 'Miền Bắc, Miền Trung', 5.00, 1, 'SanSang', 'Chứng chỉ nghiệp vụ hướng dẫn viên du lịch', 'Tiếng Việt, Tiếng Anh', '5 năm kinh nghiệm dẫn tour nội địa', 'Tốt'),
(3, 7, 'HDV', 'QuocTe', 'Đông Nam Á, Châu Âu', 4.75, 20, 'SanSang', 'Chứng chỉ HDV quốc tế, IELTS 7.0', 'Tiếng Việt, Tiếng Anh, Tiếng Thái', '7 năm kinh nghiệm dẫn tour quốc tế', 'Tốt');

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky_tour`
--

CREATE TABLE `nhat_ky_tour` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `nhan_su_id` int(11) DEFAULT NULL,
  `loai_nhat_ky` enum('hanh_trinh','su_co','phan_hoi','hoat_dong') DEFAULT 'hanh_trinh' COMMENT 'Loại nhật ký: hành trình, sự cố, phản hồi khách, hoạt động',
  `tieu_de` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề nhật ký',
  `noi_dung` text DEFAULT NULL,
  `cach_xu_ly` text DEFAULT NULL COMMENT 'Cách xử lý sự cố',
  `hinh_anh` text DEFAULT NULL COMMENT 'JSON array chứa đường dẫn hình ảnh',
  `ngay_ghi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhat_ky_tour`
--

INSERT INTO `nhat_ky_tour` (`id`, `tour_id`, `nhan_su_id`, `loai_nhat_ky`, `tieu_de`, `noi_dung`, `cach_xu_ly`, `hinh_anh`, `ngay_ghi`) VALUES
(5, 4, 2, 'hanh_trinh', 'dsfds', 'fdsfdsfd', '', NULL, '2025-11-25 00:00:00'),
(6, 4, 2, 'su_co', 'á', 'aaaaa', '', NULL, '2025-11-25 00:00:00'),
(7, 3, 2, 'su_co', 'xzzx', 'xzxzx', 'xzxz', NULL, '2025-11-25 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `nha_cung_cap`
--

CREATE TABLE `nha_cung_cap` (
  `id_nha_cung_cap` int(11) NOT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `ten_don_vi` varchar(255) DEFAULT NULL,
  `loai_dich_vu` enum('KhachSan','NhaHang','Xe','Ve','Visa','BaoHiem','Khac') DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `lien_he` varchar(100) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `danh_gia_tb` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nha_cung_cap`
--

INSERT INTO `nha_cung_cap` (`id_nha_cung_cap`, `nguoi_dung_id`, `ten_don_vi`, `loai_dich_vu`, `dia_chi`, `lien_he`, `mo_ta`, `danh_gia_tb`) VALUES
(2, 10, 'ABC Travel Services', 'KhachSan', '789 Đường Trần Hưng Đạo, Quận 5, TP.HCM', '0281234567', 'Đối tác cung cấp khách sạn 3-4 sao tại các điểm du lịch', 4.5),
(3, 11, 'Khách sạn XYZ', 'KhachSan', '321 Đường Lý Tự Trọng, Quận 1, TP.HCM', '0287654321', 'Khách sạn 5 sao tại trung tâm thành phố', 4.8);

-- --------------------------------------------------------

--
-- Table structure for table `phan_bo_dich_vu`
--

CREATE TABLE `phan_bo_dich_vu` (
  `id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) NOT NULL,
  `nha_cung_cap_id` int(11) DEFAULT NULL,
  `loai_dich_vu` enum('Xe','KhachSan','VeMayBay','NhaHang','DiemThamQuan','Visa','BaoHiem','Khac') NOT NULL,
  `ten_dich_vu` varchar(255) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  `don_vi` varchar(50) DEFAULT NULL,
  `ngay_bat_dau` date DEFAULT NULL,
  `ngay_ket_thuc` date DEFAULT NULL,
  `gio_bat_dau` time DEFAULT NULL,
  `gio_ket_thuc` time DEFAULT NULL,
  `dia_diem` varchar(255) DEFAULT NULL,
  `gia_tien` decimal(15,2) DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaXacNhan','TuChoi','Huy','HoanTat') DEFAULT 'ChoXacNhan',
  `thoi_gian_xac_nhan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_bo_dich_vu`
--

INSERT INTO `phan_bo_dich_vu` (`id`, `lich_khoi_hanh_id`, `nha_cung_cap_id`, `loai_dich_vu`, `ten_dich_vu`, `so_luong`, `don_vi`, `ngay_bat_dau`, `ngay_ket_thuc`, `gio_bat_dau`, `gio_ket_thuc`, `dia_diem`, `gia_tien`, `ghi_chu`, `trang_thai`, `thoi_gian_xac_nhan`, `created_at`, `updated_at`) VALUES
(3, 3, 3, 'KhachSan', 'dsfsdf', 1, '/phòng', NULL, NULL, NULL, NULL, NULL, 150000.00, 'dfdgfd', 'DaXacNhan', '2025-11-25 12:48:49', '2025-11-25 10:43:30', '2025-11-25 11:48:49'),
(5, 3, 3, 'Xe', 'xe', 10, 'xe', '2025-11-25', '2025-11-25', '20:07:00', '20:07:00', '', 150000.00, '', 'DaXacNhan', '2025-11-25 20:18:24', '2025-11-25 13:07:43', '2025-11-25 13:18:24'),
(6, 5, 3, 'VeMayBay', 'vé máy bay', 1, 'vé', NULL, NULL, NULL, NULL, NULL, 1500000.00, '', 'DaXacNhan', '2025-11-25 20:29:53', '2025-11-25 13:24:05', '2025-11-25 13:29:53'),
(7, 3, 3, 'VeMayBay', 'vé máy bay', 1, 'vé', NULL, NULL, NULL, NULL, NULL, 1500000.00, '', 'DaXacNhan', '2025-11-26 00:34:45', '2025-11-25 13:40:42', '2025-11-25 17:34:45'),
(9, 5, 3, 'NhaHang', 'nhà hàng 5 sao', 1, 'suất', NULL, NULL, NULL, NULL, NULL, 5000000.00, '', 'DaXacNhan', '2025-11-26 00:39:16', '2025-11-25 17:38:03', '2025-11-25 17:39:16'),
(10, 6, 3, 'VeMayBay', 'vé máy bay 44', 1, '', NULL, NULL, NULL, NULL, '', 0.00, '', 'TuChoi', '2025-11-26 00:57:07', '2025-11-25 17:55:45', '2025-11-25 17:57:07'),
(11, 6, 3, 'NhaHang', 'nhà hàng 5 sao', 1, 'suất', NULL, NULL, NULL, NULL, NULL, 600000.00, '', 'DaXacNhan', '2025-11-26 01:00:25', '2025-11-25 17:59:37', '2025-11-25 18:00:25'),
(12, 6, 3, 'VeMayBay', 'vé máy bay', 5, 'vé', NULL, NULL, NULL, NULL, '', 1500000.00, '', 'DaXacNhan', '2025-11-26 01:01:18', '2025-11-25 18:01:03', '2025-11-25 18:01:18');

-- --------------------------------------------------------

--
-- Table structure for table `phan_bo_history`
--

CREATE TABLE `phan_bo_history` (
  `id` int(11) NOT NULL,
  `phan_bo_id` int(11) NOT NULL,
  `loai_phan_bo` enum('NhanSu','DichVu') NOT NULL,
  `thay_doi` text NOT NULL,
  `nguoi_thay_doi_id` int(11) DEFAULT NULL,
  `thoi_gian` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_bo_history`
--

INSERT INTO `phan_bo_history` (`id`, `phan_bo_id`, `loai_phan_bo`, `thay_doi`, `nguoi_thay_doi_id`, `thoi_gian`) VALUES
(1, 1, 'NhanSu', 'Phân bổ HDV chính cho tour', 5, '2025-11-22 05:49:46'),
(2, 2, 'DichVu', 'Phân bổ khách sạn cho tour', 5, '2025-11-22 05:49:46');

-- --------------------------------------------------------

--
-- Table structure for table `phan_bo_nhan_su`
--

CREATE TABLE `phan_bo_nhan_su` (
  `id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) NOT NULL,
  `nhan_su_id` int(11) NOT NULL,
  `vai_tro` enum('HDV','TaiXe','HauCan','DieuHanh','Khac') NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaXacNhan','TuChoi','Huy') DEFAULT 'ChoXacNhan',
  `thoi_gian_xac_nhan` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_bo_nhan_su`
--

INSERT INTO `phan_bo_nhan_su` (`id`, `lich_khoi_hanh_id`, `nhan_su_id`, `vai_tro`, `ghi_chu`, `trang_thai`, `thoi_gian_xac_nhan`, `created_at`) VALUES
(4, 4, 2, 'HDV', 'xá', 'ChoXacNhan', NULL, '2025-11-24 02:12:23'),
(5, 5, 2, 'HDV', '', 'ChoXacNhan', NULL, '2025-11-25 12:46:14'),
(6, 6, 3, 'HDV', '', 'ChoXacNhan', NULL, '2025-11-25 17:42:29'),
(7, 6, 2, 'HDV', '', 'ChoXacNhan', NULL, '2025-11-25 18:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `phan_hoi_danh_gia`
--

CREATE TABLE `phan_hoi_danh_gia` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `nguoi_dung_id` int(11) DEFAULT NULL,
  `loai` enum('Tour','DichVu','NhaCungCap') DEFAULT NULL,
  `diem` int(11) DEFAULT NULL COMMENT 'Điểm đánh giá từ 1-5',
  `noi_dung` text DEFAULT NULL,
  `ngay_danh_gia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao_hdv`
--

CREATE TABLE `thong_bao_hdv` (
  `id` int(11) NOT NULL,
  `nhan_su_id` int(11) DEFAULT NULL COMMENT 'NULL = thông báo chung cho tất cả HDV',
  `loai_thong_bao` enum('LichTour','NhacNho','CanhBao','ThongBao') NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `uu_tien` enum('Thap','TrungBinh','Cao','KhanCap') DEFAULT 'TrungBinh',
  `da_xem` tinyint(1) DEFAULT 0,
  `ngay_gui` timestamp NULL DEFAULT current_timestamp(),
  `ngay_xem` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo và nhắc nhở cho HDV';

--
-- Dumping data for table `thong_bao_hdv`
--

INSERT INTO `thong_bao_hdv` (`id`, `nhan_su_id`, `loai_thong_bao`, `tieu_de`, `noi_dung`, `uu_tien`, `da_xem`, `ngay_gui`, `ngay_xem`) VALUES
(3, 2, 'LichTour', 'Chuẩn bị tour tuần sau', 'Tour Hà Nội - Hạ Long sẽ khởi hành vào 02/12/2025. Vui lòng chuẩn bị tài liệu và thiết bị.', 'Cao', 1, '2025-11-22 05:49:46', NULL),
(4, 2, 'NhacNho', 'Kiểm tra chứng chỉ', 'Vui lòng kiểm tra lại chứng chỉ HDV của bạn, một số chứng chỉ sắp hết hạn.', 'TrungBinh', 1, '2025-11-22 05:49:46', NULL),
(5, NULL, 'ThongBao', 'Thông báo chung cho tất cả HDV', 'Hệ thống sẽ bảo trì vào cuối tuần. Vui lòng lưu lại công việc trước khi đăng xuất.', 'Thap', 0, '2025-11-22 05:49:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour`
--

CREATE TABLE `tour` (
  `tour_id` int(11) NOT NULL,
  `ten_tour` varchar(255) DEFAULT NULL,
  `loai_tour` enum('TrongNuoc','QuocTe','TheoYeuCau') DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `gia_co_ban` decimal(15,2) DEFAULT NULL,
  `chinh_sach` text DEFAULT NULL,
  `id_nha_cung_cap` int(11) DEFAULT NULL,
  `tao_boi` int(11) DEFAULT NULL,
  `trang_thai` enum('HoatDong','TamDung','HetHan') DEFAULT 'HoatDong',
  `qr_code_path` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file QR code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`tour_id`, `ten_tour`, `loai_tour`, `mo_ta`, `gia_co_ban`, `chinh_sach`, `id_nha_cung_cap`, `tao_boi`, `trang_thai`, `qr_code_path`) VALUES
(3, 'Sài Gòn - Đà Lạt 4N3Đ', 'TrongNuoc', 'Tham quan thành phố ngàn hoa, vườn hoa, thác nước, đồi chè', 4200000.00, 'Hủy trước 10 ngày: hoàn 90%. Hủy trước 5 ngày: hoàn 70%', 2, 5, 'HoatDong', NULL),
(4, 'Bangkok - Pattaya 5N4Đ', 'QuocTe', 'Khám phá thủ đô Thái Lan, tham quan cung điện, chùa vàng, vui chơi tại Pattaya', 8500000.00, 'Hủy trước 14 ngày: hoàn 80%. Hủy trước 7 ngày: hoàn 50%', 2, 5, 'HoatDong', 'public/uploads/qr/tour_4_1764082361.png'),
(5, ' NAGOYA – PHÚ SĨ – TOKYO', 'QuocTe', 'Tham quan những danh thắng nổi tiếng là biểu tượng của đất nước Mặt trời mọc: núi Phú Sĩ, làng cổ Oshino Hakkai, Chùa Asakusa Kannon, Chùa\r\nThanh Thuỷ (di sản văn hoá UNESCO)…\r\nTrải nghiệm một chặng tàu siêu tốc Shinkansen – niềm tự hào của người Nhật\r\nThưởng thức món bò Kobe trứ danh.\r\nTrải nghiệm tắm onsen phục hồi sức khoẻ tại chân núi Phú Sĩ\r\nTặng một bữa ăn có món bò Kobe/Wagyu trứ danh Nhật Bản\r\nTặng trải nghiệm cua tuyết và kem matcha phủ vàng tại Phú Sĩ.\r\nTặng trải nghiệm mặc trang phục truyền thống, check in tại Cố đô Kyoto\r\nThưởng thức Geisha Show – màn trình diễn tinh tế kết hợp âm nhạc, vũ đạo và nghệ thuật trà đạo, tôn vinh vẻ đẹp truyền thống Nhật Bản.\r\n', 32990000.00, NULL, NULL, 5, 'HoatDong', 'public/uploads/qr/tour_5_1764087033.png');

-- --------------------------------------------------------

--
-- Table structure for table `tour_checkin`
--

CREATE TABLE `tour_checkin` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `khach_hang_id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) DEFAULT NULL,
  `ho_ten` varchar(255) NOT NULL,
  `so_cmnd` varchar(50) DEFAULT NULL,
  `so_passport` varchar(50) DEFAULT NULL,
  `ngay_sinh` date DEFAULT NULL,
  `gioi_tinh` enum('Nam','Nu','Khac') DEFAULT 'Khac',
  `quoc_tich` varchar(100) DEFAULT 'Viß╗çt Nam',
  `dia_chi` text DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `checkin_time` datetime DEFAULT current_timestamp(),
  `checkout_time` datetime DEFAULT NULL,
  `trang_thai` enum('DaCheckIn','ChuaCheckIn','DaCheckOut') DEFAULT 'ChuaCheckIn',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_hdv_san_sang`
-- (See below for the actual view)
--
CREATE TABLE `v_hdv_san_sang` (
`nhan_su_id` int(11)
,`ho_ten` varchar(255)
,`email` varchar(255)
,`so_dien_thoai` varchar(20)
,`loai_hdv` enum('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop')
,`chuyen_tuyen` varchar(255)
,`danh_gia_tb` decimal(3,2)
,`so_tour_da_dan` int(11)
,`ngon_ngu` text
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_thong_ke_hieu_suat_hdv`
-- (See below for the actual view)
--
CREATE TABLE `v_thong_ke_hieu_suat_hdv` (
`nhan_su_id` int(11)
,`ho_ten` varchar(255)
,`loai_hdv` enum('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop')
,`tong_tour` bigint(21)
,`diem_tb` decimal(7,6)
,`tour_hoan_thanh` decimal(22,0)
,`tour_gan_nhat` date
);

-- --------------------------------------------------------

--
-- Table structure for table `yeu_cau_dac_biet`
--

CREATE TABLE `yeu_cau_dac_biet` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `loai_yeu_cau` enum('an_uong','suc_khoe','di_chuyen','phong_o','hoat_dong','khac') DEFAULT 'khac',
  `tieu_de` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `muc_do_uu_tien` enum('thap','trung_binh','cao','khan_cap') DEFAULT 'trung_binh',
  `trang_thai` enum('moi','dang_xu_ly','da_giai_quyet','khong_the_thuc_hien') DEFAULT 'moi',
  `ghi_chu_hdv` text DEFAULT NULL,
  `nguoi_tao_id` int(11) DEFAULT NULL,
  `nguoi_xu_ly_id` int(11) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `ngay_cap_nhat` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `yeu_cau_dac_biet`
--

INSERT INTO `yeu_cau_dac_biet` (`id`, `booking_id`, `loai_yeu_cau`, `tieu_de`, `mo_ta`, `muc_do_uu_tien`, `trang_thai`, `ghi_chu_hdv`, `nguoi_tao_id`, `nguoi_xu_ly_id`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(3, 5, 'khac', 'Yêu cầu đặc biệt', 'dfghfd', 'trung_binh', 'moi', NULL, NULL, NULL, '2025-11-22 12:57:11', '2025-11-22 12:57:11'),
(4, 6, 'khac', 'Yêu cầu đặc biệt', 'bị câm', 'trung_binh', 'moi', NULL, NULL, NULL, '2025-11-22 13:09:54', '2025-11-22 13:09:54');

-- --------------------------------------------------------

--
-- Structure for view `v_hdv_san_sang`
--
DROP TABLE IF EXISTS `v_hdv_san_sang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_hdv_san_sang`  AS SELECT `ns`.`nhan_su_id` AS `nhan_su_id`, `nd`.`ho_ten` AS `ho_ten`, `nd`.`email` AS `email`, `nd`.`so_dien_thoai` AS `so_dien_thoai`, `ns`.`loai_hdv` AS `loai_hdv`, `ns`.`chuyen_tuyen` AS `chuyen_tuyen`, `ns`.`danh_gia_tb` AS `danh_gia_tb`, `ns`.`so_tour_da_dan` AS `so_tour_da_dan`, `ns`.`ngon_ngu` AS `ngon_ngu` FROM (`nhan_su` `ns` join `nguoi_dung` `nd` on(`ns`.`nguoi_dung_id` = `nd`.`id`)) WHERE `ns`.`vai_tro` = 'HDV' AND `ns`.`trang_thai_lam_viec` = 'SanSang' AND `ns`.`nhan_su_id` in (select `lich_lam_viec_hdv`.`nhan_su_id` from `lich_lam_viec_hdv` where `lich_lam_viec_hdv`.`trang_thai` in ('DuKien','XacNhan') AND curdate() between `lich_lam_viec_hdv`.`ngay_bat_dau` and `lich_lam_viec_hdv`.`ngay_ket_thuc`) is false ;

-- --------------------------------------------------------

--
-- Structure for view `v_thong_ke_hieu_suat_hdv`
--
DROP TABLE IF EXISTS `v_thong_ke_hieu_suat_hdv`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_thong_ke_hieu_suat_hdv`  AS SELECT `ns`.`nhan_su_id` AS `nhan_su_id`, `nd`.`ho_ten` AS `ho_ten`, `ns`.`loai_hdv` AS `loai_hdv`, count(distinct `llv`.`tour_id`) AS `tong_tour`, avg(`dg`.`diem_tong`) AS `diem_tb`, sum(case when `llv`.`trang_thai` = 'HoanThanh' then 1 else 0 end) AS `tour_hoan_thanh`, max(`llv`.`ngay_ket_thuc`) AS `tour_gan_nhat` FROM (((`nhan_su` `ns` join `nguoi_dung` `nd` on(`ns`.`nguoi_dung_id` = `nd`.`id`)) left join `lich_lam_viec_hdv` `llv` on(`ns`.`nhan_su_id` = `llv`.`nhan_su_id` and `llv`.`loai_lich` = 'Tour')) left join `danh_gia_hdv` `dg` on(`ns`.`nhan_su_id` = `dg`.`nhan_su_id`)) WHERE `ns`.`vai_tro` = 'HDV' GROUP BY `ns`.`nhan_su_id`, `nd`.`ho_ten`, `ns`.`loai_hdv` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `khach_hang_id` (`khach_hang_id`);

--
-- Indexes for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_thay_doi_id` (`nguoi_thay_doi_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_thoi_gian` (`thoi_gian`);

--
-- Indexes for table `checkin_khach`
--
ALTER TABLE `checkin_khach`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_checkin` (`diem_checkin_id`,`booking_id`),
  ADD KEY `nguoi_checkin_id` (`nguoi_checkin_id`),
  ADD KEY `idx_checkin_khach_diem` (`diem_checkin_id`,`trang_thai`),
  ADD KEY `idx_checkin_khach_booking` (`booking_id`);

--
-- Indexes for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_het_han` (`ngay_het_han`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`danh_gia_id`),
  ADD KEY `idx_khach_hang` (`khach_hang_id`),
  ADD KEY `idx_tour` (`tour_id`),
  ADD KEY `idx_nha_cung_cap` (`nha_cung_cap_id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_loai_danh_gia` (`loai_danh_gia`),
  ADD KEY `idx_diem` (`diem`),
  ADD KEY `idx_ngay_danh_gia` (`ngay_danh_gia`);

--
-- Indexes for table `danh_gia_hdv`
--
ALTER TABLE `danh_gia_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khach_hang_id` (`khach_hang_id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_tour` (`tour_id`);

--
-- Indexes for table `dich_vu_nha_cung_cap`
--
ALTER TABLE `dich_vu_nha_cung_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_dich_vu_ncc` (`nha_cung_cap_id`);

--
-- Indexes for table `diem_checkin`
--
ALTER TABLE `diem_checkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_diem_checkin_tour` (`tour_id`,`thu_tu`);

--
-- Indexes for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `hieu_suat_hdv`
--
ALTER TABLE `hieu_suat_hdv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_thang_nam` (`nhan_su_id`,`thang`,`nam`),
  ADD KEY `idx_thang_nam` (`thang`,`nam`);

--
-- Indexes for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `hotel_room_assignment`
--
ALTER TABLE `hotel_room_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `checkin_id` (`checkin_id`),
  ADD KEY `idx_room_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  ADD KEY `idx_room_booking` (`booking_id`),
  ADD KEY `idx_room_status` (`trang_thai`);

--
-- Indexes for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD PRIMARY KEY (`khach_hang_id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Indexes for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `hdv_id` (`hdv_id`);

--
-- Indexes for table `lich_lam_viec_hdv`
--
ALTER TABLE `lich_lam_viec_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `nguoi_tao_id` (`nguoi_tao_id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_ngay` (`ngay_bat_dau`,`ngay_ket_thuc`),
  ADD KEY `idx_lich_hdv_trang_thai` (`nhan_su_id`,`trang_thai`,`ngay_bat_dau`);

--
-- Indexes for table `lich_su_yeu_cau`
--
ALTER TABLE `lich_su_yeu_cau`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lich_su_yeu_cau` (`yeu_cau_id`,`ngay_thuc_hien`),
  ADD KEY `nguoi_thuc_hien_id` (`nguoi_thuc_hien_id`);

--
-- Indexes for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_dang_nhap` (`ten_dang_nhap`);

--
-- Indexes for table `nhan_su`
--
ALTER TABLE `nhan_su`
  ADD PRIMARY KEY (`nhan_su_id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`),
  ADD KEY `idx_loai_hdv` (`loai_hdv`,`trang_thai_lam_viec`);

--
-- Indexes for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `nhan_su_id` (`nhan_su_id`);

--
-- Indexes for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD PRIMARY KEY (`id_nha_cung_cap`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Indexes for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  ADD KEY `idx_nha_cung_cap` (`nha_cung_cap_id`),
  ADD KEY `idx_loai_dich_vu` (`loai_dich_vu`);

--
-- Indexes for table `phan_bo_history`
--
ALTER TABLE `phan_bo_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_thay_doi_id` (`nguoi_thay_doi_id`),
  ADD KEY `idx_phan_bo` (`phan_bo_id`,`loai_phan_bo`),
  ADD KEY `idx_thoi_gian` (`thoi_gian`);

--
-- Indexes for table `phan_bo_nhan_su`
--
ALTER TABLE `phan_bo_nhan_su`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lich_khoi_hanh` (`lich_khoi_hanh_id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`);

--
-- Indexes for table `phan_hoi_danh_gia`
--
ALTER TABLE `phan_hoi_danh_gia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `nguoi_dung_id` (`nguoi_dung_id`);

--
-- Indexes for table `thong_bao_hdv`
--
ALTER TABLE `thong_bao_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nhan_su_chua_xem` (`nhan_su_id`,`da_xem`);

--
-- Indexes for table `tour`
--
ALTER TABLE `tour`
  ADD PRIMARY KEY (`tour_id`),
  ADD KEY `id_nha_cung_cap` (`id_nha_cung_cap`),
  ADD KEY `tao_boi` (`tao_boi`);

--
-- Indexes for table `tour_checkin`
--
ALTER TABLE `tour_checkin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_khach_hang_id` (`khach_hang_id`),
  ADD KEY `idx_checkin_status` (`trang_thai`);

--
-- Indexes for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_yeu_cau_booking` (`booking_id`,`trang_thai`),
  ADD KEY `idx_yeu_cau_loai` (`loai_yeu_cau`,`muc_do_uu_tien`),
  ADD KEY `nguoi_tao_id` (`nguoi_tao_id`),
  ADD KEY `nguoi_xu_ly_id` (`nguoi_xu_ly_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `checkin_khach`
--
ALTER TABLE `checkin_khach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `danh_gia_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `danh_gia_hdv`
--
ALTER TABLE `danh_gia_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dich_vu_nha_cung_cap`
--
ALTER TABLE `dich_vu_nha_cung_cap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diem_checkin`
--
ALTER TABLE `diem_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hieu_suat_hdv`
--
ALTER TABLE `hieu_suat_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotel_room_assignment`
--
ALTER TABLE `hotel_room_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `khach_hang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lich_lam_viec_hdv`
--
ALTER TABLE `lich_lam_viec_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lich_su_yeu_cau`
--
ALTER TABLE `lich_su_yeu_cau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `nhan_su`
--
ALTER TABLE `nhan_su`
  MODIFY `nhan_su_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  MODIFY `id_nha_cung_cap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `phan_bo_history`
--
ALTER TABLE `phan_bo_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phan_bo_nhan_su`
--
ALTER TABLE `phan_bo_nhan_su`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `phan_hoi_danh_gia`
--
ALTER TABLE `phan_hoi_danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thong_bao_hdv`
--
ALTER TABLE `thong_bao_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tour_checkin`
--
ALTER TABLE `tour_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD CONSTRAINT `booking_history_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_history_ibfk_2` FOREIGN KEY (`nguoi_thay_doi_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `checkin_khach`
--
ALTER TABLE `checkin_khach`
  ADD CONSTRAINT `checkin_khach_ibfk_1` FOREIGN KEY (`diem_checkin_id`) REFERENCES `diem_checkin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkin_khach_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkin_khach_ibfk_3` FOREIGN KEY (`nguoi_checkin_id`) REFERENCES `nhan_su` (`nhan_su_id`);

--
-- Constraints for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  ADD CONSTRAINT `chung_chi_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `danh_gia_hdv`
--
ALTER TABLE `danh_gia_hdv`
  ADD CONSTRAINT `danh_gia_hdv_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_hdv_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `danh_gia_hdv_ibfk_3` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE SET NULL;

--
-- Constraints for table `dich_vu_nha_cung_cap`
--
ALTER TABLE `dich_vu_nha_cung_cap`
  ADD CONSTRAINT `fk_dv_ncc` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE CASCADE;

--
-- Constraints for table `diem_checkin`
--
ALTER TABLE `diem_checkin`
  ADD CONSTRAINT `diem_checkin_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  ADD CONSTRAINT `giao_dich_tai_chinh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `hieu_suat_hdv`
--
ALTER TABLE `hieu_suat_hdv`
  ADD CONSTRAINT `hieu_suat_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  ADD CONSTRAINT `hinh_anh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_room_assignment`
--
ALTER TABLE `hotel_room_assignment`
  ADD CONSTRAINT `hotel_room_assignment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_room_assignment_ibfk_2` FOREIGN KEY (`checkin_id`) REFERENCES `tour_checkin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `khach_hang`
--
ALTER TABLE `khach_hang`
  ADD CONSTRAINT `khach_hang_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  ADD CONSTRAINT `lich_khoi_hanh_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_khoi_hanh_ibfk_2` FOREIGN KEY (`hdv_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE SET NULL;

--
-- Constraints for table `lich_lam_viec_hdv`
--
ALTER TABLE `lich_lam_viec_hdv`
  ADD CONSTRAINT `lich_lam_viec_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_lam_viec_hdv_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lich_lam_viec_hdv_ibfk_3` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lich_su_yeu_cau`
--
ALTER TABLE `lich_su_yeu_cau`
  ADD CONSTRAINT `lich_su_yeu_cau_ibfk_1` FOREIGN KEY (`yeu_cau_id`) REFERENCES `yeu_cau_dac_biet` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lich_su_yeu_cau_ibfk_2` FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung` (`id`);

--
-- Constraints for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  ADD CONSTRAINT `lich_trinh_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `nhan_su`
--
ALTER TABLE `nhan_su`
  ADD CONSTRAINT `nhan_su_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  ADD CONSTRAINT `nhat_ky_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `nhat_ky_tour_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  ADD CONSTRAINT `nha_cung_cap_ibfk_1` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  ADD CONSTRAINT `phan_bo_dich_vu_ibfk_1` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phan_bo_dich_vu_ibfk_2` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE SET NULL;

--
-- Constraints for table `phan_bo_history`
--
ALTER TABLE `phan_bo_history`
  ADD CONSTRAINT `phan_bo_history_ibfk_1` FOREIGN KEY (`nguoi_thay_doi_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phan_bo_nhan_su`
--
ALTER TABLE `phan_bo_nhan_su`
  ADD CONSTRAINT `phan_bo_nhan_su_ibfk_1` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phan_bo_nhan_su_ibfk_2` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_hoi_danh_gia`
--
ALTER TABLE `phan_hoi_danh_gia`
  ADD CONSTRAINT `phan_hoi_danh_gia_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `phan_hoi_danh_gia_ibfk_2` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thong_bao_hdv`
--
ALTER TABLE `thong_bao_hdv`
  ADD CONSTRAINT `thong_bao_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `tour`
--
ALTER TABLE `tour`
  ADD CONSTRAINT `tour_ibfk_1` FOREIGN KEY (`id_nha_cung_cap`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE SET NULL,
  ADD CONSTRAINT `tour_ibfk_2` FOREIGN KEY (`tao_boi`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_checkin`
--
ALTER TABLE `tour_checkin`
  ADD CONSTRAINT `tour_checkin_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_checkin_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE;

--
-- Constraints for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_2` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`),
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_3` FOREIGN KEY (`nguoi_xu_ly_id`) REFERENCES `nhan_su` (`nhan_su_id`);
COMMIT;


CREATE TABLE thong_bao (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nguoi_nhan_id INT NOT NULL,
  tieu_de VARCHAR(255) NOT NULL,
  noi_dung TEXT,
  da_doc TINYINT(1) DEFAULT 0,
  ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Đã xóa bảng danh_sach_khach theo yêu cầu

ALTER TABLE booking ADD COLUMN lich_khoi_hanh_id INT DEFAULT NULL AFTER tour_id;
UPDATE booking SET lich_khoi_hanh_id = [ID_LICH_KHOI_HANH] WHERE booking_id = [ID_BOOKING];


ALTER TABLE lich_khoi_hanh ADD COLUMN gio_khoi_hanh TIME DEFAULT NULL AFTER ngay_khoi_hanh;
ALTER TABLE lich_khoi_hanh ADD COLUMN dia_diem_tap_trung VARCHAR(255) DEFAULT NULL AFTER ngay_khoi_hanh;

<<<<<<< HEAD

-- Bổ sung bảng du_toan_tour nếu chưa có
CREATE TABLE IF NOT EXISTS du_toan_tour (
  du_toan_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  ten_tour VARCHAR(255) NOT NULL,
  tong_du_toan DECIMAL(15,2) NOT NULL,
  ngay_tao DATE,
  ghi_chu TEXT,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bổ sung bảng chi_phi_thuc_te nếu chưa có
CREATE TABLE IF NOT EXISTS chi_phi_thuc_te (
  chi_phi_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  du_toan_id INT,
  loai_chi_phi VARCHAR(100) NOT NULL,
  ten_khoan_chi VARCHAR(255) NOT NULL,
  so_tien DECIMAL(15,2) NOT NULL,
  ngay_phat_sinh DATE NOT NULL,
  mo_ta TEXT,
  chung_tu VARCHAR(255),
  ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (du_toan_id) REFERENCES du_toan_tour(du_toan_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE du_toan_chi_tiet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    du_toan_id INT NOT NULL,
    tour_id INT NOT NULL,
    loai_chi_phi VARCHAR(50) NOT NULL,
    ten_khoan_chi VARCHAR(255) NOT NULL,
    so_tien DECIMAL(15,2) NOT NULL,
    ghi_chu TEXT,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (du_toan_id) REFERENCES du_toan_tour(du_toan_id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO giao_dich_tai_chinh (id, tour_id, loai, so_tien, ngay_giao_dich, mo_ta) VALUES
(1, 4, 'Thu', 8500000, '2025-11-05', 'Khách thanh toán Bangkok - Pattaya'),
(2, 4, 'Chi', 5000000, '2025-11-06', 'Chi phí ăn uống Bangkok'),
(3, 5, 'Thu', 32990000, '2025-11-10', 'Khách thanh toán NAGOYA – PHÚ SĨ – TOKYO'),
(4, 5, 'Chi', 20000000, '2025-11-11', 'Chi phí khách sạn Nhật'),
(5, 5, 'Chi', 8000000, '2025-11-12', 'Chi phí xe Nhật');

SELECT * FROM tour;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


