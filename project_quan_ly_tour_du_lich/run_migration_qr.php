<?php
// File chạy migration thêm cột qr_code_path
require_once './commons/env.php';

try {
    $conn = getPDOConnection();
    
    // Kiểm tra xem cột đã tồn tại chưa
    $checkSql = "SHOW COLUMNS FROM tour LIKE 'qr_code_path'";
    $stmt = $conn->prepare($checkSql);
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if ($exists) {
        echo "<h2 style='color: orange;'>Cột qr_code_path đã tồn tại trong bảng tour!</h2>";
        echo "<p>Không cần chạy migration nữa.</p>";
    } else {
        // Thêm cột mới
        $sql = "ALTER TABLE tour 
                ADD COLUMN qr_code_path VARCHAR(255) NULL COMMENT 'Đường dẫn file QR code' AFTER trang_thai";
        
        $conn->exec($sql);
        
        echo "<h2 style='color: green;'>✓ Migration thành công!</h2>";
        echo "<p>Đã thêm cột <strong>qr_code_path</strong> vào bảng <strong>tour</strong>.</p>";
        echo "<p><a href='index.php?act=admin/quanLyTour'>← Quay lại quản lý tour</a></p>";
    }
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Lỗi migration!</h2>";
    echo "<p>Chi tiết: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vui lòng kiểm tra kết nối database hoặc chạy SQL thủ công qua phpMyAdmin.</p>";
}
?>
