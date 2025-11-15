-- ============================================
-- 🧭 DATABASE: QUAN_LY_TOUR_DU_LICH
-- Phiên bản: Có 4 tác nhân, Admin quyền cao nhất
-- ============================================

CREATE DATABASE IF NOT EXISTS quan_ly_tour_du_lich;
USE quan_ly_tour_du_lich;

-- ==============================
-- 1. BẢNG NGƯỜI DÙNG (CHUNG)
-- ==============================
CREATE TABLE nguoi_dung (
  id INT PRIMARY KEY AUTO_INCREMENT,
  ten_dang_nhap VARCHAR(100) UNIQUE,
  mat_khau VARCHAR(255),
  ho_ten VARCHAR(255),
    avatar varchar(255),
  email VARCHAR(255),
  so_dien_thoai VARCHAR(20),
  vai_tro ENUM('Admin','HDV','KhachHang','NhaCungCap'),
  quyen_cap_cao BOOLEAN DEFAULT FALSE,
  trang_thai ENUM('HoatDong','BiKhoa') DEFAULT 'HoatDong',
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================================
-- 2. BẢNG KHÁCH HÀNG (THÔNG TIN RIÊNG)
-- ======================================
CREATE TABLE khach_hang (
  khach_hang_id INT PRIMARY KEY AUTO_INCREMENT,
  nguoi_dung_id INT,
  dia_chi VARCHAR(255),
  gioi_tinh ENUM('Nam','Nữ','Khác'),
  ngay_sinh DATE,
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id)
);

-- ======================================
-- 3. BẢNG NHÂN SỰ (HDV, ĐIỀU HÀNH, TÀI XẾ)
-- ======================================
CREATE TABLE nhan_su (
  nhan_su_id INT PRIMARY KEY AUTO_INCREMENT,
  nguoi_dung_id INT,
  vai_tro ENUM('HDV','DieuHanh','TaiXe','Khac'),
  chung_chi TEXT,
  ngon_ngu TEXT,
  kinh_nghiem TEXT,
  suc_khoe TEXT,

  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id)
);

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
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id)
);

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
  FOREIGN KEY (id_nha_cung_cap) REFERENCES nha_cung_cap(id_nha_cung_cap),
  FOREIGN KEY (tao_boi) REFERENCES nguoi_dung(id)
);

-- ======================================
-- 6. BẢNG LỊCH TRÌNH TOUR
-- ======================================
CREATE TABLE lich_trinh_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  ngay_thu INT,
  dia_diem VARCHAR(255),
  hoat_dong TEXT,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id)
);

-- ======================================
-- 7. BẢNG HÌNH ẢNH TOUR
-- ======================================
CREATE TABLE hinh_anh_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  url_anh VARCHAR(255),
  mo_ta VARCHAR(255),
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id)
);

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
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id),
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id)
);

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
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id),
  FOREIGN KEY (hdv_id) REFERENCES nhan_su(nhan_su_id)
);

-- ======================================
-- 10. BẢNG NHẬT KÝ TOUR
-- ======================================
CREATE TABLE nhat_ky_tour (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  nhan_su_id INT,
  noi_dung TEXT,
  ngay_ghi DATE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id),
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id)
);

-- ======================================
-- 11. BẢNG PHẢN HỒI & ĐÁNH GIÁ
-- ======================================
CREATE TABLE phan_hoi_danh_gia (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT,
  nguoi_dung_id INT,
  loai ENUM('Tour','DichVu','NhaCungCap'),
  diem INT CHECK (diem BETWEEN 1 AND 5),
  noi_dung TEXT,
  ngay_danh_gia DATE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id),
  FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id)
);

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
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id)
);

-- ======================================
-- 13. BẢNG YÊU CẦU ĐẶC BIỆT CỦA KHÁCH
-- ======================================
CREATE TABLE yeu_cau_dac_biet (
  id INT PRIMARY KEY AUTO_INCREMENT,
  khach_hang_id INT,
  tour_id INT,
  noi_dung TEXT,
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id),
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id)
);

-- ======================================
-- 14. DỮ LIỆU MẪU (ADMIN QUYỀN CAO NHẤT)
-- ======================================
INSERT INTO nguoi_dung (ten_dang_nhap, mat_khau, ho_ten, email, vai_tro, quyen_cap_cao)
VALUES 
('admin', 'admin123', 'Quản trị viên hệ thống', 'admin@tour.com', 'Admin', TRUE),
('hdv01', 'hdv123', 'Nguyễn Văn Hướng', 'hdv@tour.com', 'HDV', FALSE),
('khach01', 'khach123', 'Trần Thị Khách', 'khach@tour.com', 'KhachHang', FALSE),
('ncc01', 'ncc123', 'Công ty ABC Travel', 'ncc@tour.com', 'NhaCungCap', FALSE);

-- Tạo dữ liệu mẫu cho bảng KHÁCH HÀNG
INSERT INTO khach_hang (nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'khach01'), '123 Đường A, Quận B, TP. HCM', 'Nữ', '1995-05-10');

-- Tạo dữ liệu mẫu cho bảng NHÂN SỰ (HDV)
INSERT INTO nhan_su (nguoi_dung_id, vai_tro, chung_chi, ngon_ngu, kinh_nghiem, suc_khoe) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'hdv01'), 'HDV', 'Chứng chỉ nghiệp vụ hướng dẫn viên', 'Tiếng Việt, Tiếng Anh', '5 năm dẫn tour nội địa', 'Tốt');

-- Tạo dữ liệu mẫu cho bảng NHÀ CUNG CẤP
INSERT INTO nha_cung_cap (nguoi_dung_id, ten_don_vi, loai_dich_vu, dia_chi, lien_he, mo_ta, danh_gia_tb) VALUES
((SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'ncc01'), 'ABC Travel Services', 'KhachSan', '456 Đường C, Quận D, Hà Nội', '0123456789', 'Đối tác cung cấp khách sạn 3-4 sao', 4.5);

-- Tạo dữ liệu mẫu cho bảng TOUR
INSERT INTO tour (ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, id_nha_cung_cap, tao_boi, trang_thai) VALUES
('Hà Nội - Hạ Long 3N2Đ', 'TrongNuoc', 'Khám phá Vịnh Hạ Long kỳ quan thiên nhiên thế giới', 3500000, 'Hủy trước 7 ngày: hoàn 80%', (SELECT id_nha_cung_cap FROM nha_cung_cap LIMIT 1), (SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'admin'), 'HoatDong');

-- Tạo dữ liệu mẫu cho bảng LỊCH TRÌNH TOUR
INSERT INTO lich_trinh_tour (tour_id, ngay_thu, dia_diem, hoat_dong) VALUES
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 1, 'Hà Nội', 'Đón khách - Tham quan phố cổ - Ăn tối'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 2, 'Hạ Long', 'Tham quan Vịnh Hạ Long - Nghỉ đêm trên du thuyền'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 3, 'Hạ Long - Hà Nội', 'Tham quan hang động - Trở về Hà Nội');

-- Tạo dữ liệu mẫu cho bảng HÌNH ẢNH TOUR
INSERT INTO hinh_anh_tour (tour_id, url_anh, mo_ta) VALUES
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 'public/images/halong1.jpg', 'Toàn cảnh Vịnh Hạ Long'),
((SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'), 'public/images/halong2.jpg', 'Du thuyền trên Vịnh');

-- Tạo dữ liệu mẫu cho bảng BOOKING
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

-- Tạo dữ liệu mẫu cho bảng LỊCH KHỞI HÀNH
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

-- Tạo dữ liệu mẫu cho bảng NHẬT KÝ TOUR
INSERT INTO nhat_ky_tour (tour_id, nhan_su_id, noi_dung, ngay_ghi) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  (SELECT nhan_su_id FROM nhan_su LIMIT 1),
  'Đã kiểm tra trang thiết bị an toàn trên du thuyền',
  CURDATE()
);

-- Tạo dữ liệu mẫu cho bảng PHẢN HỒI & ĐÁNH GIÁ
INSERT INTO phan_hoi_danh_gia (tour_id, nguoi_dung_id, loai, diem, noi_dung, ngay_danh_gia) VALUES
(
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  (SELECT id FROM nguoi_dung WHERE ten_dang_nhap = 'khach01'),
  'Tour',
  5,
  'Trải nghiệm tuyệt vời, hướng dẫn viên nhiệt tình!',
  CURDATE()
);

-- Tạo dữ liệu mẫu cho bảng GIAO DỊCH TÀI CHÍNH
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

-- Tạo dữ liệu mẫu cho bảng YÊU CẦU ĐẶC BIỆT
INSERT INTO yeu_cau_dac_biet (khach_hang_id, tour_id, noi_dung) VALUES
(
  (SELECT khach_hang_id FROM khach_hang LIMIT 1),
  (SELECT tour_id FROM tour WHERE ten_tour = 'Hà Nội - Hạ Long 3N2Đ'),
  'Chuẩn bị bánh sinh nhật bất ngờ ngày 2'
);

-- ======================================
-- 15. BẢNG LỊCH SỬ THAY ĐỔI BOOKING
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
-- 16. BẢNG PHÂN BỔ NHÂN SỰ CHO LỊCH KHỞI HÀNH
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
-- 17. BẢNG PHÂN BỔ DỊCH VỤ CHO LỊCH KHỞI HÀNH
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
-- 18. BẢNG LỊCH SỬ THAY ĐỔI PHÂN BỔ (AUDIT LOG)
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