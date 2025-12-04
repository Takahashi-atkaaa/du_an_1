-- Script thêm dữ liệu mẫu để test trang Check-in HDV
-- Lịch khởi hành ID = 10, Tour ID = 6, Ngày khởi hành = 2025-12-02

-- 1. Kiểm tra và tạo người dùng mẫu (nếu chưa có)
-- Lưu ý: Bảng nguoi_dung không có cột dia_chi, địa chỉ được lưu trong bảng khach_hang
INSERT INTO nguoi_dung (id, ten_dang_nhap, ho_ten, email, so_dien_thoai, vai_tro, mat_khau, ngay_tao)
VALUES 
    (100, 'nguyenvana', 'Nguyễn Văn A', 'nguyenvana@test.com', '0912345678', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (101, 'tranthib', 'Trần Thị B', 'tranthib@test.com', '0912345679', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
    (102, 'levanc', 'Lê Văn C', 'levanc@test.com', '0912345680', 'KhachHang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW())
ON DUPLICATE KEY UPDATE ho_ten = VALUES(ho_ten), email = VALUES(email), so_dien_thoai = VALUES(so_dien_thoai);

-- 2. Tạo khách hàng mẫu
-- Lưu ý: Bảng khach_hang chỉ có: khach_hang_id, nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh
INSERT INTO khach_hang (khach_hang_id, nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh)
VALUES 
    (100, 100, '123 Đường ABC, Hà Nội', 'Nam', '1990-01-01'),
    (101, 101, '456 Đường XYZ, TP.HCM', 'Nu', '1988-03-20'),
    (102, 102, '789 Đường DEF, Đà Nẵng', 'Nam', '1995-07-15')
ON DUPLICATE KEY UPDATE nguoi_dung_id = VALUES(nguoi_dung_id), dia_chi = VALUES(dia_chi);

-- 3. Tạo booking mẫu cho lịch khởi hành ID = 10 (tour_id = 6, ngay_khoi_hanh = 2025-12-02)
-- Xóa booking cũ nếu có (để tránh duplicate)
DELETE FROM booking WHERE booking_id IN (100, 101, 102);

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
    (102, 102, 6, '2025-12-02', '2025-12-05', 1, 32990000.00, '2025-11-17', 'ChoXacNhan', 'Booking test 3 - 1 người lớn');

-- 4. Tạo khách chi tiết trong tour_checkin
-- Xóa dữ liệu cũ nếu có (để tránh duplicate)
DELETE FROM tour_checkin WHERE booking_id IN (100, 101, 102) AND lich_khoi_hanh_id = 10;

-- Booking 100: Nguyễn Văn A (chủ booking - người 1)
INSERT INTO tour_checkin (
    booking_id,
    khach_hang_id,
    lich_khoi_hanh_id,
    ho_ten,
    so_cmnd,
    so_passport,
    ngay_sinh,
    gioi_tinh,
    quoc_tich,
    so_dien_thoai,
    email,
    dia_chi,
    ghi_chu,
    trang_thai
)
SELECT 
    b.booking_id,
    b.khach_hang_id,
    10 as lich_khoi_hanh_id,
    nd.ho_ten,
    CONCAT('CMND', b.booking_id, '-1') as so_cmnd,
    CONCAT('PASS', b.booking_id, '-1') as so_passport,
    '1990-01-01' as ngay_sinh,
    'Nam' as gioi_tinh,
    'Việt Nam' as quoc_tich,
    nd.so_dien_thoai,
    nd.email,
    kh.dia_chi,
    'Khách test - Người chủ booking' as ghi_chu,
    'ChuaCheckIn' as trang_thai
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 100;

-- Booking 100: Người thứ 2
INSERT INTO tour_checkin (
    booking_id,
    khach_hang_id,
    lich_khoi_hanh_id,
    ho_ten,
    so_cmnd,
    so_passport,
    ngay_sinh,
    gioi_tinh,
    quoc_tich,
    so_dien_thoai,
    email,
    dia_chi,
    ghi_chu,
    trang_thai
)
VALUES 
    (100, 100, 10, 'Nguyễn Thị B - Người 2', 'CMND100-2', 'PASS100-2', '1992-05-15', 'Nu', 'Việt Nam', '0912345678', 'nguyenvana@test.com', '123 Đường ABC, Hà Nội', 'Khách test - Người thứ 2', 'ChuaCheckIn');

-- Booking 101: Trần Thị B (chủ booking - người 1)
INSERT INTO tour_checkin (
    booking_id,
    khach_hang_id,
    lich_khoi_hanh_id,
    ho_ten,
    so_cmnd,
    so_passport,
    ngay_sinh,
    gioi_tinh,
    quoc_tich,
    so_dien_thoai,
    email,
    dia_chi,
    ghi_chu,
    trang_thai
)
SELECT 
    b.booking_id,
    b.khach_hang_id,
    10 as lich_khoi_hanh_id,
    nd.ho_ten,
    CONCAT('CMND', b.booking_id, '-1') as so_cmnd,
    CONCAT('PASS', b.booking_id, '-1') as so_passport,
    '1988-03-20' as ngay_sinh,
    'Nu' as gioi_tinh,
    'Việt Nam' as quoc_tich,
    nd.so_dien_thoai,
    nd.email,
    kh.dia_chi,
    'Khách test - Người chủ booking' as ghi_chu,
    'ChuaCheckIn' as trang_thai
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 101;

-- Booking 101: Người thứ 2 và trẻ em
INSERT INTO tour_checkin (
    booking_id,
    khach_hang_id,
    lich_khoi_hanh_id,
    ho_ten,
    so_cmnd,
    so_passport,
    ngay_sinh,
    gioi_tinh,
    quoc_tich,
    so_dien_thoai,
    email,
    dia_chi,
    ghi_chu,
    trang_thai
)
VALUES 
    (101, 101, 10, 'Trần Văn C - Người 2', 'CMND101-2', 'PASS101-2', '1993-08-20', 'Nam', 'Việt Nam', '0912345679', 'tranthib@test.com', '456 Đường XYZ, TP.HCM', 'Khách test - Người thứ 2', 'ChuaCheckIn'),
    (101, 101, 10, 'Trần Thị D - Trẻ em', 'CMND101-3', 'PASS101-3', '2015-12-10', 'Nu', 'Việt Nam', NULL, NULL, '456 Đường XYZ, TP.HCM', 'Khách test - Trẻ em', 'ChuaCheckIn');

-- Booking 102: Lê Văn C (chủ booking - 1 người)
INSERT INTO tour_checkin (
    booking_id,
    khach_hang_id,
    lich_khoi_hanh_id,
    ho_ten,
    so_cmnd,
    so_passport,
    ngay_sinh,
    gioi_tinh,
    quoc_tich,
    so_dien_thoai,
    email,
    dia_chi,
    ghi_chu,
    trang_thai
)
SELECT 
    b.booking_id,
    b.khach_hang_id,
    10 as lich_khoi_hanh_id,
    nd.ho_ten,
    CONCAT('CMND', b.booking_id, '-1') as so_cmnd,
    CONCAT('PASS', b.booking_id, '-1') as so_passport,
    '1995-07-15' as ngay_sinh,
    'Nam' as gioi_tinh,
    'Việt Nam' as quoc_tich,
    nd.so_dien_thoai,
    nd.email,
    kh.dia_chi,
    'Khách test - 1 người' as ghi_chu,
    'ChuaCheckIn' as trang_thai
FROM booking b
INNER JOIN khach_hang kh ON b.khach_hang_id = kh.khach_hang_id
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE b.booking_id = 102;

-- 5. Kiểm tra dữ liệu đã insert
SELECT 
    '=== KIỂM TRA BOOKING ===' as info
UNION ALL
SELECT CONCAT('Booking ID: ', booking_id, ' | Tour ID: ', tour_id, ' | Ngày khởi hành: ', ngay_khoi_hanh, ' | Số người: ', so_nguoi, ' | Trạng thái: ', trang_thai)
FROM booking
WHERE booking_id IN (100, 101, 102)
UNION ALL
SELECT '=== KIỂM TRA KHÁCH HÀNG ==='
UNION ALL
SELECT CONCAT('Khách hàng ID: ', kh.khach_hang_id, ' | Tên: ', nd.ho_ten, ' | Email: ', nd.email)
FROM khach_hang kh
INNER JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
WHERE kh.khach_hang_id IN (100, 101, 102)
UNION ALL
SELECT '=== KIỂM TRA LỊCH KHỞI HÀNH ==='
UNION ALL
SELECT CONCAT('Lịch ID: ', id, ' | Tour ID: ', tour_id, ' | Ngày khởi hành: ', ngay_khoi_hanh)
FROM lich_khoi_hanh
WHERE id = 10;

