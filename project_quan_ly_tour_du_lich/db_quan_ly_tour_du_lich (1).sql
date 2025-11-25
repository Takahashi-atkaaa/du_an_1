-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 21, 2025 at 02:47 PM
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
(1, 1, 1, '2025-11-17', '2025-11-27', 2, 7000000.00, 'HoanTat', 'Yêu cầu phòng đôi'),
(2, 1, 2, '2025-11-18', '2025-11-19', 45, 157500000.00, 'HoanTat', 'adada | Công ty/Tổ chức: sdfsdf'),
(3, 1, 4, '2025-11-21', '2025-11-29', 50, 175000000.00, 'DaCoc', 'cố lên em nhé');

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
  `thoi_gian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_history`
--

INSERT INTO `booking_history` (`id`, `booking_id`, `trang_thai_cu`, `trang_thai_moi`, `nguoi_thay_doi_id`, `ghi_chu`, `thoi_gian`) VALUES
(1, 1, 'ChoXacNhan', 'DaCoc', 1, '', '2025-11-17 00:25:40'),
(2, 2, 'ChoXacNhan', 'DaCoc', 1, 'ok', '2025-11-18 03:01:49'),
(3, 1, 'DaCoc', 'HoanTat', 1, '', '2025-11-18 03:02:05'),
(4, 3, 'ChoXacNhan', 'DaCoc', 1, '', '2025-11-21 03:21:39'),
(5, 2, 'DaCoc', 'HoanTat', 1, '', '2025-11-21 03:21:53');

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
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý chi tiết chứng chỉ HDV';

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
  `ngay_danh_gia` timestamp NOT NULL DEFAULT current_timestamp()
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

--
-- Dumping data for table `giao_dich_tai_chinh`
--

INSERT INTO `giao_dich_tai_chinh` (`id`, `tour_id`, `loai`, `so_tien`, `mo_ta`, `ngay_giao_dich`) VALUES
(1, 1, 'Thu', 7000000.00, 'Khách đặt cọc/Thanh toán', '2025-11-17'),
(2, 1, 'Chi', 2000000.00, 'Đặt cọc dịch vụ du thuyền', '2025-11-17');

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
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Báo cáo hiệu suất HDV theo tháng';

--
-- Dumping data for table `hieu_suat_hdv`
--

INSERT INTO `hieu_suat_hdv` (`id`, `nhan_su_id`, `thang`, `nam`, `so_tour_thang`, `so_ngay_lam_viec`, `doanh_thu_mang_lai`, `diem_danh_gia_tb`, `so_khieu_nai`, `so_khen_thuong`, `ghi_chu`, `ngay_tao`) VALUES
(1, 1, 11, 2025, 3, 15, 0.00, 4.50, 0, 0, NULL, '2025-11-17 06:17:46');

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
(1, 1, 'public/images/halong1.jpg', 'Toàn cảnh Vịnh Hạ Long'),
(2, 1, 'public/images/halong2.jpg', 'Du thuyền trên Vịnh');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
(1, 3, '123 Đường A, Quận B, TP. HCM', 'Nữ', '1995-05-10'),
(2, 5, 'sfsf', 'Nam', '1999-02-03'),
(3, 6, NULL, NULL, NULL),
(4, 7, 'wfwdfds', 'Nam', '2002-10-25');

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
(1, 1, '2025-11-27', '06:00:00', '2025-11-29', '18:00:00', 'Sân bay Nội Bài - Cổng A', 50, 1, 'SapKhoiHanh', 'Lịch khởi hành mẫu cho tour Hạ Long'),
(4, 2, '2025-10-22', NULL, '2026-11-22', NULL, 'Sân bay Nội Bài - Cổng A', 50, 1, 'SapKhoiHanh', NULL);

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
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_cap_nhat` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch làm việc HDV: tour, nghỉ phép, bận';

--
-- Dumping data for table `lich_lam_viec_hdv`
--

INSERT INTO `lich_lam_viec_hdv` (`id`, `nhan_su_id`, `tour_id`, `loai_lich`, `ngay_bat_dau`, `ngay_ket_thuc`, `ghi_chu`, `trang_thai`, `nguoi_tao_id`, `ngay_tao`, `ngay_cap_nhat`) VALUES
(1, 1, NULL, 'NghiPhep', '2025-11-24', '2025-11-26', 'Nghỉ phép năm', 'XacNhan', NULL, '2025-11-17 06:17:46', '2025-11-17 06:17:46');

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
(1, 1, 1, 'Hà Nội', 'Đón khách - Tham quan phố cổ - Ăn tối'),
(2, 1, 2, 'Hạ Long', 'Tham quan Vịnh Hạ Long - Nghỉ đêm trên du thuyền'),
(3, 1, 3, 'Hạ Long - Hà Nội', 'Tham quan hang động - Trở về Hà Nội');

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
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ten_dang_nhap`, `mat_khau`, `ho_ten`, `avatar`, `email`, `so_dien_thoai`, `vai_tro`, `quyen_cap_cao`, `trang_thai`, `ngay_tao`) VALUES
(1, 'admin', '$2y$10$qL.eXn3tUV3kNtWL3mp6p.eGlKjTvIkZpHXvtrcrtO0apAgLBQ/wC', 'Quản trị viên hệ thống', NULL, 'admin@tour.com', NULL, 'Admin', 1, 'HoatDong', '2025-11-17 06:17:46'),
(2, 'hdv01', '$2y$10$GFf58ljh8cKw829Vo3ZEKOXdoU1hv2JGXnldCg4ZTc5rpRdr7fi.W', 'Nguyễn Văn Hướng', NULL, 'hdv@tour.com', NULL, 'HDV', 0, 'HoatDong', '2025-11-17 06:17:46'),
(3, 'khach01', 'khach123', 'Trần Thị Khách', NULL, 'khach@tour.com', NULL, 'KhachHang', 0, 'HoatDong', '2025-11-17 06:17:46'),
(4, 'ncc01', 'ncc123', 'Công ty ABC Travel', NULL, 'ncc@tour.com', NULL, 'NhaCungCap', 0, 'HoatDong', '2025-11-17 06:17:46'),
(5, 'nansad@gmail.com', '$2y$10$Y8LJrkBfw2QFA1mcojH9we.Eo0roPoWT6GEaH8JsRQuyzDbZ.Snv2', 'dsfsf', NULL, 'nansad@gmail.com', '343', 'KhachHang', 0, 'HoatDong', '2025-11-18 03:01:17'),
(6, 'thaichimto@gmail.com', '$2y$10$3aPcFFSavA4flPRnNJ/YhuTLANjQx40EhHg.kTKXw2i7mpgOE.9wS', 'thai chim to', NULL, 'thaichimto@gmail.com', '111111', 'KhachHang', 0, 'HoatDong', '2025-11-18 20:56:30'),
(7, 'test100@gmail.com', '$2y$10$/d2LluHexv5PMPcpvn03muST60hdwUFiyy53vsUoXjGXQg59OGez.', 'tung anh', NULL, 'test100@gmail.com', '43435', 'KhachHang', 0, 'HoatDong', '2025-11-21 03:21:21');

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
(1, 2, 'HDV', 'NoiDia', 'Miền Bắc', 0.00, 0, 'SanSang', 'Chứng chỉ nghiệp vụ hướng dẫn viên', 'Tiếng Việt, Tiếng Anh', '5 năm dẫn tour nội địa', 'Tốt');

-- --------------------------------------------------------

--
-- Table structure for table `nhat_ky_tour`
--

CREATE TABLE `nhat_ky_tour` (
  `id` int(11) NOT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `nhan_su_id` int(11) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL,
  `ngay_ghi` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhat_ky_tour`
--

INSERT INTO `nhat_ky_tour` (`id`, `tour_id`, `nhan_su_id`, `noi_dung`, `ngay_ghi`) VALUES
(1, 1, 1, 'Đã kiểm tra trang thiết bị an toàn trên du thuyền', '2025-11-17'),
(2, 1, 1, 'Tiêu đề: sfsdf\nHoạt động nổi bật: sdfsd\nSự kiện / Sự cố: fsdfds\nCách xử lý: fsdf\nPhản hồi khách hàng: dsfsd', '2025-11-21'),
(3, 1, 1, 'Tiêu đề: ẻtre\nHoạt động nổi bật: tretưerewre\nSự kiện / Sự cố: rêtr\nCách xử lý: tet\nPhản hồi khách hàng: êtr', '2025-11-21');

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
(1, 4, 'ABC Travel Services', 'KhachSan', '456 Đường C, Quận D, Hà Nội', '0123456789', 'Đối tác cung cấp khách sạn 3-4 sao', 4.5);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `thoi_gian` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_bo_nhan_su`
--

INSERT INTO `phan_bo_nhan_su` (`id`, `lich_khoi_hanh_id`, `nhan_su_id`, `vai_tro`, `ghi_chu`, `trang_thai`, `thoi_gian_xac_nhan`, `created_at`) VALUES
(4, 1, 1, 'TaiXe', '', 'DaXacNhan', '2025-11-20 15:56:39', '2025-11-19 03:04:36'),
(5, 1, 1, 'HDV', '', 'DaXacNhan', '2025-11-21 10:34:28', '2025-11-20 14:59:18'),
(6, 4, 1, 'HDV', '', 'ChoXacNhan', NULL, '2025-11-21 09:27:40');

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

--
-- Dumping data for table `phan_hoi_danh_gia`
--

INSERT INTO `phan_hoi_danh_gia` (`id`, `tour_id`, `nguoi_dung_id`, `loai`, `diem`, `noi_dung`, `ngay_danh_gia`) VALUES
(1, 1, 3, 'Tour', 5, 'Trải nghiệm tuyệt vời, hướng dẫn viên nhiệt tình!', '2025-11-17');

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
  `ngay_gui` timestamp NOT NULL DEFAULT current_timestamp(),
  `ngay_xem` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo và nhắc nhở cho HDV';

--
-- Dumping data for table `thong_bao_hdv`
--

INSERT INTO `thong_bao_hdv` (`id`, `nhan_su_id`, `loai_thong_bao`, `tieu_de`, `noi_dung`, `uu_tien`, `da_xem`, `ngay_gui`, `ngay_xem`) VALUES
(1, 1, 'NhacNho', 'Chuẩn bị tour tuần sau', 'Tour Hà Nội - Hạ Long sẽ khởi hành vào 20/11/2025. Vui lòng chuẩn bị tài liệu và thiết bị.', 'Cao', 0, '2025-11-17 06:17:46', NULL);

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
  `trang_thai` enum('HoatDong','TamDung','HetHan') DEFAULT 'HoatDong'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour`
--

INSERT INTO `tour` (`tour_id`, `ten_tour`, `loai_tour`, `mo_ta`, `gia_co_ban`, `chinh_sach`, `id_nha_cung_cap`, `tao_boi`, `trang_thai`) VALUES
(1, 'Hà Nội - Hạ Long 3N2Đ', 'TrongNuoc', 'Khám phá Vịnh Hạ Long kỳ quan thiên nhiên thế giới', 3500000.00, 'Hủy trước 7 ngày: hoàn 80%', 1, 1, 'HoatDong'),
(2, 'nhật bản 1 tháng', 'QuocTe', 'ửewr', 500000000.00, 'ưerwer', NULL, 1, 'HoatDong');

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
  `quoc_tich` varchar(100) DEFAULT 'Việt Nam',
  `dia_chi` text DEFAULT NULL,
  `so_dien_thoai` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `checkin_time` datetime DEFAULT current_timestamp(),
  `checkout_time` datetime DEFAULT NULL,
  `trang_thai` enum('DaCheckIn','ChuaCheckIn','DaCheckOut') DEFAULT 'ChuaCheckIn',
  `ghi_chu` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  `khach_hang_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `noi_dung` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `yeu_cau_dac_biet`
--

INSERT INTO `yeu_cau_dac_biet` (`id`, `khach_hang_id`, `tour_id`, `noi_dung`) VALUES
(1, 1, 1, 'Chuẩn bị bánh sinh nhật bất ngờ ngày 2'),
(2, 2, 1, 'ádad'),
(3, 4, 1, 'ăn cơm bằng mũi');

-- --------------------------------------------------------

--
-- Structure for view `v_hdv_san_sang`
--
DROP TABLE IF EXISTS `v_hdv_san_sang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_hdv_san_sang`  AS SELECT `ns`.`nhan_su_id` AS `nhan_su_id`, `nd`.`ho_ten` AS `ho_ten`, `nd`.`email` AS `email`, `nd`.`so_dien_thoai` AS `so_dien_thoai`, `ns`.`loai_hdv` AS `loai_hdv`, `ns`.`chuyen_tuyen` AS `chuyen_tuyen`, `ns`.`danh_gia_tb` AS `danh_gia_tb`, `ns`.`so_tour_da_dan` AS `so_tour_da_dan`, `ns`.`ngon_ngu` AS `ngon_ngu` FROM (`nhan_su` `ns` join `nguoi_dung` `nd` on(`ns`.`nguoi_dung_id` = `nd`.`id`)) WHERE `ns`.`vai_tro` = 'HDV' AND `ns`.`trang_thai_lam_viec` = 'SanSang' AND !(`ns`.`nhan_su_id` in (select `lich_lam_viec_hdv`.`nhan_su_id` from `lich_lam_viec_hdv` where `lich_lam_viec_hdv`.`trang_thai` in ('DuKien','XacNhan') AND curdate() between `lich_lam_viec_hdv`.`ngay_bat_dau` and `lich_lam_viec_hdv`.`ngay_ket_thuc`)) ;

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
-- Indexes for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_het_han` (`ngay_het_han`);

--
-- Indexes for table `danh_gia_hdv`
--
ALTER TABLE `danh_gia_hdv`
  ADD PRIMARY KEY (`id`),
  ADD KEY `khach_hang_id` (`khach_hang_id`),
  ADD KEY `idx_nhan_su` (`nhan_su_id`),
  ADD KEY `idx_tour` (`tour_id`);

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
  ADD KEY `khach_hang_id` (`khach_hang_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_history`
--
ALTER TABLE `booking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chung_chi_hdv`
--
ALTER TABLE `chung_chi_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_gia_hdv`
--
ALTER TABLE `danh_gia_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `giao_dich_tai_chinh`
--
ALTER TABLE `giao_dich_tai_chinh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hieu_suat_hdv`
--
ALTER TABLE `hieu_suat_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hinh_anh_tour`
--
ALTER TABLE `hinh_anh_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hotel_room_assignment`
--
ALTER TABLE `hotel_room_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khach_hang`
--
ALTER TABLE `khach_hang`
  MODIFY `khach_hang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lich_khoi_hanh`
--
ALTER TABLE `lich_khoi_hanh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lich_lam_viec_hdv`
--
ALTER TABLE `lich_lam_viec_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lich_trinh_tour`
--
ALTER TABLE `lich_trinh_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `nhan_su`
--
ALTER TABLE `nhan_su`
  MODIFY `nhan_su_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nhat_ky_tour`
--
ALTER TABLE `nhat_ky_tour`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nha_cung_cap`
--
ALTER TABLE `nha_cung_cap`
  MODIFY `id_nha_cung_cap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phan_bo_dich_vu`
--
ALTER TABLE `phan_bo_dich_vu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phan_bo_history`
--
ALTER TABLE `phan_bo_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phan_bo_nhan_su`
--
ALTER TABLE `phan_bo_nhan_su`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `phan_hoi_danh_gia`
--
ALTER TABLE `phan_hoi_danh_gia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `thong_bao_hdv`
--
ALTER TABLE `thong_bao_hdv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour`
--
ALTER TABLE `tour`
  MODIFY `tour_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tour_checkin`
--
ALTER TABLE `tour_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `yeu_cau_dac_biet`
--
ALTER TABLE `yeu_cau_dac_biet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_1` FOREIGN KEY (`khach_hang_id`) REFERENCES `khach_hang` (`khach_hang_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yeu_cau_dac_biet_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
