-- Bổ sung bảng du_toan_tour nếu chưa có
CREATE TABLE IF NOT EXISTS du_toan_tour (
  du_toan_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  ten_tour VARCHAR(255) NOT NULL,
  tong_du_toan DECIMAL(15,2) NOT NULL,
  ngay_tao DATE,
  ghi_chu TEXT,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bổ sung bảng chi_phi_thuc_te nếu chưa có
CREATE TABLE IF NOT EXISTS chi_phi_thuc_te (
  chi_phi_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  du_toan_id INT,
  loai_chi_phi VARCHAR(100) NOT NULL,
  ten_khoan_chi VARCHAR(255) NOT NULL,
  so_tien DECIMAL(15,2) NOT NULL,
  ngay_phat_sinh DATE NOT NULL,
  mo_ta TEXT,
  chung_tu VARCHAR(255),
  ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (du_toan_id) REFERENCES du_toan_tour(du_toan_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
