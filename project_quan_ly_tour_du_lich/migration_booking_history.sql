-- Migration: Tạo bảng booking_history để lưu lịch sử thay đổi trạng thái booking
-- Chạy file này trong phpMyAdmin hoặc MySQL client

USE quan_ly_tour_du_lich;

-- Tạo bảng booking_history nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS booking_history (
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

