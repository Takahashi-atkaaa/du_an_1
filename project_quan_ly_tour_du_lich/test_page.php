<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'Admin';

require_once 'commons/env.php';
require_once 'commons/function.php';
require_once 'models/DanhGia.php';

echo "Testing page load...\n\n";

try {
    $model = new DanhGia();
    
    $filters = [];
    $danhGiaList = $model->filter($filters);
    $stats = $model->getStatistics();
    
    echo "Stats:\n";
    print_r($stats);
    
    echo "\nDanh gia list count: " . count($danhGiaList) . "\n";
    
    if (count($danhGiaList) > 0) {
        echo "\nFirst item:\n";
        print_r($danhGiaList[0]);
    }
    
    echo "\n✓ All data loaded successfully!\n";
    echo "\nNow loading the view...\n";
    
    // Load view
    ob_start();
    require 'views/admin/quan_ly_danh_gia.php';
    $output = ob_get_clean();
    
    echo "View output length: " . strlen($output) . " bytes\n";
    
    if (strlen($output) < 500) {
        echo "View output too short! Content:\n";
        echo $output;
    } else {
        echo "✓ View generated successfully!\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
