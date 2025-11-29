<?php
/**
 * Script để tạo các bảng du_toan_tour và chi_phi_thuc_te
 * Chạy file này qua trình duyệt: http://localhost/tunganh/du_an_1/project_quan_ly_tour_du_lich/database/install_du_toan_chi_phi_tables.php
 */

require_once __DIR__ . '/../commons/env.php';

try {
    // Sử dụng socket khi chạy qua CLI
    if (php_sapi_name() === 'cli') {
        $socketPath = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
        $conn = new PDO(
            "mysql:unix_socket=$socketPath;dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USERNAME,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    } else {
        $conn = getPDOConnection();
    }
    
    echo "<h2>Đang tạo các bảng cho báo cáo tài chính...</h2>";
    echo "<pre>";
    
    $successCount = 0;
    $errorCount = 0;
    
    // Tạo bảng du_toan_tour
    $sql1 = "CREATE TABLE IF NOT EXISTS `du_toan_tour` (
      `du_toan_id` int(11) NOT NULL AUTO_INCREMENT,
      `tour_id` int(11) NOT NULL,
      `lich_khoi_hanh_id` int(11) DEFAULT NULL,
      `cp_phuong_tien` decimal(15,2) DEFAULT 0.00,
      `mo_ta_phuong_tien` text DEFAULT NULL,
      `cp_luu_tru` decimal(15,2) DEFAULT 0.00,
      `mo_ta_luu_tru` text DEFAULT NULL,
      `cp_ve_tham_quan` decimal(15,2) DEFAULT 0.00,
      `mo_ta_ve_tham_quan` text DEFAULT NULL,
      `cp_an_uong` decimal(15,2) DEFAULT 0.00,
      `mo_ta_an_uong` text DEFAULT NULL,
      `cp_huong_dan_vien` decimal(15,2) DEFAULT 0.00,
      `cp_dich_vu_bo_sung` decimal(15,2) DEFAULT 0.00,
      `mo_ta_dich_vu` text DEFAULT NULL,
      `cp_phat_sinh_du_kien` decimal(15,2) DEFAULT 0.00,
      `mo_ta_phat_sinh` text DEFAULT NULL,
      `tong_du_toan` decimal(15,2) DEFAULT 0.00,
      `nguoi_tao_id` int(11) DEFAULT NULL,
      `ngay_tao` datetime DEFAULT current_timestamp(),
      `ghi_chu` text DEFAULT NULL,
      PRIMARY KEY (`du_toan_id`),
      KEY `idx_tour_id` (`tour_id`),
      KEY `idx_lich_khoi_hanh_id` (`lich_khoi_hanh_id`),
      KEY `idx_nguoi_tao_id` (`nguoi_tao_id`),
      CONSTRAINT `du_toan_tour_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
      CONSTRAINT `du_toan_tour_ibfk_2` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE SET NULL,
      CONSTRAINT `du_toan_tour_ibfk_3` FOREIGN KEY (`nguoi_tao_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $conn->exec($sql1);
        echo "✓ Bảng 'du_toan_tour' đã được tạo\n";
        $successCount++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '1050') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "⚠ Bảng 'du_toan_tour' đã tồn tại (bỏ qua)\n";
        } else {
            echo "✗ Lỗi tạo bảng 'du_toan_tour': " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    
    // Tạo bảng chi_phi_thuc_te
    $sql2 = "CREATE TABLE IF NOT EXISTS `chi_phi_thuc_te` (
      `chi_phi_id` int(11) NOT NULL AUTO_INCREMENT,
      `du_toan_id` int(11) DEFAULT NULL,
      `tour_id` int(11) NOT NULL,
      `lich_khoi_hanh_id` int(11) DEFAULT NULL,
      `loai_chi_phi` varchar(100) NOT NULL,
      `ten_khoan_chi` varchar(255) NOT NULL,
      `so_tien` decimal(15,2) NOT NULL,
      `ngay_phat_sinh` date NOT NULL,
      `mo_ta` text DEFAULT NULL,
      `chung_tu` varchar(255) DEFAULT NULL,
      `trang_thai` enum('ChoXacNhan','DaDuyet','TuChoi') DEFAULT 'ChoXacNhan',
      `nguoi_ghi_nhan_id` int(11) DEFAULT NULL,
      `nguoi_duyet_id` int(11) DEFAULT NULL,
      `ngay_duyet` datetime DEFAULT NULL,
      `ly_do_tu_choi` text DEFAULT NULL,
      `created_at` datetime DEFAULT current_timestamp(),
      PRIMARY KEY (`chi_phi_id`),
      KEY `idx_du_toan_id` (`du_toan_id`),
      KEY `idx_tour_id` (`tour_id`),
      KEY `idx_lich_khoi_hanh_id` (`lich_khoi_hanh_id`),
      KEY `idx_trang_thai` (`trang_thai`),
      KEY `idx_nguoi_ghi_nhan_id` (`nguoi_ghi_nhan_id`),
      KEY `idx_nguoi_duyet_id` (`nguoi_duyet_id`),
      CONSTRAINT `chi_phi_thuc_te_ibfk_1` FOREIGN KEY (`du_toan_id`) REFERENCES `du_toan_tour` (`du_toan_id`) ON DELETE SET NULL,
      CONSTRAINT `chi_phi_thuc_te_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE,
      CONSTRAINT `chi_phi_thuc_te_ibfk_3` FOREIGN KEY (`lich_khoi_hanh_id`) REFERENCES `lich_khoi_hanh` (`id`) ON DELETE SET NULL,
      CONSTRAINT `chi_phi_thuc_te_ibfk_4` FOREIGN KEY (`nguoi_ghi_nhan_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL,
      CONSTRAINT `chi_phi_thuc_te_ibfk_5` FOREIGN KEY (`nguoi_duyet_id`) REFERENCES `nguoi_dung` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $conn->exec($sql2);
        echo "✓ Bảng 'chi_phi_thuc_te' đã được tạo\n";
        $successCount++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '1050') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "⚠ Bảng 'chi_phi_thuc_te' đã tồn tại (bỏ qua)\n";
        } else {
            echo "✗ Lỗi tạo bảng 'chi_phi_thuc_te': " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    
    // Tạo bảng du_toan_chi_tiet
    $sql3 = "CREATE TABLE IF NOT EXISTS `du_toan_chi_tiet` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `du_toan_id` int(11) NOT NULL,
      `tour_id` int(11) NOT NULL,
      `loai_chi_phi` varchar(50) NOT NULL,
      `ten_khoan_chi` varchar(255) NOT NULL,
      `so_tien` decimal(15,2) NOT NULL,
      `ghi_chu` text DEFAULT NULL,
      `ngay_tao` datetime DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `idx_du_toan_id` (`du_toan_id`),
      KEY `idx_tour_id` (`tour_id`),
      CONSTRAINT `du_toan_chi_tiet_ibfk_1` FOREIGN KEY (`du_toan_id`) REFERENCES `du_toan_tour` (`du_toan_id`) ON DELETE CASCADE,
      CONSTRAINT `du_toan_chi_tiet_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tour` (`tour_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    try {
        $conn->exec($sql3);
        echo "✓ Bảng 'du_toan_chi_tiet' đã được tạo\n";
        $successCount++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), '1050') !== false || strpos($e->getMessage(), 'already exists') !== false) {
            echo "⚠ Bảng 'du_toan_chi_tiet' đã tồn tại (bỏ qua)\n";
        } else {
            echo "✗ Lỗi tạo bảng 'du_toan_chi_tiet': " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }
    
    // Tạo trigger INSERT
    try {
        $conn->exec("DROP TRIGGER IF EXISTS `trg_du_toan_tour_before_insert`");
    } catch (PDOException $e) {
        // Bỏ qua
    }
    
    try {
        $trigger1 = "CREATE TRIGGER `trg_du_toan_tour_before_insert` 
BEFORE INSERT ON `du_toan_tour`
FOR EACH ROW
BEGIN
    SET NEW.tong_du_toan = COALESCE(NEW.cp_phuong_tien, 0) + 
                           COALESCE(NEW.cp_luu_tru, 0) + 
                           COALESCE(NEW.cp_ve_tham_quan, 0) + 
                           COALESCE(NEW.cp_an_uong, 0) + 
                           COALESCE(NEW.cp_huong_dan_vien, 0) + 
                           COALESCE(NEW.cp_dich_vu_bo_sung, 0) + 
                           COALESCE(NEW.cp_phat_sinh_du_kien, 0);
END";
        $conn->exec($trigger1);
        echo "✓ Trigger INSERT đã được tạo\n";
        $successCount++;
    } catch (PDOException $e) {
        echo "⚠ Trigger INSERT: " . $e->getMessage() . "\n";
        $errorCount++;
    }
    
    // Tạo trigger UPDATE
    try {
        $conn->exec("DROP TRIGGER IF EXISTS `trg_du_toan_tour_before_update`");
    } catch (PDOException $e) {
        // Bỏ qua
    }
    
    try {
        $trigger2 = "CREATE TRIGGER `trg_du_toan_tour_before_update` 
BEFORE UPDATE ON `du_toan_tour`
FOR EACH ROW
BEGIN
    SET NEW.tong_du_toan = COALESCE(NEW.cp_phuong_tien, 0) + 
                           COALESCE(NEW.cp_luu_tru, 0) + 
                           COALESCE(NEW.cp_ve_tham_quan, 0) + 
                           COALESCE(NEW.cp_an_uong, 0) + 
                           COALESCE(NEW.cp_huong_dan_vien, 0) + 
                           COALESCE(NEW.cp_dich_vu_bo_sung, 0) + 
                           COALESCE(NEW.cp_phat_sinh_du_kien, 0);
END";
        $conn->exec($trigger2);
        echo "✓ Trigger UPDATE đã được tạo\n";
        $successCount++;
    } catch (PDOException $e) {
        echo "⚠ Trigger UPDATE: " . $e->getMessage() . "\n";
        $errorCount++;
    }
    
    echo "\n";
    echo "========================================\n";
    echo "Kết quả:\n";
    echo "- Thành công: $successCount\n";
    echo "- Lỗi: $errorCount\n";
    echo "========================================\n";
    
    // Kiểm tra xem các bảng đã được tạo chưa
    echo "\nKiểm tra các bảng:\n";
    $tables = ['du_toan_tour', 'chi_phi_thuc_te', 'du_toan_chi_tiet'];
    foreach ($tables as $table) {
        try {
            $stmt = $conn->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                echo "✓ Bảng '$table' đã tồn tại\n";
            } else {
                echo "✗ Bảng '$table' chưa tồn tại\n";
            }
        } catch (PDOException $e) {
            echo "✗ Lỗi kiểm tra bảng '$table': " . $e->getMessage() . "\n";
        }
    }
    
    echo "</pre>";
    echo "<p><a href='../index.php'>Quay lại trang chủ</a></p>";
    
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
