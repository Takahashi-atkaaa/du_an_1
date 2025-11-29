CREATE TABLE cong_no_hdv (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT,
    hdv_id INT,
    so_tien DECIMAL(15,2),
    loai_cong_no ENUM('Thu','Chi'),
    anh_hoa_don VARCHAR(255),
    trang_thai ENUM('ChoDuyet','DaDuyet','TuChoi') DEFAULT 'ChoDuyet',
    ngay_gui DATETIME,
    ghi_chu TEXT,
    FOREIGN KEY (tour_id) REFERENCES tour(tour_id),
    FOREIGN KEY (hdv_id) REFERENCES nguoi_dung(id)
);
