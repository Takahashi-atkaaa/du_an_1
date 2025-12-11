-- ============================================================
-- MIGRATION: Hệ Thống Lương, Hoa Hồng & Thưởng cho HDV
-- Database: quan_ly_tour_du_lich
-- Ngày tạo: 2025-01-01
-- ============================================================

-- ============================================================
-- 1. ALTER TABLE nhan_su - Thêm cột commission_percentage
-- ============================================================
-- Kiểm tra cột có tồn tại không
SET @dbname = DATABASE();
SET @tablename = 'nhan_su';
SET @columnname = 'commission_percentage';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE 
    (COLUMN_NAME = @columnname) AND 
    (TABLE_NAME = @tablename) AND 
    (TABLE_SCHEMA = @dbname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " DECIMAL(5,2) DEFAULT 5.00 COMMENT 'Tỉ lệ hoa hồng (%)' AFTER kinh_nghiem")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ============================================================
-- 2. CREATE TABLE hdv_salary - Bảng lương HDV
-- ============================================================
CREATE TABLE IF NOT EXISTS hdv_salary (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID bản ghi lương',
    nhan_su_id INT NOT NULL COMMENT 'ID nhân sự',
    tour_id INT COMMENT 'ID tour',
    lich_khoi_hanh_id INT COMMENT 'ID lịch khởi hành',
    base_salary DECIMAL(15,2) DEFAULT 0 COMMENT 'Lương cơ bản',
    commission_percentage DECIMAL(5,2) DEFAULT 0 COMMENT 'Tỉ lệ hoa hồng (%)',
    tour_revenue DECIMAL(15,2) DEFAULT 0 COMMENT 'Doanh thu tour',
    commission_amount DECIMAL(15,2) DEFAULT 0 COMMENT 'Tiền hoa hồng',
    bonus_amount DECIMAL(15,2) DEFAULT 0 COMMENT 'Tiền thưởng',
    total_amount DECIMAL(15,2) DEFAULT 0 COMMENT 'Tổng tiền',
    payment_status ENUM('Pending', 'Approved', 'Paid') DEFAULT 'Pending' COMMENT 'Trạng thái thanh toán',
    payment_date DATETIME COMMENT 'Ngày thanh toán',
    notes TEXT COMMENT 'Ghi chú',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
    
    FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE SET NULL,
    FOREIGN KEY (lich_khoi_hanh_id) REFERENCES lich_khoi_hanh(id) ON DELETE SET NULL,
    
    INDEX idx_nhan_su_id (nhan_su_id),
    INDEX idx_tour_id (tour_id),
    INDEX idx_lich_khoi_hanh_id (lich_khoi_hanh_id),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lương theo tour của HDV';

-- ============================================================
-- 3. CREATE TABLE hdv_bonus - Bảng thưởng HDV
-- ============================================================
CREATE TABLE IF NOT EXISTS hdv_bonus (
    id INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID bản ghi thưởng',
    nhan_su_id INT NOT NULL COMMENT 'ID nhân sự',
    bonus_type VARCHAR(100) DEFAULT 'KhongXacDinh' COMMENT 'Loại thưởng',
    amount DECIMAL(15,2) DEFAULT 0 COMMENT 'Số tiền thưởng',
    reason TEXT COMMENT 'Lý do thưởng',
    award_date DATE COMMENT 'Ngày thưởng',
    approval_status ENUM('ChoPheDuyet', 'DuyetPhep', 'TuChoi') DEFAULT 'ChoPheDuyet' COMMENT 'Trạng thái phê duyệt',
    approved_by INT COMMENT 'Phê duyệt bởi (user_id)',
    notes TEXT COMMENT 'Ghi chú',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Ngày cập nhật',
    
    FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
    
    INDEX idx_nhan_su_id (nhan_su_id),
    INDEX idx_approval_status (approval_status),
    INDEX idx_award_date (award_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng thưởng cho HDV';

-- ============================================================
-- 4. INSERT dữ liệu mẫu (tùy chọn)
-- ============================================================
-- Thêm dữ liệu mẫu cho lương (bỏ comment để chạy)
/*
INSERT INTO hdv_salary (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status)
SELECT 
    100, 
    6, 
    10, 
    5000000.00, 
    5.00, 
    263920000.00, 
    (263920000.00 * 5.00 / 100), 
    0, 
    5000000.00 + (263920000.00 * 5.00 / 100), 
    'Pending'
WHERE NOT EXISTS (SELECT 1 FROM hdv_salary WHERE nhan_su_id = 100 AND tour_id = 6 AND lich_khoi_hanh_id = 10);
*/

-- ============================================================
-- 5. Tạo VIEW cho thống kê lương HDV
-- ============================================================
CREATE OR REPLACE VIEW view_hdv_salary_summary AS
SELECT 
    hs.nhan_su_id,
    nd.ho_ten,
    nd.so_dien_thoai,
    COUNT(DISTINCT hs.tour_id) as so_tour,
    SUM(hs.base_salary) as tong_luong_co_ban,
    SUM(hs.commission_amount) as tong_hoa_hong,
    SUM(hs.bonus_amount) as tong_bonus_trong_luong,
    SUM(hs.total_amount) as tong_luong_all,
    COALESCE(SUM(hb.amount), 0) as tong_thuong,
    (SUM(hs.total_amount) + COALESCE(SUM(hb.amount), 0)) as grand_total,
    COUNT(CASE WHEN hs.payment_status = 'Paid' THEN 1 END) as so_luong_da_thanh_toan,
    COUNT(CASE WHEN hs.payment_status = 'Pending' THEN 1 END) as so_luong_dang_cho
FROM hdv_salary hs
JOIN nhan_su ns ON hs.nhan_su_id = ns.nhan_su_id
JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
LEFT JOIN hdv_bonus hb ON hs.nhan_su_id = hb.nhan_su_id AND hb.approval_status = 'DuyetPhep'
GROUP BY hs.nhan_su_id, nd.ho_ten, nd.so_dien_thoai;

-- ============================================================
-- NOTES:
-- ============================================================
-- 1. Bảng hdv_salary lưu trữ lương chi tiết theo từng tour
-- 2. Bảng hdv_bonus lưu trữ các khoản thưởng riêng biệt
-- 3. Cột commission_percentage ở bảng nhan_su là tỉ lệ mặc định
-- 4. View view_hdv_salary_summary giúp thống kê nhanh chóng
-- 5. Trạng thái thanh toán: Pending (chưa duyệt), Approved (đã duyệt), Paid (đã thanh toán)
-- 6. Trạng thái phê duyệt thưởng: ChoPheDuyet (chờ), DuyetPhep (phê duyệt), TuChoi (từ chối)
