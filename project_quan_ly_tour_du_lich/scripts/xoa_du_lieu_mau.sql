-- ============================================================
-- SCRIPT XÓA DỮ LIỆU MẪU ĐÃ TẠO
-- Chạy script này trong phpMyAdmin hoặc MySQL client để xóa hết dữ liệu mẫu
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- XÓA THEO THỨ TỰ (Xóa dữ liệu con trước, rồi mới xóa dữ liệu cha)
-- ============================================================

-- 1. Xóa giao dịch tài chính
DELETE FROM giao_dich_tai_chinh WHERE id IN (200, 201, 202, 203, 204);

-- 2. Xóa thông báo
DELETE FROM thong_bao WHERE id IN (200, 201, 202, 203);

-- 3. Xóa phản hồi HDV
DELETE FROM phan_hoi_hdv WHERE tour_id = 100 AND hdv_id = 2;

-- 4. Xóa đánh giá HDV
DELETE FROM danh_gia_hdv WHERE tour_id = 100 AND nhan_su_id = 2;

-- 5. Xóa đánh giá
DELETE FROM danh_gia WHERE tour_id = 100 OR nhan_su_id = 2;

-- 6. Xóa nhật ký tour
DELETE FROM nhat_ky_tour WHERE tour_id = 100;

-- 7. Xóa phân bổ dịch vụ
DELETE FROM phan_bo_dich_vu WHERE lich_khoi_hanh_id = 200;

-- 8. Xóa dịch vụ nhà cung cấp
DELETE FROM dich_vu_nha_cung_cap WHERE nha_cung_cap_id IN (220, 221, 222);

-- 9. Xóa nhà cung cấp
DELETE FROM nha_cung_cap WHERE id_nha_cung_cap IN (220, 221, 222);

-- 10. Xóa người dùng nhà cung cấp
DELETE FROM nguoi_dung WHERE id IN (220, 221, 222);

-- 11. Xóa check-in khách (theo điểm check-in)
DELETE FROM checkin_khach WHERE booking_id IN (200, 201, 202, 203);

-- 12. Xóa yêu cầu đặc biệt
DELETE FROM yeu_cau_dac_biet WHERE booking_id IN (200, 201, 202, 203);

-- 13. Xóa tour_checkin (danh sách khách chi tiết)
DELETE FROM tour_checkin WHERE lich_khoi_hanh_id = 200;

-- 14. Xóa điểm check-in
DELETE FROM diem_checkin WHERE tour_id = 100 AND id IN (200, 201, 202, 203);

-- 15. Xóa booking
DELETE FROM booking WHERE booking_id IN (200, 201, 202, 203);

-- 16. Xóa phân bổ nhân sự
DELETE FROM phan_bo_nhan_su WHERE lich_khoi_hanh_id = 200;

-- 17. Xóa lịch khởi hành
DELETE FROM lich_khoi_hanh WHERE id = 200;

-- 18. Xóa lịch trình tour
DELETE FROM lich_trinh_tour WHERE tour_id = 100;

-- 19. Xóa tour
DELETE FROM tour WHERE tour_id = 100;

-- 20. Xóa nhân sự (HDV test)
DELETE FROM nhan_su WHERE nhan_su_id = 210;

-- 21. Xóa người dùng HDV test
DELETE FROM nguoi_dung WHERE id = 210;

-- 22. Xóa khách hàng
DELETE FROM khach_hang WHERE khach_hang_id IN (200, 201, 202, 203);

-- 23. Xóa người dùng khách hàng
DELETE FROM nguoi_dung WHERE id IN (200, 201, 202, 203);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- HOÀN THÀNH XÓA DỮ LIỆU MẪU!
-- ============================================================
-- Đã xóa:
-- ✅ Người dùng: 4 khách hàng + 1 HDV + 3 nhà cung cấp = 8 người
-- ✅ Khách hàng: 4 khách hàng (ID: 200, 201, 202, 203)
-- ✅ Nhân sự: 1 HDV (ID: 210)
-- ✅ Tour: 1 tour (ID: 100)
-- ✅ Lịch trình tour: 6 ngày
-- ✅ Lịch khởi hành: 1 lịch (ID: 200)
-- ✅ Phân bổ nhân sự: 1 HDV
-- ✅ Booking: 4 booking (ID: 200, 201, 202, 203)
-- ✅ Điểm check-in: 4 điểm
-- ✅ Tour check-in: 8 khách
-- ✅ Check-in khách: 4 booking
-- ✅ Yêu cầu đặc biệt: 4 yêu cầu
-- ✅ Nhà cung cấp: 3 nhà cung cấp (ID: 220, 221, 222)
-- ✅ Dịch vụ nhà cung cấp: 6 dịch vụ
-- ✅ Phân bổ dịch vụ: 4 dịch vụ
-- ✅ Nhật ký tour: 4 nhật ký
-- ✅ Đánh giá: 4 đánh giá
-- ✅ Đánh giá HDV: 3 đánh giá
-- ✅ Phản hồi HDV: 3 phản hồi
-- ✅ Thông báo: 4 thông báo
-- ✅ Giao dịch tài chính: 5 giao dịch
-- ============================================================

