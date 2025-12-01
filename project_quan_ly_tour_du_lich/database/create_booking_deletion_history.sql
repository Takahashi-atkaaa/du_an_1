-- Tạo bảng lưu lịch sử xóa booking
CREATE TABLE IF NOT EXISTS `booking_deletion_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) DEFAULT NULL COMMENT 'ID booking đã bị xóa (có thể NULL nếu booking đã bị xóa hoàn toàn)',
  `tour_id` int(11) DEFAULT NULL COMMENT 'ID tour (lưu lại để tham chiếu)',
  `khach_hang_id` int(11) DEFAULT NULL COMMENT 'ID khách hàng (lưu lại để tham chiếu)',
  `nguoi_xoa_id` int(11) DEFAULT NULL COMMENT 'ID người dùng đã xóa (Admin)',
  `ly_do_xoa` text DEFAULT NULL COMMENT 'Lý do xóa',
  `thong_tin_booking` text DEFAULT NULL COMMENT 'Thông tin booking dạng JSON trước khi xóa',
  `thoi_gian_xoa` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian xóa',
  PRIMARY KEY (`id`),
  KEY `idx_booking_id` (`booking_id`),
  KEY `idx_tour_id` (`tour_id`),
  KEY `idx_khach_hang_id` (`khach_hang_id`),
  KEY `idx_nguoi_xoa_id` (`nguoi_xoa_id`),
  KEY `idx_thoi_gian_xoa` (`thoi_gian_xoa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử xóa booking';

