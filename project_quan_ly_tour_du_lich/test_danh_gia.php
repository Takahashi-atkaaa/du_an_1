<?php
require_once 'commons/env.php';
require_once 'commons/function.php';

try {
    $conn = connectDB();
    
    // Kiểm tra bảng tồn tại
    $stmt = $conn->query("SHOW TABLES LIKE 'danh_gia'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Bảng danh_gia CHƯA được tạo!\n";
        echo "Hãy chạy file SQL: database/migrations/create_danh_gia_table.sql\n";
        exit;
    }
    
    echo "✓ Bảng danh_gia đã tồn tại\n\n";
    
    // Đếm số bản ghi
    $stmt = $conn->query('SELECT COUNT(*) as total FROM danh_gia');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Số bản ghi: " . $result['total'] . "\n\n";
    
    // Lấy 5 bản ghi đầu
    if ($result['total'] > 0) {
        echo "5 bản ghi đầu tiên:\n";
        $stmt = $conn->query('SELECT * FROM danh_gia LIMIT 5');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- ID: {$row['danh_gia_id']}, Loại: {$row['loai_danh_gia']}, Điểm: {$row['diem']}\n";
        }
    } else {
        echo "⚠️  Bảng rỗng - chưa có dữ liệu!\n";
        echo "Hãy insert dữ liệu mẫu từ file SQL\n";
    }
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
}
