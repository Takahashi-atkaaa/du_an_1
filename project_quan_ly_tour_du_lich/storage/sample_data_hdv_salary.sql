-- ============================================================
-- SAMPLE DATA INSERTION SCRIPT
-- Tạo dữ liệu mẫu cho hdv_salary và hdv_bonus
-- ============================================================

-- ============================================================
-- 1. INSERT DỮ LIỆU MẪU VÀO BẢNG hdv_salary
-- ============================================================

-- Ví dụ: HDV id=100 dẫn tour id=6 (lịch khởi hành id=10)
INSERT INTO hdv_salary (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status, notes, created_at, updated_at)
SELECT 100, 6, 10, 5000000.00, 5.00, 263920000.00, (263920000.00 * 5.00 / 100), 0, 5000000.00 + (263920000.00 * 5.00 / 100), 'Pending', 'Lương tour Nagoya - Phú Sĩ - Tokyo', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM hdv_salary WHERE nhan_su_id = 100 AND tour_id = 6 AND lich_khoi_hanh_id = 10);

-- ============================================================
-- 2. INSERT DỮ LIỆU MẪU VÀO BẢNG hdv_bonus
-- ============================================================

-- Thưởng khen thưởng
INSERT INTO hdv_bonus (nhan_su_id, bonus_type, amount, reason, award_date, approval_status, approved_by, notes, created_at, updated_at)
SELECT 100, 'KhenThuong', 1000000.00, 'Dẫn tour xuất sắc, khách hàng hài lòng', DATE_SUB(NOW(), INTERVAL 10 DAY), 'DuyetPhep', 5, 'Phê duyệt bởi admin', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM hdv_bonus WHERE nhan_su_id = 100 AND bonus_type = 'KhenThuong' AND award_date >= DATE_SUB(NOW(), INTERVAL 15 DAY));

-- Thưởng hiệu suất
INSERT INTO hdv_bonus (nhan_su_id, bonus_type, amount, reason, award_date, approval_status, approved_by, notes, created_at, updated_at)
SELECT 100, 'HieuSuat', 500000.00, 'Thưởng hiệu suất tháng 12', DATE_SUB(NOW(), INTERVAL 5 DAY), 'DuyetPhep', 5, 'Đơn vị 100% chỉ tiêu', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM hdv_bonus WHERE nhan_su_id = 100 AND bonus_type = 'HieuSuat' AND award_date >= DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Thưởng đặc biệt (chờ phê duyệt)
INSERT INTO hdv_bonus (nhan_su_id, bonus_type, amount, reason, award_date, approval_status, notes, created_at, updated_at)
SELECT 100, 'DacBiet', 2000000.00, 'Thưởng hoàn thành dự án lớn', NOW(), 'ChoPheDuyet', 'Chờ phê duyệt từ quản lý', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM hdv_bonus WHERE nhan_su_id = 100 AND bonus_type = 'DacBiet' AND DATE(award_date) = CURDATE());

-- ============================================================
-- 3. CẬP NHẬT commission_percentage CHO HDV
-- ============================================================

-- Cập nhật tỉ lệ hoa hồng cho HDV (nếu cần thiết)
UPDATE nhan_su SET commission_percentage = 5.00 WHERE nhan_su_id = 100 AND commission_percentage IS NULL;

-- ============================================================
-- 4. KIỂM TRA DỮ LIỆU ĐÃ NHẬP
-- ============================================================

-- Xem dữ liệu lương vừa nhập
SELECT 'LƯƠNG CHI TIẾT' as 'KIỂM TIỂU', hs.*, t.ten_tour 
FROM hdv_salary hs
LEFT JOIN tour t ON hs.tour_id = t.tour_id
WHERE hs.nhan_su_id = 100;

-- Xem dữ liệu thưởng vừa nhập
SELECT 'THƯỞNG' as 'KIỂM TIỂU', hb.* 
FROM hdv_bonus hb
WHERE hb.nhan_su_id = 100;

-- Xem thống kê tổng hợp
SELECT * FROM view_hdv_salary_summary WHERE nhan_su_id = 100;

-- ============================================================
-- 5. SCRIPT CẬP NHẬT TRẠNG THÁI THANH TOÁN (TÙY CHỌN)
-- ============================================================

-- Để đánh dấu lương đã thanh toán:
-- UPDATE hdv_salary SET payment_status = 'Paid', payment_date = NOW() WHERE id = 1;

-- Để phê duyệt thưởng:
-- UPDATE hdv_bonus SET approval_status = 'DuyetPhep', approved_by = 5 WHERE id = 1;

-- ============================================================
-- 6. DANH SÁCH GIÁTRỊ ENUM
-- ============================================================

/*
payment_status (Trạng thái thanh toán):
- 'Pending'  = Chưa duyệt
- 'Approved' = Đã duyệt (chờ thanh toán)
- 'Paid'     = Đã thanh toán

approval_status (Trạng thái phê duyệt thưởng):
- 'ChoPheDuyet' = Chờ phê duyệt
- 'DuyetPhep'   = Đã phê duyệt
- 'TuChoi'      = Từ chối

bonus_type (Loại thưởng) - Có thể tùy chỉnh:
- 'KhongXacDinh' = Không xác định
- 'KhenThuong'   = Khen thưởng
- 'HieuSuat'     = Thưởng hiệu suất
- 'DacBiet'      = Thưởng đặc biệt
- 'ThuongChip'   = Thưởng chip/bán hàng
*/

-- ============================================================
-- NOTES:
-- ============================================================
-- 1. Chỉnh sửa nhan_su_id, tour_id, lich_khoi_hanh_id theo dữ liệu thực tế
-- 2. Cập nhật base_salary, commission_amount, tour_revenue phù hợp
-- 3. Đảm bảo tour_id và lich_khoi_hanh_id tồn tại trong database
-- 4. approved_by phải là user_id của admin trong bảng nguoi_dung
-- 5. Có thể chạy từng câu INSERT riêng biệt để kiểm tra lỗi
