-- Migration: Thêm bảng quản lý check-in và phân phòng
-- Ngày tạo: 2025-11-18

-- Bảng check-in khách hàng
CREATE TABLE IF NOT EXISTS tour_checkin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    khach_hang_id INT NOT NULL,
    lich_khoi_hanh_id INT,
    ho_ten VARCHAR(255) NOT NULL,
    so_cmnd VARCHAR(50),
    so_passport VARCHAR(50),
    ngay_sinh DATE,
    gioi_tinh ENUM('Nam', 'Nu', 'Khac') DEFAULT 'Khac',
    quoc_tich VARCHAR(100) DEFAULT 'Việt Nam',
    dia_chi TEXT,
    so_dien_thoai VARCHAR(20),
    email VARCHAR(255),
    checkin_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    checkout_time DATETIME,
    trang_thai ENUM('DaCheckIn', 'ChuaCheckIn', 'DaCheckOut') DEFAULT 'ChuaCheckIn',
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng phân phòng khách sạn
CREATE TABLE IF NOT EXISTS hotel_room_assignment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lich_khoi_hanh_id INT NOT NULL,
    booking_id INT NOT NULL,
    checkin_id INT,
    ten_khach_san VARCHAR(255) NOT NULL,
    so_phong VARCHAR(50) NOT NULL,
    loai_phong VARCHAR(100) DEFAULT 'Standard',
    so_giuong INT DEFAULT 1,
    ngay_nhan_phong DATE NOT NULL,
    ngay_tra_phong DATE NOT NULL,
    gia_phong DECIMAL(15,2) DEFAULT 0,
    trang_thai ENUM('DaDatPhong', 'DaNhanPhong', 'DaTraPhong', 'Huy') DEFAULT 'DaDatPhong',
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (checkin_id) REFERENCES tour_checkin(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index để tăng tốc truy vấn
CREATE INDEX idx_booking_id ON tour_checkin(booking_id);
CREATE INDEX idx_khach_hang_id ON tour_checkin(khach_hang_id);
CREATE INDEX idx_checkin_status ON tour_checkin(trang_thai);
CREATE INDEX idx_room_lich_khoi_hanh ON hotel_room_assignment(lich_khoi_hanh_id);
CREATE INDEX idx_room_booking ON hotel_room_assignment(booking_id);
CREATE INDEX idx_room_status ON hotel_room_assignment(trang_thai);
