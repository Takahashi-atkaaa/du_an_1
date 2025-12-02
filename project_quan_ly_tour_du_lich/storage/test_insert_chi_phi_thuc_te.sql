
-- Thêm dữ liệu mẫu vào bảng tour nếu chưa có
INSERT INTO tour (tour_id, ten_tour, loai_tour, mo_ta, gia_co_ban, trang_thai)
VALUES (1, 'Tour Đà Nẵng', 'TrongNuoc', 'Tour du lịch Đà Nẵng 4 ngày 3 đêm', 50000000, 'HoatDong');

-- Thêm dữ liệu mẫu vào du_toan_tour

-- Thêm dữ liệu mẫu vào du_toan_tour
INSERT INTO du_toan_tour (tour_id, ten_tour, tong_du_toan, ghi_chu)
VALUES (1, 'Tour Đà Nẵng', 50000000, 'Dự toán tour Đà Nẵng');
SET @du_toan_id := LAST_INSERT_ID();

-- Thêm nhiều khoản chi thực tế cho tour này
INSERT INTO chi_phi_thuc_te (tour_id, du_toan_id, loai_chi_phi, ten_khoan_chi, so_tien, ngay_phat_sinh, mo_ta)
VALUES
(1, @du_toan_id, 'PhuongTien', 'Vé máy bay', 12000000, '2025-11-29', 'Vé máy bay khứ hồi'),
(1, @du_toan_id, 'LuuTru', 'Khách sạn', 15000000, '2025-11-29', 'Khách sạn 4 sao'),
(1, @du_toan_id, 'AnUong', 'Ăn uống', 8000000, '2025-11-29', 'Ăn uống đoàn'),
(1, @du_toan_id, 'VeThamQuan', 'Vé tham quan', 4000000, '2025-11-29', 'Vé tham quan các điểm'),
(1, @du_toan_id, 'PhatSinh', 'Chi phí phát sinh', 6000000, '2025-11-29', 'Phát sinh ngoài dự toán');
