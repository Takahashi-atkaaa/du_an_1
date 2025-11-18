-- ============================================
-- SCRIPT CẬP NHẬT DATABASE - SỬA XUNG ĐỘT
-- ============================================
-- Chạy script này để sửa các xung đột phát hiện

USE quan_ly_tour_du_lich;

-- ======================================
-- 1. CẬP NHẬT ENUM cho bảng lich_khoi_hanh
-- ======================================
-- Thêm các giá trị mới: DaXacNhan, ChoXacNhan, Huy
-- Giữ các giá trị cũ: SapKhoiHanh, DangChay, HoanThanh

ALTER TABLE lich_khoi_hanh 
MODIFY COLUMN trang_thai ENUM(
    'SapKhoiHanh',
    'DangChay', 
    'HoanThanh',
    'DaXacNhan',
    'ChoXacNhan',
    'Huy'
) DEFAULT 'ChoXacNhan';

-- ======================================
-- 2. KIỂM TRA KẾT QUẢ
-- ======================================
-- Xem cấu trúc bảng sau khi sửa
DESCRIBE lich_khoi_hanh;

-- Kiểm tra dữ liệu hiện tại
SELECT trang_thai, COUNT(*) as so_luong 
FROM lich_khoi_hanh 
GROUP BY trang_thai;

-- ======================================
-- 3. LƯU Ý
-- ======================================
-- ✅ Bảng booking_history đã được tạo với IF NOT EXISTS (dòng 194-206)
-- ✅ Định nghĩa thứ 2 (dòng 313-327) đã bị xóa khỏi database.sql
-- ✅ ENUM đã được mở rộng để hỗ trợ cả code cũ và mới
-- ✅ Không cần migrate dữ liệu (giữ nguyên giá trị cũ)

SELECT 'CẬP NHẬT THÀNH CÔNG!' as ket_qua;
