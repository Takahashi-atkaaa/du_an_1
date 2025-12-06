-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 02, 2025 at 03:45 PM
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
  `ngay_ket_thuc` date DEFAULT NULL,
  `so_nguoi` int(11) DEFAULT NULL,
  `tong_tien` decimal(15,2) DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaCoc','HoanTat','Huy') DEFAULT NULL,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `tour_id`, `khach_hang_id`, `ngay_dat`, `ngay_khoi_hanh`, `ngay_ket_thuc`, `so_nguoi`, `tong_tien`, `trang_thai`, `ghi_chu`) VALUES
(10, 6, 5, '2025-12-01', '2025-12-03', '2025-12-05', 10, 329900000.00, 'DaCoc', ''),
(11, 6, 5, '2025-12-01', '2025-12-03', '2025-12-06', 10, 329900000.00, 'DaCoc', '');

-- --------------------------------------------------------

--
-- Table structure for table `booking_deletion_history`
--

CREATE TABLE `booking_deletion_history` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL COMMENT 'ID booking đã bị xóa',
  `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour',
  `khach_hang_id` int(11) DEFAULT NULL COMMENT 'ID khách hàng',
  `nguoi_xoa_id` int(11) DEFAULT NULL COMMENT 'ID người dùng đã xóa',
  `ly_do_xoa` text DEFAULT NULL COMMENT 'Lý do xóa',
  `thong_tin_booking` text DEFAULT NULL COMMENT 'Thông tin booking dạng JSON',
  `thoi_gian_xoa` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian xóa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử xóa booking';

--
-- Dumping data for table `booking_deletion_history`
--

INSERT INTO `booking_deletion_history` (`id`, `booking_id`, `tour_id`, `khach_hang_id`, `nguoi_xoa_id`, `ly_do_xoa`, `thong_tin_booking`, `thoi_gian_xoa`) VALUES
(1, 8, 5, 5, 5, '', '{\"booking_id\":8,\"tour_id\":5,\"ten_tour\":\" NAGOYA – PHÚ SĨ – TOKYO\",\"khach_hang_id\":5,\"ten_khach_hang\":\"tung anh\",\"so_nguoi\":12,\"tong_tien\":\"329900000.00\",\"ngay_dat\":\"2025-12-01\",\"ngay_khoi_hanh\":\"2025-12-02\",\"ngay_ket_thuc\":\"2025-12-04\",\"trang_thai\":\"ChoXacNhan\",\"ghi_chu\":\"bê đê\"}', '2025-11-30 19:27:26'),
(2, 9, 5, 5, 5, '', '{\"booking_id\":9,\"tour_id\":5,\"ten_tour\":\" NAGOYA – PHÚ SĨ – TOKYO\",\"khach_hang_id\":5,\"ten_khach_hang\":\"tung anh\",\"so_nguoi\":10,\"tong_tien\":\"329900000.00\",\"ngay_dat\":\"2025-12-01\",\"ngay_khoi_hanh\":\"2025-12-02\",\"ngay_ket_thuc\":\"2025-12-05\",\"trang_thai\":\"Huy\",\"ghi_chu\":\"ăn thịt chó\"}', '2025-11-30 22:15:13'),
(3, 7, 6, 5, 5, '', '{\"booking_id\":7,\"tour_id\":6,\"ten_tour\":\" NAGOYA – PHÚ SĨ – TOKYO (Bản sao)\",\"khach_hang_id\":5,\"ten_khach_hang\":\"tung anh\",\"so_nguoi\":10,\"tong_tien\":\"329900000.00\",\"ngay_dat\":\"2025-11-27\",\"ngay_khoi_hanh\":\"2025-11-28\",\"ngay_ket_thuc\":\"2025-11-28\",\"trang_thai\":\"Huy\",\"ghi_chu\":\"sâsasasaasas | Công ty\\/Tổ chức: sdfsdf\"}', '2025-12-02 04:15:20');

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
(9, 11, 'ChoXacNhan', 'DaCoc', 5, '', '2025-12-02 04:14:41'),
(10, 10, 'ChoXacNhan', 'DaCoc', 5, 'cọc 50 tr', '2025-12-02 04:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `booking_khach_hang`
--

CREATE TABLE `booking_khach_hang` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `khach_hang_id` int(11) NOT NULL,
  `diem_danh` enum('co_mat','vang_mat') DEFAULT 'co_mat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `chi_phi_thuc_te`
--

CREATE TABLE `chi_phi_thuc_te` (
  `chi_phi_id` int(11) NOT NULL,
  `du_toan_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) DEFAULT NULL,
  `loai_chi_phi` enum('PhuongTien','LuuTru','VeThamQuan','AnUong','HuongDanVien','DichVuBoSung','PhatSinh') NOT NULL,
  `ten_khoan_chi` varchar(255) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `ngay_phat_sinh` date NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `chung_tu` varchar(255) DEFAULT NULL,
  `trang_thai` enum('ChoXacNhan','DaDuyet','TuChoi') DEFAULT 'ChoXacNhan',
  `nguoi_ghi_nhan_id` int(11) NOT NULL,
  `nguoi_duyet_id` int(11) DEFAULT NULL,
  `ngay_duyet` datetime DEFAULT NULL,
  `ly_do_tu_choi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chi_phi_thuc_te`
--

INSERT INTO `chi_phi_thuc_te` (`chi_phi_id`, `du_toan_id`, `tour_id`, `lich_khoi_hanh_id`, `loai_chi_phi`, `ten_khoan_chi`, `so_tien`, `ngay_phat_sinh`, `mo_ta`, `chung_tu`, `trang_thai`, `nguoi_ghi_nhan_id`, `nguoi_duyet_id`, `ngay_duyet`, `ly_do_tu_choi`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 10, 'PhuongTien', 'Thanh toán vé máy bay', 92000000.00, '2025-11-26', 'Thanh toán đợt 1 cho hãng bay', NULL, 'DaDuyet', 5, 5, '2025-11-27 09:00:00', NULL, '2025-11-27 09:00:00', '2025-11-27 09:00:00'),
(2, 1, 5, 10, 'LuuTru', 'Cọc khách sạn Tokyo', 46000000.00, '2025-11-27', 'Khách sạn 4 sao trung tâm', NULL, 'ChoXacNhan', 5, NULL, NULL, NULL, '2025-11-27 10:00:00', '2025-11-27 10:00:00');

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
-- Table structure for table `cong_no_hdv`
--

CREATE TABLE `cong_no_hdv` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `hdv_id` int(11) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `loai_cong_no` enum('TamUng','QuyetToan','ThuHoi') DEFAULT 'TamUng',
  `han_thanh_toan` date DEFAULT NULL,
  `trang_thai` enum('ChoDuyet','DaThanhToan','QuaHan') DEFAULT 'ChoDuyet',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cong_no_hdv`
--

INSERT INTO `cong_no_hdv` (`id`, `tour_id`, `hdv_id`, `so_tien`, `loai_cong_no`, `han_thanh_toan`, `trang_thai`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 5, 2, 15000000.00, 'TamUng', '2025-12-10', 'ChoDuyet', 'Tạm ứng tour Nhật tháng 12', '2025-11-27 04:00:00', '2025-11-27 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cong_no_nha_cung_cap`
--

CREATE TABLE `cong_no_nha_cung_cap` (
  `id` int(11) NOT NULL,
  `nha_cung_cap_id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `han_thanh_toan` date DEFAULT NULL,
  `trang_thai` enum('ChoThanhToan','DaThanhToan','QuaHan') DEFAULT 'ChoThanhToan',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cong_no_nha_cung_cap`
--

INSERT INTO `cong_no_nha_cung_cap` (`id`, `nha_cung_cap_id`, `tour_id`, `so_tien`, `han_thanh_toan`, `trang_thai`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(1, 2, 5, 30000000.00, '2025-12-05', 'ChoThanhToan', 'Công nợ khách sạn tour Nhật', '2025-11-27 05:00:00', '2025-11-27 05:00:00');

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

--
-- Dumping data for table `diem_checkin`
--

INSERT INTO `diem_checkin` (`id`, `tour_id`, `ten_diem`, `loai_diem`, `thoi_gian_du_kien`, `ghi_chu`, `thu_tu`, `ngay_tao`) VALUES
(6, 5, 'sân bay nội bài', 'tap_trung', '2025-12-02 08:35:00', 'đủ', 1, '2025-12-01 08:36:07');

-- --------------------------------------------------------

--
-- Table structure for table `du_toan_chi_tiet`
--

CREATE TABLE `du_toan_chi_tiet` (
  `id` int(11) NOT NULL,
  `du_toan_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `loai_chi_phi` varchar(50) NOT NULL,
  `ten_khoan_chi` varchar(255) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `ghi_chu` text DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `du_toan_tour`
--

CREATE TABLE `du_toan_tour` (
  `du_toan_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `lich_khoi_hanh_id` int(11) DEFAULT NULL,
  `cp_phuong_tien` decimal(15,2) DEFAULT 0.00,
  `mo_ta_phuong_tien` text DEFAULT NULL,
  `cp_luu_tru` decimal(15,2) DEFAULT 0.00,
  `mo_ta_luu_tru` text DEFAULT NULL,
  `cp_ve_tham_quan` decimal(15,2) DEFAULT 0.00,
  `mo_ta_ve_tham_quan` text DEFAULT NULL,
  `cp_an_uong` decimal(15,2) DEFAULT 0.00,
  `mo_ta_an_uong` text DEFAULT NULL,
  `cp_huong_dan_vien` decimal(15,2) DEFAULT 0.00,
  `cp_dich_vu_bo_sung` decimal(15,2) DEFAULT 0.00,
  `mo_ta_dich_vu` text DEFAULT NULL,
  `cp_phat_sinh_du_kien` decimal(15,2) DEFAULT 0.00,
  `mo_ta_phat_sinh` text DEFAULT NULL,
  `nguoi_tao_id` int(11) DEFAULT NULL,
  `ngay_tao` timestamp NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tong_du_toan` decimal(15,2) GENERATED ALWAYS AS (coalesce(`cp_phuong_tien`,0) + coalesce(`cp_luu_tru`,0) + coalesce(`cp_ve_tham_quan`,0) + coalesce(`cp_an_uong`,0) + coalesce(`cp_huong_dan_vien`,0) + coalesce(`cp_dich_vu_bo_sung`,0) + coalesce(`cp_phat_sinh_du_kien`,0)) STORED,
  `ghi_chu` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `du_toan_tour`
--

INSERT INTO `du_toan_tour` (`du_toan_id`, `tour_id`, `lich_khoi_hanh_id`, `cp_phuong_tien`, `mo_ta_phuong_tien`, `cp_luu_tru`, `mo_ta_luu_tru`, `cp_ve_tham_quan`, `mo_ta_ve_tham_quan`, `cp_an_uong`, `mo_ta_an_uong`, `cp_huong_dan_vien`, `cp_dich_vu_bo_sung`, `mo_ta_dich_vu`, `cp_phat_sinh_du_kien`, `mo_ta_phat_sinh`, `nguoi_tao_id`, `ngay_tao`, `ngay_cap_nhat`, `ghi_chu`) VALUES
(1, 5, 10, 90000000.00, 'Vé máy bay, xe di chuyển', 45000000.00, 'Khách sạn 4 sao', 20000000.00, 'Vé tham quan Phú Sĩ', 15000000.00, 'Ăn uống nhà hàng Nhật', 8000000.00, 5000000.00, 'Bảo hiểm, visa', 6000000.00, 'Quỹ dự phòng ẩn chi phí', 5, '2025-11-25 10:00:00', '2025-11-25 10:00:00', NULL),
(2, 7, NULL, 15000000.00, 'Xe giường nằm', 8000000.00, 'Khách sạn Đà Lạt', 4000000.00, 'Vé tham quan combo', 3000000.00, 'Ẩm thực địa phương', 2000000.00, 1500000.00, 'Nâng cấp dịch vụ', 1000000.00, 'Chi phí phát sinh dự kiến', 5, '2025-11-26 08:00:00', '2025-11-26 08:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `giao_dich_tai_chinh`
--

CREATE TABLE `giao_dich_tai_chinh` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `khach_hang_id` int(11) DEFAULT NULL,
  `loai` enum('Thu','Chi') DEFAULT NULL,
  `loai_doi_tuong` enum('KhachHang','NhaCungCap','HDV','Khac') DEFAULT 'Khac',
  `doi_tuong_id` int(11) DEFAULT NULL,
  `loai_giao_dich` enum('Booking','ThanhToan','ChiPhi','HoanTien','DieuChinh','Khac') DEFAULT 'Khac',
  `so_tien` decimal(15,2) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `nguoi_thuc_hien_id` int(11) DEFAULT NULL,
  `nguoi_thuc_hien` varchar(255) DEFAULT NULL,
  `ngay_giao_dich` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giao_dich_tai_chinh`
--

INSERT INTO `giao_dich_tai_chinh` (`id`, `tour_id`, `booking_id`, `khach_hang_id`, `loai`, `loai_doi_tuong`, `doi_tuong_id`, `loai_giao_dich`, `so_tien`, `mo_ta`, `nguoi_thuc_hien_id`, `nguoi_thuc_hien`, `ngay_giao_dich`, `created_at`, `updated_at`) VALUES
(1, 5, 10, 5, 'Thu', 'KhachHang', 5, 'Booking', 150000000.00, 'Khách đặt cọc tour Nhật 12/2025', 5, 'Admin', '2025-11-25', '2025-11-25 09:00:00', '2025-11-25 09:00:00'),
(2, 5, NULL, NULL, 'Chi', 'NhaCungCap', 2, 'ChiPhi', 92000000.00, 'Thanh toán vé máy bay', 5, 'Admin', '2025-11-26', '2025-11-26 08:00:00', '2025-11-26 08:00:00'),
(3, 7, NULL, NULL, 'Chi', 'HDV', 2, 'ThanhToan', 15000000.00, 'Tạm ứng HDV tour Đà Lạt', 5, 'Admin', '2025-11-27', '2025-11-27 07:00:00', '2025-11-27 07:00:00'),
(4, 7, NULL, NULL, 'Thu', 'Khac', NULL, 'ThanhToan', 8000000.00, 'Thu hoàn khách trả thêm dịch vụ', 5, 'Admin', '2025-11-28', '2025-11-28 10:00:00', '2025-11-28 10:00:00'),
(5, 5, 11, 5, 'Thu', 'KhachHang', 5, 'Booking', 329900000.00, 'Khách thanh toán phần còn lại', 5, 'Admin', '2025-12-01', '2025-12-01 09:15:00', '2025-12-01 09:15:00');

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
(7, 5, 'public/uploads/tour_images/tour_6925a4cddadde8.80437085.jpeg', ''),
(8, 6, 'public/uploads/tour_images/tour_6925a4cddadde8.80437085.jpeg', ''),
(11, 7, 'public/uploads/tour_images/tour_692d1eb4756f63.76252915.jpeg', 'Vườn hoa Đà Lạt'),
(12, 7, 'public/uploads/tour_images/tour_692d1eb4757c66.17239161.jpeg', 'Thác Datanla'),
(14, 8, 'public/uploads/tour_images/tour_6925a4cddadde8.80437085.jpeg', '');

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
  `nguoi_dung_id` int(11) DEFAULT NULL,
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
(4, 12, 'dsfdf', 'Nam', '1999-02-12'),
(5, 13, NULL, NULL, NULL),
(6, 14, NULL, NULL, NULL);

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
(9, 5, '2025-12-02', NULL, '2025-12-05', NULL, '', 50, NULL, 'SapKhoiHanh', 'Tạo tự động từ booking #9'),
(10, 6, '2025-12-02', '07:00:00', '2025-12-05', '17:00:00', '', 50, NULL, 'SapKhoiHanh', 'Tạo tự động từ booking #10');

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
-- Table structure for table `lich_su_khach_hang`
--

CREATE TABLE `lich_su_khach_hang` (
  `id` int(11) NOT NULL,
  `khach_hang_id` int(11) NOT NULL,
  `loai_hoat_dong` enum('Booking','ThanhToan','TuVan','GuiUuDai','NhacLich','GhiChu','Khac') DEFAULT 'Khac',
  `noi_dung` text NOT NULL,
  `nguoi_tao_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_khach_hang`
--

INSERT INTO `lich_su_khach_hang` (`id`, `khach_hang_id`, `loai_hoat_dong`, `noi_dung`, `nguoi_tao_id`, `created_at`, `updated_at`) VALUES
(1, 5, 'Booking', 'Khách đặt tour Nhật Bản tháng 12', 5, '2025-11-25 08:30:00', '2025-11-25 08:30:00'),
(2, 5, 'ThanhToan', 'Khách thanh toán thêm cho tour', 5, '2025-12-01 09:15:00', '2025-12-01 09:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_thanh_toan_hdv`
--

CREATE TABLE `lich_su_thanh_toan_hdv` (
  `id` int(11) NOT NULL,
  `cong_no_hdv_id` int(11) NOT NULL,
  `ngay_thanh_toan` date NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `phuong_thuc` enum('TienMat','ChuyenKhoan','Khac') DEFAULT 'TienMat',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_thanh_toan_hdv`
--

INSERT INTO `lich_su_thanh_toan_hdv` (`id`, `cong_no_hdv_id`, `ngay_thanh_toan`, `so_tien`, `phuong_thuc`, `ghi_chu`, `created_at`) VALUES
(1, 1, '2025-11-28', 5000000.00, 'ChuyenKhoan', 'Giải ngân đợt 1', '2025-11-28 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_thanh_toan_ncc`
--

CREATE TABLE `lich_su_thanh_toan_ncc` (
  `id` int(11) NOT NULL,
  `cong_no_ncc_id` int(11) NOT NULL,
  `ngay_thanh_toan` date NOT NULL,
  `so_tien_thanh_toan` decimal(15,2) NOT NULL,
  `phuong_thuc` enum('TienMat','ChuyenKhoan','Khac') DEFAULT 'ChuyenKhoan',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lich_su_thanh_toan_ncc`
--

INSERT INTO `lich_su_thanh_toan_ncc` (`id`, `cong_no_ncc_id`, `ngay_thanh_toan`, `so_tien_thanh_toan`, `phuong_thuc`, `ghi_chu`, `created_at`) VALUES
(1, 1, '2025-11-30', 10000000.00, 'ChuyenKhoan', 'Thanh toán đợt 1 cho khách sạn', '2025-11-30 14:00:00');

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
(15, 5, 5, '📅 Ngày 6: Kyoto – Trà đạo', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nTham gia trải nghiệm trà đạo.\r\n\r\nTham quan rừng tre Arashiyama.\r\n\r\nMua quà lưu niệm tại Nishiki Market.'),
(16, 6, 1, '📅 Ngày 1: Hà Nội – Tokyo (Narita)', '\r\n\r\nHướng dẫn viên đón đoàn tại Nội Bài, làm thủ tục bay sang Nhật.\r\n\r\nHạ cánh tại sân bay Narita, về khách sạn nhận phòng.'),
(17, 6, 2, '📅 Ngày 2: Nagoya – Thành phố cảng', 'Hoạt động:\r\n\r\nTham quan Lâu đài Nagoya.\r\n\r\nKhám phá khu phố Sakae, mua sắm.\r\n\r\nThưởng thức món Tebasaki nổi tiếng.'),
(18, 6, 3, '📅 Ngày 3: Nagoya – Núi Phú Sĩ', 'Địa điểm: Phú Sĩ – Kawaguchiko\r\nHoạt động:\r\n\r\nDi chuyển đến khu vực núi Phú Sĩ.\r\n\r\nTham quan trạm 5 (nếu thời tiết cho phép).\r\n\r\nChụp ảnh hồ Kawaguchi.\r\n\r\nTrải nghiệm tắm onsen.'),
(19, 6, 4, '📅 Ngày 4: Làng cổ Oshino Hakkai', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nChùa Vàng Kinkaku-ji.\r\n\r\nChùa Thanh Thủy Kiyomizu-dera.\r\n\r\nDạo phố Gion – nơi geisha sinh sống.'),
(20, 6, 5, '📅 Ngày 6: Kyoto – Trà đạo', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nTham gia trải nghiệm trà đạo.\r\n\r\nTham quan rừng tre Arashiyama.\r\n\r\nMua quà lưu niệm tại Nishiki Market.'),
(25, 7, 1, 'Sài Gòn - Đà Lạt', 'Khởi hành từ Sài Gòn - Đến Đà Lạt - Tham quan vườn hoa thành phố'),
(26, 7, 2, 'Đà Lạt', 'Tham quan thác Datanla - Vườn hoa - Chợ đêm Đà Lạt'),
(27, 7, 3, 'Đà Lạt', 'Tham quan đồi chè Cầu Đất - Làng hoa Vạn Thành - Nghỉ ngơi'),
(28, 7, 4, 'Đà Lạt - Sài Gòn', 'Tham quan chợ Đà Lạt - Trở về Sài Gòn - Kết thúc tour'),
(34, 8, 1, '📅 Ngày 1: Hà Nội – Tokyo (Narita)', '\r\nHướng dẫn viên đón đoàn tại Nội Bài, làm thủ tục bay sang Nhật.\r\n\r\nHạ cánh tại sân bay Narita, về khách sạn nhận phòng.'),
(35, 8, 2, '📅 Ngày 2: Nagoya – Thành phố cảng', 'Hoạt động:\r\n\r\nTham quan Lâu đài Nagoya.\r\n\r\nKhám phá khu phố Sakae, mua sắm.\r\n\r\nThưởng thức món Tebasaki nổi tiếng.'),
(36, 8, 3, '📅 Ngày 3: Nagoya – Núi Phú Sĩ', 'Địa điểm: Phú Sĩ – Kawaguchiko\r\nHoạt động:\r\n\r\nDi chuyển đến khu vực núi Phú Sĩ.\r\n\r\nTham quan trạm 5 (nếu thời tiết cho phép).\r\n\r\nChụp ảnh hồ Kawaguchi.\r\n\r\nTrải nghiệm tắm onsen.'),
(37, 8, 4, '📅 Ngày 4: Làng cổ Oshino Hakkai', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nChùa Vàng Kinkaku-ji.\r\n\r\nChùa Thanh Thủy Kiyomizu-dera.\r\n\r\nDạo phố Gion – nơi geisha sinh sống.'),
(38, 8, 5, '📅 Ngày 6: Kyoto – Trà đạo', 'Địa điểm: Kyoto\r\nHoạt động:\r\n\r\nTham gia trải nghiệm trà đạo.\r\n\r\nTham quan rừng tre Arashiyama.\r\n\r\nMua quà lưu niệm tại Nishiki Market.');

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
(5, 'admin', 'admin123', 'Quản trị viên hệ thống', NULL, 'admin@tour.com', '0901234567', 'Admin', 1, 'HoatDong', '2025-11-22 05:49:46'),
(6, 'hdv01', 'hdv123', 'Nguyễn Văn Hướng', NULL, 'hdv@tour.com', '0912345678', 'HDV', 0, 'HoatDong', '2025-11-22 05:49:46'),
(7, 'hdv02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Trần Thị Lan', NULL, 'hdv02@tour.com', '0923456789', 'HDV', 0, 'HoatDong', '2025-11-22 05:49:46'),
(8, 'khach01', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lê Văn An', NULL, 'khach01@email.com', '0934567890', 'KhachHang', 0, 'HoatDong', '2025-11-22 05:49:46'),
(9, 'khach02', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Phạm Thị Bình', NULL, 'khach02@email.com', '0945678901', 'KhachHang', 0, 'HoatDong', '2025-11-22 05:49:46'),
(10, 'ncc01', 'ncc123', 'Công ty ABC Travel', NULL, 'ncc01@tour.com', '0956789012', 'NhaCungCap', 0, 'HoatDong', '2025-11-22 05:49:46'),
(11, 'ncc02', '$2y$10$LwBeRXp5X5mPuzoaG1iDsu.zCDObUWlPY5kIHGpFkHf4MoOpdlwoO', 'Khách sạn XYZ', NULL, 'ncc02@tour.com', '0967890123', 'NhaCungCap', 0, 'HoatDong', '2025-11-22 05:49:46'),
(12, 'test100@gmail.com', 'khach123', 'hdv33', NULL, 'test100@gmail.com', '12312321', 'KhachHang', 0, 'HoatDong', '2025-11-21 23:53:25'),
(13, 'vana@gmail.com', '$2y$10$NXbFI2lrPgI2L4kQVOpRh.qMhRxkFQegdHpWM9Kdg5ddVaeqcRC42', 'tung anh', NULL, 'vana@gmail.com', '43435', 'HDV', 0, 'HoatDong', '2025-11-25 20:38:21'),
(14, 'thaichimto1@gmail.com', '$2y$10$2Perginy3C2qDTKmS0DUWu7/HDDeQZUdNHPGwDAL2.qhZHYRfGO2O', 'aaaaa', NULL, 'thaichimto1@gmail.com', '43435', 'KhachHang', 0, 'HoatDong', '2025-11-27 01:09:25');

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
(3, 7, 'HDV', 'QuocTe', 'Đông Nam Á, Châu Âu', 4.75, 20, 'SanSang', 'Chứng chỉ HDV quốc tế, IELTS 7.0', 'Tiếng Việt, Tiếng Anh, Tiếng Thái', '7 năm kinh nghiệm dẫn tour quốc tế', 'Tốt'),
(4, 13, 'HDV', 'TongHop', NULL, 0.00, 0, 'SanSang', 'Có chứng chỉ', 'Anh Trung Nhật Hàn', '100 năm làm HDV', 'Tốt');

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
  `thoi_tiet` varchar(255) DEFAULT NULL,
  `hinh_anh` text DEFAULT NULL COMMENT 'JSON array chứa đường dẫn hình ảnh',
  `ngay_ghi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhat_ky_tour`
--

INSERT INTO `nhat_ky_tour` (`id`, `tour_id`, `nhan_su_id`, `loai_nhat_ky`, `tieu_de`, `noi_dung`, `cach_xu_ly`, `thoi_tiet`, `hinh_anh`, `ngay_ghi`) VALUES
(5, 4, 2, 'hanh_trinh', 'dsfds', 'fdsfdsfd', '', NULL, NULL, '2025-11-25 00:00:00'),
(6, 4, 2, 'su_co', 'á', 'aaaaa', '', NULL, NULL, '2025-11-25 00:00:00'),
(7, 3, 2, 'su_co', 'xzzx', 'xzxzx', 'xzxz', NULL, NULL, '2025-11-25 00:00:00');

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
(3, 11, 'Khách sạn XYZ', 'KhachSan', '321 Đường Lý Tự Trọng, Quận 1, TP.HCM', '0287654321', 'Khách sạn 5 sao tại trung tâm thành phố', 4.8),
(4, NULL, 'dsds', 'Khac', 'wfwdfds', 'fsf', '', NULL);

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
(16, 10, 3, 'VeMayBay', 'vé máy bay', 1, 'vé', NULL, NULL, NULL, NULL, NULL, 1500000.00, '', 'DaXacNhan', '2025-12-01 09:30:22', '2025-12-01 02:29:50', '2025-12-01 02:30:22');

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
(12, 10, 2, 'HDV', '', 'DaXacNhan', '2025-12-01 03:15:38', '2025-12-01 02:14:12'),
(13, 10, 2, 'HDV', '', 'DaXacNhan', '2025-12-01 03:25:40', '2025-12-01 02:24:40');

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
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `id` int(11) NOT NULL,
  `tieu_de` varchar(255) NOT NULL,
  `noi_dung` text NOT NULL,
  `loai_thong_bao` enum('ChungChung','HDV','KhachHang','NhanSu') DEFAULT 'ChungChung',
  `muc_do_uu_tien` enum('Thap','TrungBinh','Cao') DEFAULT 'TrungBinh',
  `nguoi_gui_id` int(11) DEFAULT NULL,
  `nguoi_nhan_id` int(11) DEFAULT NULL,
  `vai_tro_nhan` varchar(50) DEFAULT NULL,
  `trang_thai` enum('ChuaGui','DaGui','Loi') DEFAULT 'ChuaGui',
  `thoi_gian_gui` datetime DEFAULT NULL,
  `thoi_gian_hen_gui` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao_doc`
--

CREATE TABLE `thong_bao_doc` (
  `thong_bao_id` int(11) NOT NULL,
  `nguoi_dung_id` int(11) NOT NULL,
  `da_doc` tinyint(1) NOT NULL DEFAULT 0,
  `thoi_gian_doc` datetime DEFAULT NULL
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
(5, ' NAGOYA – PHÚ SĨ – TOKYO', 'QuocTe', 'Tham quan những danh thắng nổi tiếng là biểu tượng của đất nước Mặt trời mọc: núi Phú Sĩ, làng cổ Oshino Hakkai, Chùa Asakusa Kannon, Chùa\r\nThanh Thuỷ (di sản văn hoá UNESCO)…\r\nTrải nghiệm một chặng tàu siêu tốc Shinkansen – niềm tự hào của người Nhật\r\nThưởng thức món bò Kobe trứ danh.\r\nTrải nghiệm tắm onsen phục hồi sức khoẻ tại chân núi Phú Sĩ\r\nTặng một bữa ăn có món bò Kobe/Wagyu trứ danh Nhật Bản\r\nTặng trải nghiệm cua tuyết và kem matcha phủ vàng tại Phú Sĩ.\r\nTặng trải nghiệm mặc trang phục truyền thống, check in tại Cố đô Kyoto\r\nThưởng thức Geisha Show – màn trình diễn tinh tế kết hợp âm nhạc, vũ đạo và nghệ thuật trà đạo, tôn vinh vẻ đẹp truyền thống Nhật Bản.\r\n', 32990000.00, NULL, NULL, 5, 'HoatDong', 'public/uploads/qr/tour_5_1764087033.png'),
(6, ' NAGOYA – PHÚ SĨ – TOKYO (Bản sao)', 'QuocTe', 'Tham quan những danh thắng nổi tiếng là biểu tượng của đất nước Mặt trời mọc: núi Phú Sĩ, làng cổ Oshino Hakkai, Chùa Asakusa Kannon, Chùa\r\nThanh Thuỷ (di sản văn hoá UNESCO)…\r\nTrải nghiệm một chặng tàu siêu tốc Shinkansen – niềm tự hào của người Nhật\r\nThưởng thức món bò Kobe trứ danh.\r\nTrải nghiệm tắm onsen phục hồi sức khoẻ tại chân núi Phú Sĩ\r\nTặng một bữa ăn có món bò Kobe/Wagyu trứ danh Nhật Bản\r\nTặng trải nghiệm cua tuyết và kem matcha phủ vàng tại Phú Sĩ.\r\nTặng trải nghiệm mặc trang phục truyền thống, check in tại Cố đô Kyoto\r\nThưởng thức Geisha Show – màn trình diễn tinh tế kết hợp âm nhạc, vũ đạo và nghệ thuật trà đạo, tôn vinh vẻ đẹp truyền thống Nhật Bản.\r\n', 32990000.00, NULL, NULL, 5, 'HoatDong', 'public/uploads/qr/tour_6_1764124071.png'),
(7, 'Sài Gòn - Đà Lạt 4N3Đ (Bản sao)', 'TrongNuoc', 'Tham quan thành phố ngàn hoa, vườn hoa, thác nước, đồi chè', 4200000.00, NULL, NULL, 5, 'HoatDong', NULL),
(8, ' NAGOYA – PHÚ SĨ – TOKYO (Bản sao) (Bản sao)', 'QuocTe', 'Tham quan những danh thắng nổi tiếng là biểu tượng của đất nước Mặt trời mọc: núi Phú Sĩ, làng cổ Oshino Hakkai, Chùa Asakusa Kannon, Chùa\r\nThanh Thuỷ (di sản văn hoá UNESCO)…\r\nTrải nghiệm một chặng tàu siêu tốc Shinkansen – niềm tự hào của người Nhật\r\nThưởng thức món bò Kobe trứ danh.\r\nTrải nghiệm tắm onsen phục hồi sức khoẻ tại chân núi Phú Sĩ\r\nTặng một bữa ăn có món bò Kobe/Wagyu trứ danh Nhật Bản\r\nTặng trải nghiệm cua tuyết và kem matcha phủ vàng tại Phú Sĩ.\r\nTặng trải nghiệm mặc trang phục truyền thống, check in tại Cố đô Kyoto\r\nThưởng thức Geisha Show – màn trình diễn tinh tế kết hợp âm nhạc, vũ đạo và nghệ thuật trà đạo, tôn vinh vẻ đẹp truyền thống Nhật Bản.\r\n', 32990000.00, NULL, NULL, 5, 'HoatDong', 'public/uploads/qr/tour_8_1764675272.png');

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

--
-- Dumping data for table `tour_checkin`
--

INSERT INTO `tour_checkin` (`id`, `booking_id`, `khach_hang_id`, `lich_khoi_hanh_id`, `ho_ten`, `so_cmnd`, `so_passport`, `ngay_sinh`, `gioi_tinh`, `quoc_tich`, `dia_chi`, `so_dien_thoai`, `email`, `checkin_time`, `checkout_time`, `trang_thai`, `ghi_chu`, `created_at`, `updated_at`) VALUES
(5, 10, 5, 10, 'tung anh', '2222', '2222', '1999-11-11', 'Nam', 'Việt Nam', 'wfwdfds', '43435', 'dasriiccds2@outlook.com', NULL, NULL, 'ChuaCheckIn', 'dsd', '2025-12-01 02:14:42', '2025-12-01 02:14:42'),
(6, 10, 5, 10, 'tung anh', '2222', '22222', '1989-12-12', 'Nam', 'Việt Nam', '', '', '', NULL, NULL, 'ChuaCheckIn', '', '2025-12-01 02:15:02', '2025-12-01 02:15:02'),
(7, 11, 5, 10, 'hdv33', '222222', '22222', '1989-12-19', 'Nam', 'Việt Nam', '', '343', 'test10210@gmail.com', NULL, NULL, 'ChuaCheckIn', '', '2025-12-01 02:25:11', '2025-12-01 02:25:11');

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
-- Stand-in structure for view `v_so_sanh_du_toan_thuc_te`
-- (See below for the actual view)
--
CREATE TABLE `v_so_sanh_du_toan_thuc_te` (
`du_toan_id` int(11)
,`tour_id` int(11)
,`ten_tour` varchar(255)
,`tong_du_toan` decimal(15,2)
,`tong_thuc_te` decimal(37,2)
,`chenh_lech` decimal(38,2)
,`canh_bao` varchar(10)
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
(8, 10, 'khac', 'Yêu cầu đặc biệt', 'dị ứng hải sản', 'trung_binh', 'moi', NULL, NULL, NULL, '2025-12-01 09:13:41', '2025-12-01 09:13:41'),
(9, 11, 'khac', 'Yêu cầu đặc biệt', 'mới đi tù về', 'trung_binh', 'moi', NULL, NULL, NULL, '2025-12-01 09:24:24', '2025-12-01 09:24:24');

-- --------------------------------------------------------

--
-- Structure for view `v_hdv_san_sang`
--
DROP TABLE IF EXISTS `v_hdv_san_sang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_hdv_san_sang`  AS SELECT `ns`.`nhan_su_id` AS `nhan_su_id`, `nd`.`ho_ten` AS `ho_ten`, `nd`.`email` AS `email`, `nd`.`so_dien_thoai` AS `so_dien_thoai`, `ns`.`loai_hdv` AS `loai_hdv`, `ns`.`chuyen_tuyen` AS `chuyen_tuyen`, `ns`.`danh_gia_tb` AS `danh_gia_tb`, `ns`.`so_tour_da_dan` AS `so_tour_da_dan`, `ns`.`ngon_ngu` AS `ngon_ngu` FROM (`nhan_su` `ns` join `nguoi_dung` `nd` on(`ns`.`nguoi_dung_id` = `nd`.`id`)) WHERE `ns`.`vai_tro` = 'HDV' AND `ns`.`trang_thai_lam_viec` = 'SanSang' AND `ns`.`nhan_su_id` in (select `lich_lam_viec_hdv`.`nhan_su_id` from `lich_lam_viec_hdv` where `lich_lam_viec_hdv`.`trang_thai` in ('DuKien','XacNhan') AND curdate() between `lich_lam_viec_hdv`.`ngay_bat_dau` and `lich_lam_viec_hdv`.`ngay_ket_thuc`) is false ;

-- --------------------------------------------------------

--
-- Structure for view `v_so_sanh_du_toan_thuc_te`
--
DROP TABLE IF EXISTS `v_so_sanh_du_toan_thuc_te`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_so_sanh_du_toan_thuc_te`  AS SELECT `dt`.`du_toan_id` AS `du_toan_id`, `dt`.`tour_id` AS `tour_id`, `t`.`ten_tour` AS `ten_tour`, `dt`.`tong_du_toan` AS `tong_du_toan`, coalesce(sum(case when `cp`.`trang_thai` = 'DaDuyet' then `cp`.`so_tien` end),0) AS `tong_thuc_te`, coalesce(sum(case when `cp`.`trang_thai` = 'DaDuyet' then `cp`.`so_tien` end),0) - `dt`.`tong_du_toan` AS `chenh_lech`, CASE WHEN coalesce(sum(case when `cp`.`trang_thai` = 'DaDuyet' then `cp`.`so_tien` end),0) > `dt`.`tong_du_toan` THEN 'VuotDuToan' WHEN coalesce(sum(case when `cp`.`trang_thai` = 'DaDuyet' then `cp`.`so_tien` end),0) >= `dt`.`tong_du_toan` * 0.9 THEN 'GanVuot' ELSE 'AnToan' END AS `canh_bao` FROM ((`du_toan_tour` `dt` left join `chi_phi_thuc_te` `cp` on(`dt`.`du_toan_id` = `cp`.`du_toan_id`)) left join `tour` `t` on(`dt`.`tour_id` = `t`.`tour_id`)) GROUP BY `dt`.`du_toan_id`, `dt`.`tour_id`, `t`.`ten_tour`, `dt`.`tong_du_toan` ;

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
-- Indexes for table `booking_deletion_history`
--
ALTER TABLE `booking_deletion_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_tour_id` (`tour_id`),
  ADD KEY `idx_khach_hang_id` (`khach_hang_id`),
  ADD KEY `idx_nguoi_xoa_id` (`nguoi_xoa_id`),
  ADD KEY `idx_thoi_gian_xoa` (`thoi_gian_xoa`);

--
-- Indexes for table `booking_history`
--
ALTER TABLE `booking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nguoi_thay_doi_id` (`nguoi_thay_doi_id`),
  ADD KEY `idx_booking_id` (`booking_id`),
  ADD KEY `idx_thoi_gian` (`thoi_gian`);

--
-- Indexes for table `booking_khach_hang`
--
ALTER TABLE `booking_khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `khach_hang_id` (`khach_hang_id`);

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
-- Indexes for table `chi_phi_thuc_te`
--
ALTER TABLE `chi_phi_thuc_te`
  ADD PRIMARY KEY (`chi_phi_id`),
  ADD KEY `idx_cp_du_toan` (`du_toan_id`),
  ADD KEY `idx_cp_tour` (`tour_id`),
  ADD KEY `idx_cp_trang_thai` (`trang_thai`),
  ADD KEY `idx_cp_loai` (`loai_chi_phi`),
  ADD KEY `fk_cp_lich` (`lich_khoi_hanh_id`),
  ADD KEY `fk_cp_nguoi_duyet` (`nguoi_duyet_id`),
  ADD KEY `fk_cp_nguoi_ghi` (`nguoi_ghi_nhan_id`);

--
-- Indexes for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_het_han` (`ngay_het_han`);

--
-- Indexes for table `cong_no_hdv`
--
ALTER TABLE `cong_no_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cong_no_tour` (`tour_id`),
  ADD KEY `idx_cong_no_hdv` (`hdv_id`);

--
-- Indexes for table `cong_no_nha_cung_cap`
--
ALTER TABLE `cong_no_nha_cung_cap`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cong_no_ncc` (`nha_cung_cap_id`),
  ADD KEY `idx_cong_no_ncc_tour` (`tour_id`);

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
-- Indexes for table `du_toan_chi_tiet`
--
ALTER TABLE `du_toan_chi_tiet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_du_toan_id` (`du_toan_id`),
  ADD KEY `idx_tour_id` (`tour_id`);

--
-- Indexes for table `du_toan_tour`
--
ALTER TABLE `du_toan_tour`
  ADD PRIMARY KEY (`du_toan_id`),
  ADD KEY `idx_tour_id` (`tour_id`),
  ADD KEY `idx_lich_khoi_hanh_id` (`lich_khoi_hanh_id`),
  ADD KEY `idx_nguoi_tao_id` (`nguoi_tao_id`);

--
-- Indexes for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `idx_gd_booking` (`booking_id`),
  ADD KEY `idx_gd_khach` (`khach_hang_id`),
  ADD KEY `idx_gd_loai_gd` (`loai_giao_dich`),
  ADD KEY `idx_gd_ngay` (`ngay_giao_dich`),
  ADD KEY `idx_gd_nguoi_th` (`nguoi_thuc_hien_id`);

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
-- Indexes for table `lich_su_khach_hang`
--
ALTER TABLE `lich_su_khach_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lskh_khach` (`khach_hang_id`),
  ADD KEY `idx_lskh_loai` (`loai_hoat_dong`),
  ADD KEY `fk_lskh_creator` (`nguoi_tao_id`);

--
-- Indexes for table `lich_su_thanh_toan_hdv`
--
ALTER TABLE `lich_su_thanh_toan_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ls_hdv_cong_no` (`cong_no_hdv_id`);

--
-- Indexes for table `lich_su_thanh_toan_ncc`
--
ALTER TABLE `lich_su_thanh_toan_ncc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ls_ncc_cong_no` (`cong_no_ncc_id`);

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
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tb_nguoi_gui` (`nguoi_gui_id`),
  ADD KEY `idx_tb_nguoi_nhan` (`nguoi_nhan_id`),
  ADD KEY `idx_tb_trang_thai` (`trang_thai`),
  ADD KEY `idx_tb_loai` (`loai_thong_bao`);

--
-- Indexes for table `thong_bao_doc`
--
ALTER TABLE `thong_bao_doc`
  ADD PRIMARY KEY (`thong_bao_id`,`nguoi_dung_id`),
  ADD KEY `idx_tbd_nguoi_dung` (`nguoi_dung_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `booking_deletion_history`
--
ALTER TABLE `booking_deletion_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `booking_khach_hang`
--
ALTER TABLE `booking_khach_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checkin_khach`
--
ALTER TABLE `checkin_khach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chi_phi_thuc_te`
--
ALTER TABLE `chi_phi_thuc_te`
  MODIFY `chi_phi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cong_no_hdv`
--
ALTER TABLE `cong_no_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cong_no_nha_cung_cap`
--
ALTER TABLE `cong_no_nha_cung_cap`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `du_toan_chi_tiet`
--
ALTER TABLE `du_toan_chi_tiet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `du_toan_tour`
--
ALTER TABLE `du_toan_tour`
  MODIFY `du_toan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hotel_room_assignment`
--
ALTER TABLE `hotel_room_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `khach_hang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lich_lam_viec_hdv`
--
ALTER TABLE `lich_lam_viec_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lich_su_khach_hang`
--
ALTER TABLE `lich_su_khach_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lich_su_thanh_toan_hdv`
--
ALTER TABLE `lich_su_thanh_toan_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_su_thanh_toan_ncc`
--
ALTER TABLE `lich_su_thanh_toan_ncc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_su_yeu_cau`
--
ALTER TABLE `lich_su_yeu_cau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `nhan_su`
--
ALTER TABLE `nhan_su`
  MODIFY `nhan_su_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  MODIFY `id_nha_cung_cap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `phan_bo_history`
--
ALTER TABLE `phan_bo_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phan_bo_nhan_su`
--
ALTER TABLE `phan_bo_nhan_su`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `phan_hoi_danh_gia`
--
ALTER TABLE `phan_hoi_danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thong_bao_hdv`
--
ALTER TABLE `thong_bao_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tour_checkin`
--
ALTER TABLE `tour_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- Constraints for table `booking_khach_hang`
--
ALTER TABLE `booking_khach_hang`
  ADD CONSTRAINT `booking_khach_hang_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_khach_hang_ibfk_2` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE;

--
-- Constraints for table `checkin_khach`
--
ALTER TABLE `checkin_khach`
  ADD CONSTRAINT `checkin_khach_ibfk_1` FOREIGN KEY (`diem_checkin_id`) REFERENCES `diem_checkin` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkin_khach_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkin_khach_ibfk_3` FOREIGN KEY (`nguoi_checkin_id`) REFERENCES `nhan_su` (`nhan_su_id`);

--
-- Constraints for table `chi_phi_thuc_te`
--
ALTER TABLE `chi_phi_thuc_te`
  ADD CONSTRAINT `fk_cp_du_toan` FOREIGN KEY (`du_toan_id`) REFERENCES `du_toan_tour` (`du_toan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cp_lich` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cp_nguoi_duyet` FOREIGN KEY (`nguoi_duyet_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_cp_nguoi_ghi` FOREIGN KEY (`nguoi_ghi_nhan_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cp_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  ADD CONSTRAINT `chung_chi_hdv_ibfk_1` FOREIGN KEY (`nhan_su_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `cong_no_hdv`
--
ALTER TABLE `cong_no_hdv`
  ADD CONSTRAINT `fk_cong_no_hdv` FOREIGN KEY (`hdv_id`) REFERENCES `nhan_su` (`nhan_su_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cong_no_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `cong_no_nha_cung_cap`
--
ALTER TABLE `cong_no_nha_cung_cap`
  ADD CONSTRAINT `fk_cong_no_ncc` FOREIGN KEY (`nha_cung_cap_id`) REFERENCES `nha_cung_cap` (`id_nha_cung_cap`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cong_no_ncc_tour` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE SET NULL;

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
-- Constraints for table `du_toan_chi_tiet`
--
ALTER TABLE `du_toan_chi_tiet`
  ADD CONSTRAINT `du_toan_chi_tiet_ibfk_1` FOREIGN KEY (`du_toan_id`) REFERENCES `du_toan_tour` (`du_toan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `du_toan_chi_tiet_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;

--
-- Constraints for table `du_toan_tour`
--
ALTER TABLE `du_toan_tour`
  ADD CONSTRAINT `du_toan_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `du_toan_tour_ibfk_2` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `du_toan_tour_ibfk_3` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  ADD CONSTRAINT `fk_gd_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_gd_khach` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_gd_nguoi` FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL,
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
-- Constraints for table `lich_su_khach_hang`
--
ALTER TABLE `lich_su_khach_hang`
  ADD CONSTRAINT `fk_lskh_creator` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lskh_khach` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_su_thanh_toan_hdv`
--
ALTER TABLE `lich_su_thanh_toan_hdv`
  ADD CONSTRAINT `fk_ls_hdv_cong_no` FOREIGN KEY (`cong_no_hdv_id`) REFERENCES `cong_no_hdv` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lich_su_thanh_toan_ncc`
--
ALTER TABLE `lich_su_thanh_toan_ncc`
  ADD CONSTRAINT `fk_ls_ncc_cong_no` FOREIGN KEY (`cong_no_ncc_id`) REFERENCES `cong_no_nha_cung_cap` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `fk_tb_nguoi_gui` FOREIGN KEY (`nguoi_gui_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_tb_nguoi_nhan` FOREIGN KEY (`nguoi_nhan_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `thong_bao_doc`
--
ALTER TABLE `thong_bao_doc`
  ADD CONSTRAINT `fk_tbd_nguoi_dung` FOREIGN KEY (`nguoi_dung_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tbd_thong_bao` FOREIGN KEY (`thong_bao_id`) REFERENCES `thong_bao` (`id`) ON DELETE CASCADE;

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

-- ============================================================
-- SCRIPT TẠO TOUR HOÀN CHỈNH TỪ A-Z ĐỂ TEST
-- Chạy script này trong phpMyAdmin hoặc MySQL client
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. TẠO NGƯỜI DÙNG (Khách hàng)
-- ============================================================
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, ngay_tao)
VALUES 
    (200, 'khach1', 'Nguyễn Văn An', 'nguyenvanan@test.com', '0911111111', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (201, 'khach2', 'Trần Thị Bình', 'tranthibinh@test.com', '0922222222', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (202, 'khach3', 'Lê Văn Cường', 'levancuong@test.com', '0933333333', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (203, 'khach4', 'Phạm Thị Dung', 'phamthidung@test.com', '0944444444', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), email = VALUES(email);

-- ============================================================
-- 2. TẠO KHÁCH HÀNG
-- ============================================================
INSERT INTO khach_hang (khach_hang_id, nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh)
VALUES 
    (200, 200, '123 Đường ABC, Quận 1, Hà Nội', 'Nam', '1990-01-15'),
    (201, 201, '456 Đường XYZ, Quận 3, TP.HCM', 'Nu', '1988-05-20'),
    (202, 202, '789 Đường DEF, Quận Hải Châu, Đà Nẵng', 'Nam', '1992-08-10'),
    (203, 203, '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'Nu', '1995-12-25')
ON DUPLICATE KEY UPDATE dia_chi = VALUES(dia_chi);

-- ============================================================
-- 3. TẠO HDV

-- ============================================================
-- 4. TẠO TOUR
-- ============================================================
INSERT INTO tour (tour_id, ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, trang_thai)
VALUES 
    (100, 'NAGOYA – PHÚ SĨ – TOKYO (5 NGÀY 4 ĐÊM)', 'QuocTe', 
     'Tour tham quan Nhật Bản với các điểm đến nổi tiếng: Nagoya, Núi Phú Sĩ, Tokyo. Trải nghiệm văn hóa, ẩm thực và cảnh đẹp Nhật Bản.', 
     32990000.00, 
     'Hủy trước 14 ngày: hoàn 80%. Hủy trước 7 ngày: hoàn 50%. Hủy trước 3 ngày: hoàn 30%.', 
     'HoatDong')
ON DUPLICATE KEY UPDATE ten_tour = VALUES(ten_tour), mo_ta = VALUES(mo_ta);

-- ============================================================
-- 5. TẠO LỊCH TRÌNH CHI TIẾT
-- ============================================================
DELETE FROM lich_trinh_tour WHERE tour_id = 100;

INSERT INTO lich_trinh_tour (tour_id, ngay_thu, dia_diem, hoat_dong) VALUES
(100, 0, 'Sân bay Nội Bài – Ga đi quốc tế', '🕘 Giờ tập trung: 21:00 (trước giờ bay 3 tiếng)\n👤 Hướng dẫn viên làm thủ tục & hỗ trợ đoàn.'),
(100, 1, 'HÀ NỘI → TOKYO (Narita)', '✈️ Sáng / Trưa / Chiều:\n🕘 09:00 – Tập trung tại sân bay Nội Bài, HDV hỗ trợ check-in.\n🕙 12:00 – Cất cánh đi Nhật Bản.\n\n🌆 Chiều / Tối:\n🕕 18:00 – Hạ cánh sân bay Narita.\n🚌 Di chuyển về khách sạn nhận phòng.\n🍱 Tối: Ăn tối tại nhà hàng địa phương.\n🏨 Nghỉ đêm tại Tokyo / Narita.'),
(100, 2, 'NAGOYA – THÀNH PHỐ CẢNG', '🍳 Sáng:\n🕗 08:00 – Ăn sáng tại khách sạn.\n🚌 Di chuyển đến Nagoya.\n🏯 Tham quan Lâu đài Nagoya – biểu tượng lịch sử nổi tiếng.\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa với món đặc sản Nagoya.\n\n🛍️ Chiều:\n🕒 14:00 – Tham quan & mua sắm tại khu vực Sakae sầm uất.\n\n🍱 Tối:\n🕕 18:00 – Thưởng thức món Tebasaki (gà rán kiểu Nagoya).\n🏨 Nghỉ đêm tại Nagoya.'),
(100, 3, 'NAGOYA – NÚI PHÚ SĨ – KAWAGUCHIKO', '🍳 Sáng:\n🕗 08:00 – Ăn sáng tại khách sạn.\n🚌 Di chuyển đến khu vực núi Phú Sĩ.\n🏔️ Tham quan trạm 5 Núi Phú Sĩ (nếu thời tiết cho phép).\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa tại Kawaguchiko.\n\n🌅 Chiều:\n🌸 Tham quan Hồ Kawaguchiko – check-in với background núi Phú Sĩ.\n🏞️ Tham quan làng cổ Oshino Hakkai.\n\n🍱 Tối:\n🕕 18:00 – Ăn tối với set kaiseki Nhật Bản.\n🛁 Tắm onsen truyền thống tại khách sạn.\n🏨 Nghỉ đêm tại Kawaguchiko.'),
(100, 4, 'KAWAGUCHIKO – TOKYO', '🍳 Sáng:\n🕗 07:30 – Ăn sáng và trả phòng.\n🚌 Khởi hành về Tokyo.\n\n🏙️ Trưa:\n🕛 12:00 – Ăn trưa tại Tokyo.\n\n🗼 Chiều – City Tour Tokyo:\n🏯 Viếng Chùa Asakusa – Đền Sensoji.\n🛍️ Tham quan mua sắm tại Nakamise.\n📷 Check-in tại Tokyo SkyTree (chụp ảnh bên ngoài).\n🚏 Ghé Shibuya Crossing & tượng Hachiko.\n\n🍱 Tối:\n🕕 18:00 – Ăn tối món Nhật.\n🏨 Nghỉ đêm tại Tokyo.'),
(100, 5, 'TOKYO – HÀ NỘI', '🍳 Sáng:\n🕗 07:00 – Ăn sáng tại khách sạn.\n👜 Tự do mua sắm tại Aeon Mall hoặc Akihabara.\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa.\n\n✈️ Chiều:\n🚌 Di chuyển ra sân bay Narita.\n🕒 Làm thủ tục check-in.\n\n🌙 Tối:\n🛫 Bay về Hà Nội.\n🏁 Kết thúc hành trình – HDV chia tay đoàn.');

-- ============================================================
-- 6. TẠO LỊCH KHỞI HÀNH
-- Sử dụng HDV có sẵn: nhan_su_id = 2, nguoi_dung_id = 6
-- ============================================================
INSERT INTO lich_khoi_hanh (id, tour_id, ngay_khoi_hanh, gio_xuat_phat, ngay_ket_thuc, gio_ket_thuc, diem_tap_trung, so_cho, hdv_id, trang_thai, ghi_chu)
VALUES 
    (200, 100, '2025-12-02', '21:00:00', '2025-12-06', '18:00:00', 'Sân bay Nội Bài – Ga đi quốc tế', 50, 2, 'SapKhoiHanh', 'Lịch khởi hành test tour hoàn chỉnh')
ON DUPLICATE KEY UPDATE ngay_khoi_hanh = VALUES(ngay_khoi_hanh), hdv_id = VALUES(hdv_id);

-- ============================================================
-- 7. PHÂN BỔ HDV
-- Sử dụng HDV có sẵn: nhan_su_id = 2
-- ============================================================
INSERT INTO phan_bo_nhan_su (lich_khoi_hanh_id, nhan_su_id, vai_tro, ghi_chu, trang_thai, thoi_gian_xac_nhan)
VALUES 
    (200, 2, 'HDV', 'Phân bổ HDV chính cho tour test', 'DaXacNhan', NOW())
ON DUPLICATE KEY UPDATE trang_thai = 'DaXacNhan';

-- ============================================================
-- 8. TẠO BOOKING
-- ============================================================
DELETE FROM booking WHERE booking_id IN (200, 201, 202, 203);

INSERT INTO booking (booking_id, khach_hang_id, tour_id, ngay_khoi_hanh, ngay_ket_thuc, so_nguoi, tong_tien, ngay_dat, trang_thai, ghi_chu)
VALUES 
    (200, 200, 100, '2025-12-02', '2025-12-06', 2, 65980000.00, NOW(), 'HoanTat', 'Booking test tour hoàn chỉnh - 2 người'),
    (201, 201, 100, '2025-12-02', '2025-12-06', 3, 98970000.00, NOW(), 'DaCoc', 'Booking test tour hoàn chỉnh - 3 người (2 lớn + 1 trẻ em)'),
    (202, 202, 100, '2025-12-02', '2025-12-06', 1, 32990000.00, NOW(), 'ChoXacNhan', 'Booking test tour hoàn chỉnh - 1 người'),
    (203, 203, 100, '2025-12-02', '2025-12-06', 2, 65980000.00, NOW(), 'DaCoc', 'Booking test tour hoàn chỉnh - 2 người');

-- ============================================================
-- 9. TẠO ĐIỂM CHECK-IN
-- ============================================================
DELETE FROM diem_checkin WHERE tour_id = 100 AND id IN (200, 201, 202, 203);

INSERT INTO diem_checkin (id, tour_id, ten_diem, loai_diem, thoi_gian_du_kien, ghi_chu, thu_tu)
VALUES 
    (200, 100, 'Sân bay Nội Bài - Điểm tập trung', 'tap_trung', '2025-12-02 21:00:00', 'Điểm check-in test', 1),
    (201, 100, 'Khách sạn Tokyo - Check-in', 'nghi_ngoi', '2025-12-02 20:00:00', 'Điểm check-in test', 2),
    (202, 100, 'Lâu đài Nagoya - Tham quan', 'tham_quan', '2025-12-03 10:00:00', 'Điểm check-in test', 3),
    (203, 100, 'Núi Phú Sĩ - Tham quan', 'tham_quan', '2025-12-04 09:00:00', 'Điểm check-in test', 4);

-- ============================================================
-- 10. TẠO TOUR_CHECKIN (Danh sách khách chi tiết)
-- Đảm bảo số lượng khách khớp với so_nguoi trong booking
-- ============================================================
DELETE FROM tour_checkin WHERE booking_id IN (200, 201, 202, 203) AND lich_khoi_hanh_id = 200;

-- Booking 200: 2 người (khach_hang_id = 200)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 200, 200, 200, nd.ho_ten, 'CMND200-1', 'PASS200-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 200;

-- Người thứ 2 trong booking 200 (người đi kèm, không có khach_hang_id riêng)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES (200, 200, 200, 'Nguyễn Thị Lan', 'CMND200-2', 'PASS200-2', '1992-03-20', 'Nu', 'Việt Nam', '0911111111', 'nguyenvanan@test.com', '123 Đường ABC, Quận 1, Hà Nội', 'ChuaCheckIn');

-- Booking 201: 3 người (khach_hang_id = 201)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 201, 201, 200, nd.ho_ten, 'CMND201-1', 'PASS201-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 201;

-- Người thứ 2 và 3 trong booking 201
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES 
    (201, 201, 200, 'Trần Văn Hùng', 'CMND201-2', 'PASS201-2', '1990-07-15', 'Nam', 'Việt Nam', '0922222222', 'tranthibinh@test.com', '456 Đường XYZ, Quận 3, TP.HCM', 'ChuaCheckIn'),
    (201, 201, 200, 'Trần Thị Mai', 'CMND201-3', 'PASS201-3', '2015-10-20', 'Nu', 'Việt Nam', NULL, NULL, '456 Đường XYZ, Quận 3, TP.HCM', 'ChuaCheckIn');

-- Booking 202: 1 người (khach_hang_id = 202)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 202, 202, 200, nd.ho_ten, 'CMND202-1', 'PASS202-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 202;

-- Booking 203: 2 người (khach_hang_id = 203)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 203, 203, 200, nd.ho_ten, 'CMND203-1', 'PASS203-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 203;

-- Người thứ 2 trong booking 203
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES (203, 203, 200, 'Phạm Văn Đức', 'CMND203-2', 'PASS203-2', '1993-04-12', 'Nam', 'Việt Nam', '0944444444', 'phamthidung@test.com', '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'ChuaCheckIn');

-- ============================================================
-- 11. TẠO CHECK-IN KHÁCH (Trạng thái check-in tại điểm)
-- Lưu ý: checkin_khach lưu theo booking_id (không phải từng khách riêng)
-- Mỗi booking có 1 record check-in tại mỗi điểm check-in
-- ============================================================
DELETE FROM checkin_khach WHERE booking_id IN (200, 201, 202, 203) AND diem_checkin_id = 200;

-- Check-in tại điểm 200 (Sân bay Nội Bài) cho các booking
-- Booking 200: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 200, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 2 người', 2);

-- Booking 201: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 201, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 3 người', 2);

-- Booking 202: chưa check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 202, 'chua_checkin', NULL, NULL, NULL);

-- Booking 203: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 203, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 2 người', 2);

-- ============================================================
-- 12. TẠO YÊU CẦU ĐẶC BIỆT
-- ============================================================
DELETE FROM yeu_cau_dac_biet WHERE booking_id IN (200, 201, 202, 203);

INSERT INTO yeu_cau_dac_biet (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien, trang_thai, nguoi_tao_id)
VALUES 
    (200, 'an_uong', 'Dị ứng hải sản', 'Khách bị dị ứng hải sản, cần tránh các món có hải sản trong suốt chuyến đi', 'cao', 'moi', 200),
    (201, 'suc_khoe', 'Cần hỗ trợ di chuyển', 'Có trẻ em 10 tuổi, cần hỗ trợ khi di chuyển và tham quan', 'trung_binh', 'moi', 201),
    (202, 'phong_o', 'Phòng đơn', 'Yêu cầu phòng đơn riêng, không ở chung', 'thap', 'moi', 202),
    (203, 'khac', 'Yêu cầu đặc biệt về visa', 'Cần hỗ trợ đặc biệt về thủ tục visa và giấy tờ', 'trung_binh', 'moi', 203);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- HOÀN THÀNH!
-- ============================================================
-- Tour ID: 100
-- Lịch khởi hành ID: 200
-- HDV ID: 2 (nhan_su_id = 2, nguoi_dung_id = 6)
-- Số booking: 4
-- Tổng số khách: 8
-- ============================================================




/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
