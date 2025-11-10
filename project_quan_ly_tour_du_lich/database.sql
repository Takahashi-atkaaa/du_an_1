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
  ngay_ket_thuc DATE,
  diem_tap_trung VARCHAR(255),
  hdv_id INT,
  trang_thai ENUM('SapKhoiHanh','DangChay','HoanThanh'),
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
