-- ============================================================
-- SCRIPT THÊM DỮ LIỆU MẪU ĐẦY ĐỦ CHO TẤT CẢ CÁC BẢNG
-- Database: quan_ly_tour_du_lich
-- ============================================================

-- ============================================================
-- 1. NGƯỜI DÙNG (nguoi_dung)
-- ============================================================
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, trang_thai, ngay_tao)
VALUES 
    (100, 'nguyenvana', 'Nguyễn Văn A', 'nguyenvana@test.com', '0912345678', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW()),
    (101, 'tranthib', 'Trần Thị B', 'tranthib@test.com', '0912345679', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW()),
    (102, 'levanc', 'Lê Văn C', 'levanc@test.com', '0912345680', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW()),
    (103, 'phamthid', 'Phạm Thị D', 'phamthid@test.com', '0912345681', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW()),
    (104, 'hdv_test', 'HDV Test', 'hdvtest@test.com', '0912345682', 'HDV', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW()),
    (105, 'ncc_test', 'Nhà Cung Cấp Test', 'ncctest@test.com', '0912345683', 'NhaCungCap', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HoatDong', NOW())
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), email = VALUES(email), so_dien_thoai = VALUES(so_dien_thoai);

-- ============================================================
-- 2. KHÁCH HÀNG (khach_hang)
-- ============================================================
INSERT INTO khach_hang (khach_hang_id, nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh)
VALUES 
    (100, 100, '123 Đường ABC, Quận 1, Hà Nội', 'Nam', '1990-01-01'),
    (101, 101, '456 Đường XYZ, Quận 3, TP.HCM', 'Nu', '1988-03-20'),
    (102, 102, '789 Đường DEF, Quận Hải Châu, Đà Nẵng', 'Nam', '1995-07-15'),
    (103, 103, '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'Nu', '1992-11-30')
ON DUPLICATE KEY UPDATE nguoi_dung_id = VALUES(nguoi_dung_id), dia_chi = VALUES(dia_chi);

-- ============================================================
-- 3. NHÂN SỰ (nhan_su) - HDV
-- ============================================================
INSERT INTO nhan_su (nhan_su_id, nguoi_dung_id, vai_tro, loai_hdv, chuyen_tuyen, danh_gia_tb, so_tour_da_dan, trang_thai_lam_viec, ngon_ngu, kinh_nghiem)
VALUES 
    (100, 104, 'HDV', 'QuocTe', 'Đông Nam Á, Nhật Bản, Hàn Quốc', 4.50, 5, 'SanSang', 'Tiếng Việt, Tiếng Anh, Tiếng Nhật', '3 năm kinh nghiệm dẫn tour quốc tế')
ON DUPLICATE KEY UPDATE nguoi_dung_id = VALUES(nguoi_dung_id);

-- ============================================================
-- 4. NHÀ CUNG CẤP (nha_cung_cap)
-- ============================================================
INSERT INTO nha_cung_cap (id_nha_cung_cap, nguoi_dung_id, ten_don_vi, loai_dich_vu, dia_chi, lien_he, mo_ta, danh_gia_tb)
VALUES 
    (100, 105, 'Công ty Dịch vụ Du lịch Test', 'KhachSan', '789 Đường Test, Quận 1, TP.HCM', '0281234567', 'Cung cấp dịch vụ khách sạn và tour du lịch', 4.5)
ON DUPLICATE KEY UPDATE nguoi_dung_id = VALUES(nguoi_dung_id);

-- ============================================================
-- 5. TOUR (tour) - Nếu tour_id = 6 chưa tồn tại
-- ============================================================
-- Lưu ý: Chỉ insert nếu tour_id = 6 chưa có, nếu có rồi thì bỏ qua
INSERT INTO tour (tour_id, ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, trang_thai)
SELECT 6, 'NAGOYA – PHÚ SĨ – TOKYO (Bản sao)', 'QuocTe', 
       'Tour tham quan Nhật Bản với các điểm đến nổi tiếng', 
       32990000.00, 
       'Hủy trước 14 ngày: hoàn 80%. Hủy trước 7 ngày: hoàn 50%', 
       'HoatDong'
WHERE NOT EXISTS (SELECT 1 FROM tour WHERE tour_id = 6);

-- ============================================================
-- 6. LỊCH KHỞI HÀNH (lich_khoi_hanh)
-- ============================================================
-- Đảm bảo lịch khởi hành ID = 10 tồn tại
INSERT INTO lich_khoi_hanh (id, tour_id, ngay_khoi_hanh, gio_xuat_phat, ngay_ket_thuc, gio_ket_thuc, diem_tap_trung, so_cho, hdv_id, trang_thai, ghi_chu)
SELECT 10, 6, '2025-12-02', '07:00:00', '2025-12-05', '17:00:00', 'Sân bay Nội Bài', 50, NULL, 'DangChay', 'Lịch khởi hành test'
WHERE NOT EXISTS (SELECT 1 FROM lich_khoi_hanh WHERE id = 10);

-- ============================================================
-- 7. BOOKING (booking)
-- ============================================================
DELETE FROM booking WHERE booking_id IN (100, 101, 102, 103);

INSERT INTO booking (
    booking_id,
    khach_hang_id,
    tour_id,
    ngay_khoi_hanh,
    ngay_ket_thuc,
    so_nguoi,
    tong_tien,
    ngay_dat,
    trang_thai,
    ghi_chu
)
VALUES 
    (100, 100, 6, '2025-12-02', '2025-12-05', 2, 65980000.00, '2025-11-15', 'HoanTat', 'Booking test 1 - 2 người lớn'),
    (101, 101, 6, '2025-12-02', '2025-12-05', 3, 98970000.00, '2025-11-16', 'DaCoc', 'Booking test 2 - 2 người lớn + 1 trẻ em'),
    (102, 102, 6, '2025-12-02', '2025-12-05', 1, 32990000.00, '2025-11-17', 'ChoXacNhan', 'Booking test 3 - 1 người lớn'),
    (103, 103, 6, '2025-12-02', '2025-12-05', 2, 65980000.00, '2025-11-18', 'DaCoc', 'Booking test 4 - 2 người lớn');

-- ============================================================
-- 8. PHÂN BỔ NHÂN SỰ (phan_bo_nhan_su)
-- ============================================================
DELETE FROM phan_bo_nhan_su WHERE lich_khoi_hanh_id = 10 AND nhan_su_id = 100;

INSERT INTO phan_bo_nhan_su (lich_khoi_hanh_id, nhan_su_id, vai_tro, ghi_chu, trang_thai, thoi_gian_xac_nhan)
VALUES 
    (10, 100, 'HDV', 'Phân bổ HDV cho lịch khởi hành test', 'DaXacNhan', NOW())
ON DUPLICATE KEY UPDATE trang_thai = 'DaXacNhan', thoi_gian_xac_nhan = NOW();

-- ============================================================
-- 9. ĐIỂM CHECK-IN (diem_checkin)
-- ============================================================
DELETE FROM diem_checkin WHERE tour_id = 6 AND id IN (100, 101, 102);

INSERT INTO diem_checkin (id, tour_id, ten_diem, loai_diem, thoi_gian_du_kien, ghi_chu, thu_tu)
VALUES 
    (100, 6, 'Sân bay Nội Bài - Điểm tập trung', 'tap_trung', '2025-12-02 07:00:00', 'Tập trung tại sân bay Nội Bài', 1),
    (101, 6, 'Khách sạn Tokyo - Check-in', 'nghi_ngoi', '2025-12-02 20:00:00', 'Nhận phòng khách sạn', 2),
    (102, 6, 'Núi Phú Sĩ - Tham quan', 'tham_quan', '2025-12-03 10:00:00', 'Tham quan núi Phú Sĩ', 3)
ON DUPLICATE KEY UPDATE ten_diem = VALUES(ten_diem), thoi_gian_du_kien = VALUES(thoi_gian_du_kien);

-- ============================================================
-- 10. TOUR CHECK-IN (tour_checkin) - Khách chi tiết
-- ============================================================
DELETE FROM tour_checkin WHERE booking_id IN (100, 101, 102, 103) AND lich_khoi_hanh_id = 10;

-- Booking 100: Nguyễn Văn A (chủ booking - người 1)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
SELECT b.booking_id, b.khach_hang_id, 10, nd.ho_ten, CONCAT('CMND', b.booking_id, '-1'), CONCAT('PASS', b.booking_id, '-1'), '1990-01-01', 'Nam', 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'Khách test - Người chủ booking', 'ChuaCheckIn'
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 100;

-- Booking 100: Người thứ 2
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
VALUES (100, 100, 10, 'Nguyễn Thị B - Người 2', 'CMND100-2', 'PASS100-2', '1992-05-15', 'Nu', 'Việt Nam', '0912345678', 'nguyenvana@test.com', '123 Đường ABC, Quận 1, Hà Nội', 'Khách test - Người thứ 2', 'ChuaCheckIn');

-- Booking 101: Trần Thị B (chủ booking - người 1)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
SELECT b.booking_id, b.khach_hang_id, 10, nd.ho_ten, CONCAT('CMND', b.booking_id, '-1'), CONCAT('PASS', b.booking_id, '-1'), '1988-03-20', 'Nu', 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'Khách test - Người chủ booking', 'ChuaCheckIn'
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 101;

-- Booking 101: Người thứ 2 và trẻ em
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
VALUES 
    (101, 101, 10, 'Trần Văn C - Người 2', 'CMND101-2', 'PASS101-2', '1993-08-20', 'Nam', 'Việt Nam', '0912345679', 'tranthib@test.com', '456 Đường XYZ, Quận 3, TP.HCM', 'Khách test - Người thứ 2', 'ChuaCheckIn'),
    (101, 101, 10, 'Trần Thị D - Trẻ em', 'CMND101-3', 'PASS101-3', '2015-12-10', 'Nu', 'Việt Nam', NULL, NULL, '456 Đường XYZ, Quận 3, TP.HCM', 'Khách test - Trẻ em', 'ChuaCheckIn');

-- Booking 102: Lê Văn C (chủ booking - 1 người)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
SELECT b.booking_id, b.khach_hang_id, 10, nd.ho_ten, CONCAT('CMND', b.booking_id, '-1'), CONCAT('PASS', b.booking_id, '-1'), '1995-07-15', 'Nam', 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'Khách test - 1 người', 'ChuaCheckIn'
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 102;

-- Booking 103: Phạm Thị D (chủ booking - người 1)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
SELECT b.booking_id, b.khach_hang_id, 10, nd.ho_ten, CONCAT('CMND', b.booking_id, '-1'), CONCAT('PASS', b.booking_id, '-1'), '1992-11-30', 'Nu', 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'Khách test - Người chủ booking', 'ChuaCheckIn'
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 103;

-- Booking 103: Người thứ 2
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, ghi_chu, trang_thai)
VALUES (103, 103, 10, 'Phạm Văn E - Người 2', 'CMND103-2', 'PASS103-2', '1994-06-25', 'Nam', 'Việt Nam', '0912345681', 'phamthid@test.com', '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'Khách test - Người thứ 2', 'ChuaCheckIn');

-- ============================================================
-- 11. CHECK-IN KHÁCH (checkin_khach) - Trạng thái check-in tại điểm
-- ============================================================
DELETE FROM checkin_khach WHERE booking_id IN (100, 101, 102, 103) AND diem_checkin_id IN (100, 101, 102);

-- Check-in tại điểm 100 (Sân bay) - Một số đã check-in, một số chưa
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES 
    (100, 100, 'da_checkin', NOW(), 'Đã check-in tại sân bay', 100),
    (100, 101, 'da_checkin', NOW(), 'Đã check-in tại sân bay', 100),
    (100, 102, 'chua_checkin', NULL, NULL, NULL),
    (100, 103, 'da_checkin', NOW(), 'Đã check-in tại sân bay', 100);

-- Check-in tại điểm 101 (Khách sạn) - Chưa check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES 
    (101, 100, 'chua_checkin', NULL, NULL, NULL),
    (101, 101, 'chua_checkin', NULL, NULL, NULL),
    (101, 102, 'chua_checkin', NULL, NULL, NULL),
    (101, 103, 'chua_checkin', NULL, NULL, NULL);

-- ============================================================
-- 12. YÊU CẦU ĐẶC BIỆT (yeu_cau_dac_biet)
-- ============================================================
DELETE FROM yeu_cau_dac_biet WHERE booking_id IN (100, 101, 102, 103);

INSERT INTO yeu_cau_dac_biet (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien, trang_thai, nguoi_tao_id)
VALUES 
    (100, 'an_uong', 'Dị ứng hải sản', 'Khách bị dị ứng hải sản, cần tránh các món có hải sản', 'cao', 'moi', 100),
    (101, 'suc_khoe', 'Cần hỗ trợ di chuyển', 'Có trẻ em 10 tuổi, cần hỗ trợ khi di chuyển', 'trung_binh', 'moi', 101),
    (102, 'phong_o', 'Phòng đơn', 'Yêu cầu phòng đơn riêng', 'thap', 'moi', 102),
    (103, 'khac', 'Yêu cầu đặc biệt', 'Cần hỗ trợ đặc biệt về visa', 'trung_binh', 'moi', 103);

-- ============================================================
-- 13. PHÂN BỔ DỊCH VỤ (phan_bo_dich_vu)
-- ============================================================
DELETE FROM phan_bo_dich_vu WHERE lich_khoi_hanh_id = 10 AND id IN (100, 101, 102);

INSERT INTO phan_bo_dich_vu (id, lich_khoi_hanh_id, nha_cung_cap_id, loai_dich_vu, ten_dich_vu, so_luong, don_vi, ngay_bat_dau, ngay_ket_thuc, gia_tien, ghi_chu, trang_thai)
VALUES 
    (100, 10, 100, 'KhachSan', 'Khách sạn Tokyo 4 sao', 10, 'phòng', '2025-12-02', '2025-12-05', 4500000.00, 'Khách sạn tại trung tâm Tokyo', 'DaXacNhan'),
    (101, 10, 100, 'VeMayBay', 'Vé máy bay Hà Nội - Tokyo', 8, 'vé', '2025-12-02', '2025-12-05', 15000000.00, 'Vé máy bay khứ hồi', 'DaXacNhan'),
    (102, 10, 100, 'NhaHang', 'Nhà hàng Nhật Bản', 4, 'bữa', '2025-12-02', '2025-12-05', 500000.00, 'Bữa ăn tại nhà hàng địa phương', 'ChoXacNhan')
ON DUPLICATE KEY UPDATE trang_thai = VALUES(trang_thai);

-- ============================================================
-- 14. DỊCH VỤ NHÀ CUNG CẤP (dich_vu_nha_cung_cap)
-- ============================================================
DELETE FROM dich_vu_nha_cung_cap WHERE nha_cung_cap_id = 100 AND id IN (100, 101, 102);

INSERT INTO dich_vu_nha_cung_cap (id, nha_cung_cap_id, ten_dich_vu, mo_ta, loai_dich_vu, gia_tham_khao, don_vi_tinh, cong_suat_toi_da, thoi_gian_xu_ly, trang_thai)
VALUES 
    (100, 100, 'Khách sạn 4 sao Tokyo', 'Khách sạn tại trung tâm Tokyo, tiện nghi đầy đủ', 'KhachSan', 4500000.00, '/phòng/đêm', 50, '24h', 'HoatDong'),
    (101, 100, 'Vé máy bay quốc tế', 'Vé máy bay khứ hồi các tuyến quốc tế', 'VeMayBay', 15000000.00, '/vé', NULL, '48h', 'HoatDong'),
    (102, 100, 'Dịch vụ ăn uống', 'Cung cấp bữa ăn tại nhà hàng địa phương', 'NhaHang', 500000.00, '/bữa', 100, '12h', 'HoatDong')
ON DUPLICATE KEY UPDATE ten_dich_vu = VALUES(ten_dich_vu);

-- ============================================================
-- 15. NHẬT KÝ TOUR (nhat_ky_tour)
-- ============================================================
DELETE FROM nhat_ky_tour WHERE tour_id = 6 AND id IN (100, 101, 102);

INSERT INTO nhat_ky_tour (id, tour_id, nhan_su_id, loai_nhat_ky, tieu_de, noi_dung, cach_xu_ly, thoi_tiet, ngay_ghi)
VALUES 
    (100, 6, 100, 'hanh_trinh', 'Khởi hành từ Hà Nội', 'Đoàn đã tập trung đầy đủ tại sân bay Nội Bài, làm thủ tục xuất cảnh', NULL, 'Trời quang, nhiệt độ 25°C', '2025-12-02 07:00:00'),
    (101, 6, 100, 'hoat_dong', 'Tham quan núi Phú Sĩ', 'Đoàn đã đến núi Phú Sĩ, thời tiết đẹp, tầm nhìn rõ', NULL, 'Trời nắng, nhiệt độ 15°C', '2025-12-03 10:00:00'),
    (102, 6, 100, 'phan_hoi', 'Phản hồi từ khách hàng', 'Khách hàng rất hài lòng với dịch vụ và hướng dẫn viên', NULL, NULL, '2025-12-04 15:00:00')
ON DUPLICATE KEY UPDATE noi_dung = VALUES(noi_dung);

-- ============================================================
-- 16. DỰ TOÁN TOUR (du_toan_tour)
-- ============================================================
DELETE FROM du_toan_tour WHERE lich_khoi_hanh_id = 10 AND du_toan_id IN (100);

INSERT INTO du_toan_tour (du_toan_id, tour_id, lich_khoi_hanh_id, cp_phuong_tien, mo_ta_phuong_tien, cp_luu_tru, mo_ta_luu_tru, cp_ve_tham_quan, mo_ta_ve_tham_quan, cp_an_uong, mo_ta_an_uong, cp_huong_dan_vien, cp_dich_vu_bo_sung, mo_ta_dich_vu, cp_phat_sinh_du_kien, mo_ta_phat_sinh, nguoi_tao_id)
VALUES 
    (100, 6, 10, 120000000.00, 'Vé máy bay khứ hồi cho 8 người', 36000000.00, 'Khách sạn 4 sao, 3 đêm', 20000000.00, 'Vé tham quan núi Phú Sĩ, đền thờ', 20000000.00, 'Bữa ăn tại nhà hàng địa phương', 8000000.00, 5000000.00, 'Bảo hiểm, visa', 6000000.00, 'Quỹ dự phòng', 5)
ON DUPLICATE KEY UPDATE cp_phuong_tien = VALUES(cp_phuong_tien);

-- ============================================================
-- 17. CHI PHÍ THỰC TẾ (chi_phi_thuc_te)
-- ============================================================
DELETE FROM chi_phi_thuc_te WHERE lich_khoi_hanh_id = 10 AND chi_phi_id IN (100, 101, 102);

INSERT INTO chi_phi_thuc_te (chi_phi_id, du_toan_id, tour_id, lich_khoi_hanh_id, loai_chi_phi, ten_khoan_chi, so_tien, ngay_phat_sinh, mo_ta, trang_thai, nguoi_ghi_nhan_id, nguoi_duyet_id, ngay_duyet)
VALUES 
    (100, 100, 6, 10, 'PhuongTien', 'Thanh toán vé máy bay', 120000000.00, '2025-11-28', 'Thanh toán đợt 1 cho hãng bay', 'DaDuyet', 5, 5, NOW()),
    (101, 100, 6, 10, 'LuuTru', 'Cọc khách sạn Tokyo', 18000000.00, '2025-11-29', 'Khách sạn 4 sao trung tâm', 'DaDuyet', 5, 5, NOW()),
    (102, 100, 6, 10, 'AnUong', 'Thanh toán bữa ăn', 10000000.00, '2025-12-01', 'Bữa ăn tại nhà hàng', 'ChoXacNhan', 5, NULL, NULL)
ON DUPLICATE KEY UPDATE so_tien = VALUES(so_tien);

-- ============================================================
-- 18. LỊCH SỬ KHÁCH HÀNG (lich_su_khach_hang)
-- ============================================================
DELETE FROM lich_su_khach_hang WHERE khach_hang_id IN (100, 101, 102, 103) AND id IN (100, 101, 102, 103);

INSERT INTO lich_su_khach_hang (id, khach_hang_id, loai_hoat_dong, noi_dung, nguoi_tao_id)
VALUES 
    (100, 100, 'Booking', 'Khách đặt tour Nhật Bản tháng 12/2025', 5),
    (101, 101, 'Booking', 'Khách đặt tour Nhật Bản tháng 12/2025', 5),
    (102, 102, 'Booking', 'Khách đặt tour Nhật Bản tháng 12/2025', 5),
    (103, 103, 'Booking', 'Khách đặt tour Nhật Bản tháng 12/2025', 5)
ON DUPLICATE KEY UPDATE noi_dung = VALUES(noi_dung);

-- ============================================================
-- 19. BOOKING HISTORY (booking_history)
-- ============================================================
DELETE FROM booking_history WHERE booking_id IN (100, 101, 102, 103) AND id IN (100, 101, 102, 103);

INSERT INTO booking_history (id, booking_id, trang_thai_cu, trang_thai_moi, nguoi_thay_doi_id, ghi_chu)
VALUES 
    (100, 100, NULL, 'ChoXacNhan', 5, 'Tạo booking mới'),
    (101, 100, 'ChoXacNhan', 'DaCoc', 5, 'Khách đã đặt cọc'),
    (102, 100, 'DaCoc', 'HoanTat', 5, 'Khách đã thanh toán đủ'),
    (103, 101, NULL, 'ChoXacNhan', 5, 'Tạo booking mới'),
    (104, 101, 'ChoXacNhan', 'DaCoc', 5, 'Khách đã đặt cọc 50%')
ON DUPLICATE KEY UPDATE trang_thai_moi = VALUES(trang_thai_moi);

-- ============================================================
-- 20. GIAO DỊCH TÀI CHÍNH (giao_dich_tai_chinh)
-- ============================================================
DELETE FROM giao_dich_tai_chinh WHERE booking_id IN (100, 101, 102, 103) AND id IN (100, 101, 102, 103, 104);

INSERT INTO giao_dich_tai_chinh (id, tour_id, booking_id, khach_hang_id, loai, loai_doi_tuong, doi_tuong_id, loai_giao_dich, so_tien, mo_ta, nguoi_thuc_hien_id, nguoi_thuc_hien, ngay_giao_dich)
VALUES 
    (100, 6, 100, 100, 'Thu', 'KhachHang', 100, 'Booking', 32990000.00, 'Khách đặt cọc tour Nhật Bản', 5, 'Admin', '2025-11-15'),
    (101, 6, 100, 100, 'Thu', 'KhachHang', 100, 'ThanhToan', 32990000.00, 'Khách thanh toán phần còn lại', 5, 'Admin', '2025-11-20'),
    (102, 6, 101, 101, 'Thu', 'KhachHang', 101, 'Booking', 49485000.00, 'Khách đặt cọc 50% tour Nhật Bản', 5, 'Admin', '2025-11-16'),
    (103, 6, 102, 102, 'Thu', 'KhachHang', 102, 'Booking', 32990000.00, 'Khách đặt tour Nhật Bản', 5, 'Admin', '2025-11-17'),
    (104, 6, 103, 103, 'Thu', 'KhachHang', 103, 'Booking', 32990000.00, 'Khách đặt cọc tour Nhật Bản', 5, 'Admin', '2025-11-18')
ON DUPLICATE KEY UPDATE so_tien = VALUES(so_tien);

-- ============================================================
-- KIỂM TRA DỮ LIỆU ĐÃ INSERT
-- ============================================================
SELECT '=== KIỂM TRA NGƯỜI DÙNG ===' as info
UNION ALL
SELECT CONCAT('ID: ', id, ' | Tên: ', ho_ten, ' | Vai trò: ', vai_tro)
FROM nguoi_dung
WHERE id IN (100, 101, 102, 103, 104, 105)
UNION ALL
SELECT '=== KIỂM TRA KHÁCH HÀNG ==='
UNION ALL
SELECT CONCAT('ID: ', khach_hang_id, ' | Tên: ', nd.ho_ten, ' | Email: ', nd.email)
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE khach_hang_id IN (100, 101, 102, 103)
UNION ALL
SELECT '=== KIỂM TRA BOOKING ==='
UNION ALL
SELECT CONCAT('Booking ID: ', booking_id, ' | Tour ID: ', tour_id, ' | Ngày: ', ngay_khoi_hanh, ' | Số người: ', so_nguoi, ' | Trạng thái: ', trang_thai)
FROM booking
WHERE booking_id IN (100, 101, 102, 103)
UNION ALL
SELECT '=== KIỂM TRA LỊCH KHỞI HÀNH ==='
UNION ALL
SELECT CONCAT('Lịch ID: ', id, ' | Tour ID: ', tour_id, ' | Ngày: ', ngay_khoi_hanh, ' | Trạng thái: ', trang_thai)
FROM lich_khoi_hanh
WHERE id = 10
UNION ALL
SELECT '=== KIỂM TRA TOUR CHECK-IN ==='
UNION ALL
SELECT CONCAT('ID: ', id, ' | Booking: ', booking_id, ' | Tên: ', ho_ten, ' | Trạng thái: ', trang_thai)
FROM tour_checkin
WHERE booking_id IN (100, 101, 102, 103) AND lich_khoi_hanh_id = 10
UNION ALL
SELECT '=== KIỂM TRA CHECK-IN KHÁCH ==='
UNION ALL
SELECT CONCAT('ID: ', id, ' | Điểm: ', diem_checkin_id, ' | Booking: ', booking_id, ' | Trạng thái: ', trang_thai)
FROM checkin_khach
WHERE booking_id IN (100, 101, 102, 103)
UNION ALL
SELECT '=== KIỂM TRA YÊU CẦU ĐẶC BIỆT ==='
UNION ALL
SELECT CONCAT('ID: ', id, ' | Booking: ', booking_id, ' | Loại: ', loai_yeu_cau, ' | Trạng thái: ', trang_thai)
FROM yeu_cau_dac_biet
WHERE booking_id IN (100, 101, 102, 103);

