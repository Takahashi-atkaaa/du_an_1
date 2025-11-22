-- ============================================
-- 🧭 DATABASE: QUAN_LY_TOUR_DU_LICH
-- Phiên bản: Hoàn chỉnh với hệ thống quản lý HDV nâng cao
-- Ngày tạo: 2025
-- ============================================

CREATE DATABASE IF NOT EXISTS quan_ly_tour_du_lich;
USE quan_ly_tour_du_lich;

-- ============================================
-- PHẦN 1: TẠO CÁC BẢNG CƠ BẢN
-- ============================================

-- ==============================
-- 1. BẢNG NGƯỜI DÙNG (CHUNG)
-- ==============================
CREATE TABLE nguoi_dung (
  id INT PRIMARY KEY AUTO_INCREMENT,
  ten_dang_nhap VARCHAR(100) UNIQUE,
  mat_khau VARCHAR(255),
  ho_ten VARCHAR(255),
  avatar VARCHAR(255),
  email VARCHAR(255),
  so_dien_thoai VARCHAR(20),
  vai_tro ENUM('Admin','HDV','KhachHang','NhaCungCap'),
  quyen_cap_cao BOOLEAN DEFAULT FALSE,
  trang_thai ENUM('HoatDong','BiKhoa') DEFAULT 'HoatDong',
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 2. BẢNG KHÁCH HÀNG (THÔNG TIN RIÊNG)
-- ======================================
CREATE TABLE khach_hang (
  khach_hang_id INT PRIMARY KEY AUTO_INCREMENT,
  nguoi_dung_id INT,
  dia_chi VARCHAR(255),
  gioi_tinh ENUM('Nam','Nữ','Khác'),
  ngay_sinh DATE,
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 3. BẢNG NHÂN SỰ (HDV, ĐIỀU HÀNH, TÀI XẾ)
-- ======================================
CREATE TABLE nhan_su (
  nhan_su_id INT PRIMARY KEY AUTO_INCREMENT,
  nguoi_dung_id INT,
  vai_tro ENUM('HDV','DieuHanh','TaiXe','Khac'),
  loai_hdv ENUM('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') DEFAULT 'TongHop' COMMENT 'Loại HDV',
  chuyen_tuyen VARCHAR(255) COMMENT 'Các tuyến chuyên: Miền Bắc, Miền Trung, Miền Nam, Đông Nam Á...',
  danh_gia_tb DECIMAL(3,2) DEFAULT 0 COMMENT 'Điểm đánh giá trung bình 0-5',
  so_tour_da_dan INT DEFAULT 0 COMMENT 'Tổng số tour đã dẫn',
  trang_thai_lam_viec ENUM('SanSang','DangBan','NghiPhep','TamNghi') DEFAULT 'SanSang' COMMENT 'Trạng thái làm việc',
  chung_chi TEXT,
  ngon_ngu TEXT,
  kinh_nghiem TEXT,
  suc_khoe TEXT,
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 4. BẢNG NHÀ CUNG CẤP
-- ======================================
CREATE TABLE nha_cung_cap (
  id_nha_cung_cap INT PRIMARY KEY AUTO_INCREMENT,
  nguoi_dung_id INT,
  ten_don_vi VARCHAR(255),
  loai_dich_vu ENUM('KhachSan','NhaHang','Xe','Ve','Visa','BaoHiem','Khac'),
  dia_chi VARCHAR(255),
  lien_he VARCHAR(100),
  mo_ta TEXT,
  danh_gia_tb FLOAT,
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 5. BẢNG TOUR
-- ======================================
CREATE TABLE tour (
  tour_id INT PRIMARY KEY AUTO_INCREMENT,
  ten_tour VARCHAR(255),
  loai_tour ENUM('TrongNuoc','QuocTe','TheoYeuCau'),
  mo_ta TEXT,
  gia_co_ban DECIMAL(15,2),
  chinh_sach TEXT,
  id_nha_cung_cap INT,
  tao_boi INT,
  trang_thai ENUM('HoatDong','TamDung','HetHan') DEFAULT 'HoatDong',
  FOREIGN KEY (id_nha_cung_cap) REFERENCES nha_cung_cap(id_nha_cung_cap) ON DELETE SET NULL,
  FOREIGN KEY (tao_boi) REFERENCES nguoi_dung(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 6. BẢNG LỊCH TRÌNH TOUR
-- ======================================
CREATE TABLE lich_trinh_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  ngay_thu INT,
  dia_diem VARCHAR(255),
  hoat_dong TEXT,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 7. BẢNG HÌNH ẢNH TOUR
-- ======================================
CREATE TABLE hinh_anh_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  url_anh VARCHAR(255),
  mo_ta VARCHAR(255),
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 8. BẢNG BOOKING (ĐẶT TOUR)
-- ======================================
CREATE TABLE booking (
  booking_id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  khach_hang_id INT,
  ngay_dat DATE,
  ngay_khoi_hanh DATE,
  so_nguoi INT,
  tong_tien DECIMAL(15,2),
  trang_thai ENUM('ChoXacNhan','DaCoc','HoanTat','Huy'),
  ghi_chu TEXT,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 9. BẢNG LỊCH KHỞI HÀNH (PHÂN CÔNG HDV)
-- ======================================
CREATE TABLE lich_khoi_hanh (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  ngay_khoi_hanh DATE,
  gio_xuat_phat TIME NULL,
  ngay_ket_thuc DATE,
  gio_ket_thuc TIME NULL,
  diem_tap_trung VARCHAR(255),
  so_cho INT DEFAULT 50,
  hdv_id INT,
  trang_thai ENUM('SapKhoiHanh','DangChay','HoanThanh'),
  ghi_chu TEXT NULL,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (hdv_id) REFERENCES nhan_su(nhan_su_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 10. BẢNG NHẬT KÝ TOUR
-- ======================================
CREATE TABLE nhat_ky_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  nhan_su_id INT,
  noi_dung TEXT,
  ngay_ghi DATE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CẬP NHẬT BẢNG NHẬT KÝ TOUR
-- Thêm cột loai_nhat_ky
ALTER TABLE nhat_ky_tour 
ADD COLUMN loai_nhat_ky ENUM('hanh_trinh', 'su_co', 'phan_hoi', 'hoat_dong') 
DEFAULT 'hanh_trinh' 
COMMENT 'Loại nhật ký: hành trình, sự cố, phản hồi khách, hoạt động'
AFTER nhan_su_id;

-- Thêm cột tieu_de
ALTER TABLE nhat_ky_tour 
ADD COLUMN tieu_de VARCHAR(255) 
COMMENT 'Tiêu đề nhật ký'
AFTER loai_nhat_ky;

-- Thêm cột cach_xu_ly
ALTER TABLE nhat_ky_tour 
ADD COLUMN cach_xu_ly TEXT 
COMMENT 'Cách xử lý sự cố'
AFTER noi_dung;

-- Thêm cột hinh_anh
ALTER TABLE nhat_ky_tour 
ADD COLUMN hinh_anh TEXT 
COMMENT 'JSON array chứa đường dẫn hình ảnh'
AFTER cach_xu_ly;

-- Cập nhật kiểu dữ liệu cột ngay_ghi
ALTER TABLE nhat_ky_tour 
MODIFY COLUMN ngay_ghi DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Hiển thị cấu trúc bảng sau khi cập nhật
DESCRIBE nhat_ky_tour;


-- ======================================
-- 11. BẢNG PHẢN HỒI & ĐÁNH GIÁ
-- ======================================
CREATE TABLE phan_hoi_danh_gia (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  nguoi_dung_id INT,
  loai ENUM('Tour','DichVu','NhaCungCap'),
  diem INT COMMENT 'Điểm đánh giá từ 1-5',
  noi_dung TEXT,
  ngay_danh_gia DATE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 12. BẢNG GIAO DỊCH TÀI CHÍNH
-- ======================================
CREATE TABLE giao_dich_tai_chinh (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  loai ENUM('Thu','Chi'),
  so_tien DECIMAL(15,2),
  mo_ta TEXT,
  ngay_giao_dich DATE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 13. YÊU CẦU ĐẶC BIỆT (optional) - Đã di chuyển xuống dòng 466
-- ======================================
-- DROP TABLE IF EXISTS yeu_cau_dac_biet; -- Bảng cũ đã được thay thế

-- ======================================
-- 14. BẢNG LỊCH SỬ THAY ĐỔI BOOKING
-- ======================================
CREATE TABLE booking_history (
  id INT PRIMARY KEY AUTO_INCREMENT,
  booking_id INT NOT NULL,
  trang_thai_cu ENUM('ChoXacNhan','DaCoc','HoanTat','Huy') NULL,
  trang_thai_moi ENUM('ChoXacNhan','DaCoc','HoanTat','Huy') NOT NULL,
  nguoi_thay_doi_id INT NULL,
  ghi_chu TEXT NULL,
  thoi_gian TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
  FOREIGN KEY (nguoi_thay_doi_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
  INDEX idx_booking_id (booking_id),
  INDEX idx_thoi_gian (thoi_gian)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 15. BẢNG PHÂN BỔ NHÂN SỰ CHO LỊCH KHỞI HÀNH
-- ======================================
CREATE TABLE phan_bo_nhan_su (
  id INT PRIMARY KEY AUTO_INCREMENT,
  lich_khoi_hanh_id INT NOT NULL,
  nhan_su_id INT NOT NULL,
  vai_tro ENUM('HDV','TaiXe','HauCan','DieuHanh','Khac') NOT NULL,
  ghi_chu TEXT NULL,
  trang_thai ENUM('ChoXacNhan','DaXacNhan','TuChoi','Huy') DEFAULT 'ChoXacNhan',
  thoi_gian_xac_nhan DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lich_khoi_hanh_id) REFERENCES lich_khoi_hanh(id) ON DELETE CASCADE,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  INDEX idx_lich_khoi_hanh (lich_khoi_hanh_id),
  INDEX idx_nhan_su (nhan_su_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 16. BẢNG PHÂN BỔ DỊCH VỤ CHO LỊCH KHỞI HÀNH
-- ======================================
CREATE TABLE phan_bo_dich_vu (
  id INT PRIMARY KEY AUTO_INCREMENT,
  lich_khoi_hanh_id INT NOT NULL,
  nha_cung_cap_id INT NULL,
  loai_dich_vu ENUM('Xe','KhachSan','VeMayBay','NhaHang','DiemThamQuan','Visa','BaoHiem','Khac') NOT NULL,
  ten_dich_vu VARCHAR(255) NOT NULL,
  so_luong INT DEFAULT 1,
  don_vi VARCHAR(50) NULL,
  ngay_bat_dau DATE NULL,
  ngay_ket_thuc DATE NULL,
  gio_bat_dau TIME NULL,
  gio_ket_thuc TIME NULL,
  dia_diem VARCHAR(255) NULL,
  gia_tien DECIMAL(15,2) NULL,
  ghi_chu TEXT NULL,
  trang_thai ENUM('ChoXacNhan','DaXacNhan','TuChoi','Huy','HoanTat') DEFAULT 'ChoXacNhan',
  thoi_gian_xac_nhan DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (lich_khoi_hanh_id) REFERENCES lich_khoi_hanh(id) ON DELETE CASCADE,
  FOREIGN KEY (nha_cung_cap_id) REFERENCES nha_cung_cap(id_nha_cung_cap) ON DELETE SET NULL,
  INDEX idx_lich_khoi_hanh (lich_khoi_hanh_id),
  INDEX idx_nha_cung_cap (nha_cung_cap_id),
  INDEX idx_loai_dich_vu (loai_dich_vu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 17. BẢNG LỊCH SỬ THAY ĐỔI PHÂN BỔ (AUDIT LOG)
-- ======================================
CREATE TABLE phan_bo_history (
  id INT PRIMARY KEY AUTO_INCREMENT,
  phan_bo_id INT NOT NULL,
  loai_phan_bo ENUM('NhanSu','DichVu') NOT NULL,
  thay_doi TEXT NOT NULL,
  nguoi_thay_doi_id INT NULL,
  thoi_gian TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nguoi_thay_doi_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
  INDEX idx_phan_bo (phan_bo_id, loai_phan_bo),
  INDEX idx_thoi_gian (thoi_gian)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- PHẦN 2: CÁC BẢNG QUẢN LÝ HDV NÂNG CAO
-- ============================================

-- ======================================
-- 18. BẢNG LỊCH LÀM VIỆC HDV (theo dõi lịch, ngày nghỉ, ngày bận)
-- ======================================
CREATE TABLE lich_lam_viec_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  tour_id INT NULL COMMENT 'NULL nếu là ngày nghỉ/bận',
  loai_lich ENUM('Tour','NghiPhep','Ban','DatTruoc') NOT NULL COMMENT 'Loại lịch làm việc',
  ngay_bat_dau DATE NOT NULL,
  ngay_ket_thuc DATE NOT NULL,
  ghi_chu TEXT,
  trang_thai ENUM('DuKien','XacNhan','HoanThanh','Huy') DEFAULT 'DuKien',
  nguoi_tao_id INT COMMENT 'Người tạo lịch (admin)',
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE SET NULL,
  FOREIGN KEY (nguoi_tao_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_ngay (ngay_bat_dau, ngay_ket_thuc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch làm việc HDV: tour, nghỉ phép, bận';

-- ======================================
-- 19. BẢNG HIỆU SUẤT HDV (phân tích, đánh giá)
-- ======================================
CREATE TABLE hieu_suat_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  thang INT NOT NULL COMMENT 'Tháng 1-12',
  nam INT NOT NULL COMMENT 'Năm',
  so_tour_thang INT DEFAULT 0 COMMENT 'Số tour trong tháng',
  so_ngay_lam_viec INT DEFAULT 0 COMMENT 'Số ngày làm việc',
  doanh_thu_mang_lai DECIMAL(15,2) DEFAULT 0 COMMENT 'Doanh thu tour đã dẫn',
  diem_danh_gia_tb DECIMAL(3,2) DEFAULT 0 COMMENT 'Điểm TB từ khách hàng',
  so_khieu_nai INT DEFAULT 0 COMMENT 'Số khiếu nại trong tháng',
  so_khen_thuong INT DEFAULT 0 COMMENT 'Số lần được khen thưởng',
  ghi_chu TEXT,
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  UNIQUE KEY unique_thang_nam (nhan_su_id, thang, nam),
  INDEX idx_thang_nam (thang, nam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Báo cáo hiệu suất HDV theo tháng';

-- ======================================
-- 20. BẢNG ĐÁNH GIÁ HDV (từ khách hàng sau tour)
-- ======================================
CREATE TABLE danh_gia_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT NOT NULL,
  nhan_su_id INT NOT NULL COMMENT 'HDV được đánh giá',
  khach_hang_id INT COMMENT 'Khách hàng đánh giá',
  diem_chuyen_mon TINYINT COMMENT 'Điểm chuyên môn 1-5',
  diem_thai_do TINYINT COMMENT 'Điểm thái độ 1-5',
  diem_giao_tiep TINYINT COMMENT 'Điểm giao tiếp 1-5',
  diem_tong DECIMAL(3,2) COMMENT 'Điểm tổng = TB 3 tiêu chí',
  noi_dung_danh_gia TEXT COMMENT 'Nhận xét chi tiết',
  ngay_danh_gia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id) ON DELETE SET NULL,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_tour (tour_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Đánh giá HDV từ khách hàng';

-- ======================================
-- 21. BẢNG THÔNG BÁO/NHẮC NHỞ HDV
-- ======================================
CREATE TABLE thong_bao_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT COMMENT 'NULL = thông báo chung cho tất cả HDV',
  loai_thong_bao ENUM('LichTour','NhacNho','CanhBao','ThongBao') NOT NULL,
  tieu_de VARCHAR(255) NOT NULL,
  noi_dung TEXT NOT NULL,
  uu_tien ENUM('Thap','TrungBinh','Cao','KhanCap') DEFAULT 'TrungBinh',
  da_xem BOOLEAN DEFAULT FALSE,
  ngay_gui TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ngay_xem TIMESTAMP NULL,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  INDEX idx_nhan_su_chua_xem (nhan_su_id, da_xem)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông báo và nhắc nhở cho HDV';

-- ======================================
-- 22. BẢNG CHỨNG CHỈ HDV (chi tiết hơn)
-- ======================================
CREATE TABLE chung_chi_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  ten_chung_chi VARCHAR(255) NOT NULL COMMENT 'Tên chứng chỉ/bằng cấp',
  loai_chung_chi ENUM('HDV','NgoaiNgu','KyNang','AnToan','Khac') NOT NULL,
  co_quan_cap VARCHAR(255) COMMENT 'Nơi cấp',
  ngay_cap DATE,
  ngay_het_han DATE COMMENT 'NULL nếu vô thời hạn',
  so_chung_chi VARCHAR(100),
  file_dinh_kem VARCHAR(255) COMMENT 'Link file scan chứng chỉ',
  trang_thai ENUM('ConHan','SapHetHan','HetHan') DEFAULT 'ConHan',
  ghi_chu TEXT,
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_het_han (ngay_het_han)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý chi tiết chứng chỉ HDV';

-- ======================================
-- 23. BẢNG ĐIỂM CHECK-IN TRONG TOUR
-- ======================================
CREATE TABLE IF NOT EXISTS `diem_checkin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tour_id` INT NOT NULL,
  `ten_diem` VARCHAR(255) NOT NULL,
  `loai_diem` ENUM('tap_trung', 'tham_quan', 'an_uong', 'nghi_ngoi', 'khac') DEFAULT 'tap_trung',
  `thoi_gian_du_kien` DATETIME,
  `ghi_chu` TEXT,
  `thu_tu` INT DEFAULT 1,
  `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tour_id`) REFERENCES `tour`(`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 24. Bảng chi tiết check-in của từng khách
-- ======================================
CREATE TABLE IF NOT EXISTS `checkin_khach` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `diem_checkin_id` INT NOT NULL,
  `booking_id` INT NOT NULL,
  `trang_thai` ENUM('chua_checkin', 'da_checkin', 'vang_mat', 're_gio') DEFAULT 'chua_checkin',
  `thoi_gian_checkin` DATETIME,
  `ghi_chu` TEXT,
  `nguoi_checkin_id` INT,
  `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`diem_checkin_id`) REFERENCES `diem_checkin`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booking_id`) REFERENCES `booking`(`booking_id`) ON DELETE CASCADE,
  FOREIGN KEY (`nguoi_checkin_id`) REFERENCES `nhan_su`(`nhan_su_id`),
  UNIQUE KEY `unique_checkin` (`diem_checkin_id`, `booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm index để tăng tốc query
CREATE INDEX idx_diem_checkin_tour ON diem_checkin(tour_id, thu_tu);
CREATE INDEX idx_checkin_khach_diem ON checkin_khach(diem_checkin_id, trang_thai);
CREATE INDEX idx_checkin_khach_booking ON checkin_khach(booking_id);

-- ======================================
-- 25. Bảng yêu cầu đặc biệt của khách hàng
-- ======================================
CREATE TABLE `yeu_cau_dac_biet` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `booking_id` INT NOT NULL,
  `loai_yeu_cau` ENUM('an_uong', 'suc_khoe', 'di_chuyen', 'phong_o', 'hoat_dong', 'khac') DEFAULT 'khac',
  `tieu_de` VARCHAR(255) NOT NULL,
  `mo_ta` TEXT,
  `muc_do_uu_tien` ENUM('thap', 'trung_binh', 'cao', 'khan_cap') DEFAULT 'trung_binh',
  `trang_thai` ENUM('moi', 'dang_xu_ly', 'da_giai_quyet', 'khong_the_thuc_hien') DEFAULT 'moi',
  `ghi_chu_hdv` TEXT,
  `nguoi_tao_id` INT,
  `nguoi_xu_ly_id` INT,
  `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_yeu_cau_booking` (`booking_id`, `trang_thai`),
  INDEX `idx_yeu_cau_loai` (`loai_yeu_cau`, `muc_do_uu_tien`),
  FOREIGN KEY (`booking_id`) REFERENCES `booking`(`booking_id`) ON DELETE CASCADE,
  FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung`(`id`),
  FOREIGN KEY (`nguoi_xu_ly_id`) REFERENCES `nhan_su`(`nhan_su_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 26.Bảng lịch sử cập nhật yêu cầu đặc biệt
-- ======================================
CREATE TABLE `lich_su_yeu_cau` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `yeu_cau_id` INT NOT NULL,
  `hanh_dong` VARCHAR(100) NOT NULL,
  `noi_dung` TEXT,
  `nguoi_thuc_hien_id` INT,
  `ngay_thuc_hien` DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_lich_su_yeu_cau` (`yeu_cau_id`, `ngay_thuc_hien`),
  FOREIGN KEY (`yeu_cau_id`) REFERENCES `yeu_cau_dac_biet`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`nguoi_thuc_hien_id`) REFERENCES `nguoi_dung`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 27.Bảng phản hồi đánh giá của HDV
-- ======================================
CREATE TABLE IF NOT EXISTS `phan_hoi_hdv` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tour_id` INT NOT NULL,
  `hdv_id` INT NOT NULL,
  `loai_danh_gia` ENUM('tour', 'khach_san', 'nha_hang', 'van_chuyen', 'nha_cung_cap', 'khac') NOT NULL,
  `ten_doi_tuong` VARCHAR(255) NOT NULL COMMENT 'Tên khách sạn, nhà hàng, xe, nhà cung cấp...',
  `doi_tuong_id` INT NULL COMMENT 'ID của đối tượng nếu có trong hệ thống',
  `diem_danh_gia` TINYINT NOT NULL CHECK (`diem_danh_gia` BETWEEN 1 AND 5),
  `tieu_de` VARCHAR(255) NOT NULL,
  `noi_dung` TEXT NOT NULL,
  `diem_manh` TEXT COMMENT 'Những điểm tốt, ưu điểm',
  `diem_yeu` TEXT COMMENT 'Những điểm cần cải thiện',
  `de_xuat` TEXT COMMENT 'Đề xuất, kiến nghị',
  `hinh_anh` TEXT COMMENT 'JSON array chứa đường dẫn các ảnh minh chứng',
  `trang_thai` ENUM('moi', 'da_xem', 'dang_xu_ly', 'da_xu_ly') DEFAULT 'moi',
  `nguoi_xu_ly_id` INT NULL COMMENT 'Quản lý xử lý phản hồi',
  `ghi_chu_xu_ly` TEXT COMMENT 'Ghi chú từ quản lý khi xử lý',
  `ngay_tao` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `ngay_cap_nhat` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_phan_hoi_tour` (`tour_id`, `loai_danh_gia`),
  INDEX `idx_phan_hoi_hdv` (`hdv_id`, `ngay_tao`),
  INDEX `idx_phan_hoi_trang_thai` (`trang_thai`, `ngay_tao`),
  INDEX `idx_phan_hoi_diem` (`diem_danh_gia`, `loai_danh_gia`),
  FOREIGN KEY (`tour_id`) REFERENCES `tour`(`tour_id`) ON DELETE CASCADE,
  FOREIGN KEY (`hdv_id`) REFERENCES `nhan_su`(`nhan_su_id`) ON DELETE CASCADE,
  FOREIGN KEY (`nguoi_xu_ly_id`) REFERENCES `nhan_su`(`nhan_su_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ======================================
-- 28.Bảng thống kê đánh giá theo đối tượng
-- ======================================
CREATE TABLE IF NOT EXISTS `thong_ke_danh_gia` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `loai_doi_tuong` ENUM('tour', 'khach_san', 'nha_hang', 'van_chuyen', 'nha_cung_cap', 'khac') NOT NULL,
  `ten_doi_tuong` VARCHAR(255) NOT NULL,
  `doi_tuong_id` INT NULL,
  `tong_danh_gia` INT DEFAULT 0,
  `diem_trung_binh` DECIMAL(3,2) DEFAULT 0.00,
  `so_sao_1` INT DEFAULT 0,
  `so_sao_2` INT DEFAULT 0,
  `so_sao_3` INT DEFAULT 0,
  `so_sao_4` INT DEFAULT 0,
  `so_sao_5` INT DEFAULT 0,
  `lan_cap_nhat_cuoi` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_doi_tuong` (`loai_doi_tuong`, `ten_doi_tuong`),
  INDEX `idx_thong_ke_loai` (`loai_doi_tuong`, `diem_trung_binh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- PHẦN 3: INDEX TỐI ƯU TÌM KIẾM
-- ============================================

CREATE INDEX idx_loai_hdv ON nhan_su(loai_hdv, trang_thai_lam_viec);
CREATE INDEX idx_lich_hdv_trang_thai ON lich_lam_viec_hdv(nhan_su_id, trang_thai, ngay_bat_dau);

-- ============================================
-- PHẦN 4: TRIGGER TỰ ĐỘNG CẬP NHẬT
-- ============================================

-- Trigger: Tự động tính điểm tổng khi đánh giá HDV
DELIMITER $$
CREATE TRIGGER before_insert_danh_gia_hdv
BEFORE INSERT ON danh_gia_hdv
FOR EACH ROW
BEGIN
    IF NEW.diem_chuyen_mon IS NOT NULL AND NEW.diem_thai_do IS NOT NULL AND NEW.diem_giao_tiep IS NOT NULL THEN
        SET NEW.diem_tong = (NEW.diem_chuyen_mon + NEW.diem_thai_do + NEW.diem_giao_tiep) / 3;
    END IF;
END$$
DELIMITER ;

-- Trigger: Cập nhật điểm TB và số tour đã dẫn của HDV khi có đánh giá mới
DELIMITER $$
CREATE TRIGGER after_insert_danh_gia_hdv
AFTER INSERT ON danh_gia_hdv
FOR EACH ROW
BEGIN
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
END$$
DELIMITER ;

-- Trigger: Cảnh báo khi chứng chỉ sắp hết hạn (30 ngày)
DELIMITER $$
CREATE TRIGGER after_insert_chung_chi_hdv
AFTER INSERT ON chung_chi_hdv
FOR EACH ROW
BEGIN
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
END$$
DELIMITER ;

-- ============================================
-- PHẦN 5: VIEW HỖ TRỢ TRUY VẤN NHANH
-- ============================================

-- View: HDV đang rảnh (sẵn sàng nhận tour)
CREATE VIEW v_hdv_san_sang AS
SELECT 
    ns.nhan_su_id,
    nd.ho_ten,
    nd.email,
    nd.so_dien_thoai,
    ns.loai_hdv,
    ns.chuyen_tuyen,
    ns.danh_gia_tb,
    ns.so_tour_da_dan,
    ns.ngon_ngu
FROM nhan_su ns
INNER JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
WHERE ns.vai_tro = 'HDV' 
  AND ns.trang_thai_lam_viec = 'SanSang'
  AND ns.nhan_su_id NOT IN (
      SELECT nhan_su_id 
      FROM lich_lam_viec_hdv 
      WHERE trang_thai IN ('DuKien', 'XacNhan')
        AND CURDATE() BETWEEN ngay_bat_dau AND ngay_ket_thuc
  );

-- View: Thống kê hiệu suất HDV
CREATE VIEW v_thong_ke_hieu_suat_hdv AS
SELECT 
    ns.nhan_su_id,
    nd.ho_ten,
    ns.loai_hdv,
    COUNT(DISTINCT llv.tour_id) as tong_tour,
    AVG(dg.diem_tong) as diem_tb,
    SUM(CASE WHEN llv.trang_thai = 'HoanThanh' THEN 1 ELSE 0 END) as tour_hoan_thanh,
    MAX(llv.ngay_ket_thuc) as tour_gan_nhat
FROM nhan_su ns
INNER JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
LEFT JOIN lich_lam_viec_hdv llv ON ns.nhan_su_id = llv.nhan_su_id AND llv.loai_lich = 'Tour'
LEFT JOIN danh_gia_hdv dg ON ns.nhan_su_id = dg.nhan_su_id
WHERE ns.vai_tro = 'HDV'
GROUP BY ns.nhan_su_id, nd.ho_ten, ns.loai_hdv;

-- ============================================
-- PHẦN 6: DỮ LIỆU MẪU
-- ============================================

-- Dữ liệu mẫu cho bảng NGƯỜI DÙNG
INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, ho_ten, email, vai_tro, quyen_cap_cao)
VALUES 
('admin', 'admin123', 'Quản trị viên hệ thống', 'admin@tour.com', 'Admin', TRUE),
('hdv01', 'hdv123', 'Nguyễn Văn Hướng', 'hdv@tour.com', 'HDV', FALSE),
('khach01', 'khach123', 'Trần Thị Khách', 'khach@tour.com', 'KhachHang', FALSE),
('ncc01', 'ncc123', 'Công ty ABC Travel', 'ncc@tour.com', 'NhaCungCap', FALSE);

-- Dữ liệu mẫu cho bảng KHÁCH HÀNG
INSERT INTO khach_hang (nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'khach01'), '123 Đường A, Quận B, TP. HCM', 'Nữ', '1995-05-10');

-- Dữ liệu mẫu cho bảng NHÂN SỰ (HDV)
INSERT INTO nhan_su (nguoi_dung_id, vai_tro, loai_hdv, chuyen_tuyen, danh_gia_tb, so_tour_da_dan, trang_thai_lam_viec, chung_chi, ngon_ngu, kinh_nghiem, suc_khoe) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'hdv01'), 'HDV', 'NoiDia', 'Miền Bắc', 0, 0, 'SanSang', 'Chứng chỉ nghiệp vụ hướng dẫn viên', 'Tiếng Việt, Tiếng Anh', '5 năm dẫn tour nội địa', 'Tốt');

-- Dữ liệu mẫu cho bảng NHÀ CUNG CẤP
INSERT INTO nha_cung_cap (nguoi_dung_id, ten_don_vi, loai_dich_vu, dia_chi, lien_he, mo_ta, danh_gia_tb) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'ncc01'), 'ABC Travel Services', 'KhachSan', '456 Đường C, Quận D, Hà Nội', '0123456789', 'Đối tác cung cấp khách sạn 3-4 sao', 4.5);

-- Dữ liệu mẫu cho bảng TOUR
INSERT INTO tour (ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, id_nha_cung_cap, tao_boi, trang_thai) VALUES
('Hà Nội - Hạ Long 3N2Đ', 'TrongNuoc', 'Khám phá Vịnh Hạ Long kỳ quan thiên nhiên thế giới', 3500000, 'Hủy trước 7 ngày: hoàn 80%', (SELECT id_nha_cung_cap FROM nha_cung_cap LIMIT 1), (SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'admin'), 'HoatDong');

-- Dữ liệu mẫu cho bảng LỊCH TRÌNH TOUR
INSERT INTO lich_trinh_tour (tour_id, ngay_thu, dia_diem, hoat_dong) VALUES
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 1, 'Hà Nội', 'Đón khách - Tham quan phố cổ - Ăn tối'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 2, 'Hạ Long', 'Tham quan Vịnh Hạ Long - Nghỉ đêm trên du thuyền'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 3, 'Hạ Long - Hà Nội', 'Tham quan hang động - Trở về Hà Nội');

-- Dữ liệu mẫu cho bảng HÌNH ẢNH TOUR
INSERT INTO hinh_anh_tour (tour_id, url_anh, mo_ta) VALUES
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 'public/images/halong1.jpg', 'Toàn cảnh Vịnh Hạ Long'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 'public/images/halong2.jpg', 'Du thuyền trên Vịnh');

-- Dữ liệu mẫu cho bảng BOOKING
INSERT INTO booking (tour_id, khach_hang_id, ngay_dat, ngay_khoi_hanh, so_nguoi, tong_tien, trang_thai, ghi_chu) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  (SELECT khach_hang_id FROM khach_hang LIMIT 1),
  CURDATE(),
  DATE_ADD(CURDATE(), INTERVAL 10 DAY),
  2,
  7000000,
  'ChoXacNhan',
  'Yêu cầu phòng đôi'
);

-- Dữ liệu mẫu cho bảng LỊCH KHỞI HÀNH
INSERT INTO lich_khoi_hanh (tour_id, ngay_khoi_hanh, gio_xuat_phat, ngay_ket_thuc, gio_ket_thuc, diem_tap_trung, so_cho, hdv_id, trang_thai, ghi_chu) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  DATE_ADD(CURDATE(), INTERVAL 10 DAY),
  '06:00:00',
  DATE_ADD(CURDATE(), INTERVAL 12 DAY),
  '18:00:00',
  'Sân bay Nội Bài - Cổng A',
  50,
  (SELECT nhan_su_id FROM nhan_su LIMIT 1),
  'SapKhoiHanh',
  'Lịch khởi hành mẫu cho tour Hạ Long'
);

-- Dữ liệu mẫu cho bảng NHẬT KÝ TOUR
INSERT INTO nhat_ky_tour (tour_id, nhan_su_id, noi_dung, ngay_ghi) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  (SELECT nhan_su_id FROM nhan_su LIMIT 1),
  'Đã kiểm tra trang thiết bị an toàn trên du thuyền',
  CURDATE()
);

-- Dữ liệu mẫu cho bảng PHẢN HỒI & ĐÁNH GIÁ
INSERT INTO phan_hoi_danh_gia (tour_id, nguoi_dung_id, loai, diem, noi_dung, ngay_danh_gia) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  (SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'khach01'),
  'Tour',
  5,
  'Trải nghiệm tuyệt vời, hướng dẫn viên nhiệt tình!',
  CURDATE()
);

-- Dữ liệu mẫu cho bảng GIAO DỊCH TÀI CHÍNH
INSERT INTO giao_dich_tai_chinh (tour_id, loai, so_tien, mo_ta, ngay_giao_dich) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  'Thu',
  7000000,
  'Khách đặt cọc/Thanh toán',
  CURDATE()
),
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  'Chi',
  2000000,
  'Đặt cọc dịch vụ du thuyền',
  CURDATE()
);

-- Dữ liệu mẫu cho bảng YÊU CẦU ĐẶC BIỆT
INSERT INTO yeu_cau_dac_biet (khach_hang_id, tour_id, noi_dung) VALUES
(
  (SELECT khach_hang_id FROM khach_hang LIMIT 1),
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  'Chuẩn bị bánh sinh nhật bất ngờ ngày 2'
);

-- Dữ liệu mẫu cho HỆ THỐNG QUẢN LÝ HDV NÂNG CAO
-- Thêm lịch làm việc mẫu (ngày nghỉ)
INSERT INTO lich_lam_viec_hdv (nhan_su_id, loai_lich, ngay_bat_dau, ngay_ket_thuc, ghi_chu, trang_thai)
SELECT nhan_su_id, 'NghiPhep', CURDATE() + INTERVAL 7 DAY, CURDATE() + INTERVAL 9 DAY, 'Nghỉ phép năm', 'XacNhan'
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- Thêm báo cáo hiệu suất mẫu
INSERT INTO hieu_suat_hdv (nhan_su_id, thang, nam, so_tour_thang, so_ngay_lam_viec, diem_danh_gia_tb)
SELECT nhan_su_id, MONTH(CURDATE()), YEAR(CURDATE()), 3, 15, 4.5
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- Thêm thông báo mẫu
INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
SELECT nhan_su_id, 'NhacNho', 'Chuẩn bị tour tuần sau', 'Tour Hà Nội - Hạ Long sẽ khởi hành vào 20/11/2025. Vui lòng chuẩn bị tài liệu và thiết bị.', 'Cao'
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- ============================================
-- KẾT THÚC FILE DATABASE
-- ============================================


