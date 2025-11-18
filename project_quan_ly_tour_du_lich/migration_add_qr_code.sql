-- Migration: Thêm trường QR code vào bảng tour
-- Ngày tạo: 2025-11-18

-- Thêm cột qr_code_path để lưu đường dẫn file QR code
ALTER TABLE tour 
ADD COLUMN qr_code_path VARCHAR(255) NULL COMMENT 'Đường dẫn file QR code' AFTER trang_thai;

-- Tạo thư mục lưu QR code (cần tạo thủ công: public/uploads/qr_codes/)
