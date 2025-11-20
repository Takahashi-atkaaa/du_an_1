<?php
// File chạy migration cho check-in và phân phòng
require_once './commons/env.php';

try {
    $conn = getPDOConnection();
    
    echo "<h2>Chạy Migration - Check-in & Phân phòng</h2>";
    echo "<hr>";
    
    // Đọc file SQL
    $sqlFile = file_get_contents('./migration_checkin_room.sql');
    $statements = explode(';', $sqlFile);
    
    $success = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $conn->exec($statement);
            $success++;
        } catch (PDOException $e) {
            // Bỏ qua lỗi "table already exists"
            if (strpos($e->getMessage(), 'already exists') === false) {
                $errors[] = $e->getMessage();
            }
        }
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 10px 0; border-radius: 4px;'>";
    echo "<h3>✓ Migration hoàn tất!</h3>";
    echo "<p>Đã thực thi thành công: <strong>$success</strong> câu lệnh SQL</p>";
    echo "</div>";
    
    if (!empty($errors)) {
        echo "<div style='background: #fff3cd; color: #856404; padding: 15px; margin: 10px 0; border-radius: 4px;'>";
        echo "<h4>Một số cảnh báo:</h4>";
        foreach ($errors as $error) {
            echo "<p>• " . htmlspecialchars($error) . "</p>";
        }
        echo "</div>";
    }
    
    echo "<h3>Các bảng đã tạo:</h3>";
    echo "<ul>";
    echo "<li><strong>tour_checkin</strong> - Quản lý check-in khách hàng</li>";
    echo "<li><strong>hotel_room_assignment</strong> - Quản lý phân phòng khách sạn</li>";
    echo "</ul>";
    
    echo "<p style='margin-top: 20px;'><a href='index.php?act=admin/dashboard' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>← Quay lại Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 10px 0; border-radius: 4px;'>";
    echo "<h3>✗ Lỗi Migration!</h3>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}
?>
