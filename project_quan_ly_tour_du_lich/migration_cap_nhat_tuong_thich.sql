-- ============================================
-- MIGRATION: CẬP NHẬT TƯƠNG THÍCH VỚI CODE HIỆN TẠI
-- ============================================
-- Đảm bảo các cột trong nhan_su được tự động cập nhật từ dữ liệu hiện có

USE quan_ly_tour_du_lich;

-- ======================================
-- BƯỚC 1: CẬP NHẬT DỮ LIỆU HIỆN CÓ
-- ======================================

-- Cập nhật loai_hdv từ ngon_ngu và kinh_nghiem
UPDATE nhan_su 
SET loai_hdv = CASE 
    WHEN ngon_ngu LIKE '%Anh%' OR ngon_ngu LIKE '%Nhật%' OR ngon_ngu LIKE '%Hàn%' THEN 'QuocTe'
    WHEN kinh_nghiem LIKE '%chuyên%' OR kinh_nghiem LIKE '%tuyến%' THEN 'ChuyenTuyen'
    WHEN kinh_nghiem LIKE '%đoàn%' THEN 'ChuyenDoan'
    ELSE 'NoiDia'
END
WHERE vai_tro = 'HDV' AND loai_hdv IS NULL;

-- Cập nhật chuyen_tuyen từ kinh_nghiem
UPDATE nhan_su 
SET chuyen_tuyen = CASE 
    WHEN kinh_nghiem LIKE '%Miền Bắc%' THEN 'Miền Bắc'
    WHEN kinh_nghiem LIKE '%Miền Trung%' THEN 'Miền Trung'
    WHEN kinh_nghiem LIKE '%Miền Nam%' THEN 'Miền Nam'
    WHEN kinh_nghiem LIKE '%Đông Nam Á%' THEN 'Đông Nam Á'
    ELSE NULL
END
WHERE vai_tro = 'HDV' AND chuyen_tuyen IS NULL;

-- Cập nhật danh_gia_tb từ phan_hoi_danh_gia
UPDATE nhan_su ns
SET danh_gia_tb = COALESCE(
    (SELECT AVG(phd.diem) 
     FROM phan_hoi_danh_gia phd
     INNER JOIN lich_khoi_hanh lkh ON phd.tour_id = lkh.tour_id
     WHERE lkh.hdv_id = ns.nhan_su_id AND phd.loai = 'Tour'),
    0
)
WHERE ns.vai_tro = 'HDV';

-- Cập nhật so_tour_da_dan từ lich_khoi_hanh
UPDATE nhan_su ns
SET so_tour_da_dan = COALESCE(
    (SELECT COUNT(DISTINCT tour_id) 
     FROM lich_khoi_hanh 
     WHERE hdv_id = ns.nhan_su_id),
    0
)
WHERE ns.vai_tro = 'HDV';

-- Cập nhật trang_thai_lam_viec
UPDATE nhan_su ns
INNER JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
SET ns.trang_thai_lam_viec = CASE
    WHEN EXISTS (
        SELECT 1 FROM lich_khoi_hanh 
        WHERE hdv_id = ns.nhan_su_id 
        AND trang_thai = 'DangChay'
        AND CURDATE() BETWEEN ngay_khoi_hanh AND ngay_ket_thuc
    ) THEN 'DangBan'
    WHEN nd.trang_thai = 'BiKhoa' THEN 'TamNghi'
    ELSE 'SanSang'
END
WHERE ns.vai_tro = 'HDV';

-- ======================================
-- BƯỚC 2: TẠO TRIGGER TỰ ĐỘNG CẬP NHẬT
-- ======================================

-- Trigger: Tự động cập nhật loai_hdv và chuyen_tuyen khi INSERT/UPDATE nhan_su
DELIMITER $$
DROP TRIGGER IF EXISTS before_nhan_su_insert_update$$
CREATE TRIGGER before_nhan_su_insert_update
BEFORE INSERT ON nhan_su
FOR EACH ROW
BEGIN
    -- Tự động set loai_hdv nếu NULL
    IF NEW.vai_tro = 'HDV' AND NEW.loai_hdv IS NULL THEN
        SET NEW.loai_hdv = CASE 
            WHEN NEW.ngon_ngu LIKE '%Anh%' OR NEW.ngon_ngu LIKE '%Nhật%' OR NEW.ngon_ngu LIKE '%Hàn%' THEN 'QuocTe'
            WHEN NEW.kinh_nghiem LIKE '%chuyên%' OR NEW.kinh_nghiem LIKE '%tuyến%' THEN 'ChuyenTuyen'
            WHEN NEW.kinh_nghiem LIKE '%đoàn%' THEN 'ChuyenDoan'
            ELSE 'NoiDia'
        END;
    END IF;
    
    -- Tự động set chuyen_tuyen nếu NULL
    IF NEW.vai_tro = 'HDV' AND NEW.chuyen_tuyen IS NULL THEN
        SET NEW.chuyen_tuyen = CASE 
            WHEN NEW.kinh_nghiem LIKE '%Miền Bắc%' THEN 'Miền Bắc'
            WHEN NEW.kinh_nghiem LIKE '%Miền Trung%' THEN 'Miền Trung'
            WHEN NEW.kinh_nghiem LIKE '%Miền Nam%' THEN 'Miền Nam'
            WHEN NEW.kinh_nghiem LIKE '%Đông Nam Á%' THEN 'Đông Nam Á'
            ELSE NULL
        END;
    END IF;
    
    -- Default values
    IF NEW.danh_gia_tb IS NULL THEN SET NEW.danh_gia_tb = 0; END IF;
    IF NEW.so_tour_da_dan IS NULL THEN SET NEW.so_tour_da_dan = 0; END IF;
    IF NEW.trang_thai_lam_viec IS NULL THEN SET NEW.trang_thai_lam_viec = 'SanSang'; END IF;
END$$
DELIMITER ;

-- Trigger: Cập nhật so_tour_da_dan khi thêm/xóa lich_khoi_hanh
DELIMITER $$
DROP TRIGGER IF EXISTS after_lich_khoi_hanh_insert$$
CREATE TRIGGER after_lich_khoi_hanh_insert
AFTER INSERT ON lich_khoi_hanh
FOR EACH ROW
BEGIN
    UPDATE nhan_su 
    SET so_tour_da_dan = (
        SELECT COUNT(DISTINCT tour_id) 
        FROM lich_khoi_hanh 
        WHERE hdv_id = NEW.hdv_id
    )
    WHERE nhan_su_id = NEW.hdv_id;
    
    -- Cập nhật trạng thái làm việc
    UPDATE nhan_su 
    SET trang_thai_lam_viec = CASE
        WHEN EXISTS (
            SELECT 1 FROM lich_khoi_hanh 
            WHERE hdv_id = NEW.hdv_id 
            AND trang_thai = 'DangChay'
            AND CURDATE() BETWEEN ngay_khoi_hanh AND ngay_ket_thuc
        ) THEN 'DangBan'
        ELSE 'SanSang'
    END
    WHERE nhan_su_id = NEW.hdv_id;
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS after_lich_khoi_hanh_delete$$
CREATE TRIGGER after_lich_khoi_hanh_delete
AFTER DELETE ON lich_khoi_hanh
FOR EACH ROW
BEGIN
    UPDATE nhan_su 
    SET so_tour_da_dan = (
        SELECT COUNT(DISTINCT tour_id) 
        FROM lich_khoi_hanh 
        WHERE hdv_id = OLD.hdv_id
    )
    WHERE nhan_su_id = OLD.hdv_id;
    
    -- Cập nhật trạng thái làm việc
    UPDATE nhan_su 
    SET trang_thai_lam_viec = CASE
        WHEN EXISTS (
            SELECT 1 FROM lich_khoi_hanh 
            WHERE hdv_id = OLD.hdv_id 
            AND trang_thai = 'DangChay'
            AND CURDATE() BETWEEN ngay_khoi_hanh AND ngay_ket_thuc
        ) THEN 'DangBan'
        ELSE 'SanSang'
    END
    WHERE nhan_su_id = OLD.hdv_id;
END$$
DELIMITER ;

-- Trigger: Cập nhật danh_gia_tb khi có đánh giá mới/sửa/xóa
DELIMITER $$
DROP TRIGGER IF EXISTS after_phan_hoi_insert_update$$
CREATE TRIGGER after_phan_hoi_insert_update
AFTER INSERT ON phan_hoi_danh_gia
FOR EACH ROW
BEGIN
    UPDATE nhan_su ns
    SET danh_gia_tb = COALESCE(
        (SELECT AVG(phd.diem) 
         FROM phan_hoi_danh_gia phd
         INNER JOIN lich_khoi_hanh lkh ON phd.tour_id = lkh.tour_id
         WHERE lkh.hdv_id = ns.nhan_su_id AND phd.loai = 'Tour'),
        0
    )
    WHERE ns.nhan_su_id IN (
        SELECT hdv_id FROM lich_khoi_hanh WHERE tour_id = NEW.tour_id
    );
END$$
DELIMITER ;

DELIMITER $$
DROP TRIGGER IF EXISTS after_phan_hoi_delete$$
CREATE TRIGGER after_phan_hoi_delete
AFTER DELETE ON phan_hoi_danh_gia
FOR EACH ROW
BEGIN
    UPDATE nhan_su ns
    SET danh_gia_tb = COALESCE(
        (SELECT AVG(phd.diem) 
         FROM phan_hoi_danh_gia phd
         INNER JOIN lich_khoi_hanh lkh ON phd.tour_id = lkh.tour_id
         WHERE lkh.hdv_id = ns.nhan_su_id AND phd.loai = 'Tour'),
        0
    )
    WHERE ns.nhan_su_id IN (
        SELECT hdv_id FROM lich_khoi_hanh WHERE tour_id = OLD.tour_id
    );
END$$
DELIMITER ;

-- ======================================
-- HOÀN TẤT
-- ======================================
SELECT 'Migration hoàn tất - Database đã tương thích với code hiện tại!' as status;
