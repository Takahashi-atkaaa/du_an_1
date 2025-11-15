-- Migration: Mở rộng bảng lich_khoi_hanh và tạo các bảng phân bổ nhân sự, dịch vụ
-- Chạy file này trong phpMyAdmin hoặc MySQL client

USE quan_ly_tour_du_lich;

-- Cập nhật bảng lich_khoi_hanh: thêm giờ xuất phát, giờ kết thúc
-- Chạy từng câu lệnh một, bỏ qua lỗi nếu cột đã tồn tại
ALTER TABLE lich_khoi_hanh ADD COLUMN gio_xuat_phat TIME NULL AFTER ngay_khoi_hanh;
ALTER TABLE lich_khoi_hanh ADD COLUMN gio_ket_thuc TIME NULL AFTER ngay_ket_thuc;
ALTER TABLE lich_khoi_hanh ADD COLUMN so_cho INT DEFAULT 50 AFTER diem_tap_trung;
ALTER TABLE lich_khoi_hanh ADD COLUMN ghi_chu TEXT NULL AFTER trang_thai;

-- Bảng phân bổ nhân sự cho lịch khởi hành
CREATE TABLE IF NOT EXISTS phan_bo_nhan_su (
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

-- Bảng phân bổ dịch vụ cho lịch khởi hành
CREATE TABLE IF NOT EXISTS phan_bo_dich_vu (
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

-- Bảng lịch sử thay đổi phân bổ (audit log)
CREATE TABLE IF NOT EXISTS phan_bo_history (
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

