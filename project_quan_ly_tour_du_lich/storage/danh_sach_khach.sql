CREATE TABLE danh_sach_khach (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    ho_ten VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    so_dien_thoai VARCHAR(50),
    ngay_them DATETIME DEFAULT CURRENT_TIMESTAMP,
    ghi_chu TEXT,
    FOREIGN KEY (tour_id) REFERENCES tour(tour_id)
);