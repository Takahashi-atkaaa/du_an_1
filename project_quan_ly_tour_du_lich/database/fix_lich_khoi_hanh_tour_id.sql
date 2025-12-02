-- Script sửa lỗi: Cập nhật tour_id cho các lich_khoi_hanh bị NULL
-- Lấy tour_id từ booking dựa trên ghi chú (booking #X)

UPDATE lich_khoi_hanh lkh
INNER JOIN booking b ON (
    lkh.ghi_chu LIKE CONCAT('%booking #', b.booking_id, '%')
)
SET lkh.tour_id = b.tour_id
WHERE lkh.tour_id IS NULL 
AND b.tour_id IS NOT NULL;

-- Kiểm tra kết quả
SELECT 
    lkh.id,
    lkh.tour_id,
    lkh.ngay_khoi_hanh,
    lkh.ghi_chu,
    b.booking_id,
    b.tour_id AS booking_tour_id
FROM lich_khoi_hanh lkh
LEFT JOIN booking b ON (
    DATE(lkh.ngay_khoi_hanh) = DATE(b.ngay_khoi_hanh) 
    AND lkh.ghi_chu LIKE CONCAT('%booking #', b.booking_id, '%')
)
WHERE lkh.tour_id IS NULL;

