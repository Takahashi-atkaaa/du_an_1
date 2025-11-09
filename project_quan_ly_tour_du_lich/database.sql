-- Tạo database
CREATE DATABASE IF NOT EXISTS quan_ly_tour_du_lich CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_ly_tour_du_lich;

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS nguoi_dung (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    vai_tro ENUM('admin', 'hdv', 'khach_hang', 'nha_cung_cap') DEFAULT 'khach_hang',
    so_dien_thoai VARCHAR(20),
    dia_chi TEXT,
    trang_thai TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng khách hàng
CREATE TABLE IF NOT EXISTS khach_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id INT NOT NULL,
    cmnd_cccd VARCHAR(20),
    ngay_sinh DATE,
    gioi_tinh ENUM('nam', 'nu', 'khac'),
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng HDV (Hướng dẫn viên)
CREATE TABLE IF NOT EXISTS hdv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id INT NOT NULL,
    bang_cap VARCHAR(100),
    kinh_nghiem TEXT,
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng nhà cung cấp
CREATE TABLE IF NOT EXISTS nha_cung_cap (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nguoi_dung_id INT NOT NULL,
    ten_cong_ty VARCHAR(200),
    ma_so_thue VARCHAR(50),
    dia_chi TEXT,
    so_dien_thoai VARCHAR(20),
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng tour
CREATE TABLE IF NOT EXISTS tours (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_tour VARCHAR(200) NOT NULL,
    mo_ta TEXT,
    gia DECIMAL(15,2) NOT NULL,
    so_ngay INT DEFAULT 1,
    so_dem INT DEFAULT 0,
    diem_khoi_hanh VARCHAR(100),
    diem_den VARCHAR(100),
    hinh_anh VARCHAR(255),
    so_cho_trong INT DEFAULT 0,
    trang_thai ENUM('dang_mo', 'het_cho', 'dang_chuan_bi', 'da_ket_thuc') DEFAULT 'dang_mo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng booking (Đặt tour)
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    khach_hang_id INT NOT NULL,
    hdv_id INT,
    so_luong_nguoi INT NOT NULL DEFAULT 1,
    ngay_khoi_hanh DATE,
    ngay_ket_thuc DATE,
    tong_tien DECIMAL(15,2) NOT NULL,
    trang_thai ENUM('cho_xac_nhan', 'da_xac_nhan', 'dang_di', 'hoan_thanh', 'da_huy') DEFAULT 'cho_xac_nhan',
    ghi_chu TEXT,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (khach_hang_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    FOREIGN KEY (hdv_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng giao dịch (Thanh toán)
CREATE TABLE IF NOT EXISTS giao_dich (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    so_tien DECIMAL(15,2) NOT NULL,
    loai_giao_dich ENUM('thanh_toan', 'hoan_tien') DEFAULT 'thanh_toan',
    phuong_thuc ENUM('tien_mat', 'chuyen_khoan', 'the') DEFAULT 'tien_mat',
    trang_thai ENUM('cho_xu_ly', 'thanh_cong', 'that_bai') DEFAULT 'cho_xu_ly',
    ghi_chu TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng đánh giá
CREATE TABLE IF NOT EXISTS danh_gia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    khach_hang_id INT NOT NULL,
    booking_id INT,
    diem_so INT NOT NULL CHECK (diem_so >= 1 AND diem_so <= 5),
    noi_dung TEXT,
    trang_thai TINYINT(1) DEFAULT 1,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
    FOREIGN KEY (khach_hang_id) REFERENCES nguoi_dung(id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
-- Tạo tài khoản mặc định (password: password)
-- Lưu ý: Password đã được hash bằng password_hash('password', PASSWORD_DEFAULT)
-- Để tạo hash mới, chạy: php generate_password.php

INSERT INTO nguoi_dung (ten, email, password, vai_tro) VALUES
('Admin', 'admin@example.com', '$2y$12$9eUtO/stZ3WwiOshqytBwOjySbJuJjrI4ndgrmyiJe93RJR0Jp4kC', 'admin'),
('Hướng dẫn viên 1', 'hdv@example.com', '$2y$12$9eUtO/stZ3WwiOshqytBwOjySbJuJjrI4ndgrmyiJe93RJR0Jp4kC', 'hdv'),
('Khách hàng 1', 'khach@example.com', '$2y$12$9eUtO/stZ3WwiOshqytBwOjySbJuJjrI4ndgrmyiJe93RJR0Jp4kC', 'khach_hang');

-- Thêm tour mẫu
INSERT INTO tours (ten_tour, mo_ta, gia, so_ngay, so_dem, diem_khoi_hanh, diem_den, so_cho_trong) VALUES
('Tour Hà Nội - Sapa', 'Tour khám phá Sapa với cảnh đẹp núi rừng và văn hóa dân tộc', 2500000, 3, 2, 'Hà Nội', 'Sapa', 20),
('Tour Đà Nẵng - Hội An', 'Tour tham quan phố cổ Hội An và bãi biển Đà Nẵng', 3000000, 4, 3, 'Đà Nẵng', 'Hội An', 15),
('Tour Phú Quốc', 'Tour nghỉ dưỡng tại đảo Phú Quốc', 5000000, 5, 4, 'TP.HCM', 'Phú Quốc', 30);

-- Lưu ý: 
-- - Password mặc định cho tất cả tài khoản: "password"
-- - Để đổi password, sử dụng: UPDATE nguoi_dung SET password = password_hash('password_moi', PASSWORD_DEFAULT) WHERE id = ?;
-- - Hoặc chạy script: php generate_password.php để tạo hash mới

