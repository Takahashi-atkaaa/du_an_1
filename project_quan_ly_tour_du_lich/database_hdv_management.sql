-- ======================================
-- BỔ SUNG HỆ THỐNG QUẢN LÝ HDV NÂNG CAO
-- ======================================

-- 1. Cập nhật bảng nhan_su: Thêm các trường phân loại HDV
ALTER TABLE nhan_su 
ADD COLUMN loai_hdv ENUM('NoiDia','QuocTe','ChuyenTuyen','ChuyenDoan','TongHop') DEFAULT 'TongHop' AFTER vai_tro,
ADD COLUMN chuyen_tuyen VARCHAR(255) COMMENT 'Các tuyến chuyên: Miền Bắc, Miền Trung, Miền Nam, Đông Nam Á...' AFTER loai_hdv,
ADD COLUMN danh_gia_tb DECIMAL(3,2) DEFAULT 0 COMMENT 'Điểm đánh giá trung bình 0-5' AFTER chuyen_tuyen,
ADD COLUMN so_tour_da_dan INT DEFAULT 0 COMMENT 'Tổng số tour đã dẫn' AFTER danh_gia_tb,
ADD COLUMN trang_thai_lam_viec ENUM('SanSang','DangBan','NghiPhep','TamNghi') DEFAULT 'SanSang' AFTER so_tour_da_dan;

-- 2. Bảng LỊCH LÀM VIỆC HDV (theo dõi lịch, ngày nghỉ, ngày bận)
CREATE TABLE lich_lam_viec_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  tour_id INT NULL COMMENT 'NULL nếu là ngày nghỉ/bận',
  loai_lich ENUM('Tour','NghiPhep','Ban','DatTruoc') NOT NULL COMMENT 'Loại lịch làm việc',
  ngay_bat_dau DATE NOT NULL,
  ngay_ket_thuc DATE NOT NULL,
  ghi_chu TEXT,
  trang_thai ENUM('DuKien','XacNhan','HoanThanh','Huy') DEFAULT 'DuKien',
  nguoi_tao_id INT COMMENT 'Người tạo lịch (admin)',
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ngay_cap_nhat TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE SET NULL,
  FOREIGN KEY (nguoi_tao_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_ngay (ngay_bat_dau, ngay_ket_thuc)
) COMMENT='Lịch làm việc HDV: tour, nghỉ phép, bận';

-- 3. Bảng HIỆU SUẤT HDV (phân tích, đánh giá)
CREATE TABLE hieu_suat_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  thang INT NOT NULL COMMENT 'Tháng 1-12',
  nam INT NOT NULL COMMENT 'Năm',
  so_tour_thang INT DEFAULT 0 COMMENT 'Số tour trong tháng',
  so_ngay_lam_viec INT DEFAULT 0 COMMENT 'Số ngày làm việc',
  doanh_thu_mang_lai DECIMAL(15,2) DEFAULT 0 COMMENT 'Doanh thu tour đã dẫn',
  diem_danh_gia_tb DECIMAL(3,2) DEFAULT 0 COMMENT 'Điểm TB từ khách hàng',
  so_khieu_nai INT DEFAULT 0 COMMENT 'Số khiếu nại trong tháng',
  so_khen_thuong INT DEFAULT 0 COMMENT 'Số lần được khen thưởng',
  ghi_chu TEXT,
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  UNIQUE KEY unique_thang_nam (nhan_su_id, thang, nam),
  INDEX idx_thang_nam (thang, nam)
) COMMENT='Báo cáo hiệu suất HDV theo tháng';

-- 4. Bảng ĐÁNH GIÁ HDV (từ khách hàng sau tour)
CREATE TABLE danh_gia_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  tour_id INT NOT NULL,
  nhan_su_id INT NOT NULL COMMENT 'HDV được đánh giá',
  khach_hang_id INT COMMENT 'Khách hàng đánh giá',
  diem_chuyen_mon TINYINT CHECK (diem_chuyen_mon BETWEEN 1 AND 5) COMMENT 'Điểm chuyên môn 1-5',
  diem_thai_do TINYINT CHECK (diem_thai_do BETWEEN 1 AND 5) COMMENT 'Điểm thái độ 1-5',
  diem_giao_tiep TINYINT CHECK (diem_giao_tiep BETWEEN 1 AND 5) COMMENT 'Điểm giao tiếp 1-5',
  diem_tong DECIMAL(3,2) COMMENT 'Điểm tổng = TB 3 tiêu chí',
  noi_dung_danh_gia TEXT COMMENT 'Nhận xét chi tiết',
  ngay_danh_gia TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tour(tour_id) ON DELETE CASCADE,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  FOREIGN KEY (khach_hang_id) REFERENCES khach_hang(khach_hang_id) ON DELETE SET NULL,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_tour (tour_id)
) COMMENT='Đánh giá HDV từ khách hàng';

-- 5. Bảng THÔNG BÁO/NHẮC NHỞ HDV
CREATE TABLE thong_bao_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT COMMENT 'NULL = thông báo chung cho tất cả HDV',
  loai_thong_bao ENUM('LichTour','NhacNho','CanhBao','ThongBao') NOT NULL,
  tieu_de VARCHAR(255) NOT NULL,
  noi_dung TEXT NOT NULL,
  uu_tien ENUM('Thap','TrungBinh','Cao','KhanCap') DEFAULT 'TrungBinh',
  da_xem BOOLEAN DEFAULT FALSE,
  ngay_gui TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  ngay_xem TIMESTAMP NULL,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  INDEX idx_nhan_su_chua_xem (nhan_su_id, da_xem)
) COMMENT='Thông báo và nhắc nhở cho HDV';

-- 6. Bảng CHỨNG CHỈ HDV (chi tiết hơn)
CREATE TABLE chung_chi_hdv (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nhan_su_id INT NOT NULL,
  ten_chung_chi VARCHAR(255) NOT NULL COMMENT 'Tên chứng chỉ/bằng cấp',
  loai_chung_chi ENUM('HDV','NgoaiNgu','KyNang','AnToan','Khac') NOT NULL,
  co_quan_cap VARCHAR(255) COMMENT 'Nơi cấp',
  ngay_cap DATE,
  ngay_het_han DATE COMMENT 'NULL nếu vô thời hạn',
  so_chung_chi VARCHAR(100),
  file_dinh_kem VARCHAR(255) COMMENT 'Link file scan chứng chỉ',
  trang_thai ENUM('ConHan','SapHetHan','HetHan') DEFAULT 'ConHan',
  ghi_chu TEXT,
  ngay_tao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (nhan_su_id) REFERENCES nhan_su(nhan_su_id) ON DELETE CASCADE,
  INDEX idx_nhan_su (nhan_su_id),
  INDEX idx_het_han (ngay_het_han)
) COMMENT='Quản lý chi tiết chứng chỉ HDV';

-- ======================================
-- DỮ LIỆU MẪU
-- ======================================

-- Cập nhật phân loại HDV cho nhân sự hiện có
UPDATE nhan_su 
SET loai_hdv = 'NoiDia', 
    chuyen_tuyen = 'Miền Bắc',
    trang_thai_lam_viec = 'SanSang'
WHERE vai_tro = 'HDV' LIMIT 1;

-- Thêm lịch làm việc mẫu (ngày nghỉ)
INSERT INTO lich_lam_viec_hdv (nhan_su_id, loai_lich, ngay_bat_dau, ngay_ket_thuc, ghi_chu, trang_thai)
SELECT nhan_su_id, 'NghiPhep', CURDATE() + INTERVAL 7 DAY, CURDATE() + INTERVAL 9 DAY, 'Nghỉ phép năm', 'XacNhan'
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- Thêm báo cáo hiệu suất mẫu
INSERT INTO hieu_suat_hdv (nhan_su_id, thang, nam, so_tour_thang, so_ngay_lam_viec, diem_danh_gia_tb)
SELECT nhan_su_id, MONTH(CURDATE()), YEAR(CURDATE()), 3, 15, 4.5
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- Thêm thông báo mẫu
INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
SELECT nhan_su_id, 'NhacNho', 'Chuẩn bị tour tuần sau', 'Tour Hà Nội - Hạ Long sẽ khởi hành vào 20/11/2025. Vui lòng chuẩn bị tài liệu và thiết bị.', 'Cao'
FROM nhan_su WHERE vai_tro = 'HDV' LIMIT 1;

-- ======================================
-- TRIGGER TỰ ĐỘNG CẬP NHẬT
-- ======================================

-- Trigger: Tự động tính điểm tổng khi đánh giá HDV
DELIMITER $$
CREATE TRIGGER before_insert_danh_gia_hdv
BEFORE INSERT ON danh_gia_hdv
FOR EACH ROW
BEGIN
    SET NEW.diem_tong = (NEW.diem_chuyen_mon + NEW.diem_thai_do + NEW.diem_giao_tiep) / 3;
END$$
DELIMITER ;

-- Trigger: Cập nhật điểm TB và số tour đã dẫn của HDV khi có đánh giá mới
DELIMITER $$
CREATE TRIGGER after_insert_danh_gia_hdv
AFTER INSERT ON danh_gia_hdv
FOR EACH ROW
BEGIN
    DECLARE avg_score DECIMAL(3,2);
    DECLARE tour_count INT;
    
    -- Tính điểm TB
    SELECT AVG(diem_tong) INTO avg_score
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Đếm số tour
    SELECT COUNT(DISTINCT tour_id) INTO tour_count
    FROM danh_gia_hdv
    WHERE nhan_su_id = NEW.nhan_su_id;
    
    -- Cập nhật vào bảng nhan_su
    UPDATE nhan_su
    SET danh_gia_tb = avg_score,
        so_tour_da_dan = tour_count
    WHERE nhan_su_id = NEW.nhan_su_id;
END$$
DELIMITER ;

-- Trigger: Cảnh báo khi chứng chỉ sắp hết hạn (30 ngày)
DELIMITER $$
CREATE TRIGGER after_insert_chung_chi_hdv
AFTER INSERT ON chung_chi_hdv
FOR EACH ROW
BEGIN
    IF NEW.ngay_het_han IS NOT NULL AND DATEDIFF(NEW.ngay_het_han, CURDATE()) <= 30 THEN
        UPDATE chung_chi_hdv
        SET trang_thai = 'SapHetHan'
        WHERE id = NEW.id;
        
        -- Tạo thông báo nhắc nhở
        INSERT INTO thong_bao_hdv (nhan_su_id, loai_thong_bao, tieu_de, noi_dung, uu_tien)
        VALUES (
            NEW.nhan_su_id,
            'CanhBao',
            CONCAT('Chứng chỉ ', NEW.ten_chung_chi, ' sắp hết hạn'),
            CONCAT('Chứng chỉ của bạn sẽ hết hạn vào ', DATE_FORMAT(NEW.ngay_het_han, '%d/%m/%Y'), '. Vui lòng gia hạn kịp thời.'),
            'Cao'
        );
    END IF;
END$$
DELIMITER ;

-- ======================================
-- INDEX ĐỂ TỐI ƯU TÌM KIẾM
-- ======================================
CREATE INDEX idx_loai_hdv ON nhan_su(loai_hdv, trang_thai_lam_viec);
CREATE INDEX idx_lich_hdv_trang_thai ON lich_lam_viec_hdv(nhan_su_id, trang_thai, ngay_bat_dau);

-- ======================================
-- VIEW HỖ TRỢ TRUY VẤN NHANH
-- ======================================

-- View: HDV đang rảnh (sẵn sàng nhận tour)
CREATE VIEW v_hdv_san_sang AS
SELECT 
    ns.nhan_su_id,
    nd.ho_ten,
    nd.email,
    nd.so_dien_thoai,
    ns.loai_hdv,
    ns.chuyen_tuyen,
    ns.danh_gia_tb,
    ns.so_tour_da_dan,
    ns.ngon_ngu
FROM nhan_su ns
INNER JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
WHERE ns.vai_tro = 'HDV' 
  AND ns.trang_thai_lam_viec = 'SanSang'
  AND ns.nhan_su_id NOT IN (
      SELECT nhan_su_id 
      FROM lich_lam_viec_hdv 
      WHERE trang_thai IN ('DuKien', 'XacNhan')
        AND CURDATE() BETWEEN ngay_bat_dau AND ngay_ket_thuc
  );

-- View: Thống kê hiệu suất HDV
CREATE VIEW v_thong_ke_hieu_suat_hdv AS
SELECT 
    ns.nhan_su_id,
    nd.ho_ten,
    ns.loai_hdv,
    COUNT(DISTINCT llv.tour_id) as tong_tour,
    AVG(dg.diem_tong) as diem_tb,
    SUM(CASE WHEN llv.trang_thai = 'HoanThanh' THEN 1 ELSE 0 END) as tour_hoan_thanh,
    MAX(llv.ngay_ket_thuc) as tour_gan_nhat
FROM nhan_su ns
INNER JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
LEFT JOIN lich_lam_viec_hdv llv ON ns.nhan_su_id = llv.nhan_su_id AND llv.loai_lich = 'Tour'
LEFT JOIN danh_gia_hdv dg ON ns.nhan_su_id = dg.nhan_su_id
WHERE ns.vai_tro = 'HDV'
GROUP BY ns.nhan_su_id, nd.ho_ten, ns.loai_hdv;
