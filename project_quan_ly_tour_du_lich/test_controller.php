<?php
session_start();

// Test đăng nhập admin
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'Admin';

require_once 'commons/env.php';
require_once 'commons/function.php';
require_once 'models/DanhGia.php';

echo "Testing DanhGiaController...\n\n";

try {
    $model = new DanhGia();
    
    // Test getStatistics
    echo "1. Testing getStatistics():\n";
    $stats = $model->getStatistics();
    print_r($stats);
    echo "\n";
    
    // Test filter
    echo "2. Testing filter():\n";
    $danhGiaList = $model->filter([]);
    echo "Số đánh giá: " . count($danhGiaList) . "\n";
    if (!empty($danhGiaList)) {
        echo "Đánh giá đầu tiên:\n";
        print_r($danhGiaList[0]);
    }
    
    echo "\n✓ Model hoạt động OK!\n";
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
