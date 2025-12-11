-- ============================================================
-- SCRIPT TẠO TOUR HOÀN CHỈNH TỪ A-Z ĐỂ TEST
-- Chạy script này trong phpMyAdmin hoặc MySQL client
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- 1. TẠO NGƯỜI DÙNG (Khách hàng)
-- ============================================================
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, ngay_tao)
VALUES 
    (200, 'khach1', 'Nguyễn Văn An', 'nguyenvanan@test.com', '0911111111', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (201, 'khach2', 'Trần Thị Bình', 'tranthibinh@test.com', '0922222222', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (202, 'khach3', 'Lê Văn Cường', 'levancuong@test.com', '0933333333', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (203, 'khach4', 'Phạm Thị Dung', 'phamthidung@test.com', '0944444444', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), email = VALUES(email);

-- ============================================================
-- 2. TẠO KHÁCH HÀNG
-- ============================================================
INSERT INTO khach_hang (khach_hang_id, nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh)
VALUES 
    (200, 200, '123 Đường ABC, Quận 1, Hà Nội', 'Nam', '1990-01-15'),
    (201, 201, '456 Đường XYZ, Quận 3, TP.HCM', 'Nu', '1988-05-20'),
    (202, 202, '789 Đường DEF, Quận Hải Châu, Đà Nẵng', 'Nam', '1992-08-10'),
    (203, 203, '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'Nu', '1995-12-25')
ON DUPLICATE KEY UPDATE dia_chi = VALUES(dia_chi);

-- ============================================================
-- 3. TẠO HDV
-- ============================================================
-- Xóa nhan_su cũ nếu có (tránh conflict)
DELETE FROM nhan_su WHERE nhan_su_id = 210;

-- Xóa nguoi_dung cũ theo ID hoặc ten_dang_nhap (tránh duplicate key)
DELETE FROM nguoi_dung WHERE id = 210 OR ten_dang_nhap = 'hdv_test';

-- Tạo người dùng HDV
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, ngay_tao)
VALUES 
    (210, 'hdv_test', 'HDV Test Full', 'hdvtestfull@test.com', '0955555555', 'HDV', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- Tạo nhân sự HDV (sau khi đã có nguoi_dung)
INSERT INTO nhan_su (nhan_su_id, nguoi_dung_id, vai_tro)
VALUES 
    (210, 210, 'HDV');

-- ============================================================
-- 4. TẠO TOUR
-- ============================================================
INSERT INTO tour (tour_id, ten_tour, loai_tour, mo_ta, gia_co_ban, chinh_sach, trang_thai)
VALUES 
    (100, 'NAGOYA – PHÚ SĨ – TOKYO (5 NGÀY 4 ĐÊM)', 'QuocTe', 
     'Tour tham quan Nhật Bản với các điểm đến nổi tiếng: Nagoya, Núi Phú Sĩ, Tokyo. Trải nghiệm văn hóa, ẩm thực và cảnh đẹp Nhật Bản.', 
     32990000.00, 
     'Hủy trước 14 ngày: hoàn 80%. Hủy trước 7 ngày: hoàn 50%. Hủy trước 3 ngày: hoàn 30%.', 
     'HoatDong')
ON DUPLICATE KEY UPDATE ten_tour = VALUES(ten_tour), mo_ta = VALUES(mo_ta);

-- ============================================================
-- 5. TẠO LỊCH TRÌNH CHI TIẾT
-- ============================================================
DELETE FROM lich_trinh_tour WHERE tour_id = 100;

INSERT INTO lich_trinh_tour (tour_id, ngay_thu, dia_diem, hoat_dong) VALUES
(100, 0, 'Sân bay Nội Bài – Ga đi quốc tế', '🕘 Giờ tập trung: 21:00 (trước giờ bay 3 tiếng)\n👤 Hướng dẫn viên làm thủ tục & hỗ trợ đoàn.'),
(100, 1, 'HÀ NỘI → TOKYO (Narita)', '✈️ Sáng / Trưa / Chiều:\n🕘 09:00 – Tập trung tại sân bay Nội Bài, HDV hỗ trợ check-in.\n🕙 12:00 – Cất cánh đi Nhật Bản.\n\n🌆 Chiều / Tối:\n🕕 18:00 – Hạ cánh sân bay Narita.\n🚌 Di chuyển về khách sạn nhận phòng.\n🍱 Tối: Ăn tối tại nhà hàng địa phương.\n🏨 Nghỉ đêm tại Tokyo / Narita.'),
(100, 2, 'NAGOYA – THÀNH PHỐ CẢNG', '🍳 Sáng:\n🕗 08:00 – Ăn sáng tại khách sạn.\n🚌 Di chuyển đến Nagoya.\n🏯 Tham quan Lâu đài Nagoya – biểu tượng lịch sử nổi tiếng.\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa với món đặc sản Nagoya.\n\n🛍️ Chiều:\n🕒 14:00 – Tham quan & mua sắm tại khu vực Sakae sầm uất.\n\n🍱 Tối:\n🕕 18:00 – Thưởng thức món Tebasaki (gà rán kiểu Nagoya).\n🏨 Nghỉ đêm tại Nagoya.'),
(100, 3, 'NAGOYA – NÚI PHÚ SĨ – KAWAGUCHIKO', '🍳 Sáng:\n🕗 08:00 – Ăn sáng tại khách sạn.\n🚌 Di chuyển đến khu vực núi Phú Sĩ.\n🏔️ Tham quan trạm 5 Núi Phú Sĩ (nếu thời tiết cho phép).\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa tại Kawaguchiko.\n\n🌅 Chiều:\n🌸 Tham quan Hồ Kawaguchiko – check-in với background núi Phú Sĩ.\n🏞️ Tham quan làng cổ Oshino Hakkai.\n\n🍱 Tối:\n🕕 18:00 – Ăn tối với set kaiseki Nhật Bản.\n🛁 Tắm onsen truyền thống tại khách sạn.\n🏨 Nghỉ đêm tại Kawaguchiko.'),
(100, 4, 'KAWAGUCHIKO – TOKYO', '🍳 Sáng:\n🕗 07:30 – Ăn sáng và trả phòng.\n🚌 Khởi hành về Tokyo.\n\n🏙️ Trưa:\n🕛 12:00 – Ăn trưa tại Tokyo.\n\n🗼 Chiều – City Tour Tokyo:\n🏯 Viếng Chùa Asakusa – Đền Sensoji.\n🛍️ Tham quan mua sắm tại Nakamise.\n📷 Check-in tại Tokyo SkyTree (chụp ảnh bên ngoài).\n🚏 Ghé Shibuya Crossing & tượng Hachiko.\n\n🍱 Tối:\n🕕 18:00 – Ăn tối món Nhật.\n🏨 Nghỉ đêm tại Tokyo.'),
(100, 5, 'TOKYO – HÀ NỘI', '🍳 Sáng:\n🕗 07:00 – Ăn sáng tại khách sạn.\n👜 Tự do mua sắm tại Aeon Mall hoặc Akihabara.\n\n🍜 Trưa:\n🕛 12:00 – Ăn trưa.\n\n✈️ Chiều:\n🚌 Di chuyển ra sân bay Narita.\n🕒 Làm thủ tục check-in.\n\n🌙 Tối:\n🛫 Bay về Hà Nội.\n🏁 Kết thúc hành trình – HDV chia tay đoàn.');

-- ============================================================
-- 6. TẠO LỊCH KHỞI HÀNH
-- Sử dụng HDV có sẵn: nhan_su_id = 2, nguoi_dung_id = 6
-- ============================================================
INSERT INTO lich_khoi_hanh (id, tour_id, ngay_khoi_hanh, gio_xuat_phat, ngay_ket_thuc, gio_ket_thuc, diem_tap_trung, so_cho, hdv_id, trang_thai, ghi_chu)
VALUES 
    (200, 100, '2025-12-02', '21:00:00', '2025-12-06', '18:00:00', 'Sân bay Nội Bài – Ga đi quốc tế', 50, 2, 'SapKhoiHanh', 'Lịch khởi hành test tour hoàn chỉnh')
ON DUPLICATE KEY UPDATE ngay_khoi_hanh = VALUES(ngay_khoi_hanh), hdv_id = VALUES(hdv_id);

-- ============================================================
-- 7. PHÂN BỔ HDV
-- Sử dụng HDV có sẵn: nhan_su_id = 2
-- ============================================================
INSERT INTO phan_bo_nhan_su (lich_khoi_hanh_id, nhan_su_id, vai_tro, ghi_chu, trang_thai, thoi_gian_xac_nhan)
VALUES 
    (200, 2, 'HDV', 'Phân bổ HDV chính cho tour test', 'DaXacNhan', NOW())
ON DUPLICATE KEY UPDATE trang_thai = 'DaXacNhan';

-- ============================================================
-- 8. TẠO BOOKING
-- ============================================================
DELETE FROM booking WHERE booking_id IN (200, 201, 202, 203);

INSERT INTO booking (booking_id, khach_hang_id, tour_id, ngay_khoi_hanh, ngay_ket_thuc, so_nguoi, tong_tien, ngay_dat, trang_thai, ghi_chu)
VALUES 
    (200, 200, 100, '2025-12-02', '2025-12-06', 2, 65980000.00, NOW(), 'HoanTat', 'Booking test tour hoàn chỉnh - 2 người'),
    (201, 201, 100, '2025-12-02', '2025-12-06', 3, 98970000.00, NOW(), 'DaCoc', 'Booking test tour hoàn chỉnh - 3 người (2 lớn + 1 trẻ em)'),
    (202, 202, 100, '2025-12-02', '2025-12-06', 1, 32990000.00, NOW(), 'ChoXacNhan', 'Booking test tour hoàn chỉnh - 1 người'),
    (203, 203, 100, '2025-12-02', '2025-12-06', 2, 65980000.00, NOW(), 'DaCoc', 'Booking test tour hoàn chỉnh - 2 người');

-- ============================================================
-- 9. TẠO ĐIỂM CHECK-IN
-- ============================================================
DELETE FROM diem_checkin WHERE tour_id = 100 AND id IN (200, 201, 202, 203);

INSERT INTO diem_checkin (id, tour_id, ten_diem, loai_diem, thoi_gian_du_kien, ghi_chu, thu_tu)
VALUES 
    (200, 100, 'Sân bay Nội Bài - Điểm tập trung', 'tap_trung', '2025-12-02 21:00:00', 'Điểm check-in test', 1),
    (201, 100, 'Khách sạn Tokyo - Check-in', 'nghi_ngoi', '2025-12-02 20:00:00', 'Điểm check-in test', 2),
    (202, 100, 'Lâu đài Nagoya - Tham quan', 'tham_quan', '2025-12-03 10:00:00', 'Điểm check-in test', 3),
    (203, 100, 'Núi Phú Sĩ - Tham quan', 'tham_quan', '2025-12-04 09:00:00', 'Điểm check-in test', 4);

-- ============================================================
-- 10. TẠO TOUR_CHECKIN (Danh sách khách chi tiết)
-- LƯU Ý: 
-- - Khách đặt tour (có booking) → Cần đăng ký (nguoi_dung, khach_hang)
-- - Khách check-in (không có booking) → KHÔNG cần đăng ký, chỉ cần thông tin trong tour_checkin
-- ============================================================
DELETE FROM tour_checkin WHERE lich_khoi_hanh_id = 200;

-- Booking 200: 2 người (khach_hang_id = 200)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 200, 200, 200, nd.ho_ten, 'CMND200-1', 'PASS200-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 200;

-- Người thứ 2 trong booking 200 (người đi kèm, không có khach_hang_id riêng)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES (200, 200, 200, 'Nguyễn Thị Lan', 'CMND200-2', 'PASS200-2', '1992-03-20', 'Nu', 'Việt Nam', '0911111111', 'nguyenvanan@test.com', '123 Đường ABC, Quận 1, Hà Nội', 'ChuaCheckIn');

-- Booking 201: 3 người (khach_hang_id = 201)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 201, 201, 200, nd.ho_ten, 'CMND201-1', 'PASS201-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 201;

-- Người thứ 2 và 3 trong booking 201
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES 
    (201, 201, 200, 'Trần Văn Hùng', 'CMND201-2', 'PASS201-2', '1990-07-15', 'Nam', 'Việt Nam', '0922222222', 'tranthibinh@test.com', '456 Đường XYZ, Quận 3, TP.HCM', 'ChuaCheckIn'),
    (201, 201, 200, 'Trần Thị Mai', 'CMND201-3', 'PASS201-3', '2015-10-20', 'Nu', 'Việt Nam', NULL, NULL, '456 Đường XYZ, Quận 3, TP.HCM', 'ChuaCheckIn');

-- Booking 202: 1 người (khach_hang_id = 202)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 202, 202, 200, nd.ho_ten, 'CMND202-1', 'PASS202-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 202;

-- Booking 203: 2 người (khach_hang_id = 203)
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
SELECT 203, 203, 200, nd.ho_ten, 'CMND203-1', 'PASS203-1', kh.ngay_sinh, kh.gioi_tinh, 'Việt Nam', nd.so_dien_thoai, nd.email, kh.dia_chi, 'ChuaCheckIn'
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id = 203;

-- Người thứ 2 trong booking 203
INSERT INTO tour_checkin (booking_id, khach_hang_id, lich_khoi_hanh_id, ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh, quoc_tich, so_dien_thoai, email, dia_chi, trang_thai)
VALUES (203, 203, 200, 'Phạm Văn Đức', 'CMND203-2', 'PASS203-2', '1993-04-12', 'Nam', 'Việt Nam', '0944444444', 'phamthidung@test.com', '321 Đường GHI, Quận Thanh Khê, Đà Nẵng', 'ChuaCheckIn');

-- ============================================================
-- 11. TẠO CHECK-IN KHÁCH (Trạng thái check-in tại điểm)
-- Lưu ý: checkin_khach lưu theo booking_id (không phải từng khách riêng)
-- Mỗi booking có 1 record check-in tại mỗi điểm check-in
-- ============================================================
DELETE FROM checkin_khach WHERE booking_id IN (200, 201, 202, 203) AND diem_checkin_id = 200;

-- Check-in tại điểm 200 (Sân bay Nội Bài) cho các booking
-- Booking 200: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 200, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 2 người', 2);

-- Booking 201: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 201, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 3 người', 2);

-- Booking 202: chưa check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 202, 'chua_checkin', NULL, NULL, NULL);

-- Booking 203: đã check-in
INSERT INTO checkin_khach (diem_checkin_id, booking_id, trang_thai, thoi_gian_checkin, ghi_chu, nguoi_checkin_id)
VALUES (200, 203, 'da_checkin', NOW(), 'Đã check-in tại sân bay - 2 người', 2);

-- ============================================================
-- 12. TẠO YÊU CẦU ĐẶC BIỆT
-- ============================================================
DELETE FROM yeu_cau_dac_biet WHERE booking_id IN (200, 201, 202, 203);

INSERT INTO yeu_cau_dac_biet (booking_id, loai_yeu_cau, tieu_de, mo_ta, muc_do_uu_tien, trang_thai, nguoi_tao_id)
VALUES 
    (200, 'an_uong', 'Dị ứng hải sản', 'Khách bị dị ứng hải sản, cần tránh các món có hải sản trong suốt chuyến đi', 'cao', 'moi', 200),
    (201, 'suc_khoe', 'Cần hỗ trợ di chuyển', 'Có trẻ em 10 tuổi, cần hỗ trợ khi di chuyển và tham quan', 'trung_binh', 'moi', 201),
    (202, 'phong_o', 'Phòng đơn', 'Yêu cầu phòng đơn riêng, không ở chung', 'thap', 'moi', 202),
    (203, 'khac', 'Yêu cầu đặc biệt về visa', 'Cần hỗ trợ đặc biệt về thủ tục visa và giấy tờ', 'trung_binh', 'moi', 203);

-- ============================================================
-- 13. TẠO NHÀ CUNG CẤP
-- ============================================================
DELETE FROM nguoi_dung WHERE id IN (220, 221, 222);
DELETE FROM nha_cung_cap WHERE id_nha_cung_cap IN (220, 221, 222);

-- Tạo người dùng nhà cung cấp
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, ngay_tao)
VALUES 
    (220, 'ncc_khachsan', 'Nhà cung cấp Khách sạn', 'ncckhachsan@test.com', '0966666666', 'NhaCungCap', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (221, 'ncc_nhahang', 'Nhà cung cấp Nhà hàng', 'nccnhahang@test.com', '0977777777', 'NhaCungCap', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (222, 'ncc_xe', 'Nhà cung cấp Xe', 'nccxe@test.com', '0988888888', 'NhaCungCap', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), email = VALUES(email);

-- Tạo nhà cung cấp
INSERT INTO nha_cung_cap (id_nha_cung_cap, nguoi_dung_id, ten_don_vi, loai_dich_vu, dia_chi, lien_he, mo_ta, danh_gia_tb)
VALUES 
    (220, 220, 'Khách sạn Tokyo Grand', 'KhachSan', '123 Shibuya, Tokyo, Nhật Bản', '0987654321', 'Khách sạn 4 sao tại trung tâm Tokyo, gần các điểm tham quan nổi tiếng', 4.5),
    (221, 221, 'Nhà hàng Sushi Master', 'NhaHang', '456 Ginza, Tokyo, Nhật Bản', '0987654322', 'Nhà hàng sushi truyền thống Nhật Bản, phục vụ các món ăn cao cấp', 4.8),
    (222, 222, 'Công ty Vận chuyển Nhật Bản', 'Xe', '789 Shinjuku, Tokyo, Nhật Bản', '0987654323', 'Dịch vụ xe du lịch chuyên nghiệp, xe mới, tài xế kinh nghiệm', 4.7)
ON DUPLICATE KEY UPDATE ten_don_vi = VALUES(ten_don_vi), mo_ta = VALUES(mo_ta);

-- ============================================================
-- 14. TẠO DỊCH VỤ NHÀ CUNG CẤP
-- ============================================================
DELETE FROM dich_vu_nha_cung_cap WHERE nha_cung_cap_id IN (220, 221, 222);

INSERT INTO dich_vu_nha_cung_cap (nha_cung_cap_id, ten_dich_vu, mo_ta, loai_dich_vu, gia_tham_khao, don_vi_tinh, cong_suat_toi_da, thoi_gian_xu_ly, trang_thai)
VALUES 
    (220, 'Phòng đôi tiêu chuẩn', 'Phòng đôi tiêu chuẩn 4 sao, view đẹp, đầy đủ tiện nghi', 'KhachSan', 2500000.00, 'phòng/đêm', 20, '2 giờ', 'HoatDong'),
    (220, 'Phòng đơn cao cấp', 'Phòng đơn cao cấp với view thành phố', 'KhachSan', 1800000.00, 'phòng/đêm', 10, '2 giờ', 'HoatDong'),
    (221, 'Set menu Sushi Omakase', 'Set menu sushi cao cấp do đầu bếp chế biến', 'NhaHang', 1500000.00, 'người', 30, '1 giờ', 'HoatDong'),
    (221, 'Bữa tối Kaiseki', 'Bữa tối truyền thống Nhật Bản đầy đủ các món', 'NhaHang', 2000000.00, 'người', 25, '1.5 giờ', 'HoatDong'),
    (222, 'Xe 16 chỗ', 'Xe du lịch 16 chỗ ngồi, có điều hòa, wifi', 'Xe', 5000000.00, 'xe/ngày', 5, '30 phút', 'HoatDong'),
    (222, 'Xe 29 chỗ', 'Xe du lịch 29 chỗ ngồi, tiện nghi cao cấp', 'Xe', 8000000.00, 'xe/ngày', 3, '30 phút', 'HoatDong');

-- ============================================================
-- 15. TẠO PHÂN BỔ DỊCH VỤ
-- ============================================================
DELETE FROM phan_bo_dich_vu WHERE lich_khoi_hanh_id = 200;

INSERT INTO phan_bo_dich_vu (lich_khoi_hanh_id, nha_cung_cap_id, loai_dich_vu, ten_dich_vu, so_luong, don_vi, ngay_bat_dau, ngay_ket_thuc, gio_bat_dau, gio_ket_thuc, dia_diem, gia_tien, ghi_chu, trang_thai, thoi_gian_xac_nhan)
VALUES 
    (200, 220, 'KhachSan', 'Phòng đôi tiêu chuẩn - Tokyo', 10, 'phòng', '2025-12-02', '2025-12-06', '14:00:00', '11:00:00', 'Khách sạn Tokyo Grand', 10000000.00, 'Đặt 10 phòng đôi cho đoàn', 'DaXacNhan', NOW()),
    (200, 220, 'KhachSan', 'Phòng đơn cao cấp - Nagoya', 2, 'phòng', '2025-12-03', '2025-12-04', '14:00:00', '11:00:00', 'Khách sạn Nagoya Plaza', 3600000.00, 'Đặt 2 phòng đơn tại Nagoya', 'DaXacNhan', NOW()),
    (200, 221, 'NhaHang', 'Bữa tối Kaiseki - Tokyo', 8, 'người', '2025-12-02', '2025-12-02', '18:00:00', '20:00:00', 'Nhà hàng Sushi Master', 16000000.00, 'Bữa tối chào mừng đoàn', 'DaXacNhan', NOW()),
    (200, 222, 'Xe', 'Xe 29 chỗ - Tour 5 ngày', 1, 'xe', '2025-12-02', '2025-12-06', '08:00:00', '18:00:00', 'Toàn bộ hành trình', 32000000.00, 'Xe phục vụ suốt tour', 'DaXacNhan', NOW());

-- ============================================================
-- 16. TẠO NHẬT KÝ TOUR
-- ============================================================
DELETE FROM nhat_ky_tour WHERE tour_id = 100;

INSERT INTO nhat_ky_tour (tour_id, nhan_su_id, loai_nhat_ky, tieu_de, noi_dung, cach_xu_ly, thoi_tiet, ngay_ghi)
VALUES 
    (100, 2, 'hanh_trinh', 'Khởi hành từ Hà Nội', 'Đoàn đã tập trung đầy đủ tại sân bay Nội Bài. Tất cả khách đều có mặt đúng giờ. Thủ tục check-in diễn ra suôn sẻ.', 'Đã hoàn tất thủ tục, chờ lên máy bay', 'Trời quang, không mưa', '2025-12-02 21:00:00'),
    (100, 2, 'hoat_dong', 'Tham quan Lâu đài Nagoya', 'Đoàn đã đến tham quan Lâu đài Nagoya. Khách rất thích thú với kiến trúc cổ kính và lịch sử của lâu đài.', 'Tiếp tục hành trình theo lịch trình', 'Nắng đẹp, nhiệt độ 20°C', '2025-12-03 10:30:00'),
    (100, 2, 'hanh_trinh', 'Đến Núi Phú Sĩ', 'Đoàn đã đến khu vực Núi Phú Sĩ. Thời tiết tốt, có thể nhìn thấy đỉnh núi rõ ràng. Khách rất phấn khích.', 'Chụp ảnh và tham quan theo lịch trình', 'Trời quang, nhìn thấy đỉnh núi', '2025-12-04 09:00:00'),
    (100, 2, 'su_co', 'Một khách bị say xe', 'Có một khách bị say xe khi di chuyển từ Nagoya đến Kawaguchiko. Đã cho uống thuốc chống say xe.', 'Đã xử lý, khách đã ổn định. Tiếp tục hành trình', 'Nắng, nhiệt độ 18°C', '2025-12-04 11:00:00');

-- ============================================================
-- 17. TẠO ĐÁNH GIÁ
-- ============================================================
DELETE FROM danh_gia WHERE tour_id = 100 OR nhan_su_id = 2;

INSERT INTO danh_gia (khach_hang_id, tour_id, nhan_su_id, loai_danh_gia, tieu_chi, loai_dich_vu, diem, noi_dung, ngay_danh_gia)
VALUES 
    (200, 100, 2, 'Tour', 'Chất lượng tour', 'Tour', 5, 'Tour rất tuyệt vời! Hướng dẫn viên nhiệt tình, lịch trình hợp lý, các điểm tham quan đẹp. Sẽ quay lại lần sau.', NOW()),
    (201, 100, 2, 'Tour', 'Chất lượng tour', 'Tour', 4, 'Tour tốt, nhưng thời gian ở một số điểm hơi gấp. HDV rất chuyên nghiệp.', NOW()),
    (202, 100, 2, 'NhanSu', 'Chuyên môn', 'HDV', 5, 'HDV rất am hiểu về văn hóa Nhật Bản, giải thích rõ ràng, nhiệt tình hỗ trợ khách.', NOW()),
    (203, 100, 2, 'NhanSu', 'Thái độ phục vụ', 'HDV', 5, 'HDV có thái độ phục vụ rất tốt, luôn sẵn sàng giúp đỡ khách hàng.', NOW());

-- ============================================================
-- 18. TẠO ĐÁNH GIÁ HDV
-- ============================================================
DELETE FROM danh_gia_hdv WHERE nhan_su_id = 2 AND tour_id = 100;

INSERT INTO danh_gia_hdv (tour_id, nhan_su_id, khach_hang_id, diem_chuyen_mon, diem_thai_do, diem_giao_tiep, diem_tong, noi_dung_danh_gia, ngay_danh_gia)
VALUES 
    (100, 2, 200, 5, 5, 5, 5.00, 'HDV rất chuyên nghiệp, am hiểu sâu về địa điểm tham quan. Thái độ phục vụ tuyệt vời!', NOW()),
    (100, 2, 201, 4, 5, 4, 4.33, 'HDV tốt, nhưng có thể cải thiện thêm về kỹ năng giao tiếp.', NOW()),
    (100, 2, 202, 5, 5, 5, 5.00, 'HDV xuất sắc! Rất hài lòng với dịch vụ.', NOW());

-- ============================================================
-- 19. TẠO PHẢN HỒI HDV
-- ============================================================
DELETE FROM phan_hoi_hdv WHERE tour_id = 100 AND hdv_id = 2;

INSERT INTO phan_hoi_hdv (tour_id, hdv_id, loai_danh_gia, ten_doi_tuong, diem_danh_gia, tieu_de, noi_dung, diem_manh, diem_yeu, de_xuat, trang_thai, ngay_tao)
VALUES 
    (100, 2, 'KhachSan', 'Khách sạn Tokyo Grand', 4, 'Đánh giá dịch vụ khách sạn', 'Khách sạn có vị trí tốt, phòng sạch sẽ, nhưng dịch vụ ăn sáng cần cải thiện.', 'Vị trí đẹp, phòng sạch sẽ', 'Dịch vụ ăn sáng chưa đa dạng', 'Nên cải thiện menu ăn sáng', 'moi', NOW()),
    (100, 2, 'NhaHang', 'Nhà hàng Sushi Master', 5, 'Đánh giá nhà hàng', 'Nhà hàng tuyệt vời! Món ăn ngon, phục vụ chuyên nghiệp, không gian đẹp.', 'Món ăn ngon, phục vụ tốt', 'Không có', 'Tiếp tục duy trì chất lượng', 'da_xem', NOW()),
    (100, 2, 'Xe', 'Công ty Vận chuyển', 4, 'Đánh giá dịch vụ xe', 'Xe mới, sạch sẽ, tài xế lái an toàn. Tuy nhiên, cần cải thiện wifi trên xe.', 'Xe mới, tài xế an toàn', 'Wifi không ổn định', 'Cải thiện chất lượng wifi', 'moi', NOW());

-- ============================================================
-- 20. TẠO THÔNG BÁO
-- ============================================================
DELETE FROM thong_bao WHERE id >= 200 AND id < 210;

INSERT INTO thong_bao (id, tieu_de, noi_dung, loai_thong_bao, muc_do_uu_tien, nguoi_gui_id, nguoi_nhan_id, vai_tro_nhan, trang_thai, thoi_gian_gui, created_at)
VALUES 
    (200, 'Tour sắp khởi hành', 'Tour NAGOYA – PHÚ SĨ – TOKYO sẽ khởi hành vào ngày 02/12/2025. Vui lòng chuẩn bị đầy đủ giấy tờ và hành lý.', 'KhachHang', 'Cao', 1, NULL, 'KhachHang', 'DaGui', NOW(), NOW()),
    (201, 'Thông báo cho HDV', 'Bạn đã được phân bổ làm HDV cho tour NAGOYA – PHÚ SĨ – TOKYO. Vui lòng xem chi tiết lịch trình.', 'HDV', 'Cao', 1, 2, 'HDV', 'DaGui', NOW(), NOW()),
    (202, 'Yêu cầu đặc biệt cần xử lý', 'Có yêu cầu đặc biệt về dị ứng hải sản cần được xử lý cho tour sắp tới.', 'HDV', 'TrungBinh', 1, 2, 'HDV', 'DaGui', NOW(), NOW()),
    (203, 'Đánh giá tour', 'Cảm ơn bạn đã tham gia tour. Vui lòng dành vài phút để đánh giá tour của chúng tôi.', 'KhachHang', 'Thap', 1, NULL, 'KhachHang', 'DaGui', NOW(), NOW());

-- ============================================================
-- 21. TẠO GIAO DỊCH TÀI CHÍNH
-- ============================================================
DELETE FROM giao_dich_tai_chinh WHERE id >= 200 AND id < 210;

INSERT INTO giao_dich_tai_chinh (id, tour_id, booking_id, khach_hang_id, loai, loai_doi_tuong, doi_tuong_id, loai_giao_dich, so_tien, mo_ta, nguoi_thuc_hien_id, ngay_giao_dich)
VALUES 
    (200, 100, 200, 200, 'Thu', 'KhachHang', 200, 'Booking', 65980000.00, 'Thanh toán booking #200 - Tour NAGOYA – PHÚ SĨ – TOKYO', 200, '2025-12-01'),
    (201, 100, 201, 201, 'Thu', 'KhachHang', 201, 'Booking', 50000000.00, 'Cọc booking #201 - Tour NAGOYA – PHÚ SĨ – TOKYO', 201, '2025-12-01'),
    (202, 100, NULL, NULL, 'Chi', 'NhaCungCap', 220, 'ChiPhi', 10000000.00, 'Thanh toán khách sạn Tokyo Grand - 10 phòng', 1, '2025-12-02'),
    (203, 100, NULL, NULL, 'Chi', 'NhaCungCap', 221, 'ChiPhi', 16000000.00, 'Thanh toán nhà hàng Sushi Master - Bữa tối Kaiseki', 1, '2025-12-02'),
    (204, 100, NULL, NULL, 'Chi', 'NhaCungCap', 222, 'ChiPhi', 32000000.00, 'Thanh toán dịch vụ xe - Tour 5 ngày', 1, '2025-12-02');

SET FOREIGN_KEY_CHECKS = 1;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- HOÀN THÀNH!
-- ============================================================
-- Dữ liệu mẫu đã tạo:
-- ✅ Người dùng: 4 khách hàng + 1 HDV + 3 nhà cung cấp = 8 người
-- ✅ Khách hàng: 4 khách hàng
-- ✅ Nhân sự: 1 HDV (nhan_su_id = 210)
-- ✅ Tour: 1 tour (tour_id = 100)
-- ✅ Lịch trình tour: 6 ngày
-- ✅ Lịch khởi hành: 1 lịch (id = 200)
-- ✅ Phân bổ nhân sự: 1 HDV
-- ✅ Booking: 4 booking (200, 201, 202, 203)
-- ✅ Điểm check-in: 4 điểm
-- ✅ Tour check-in: 8 khách (từ booking + khách độc lập)
-- ✅ Check-in khách: 4 booking đã check-in
-- ✅ Yêu cầu đặc biệt: 4 yêu cầu
-- ✅ Nhà cung cấp: 3 nhà cung cấp (khách sạn, nhà hàng, xe)
-- ✅ Dịch vụ nhà cung cấp: 6 dịch vụ
-- ✅ Phân bổ dịch vụ: 4 dịch vụ đã phân bổ
-- ✅ Nhật ký tour: 4 nhật ký
-- ✅ Đánh giá: 4 đánh giá
-- ✅ Đánh giá HDV: 3 đánh giá
-- ✅ Phản hồi HDV: 3 phản hồi
-- ✅ Thông báo: 4 thông báo
-- ✅ Giao dịch tài chính: 5 giao dịch
-- ============================================================

