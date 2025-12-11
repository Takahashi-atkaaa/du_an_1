<?php
/**
 * TEST SCRIPT: Tính Lương & Thưởng cho HDV
 * File: storage/test_salary_calculation.php
 * 
 * Hướng dẫn:
 * 1. Mở file này trong browser: http://localhost/du_an_1/project_quan_ly_tour_du_lich/storage/test_salary_calculation.php
 * 2. Hoặc chạy từ command line: php storage/test_salary_calculation.php
 */

// Kết nối database
require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../modules/luong_thuong_hoa_hong/models/SalaryBonus.php';

echo "=== TEST TÍNH LƯƠNG & THƯỞNG CHO HDV ===\n\n";

try {
    $salaryBonus = new SalaryBonus();
    
    // Test với HDV ID = 100 (từ dữ liệu mẫu)
    $hdvId = 100;
    
    echo "📊 HDV ID: $hdvId\n";
    echo str_repeat("=", 50) . "\n\n";
    
    // 1. Lấy thông tin thống kê
    echo "1️⃣  THỐNG KÊ TỔNG HỢP:\n";
    echo "-" . str_repeat("-", 48) . "\n";
    $summary = $salaryBonus->getSalarySummary($hdvId);
    
    echo "   Lương cơ bản:        " . number_format($summary['base_salary'], 0) . " ₫\n";
    echo "   Hoa hồng:            " . number_format($summary['commission'], 0) . " ₫\n";
    echo "   Tỉ lệ hoa hồng:      " . number_format($summary['commission_percentage'], 2) . " %\n";
    echo "   Thưởng:              " . number_format($summary['total_bonus'], 0) . " ₫\n";
    echo "   ---\n";
    echo "   TỔNG CỘNG:           " . number_format($summary['grand_total'], 0) . " ₫\n";
    echo "\n";
    
    // 2. Lấy danh sách lương theo tour
    echo "2️⃣  LƯƠNG THEO TOUR:\n";
    echo "-" . str_repeat("-", 48) . "\n";
    $salaryByTour = $salaryBonus->getSalaryByTour($hdvId);
    
    if (empty($salaryByTour)) {
        echo "   ⚠️  Chưa có dữ liệu lương theo tour\n";
        echo "   Cần chạy sample_data_hdv_salary.sql để có dữ liệu mẫu\n";
    } else {
        foreach ($salaryByTour as $item) {
            echo "\n   Tour: " . $item['ten_tour'] . "\n";
            echo "   Ngày: " . date('d/m/Y', strtotime($item['ngay_khoi_hanh'])) . "\n";
            echo "   Doanh thu: " . number_format($item['tour_revenue'], 0) . " ₫\n";
            echo "   Hoa hồng (%): " . number_format($item['commission_percentage'], 2) . "%\n";
            echo "   Tiền hoa hồng: " . number_format($item['commission_amount'], 0) . " ₫\n";
            echo "   Lương cơ bản: " . number_format($item['base_salary'], 0) . " ₫\n";
            echo "   Thưởng: " . number_format($item['bonus_amount'], 0) . " ₫\n";
            echo "   ---\n";
            echo "   Tổng: " . number_format($item['total_amount'], 0) . " ₫\n";
            echo "   Trạng thái: " . ($item['payment_status'] ?? 'Pending') . "\n";
        }
    }
    echo "\n";
    
    // 3. Lấy danh sách thưởng
    echo "3️⃣  DANH SÁCH THƯỞNG:\n";
    echo "-" . str_repeat("-", 48) . "\n";
    $bonuses = $salaryBonus->getBonuses($hdvId);
    
    if (empty($bonuses)) {
        echo "   ⚠️  Chưa có dữ liệu thưởng\n";
        echo "   Cần chạy sample_data_hdv_salary.sql để có dữ liệu mẫu\n";
    } else {
        foreach ($bonuses as $bonus) {
            echo "\n   Loại: " . $bonus['bonus_type'] . "\n";
            echo "   Lý do: " . $bonus['reason'] . "\n";
            echo "   Số tiền: " . number_format($bonus['amount'], 0) . " ₫\n";
            echo "   Ngày thưởng: " . date('d/m/Y', strtotime($bonus['award_date'])) . "\n";
            echo "   Trạng thái: " . $bonus['approval_status'] . "\n";
        }
    }
    echo "\n";
    
    // 4. Test tính toán hoa hồng
    echo "4️⃣  CÔNG THỨC TÍNH TOÁN:\n";
    echo "-" . str_repeat("-", 48) . "\n";
    echo "   Công thức hoa hồng:\n";
    echo "   Tiền hoa hồng = (Doanh thu × Tỉ lệ %) / 100\n";
    echo "\n";
    
    $testRevenue = 100000000;
    $testCommissionRate = 5;
    $commission = $salaryBonus->calculateCommission($testRevenue, $testCommissionRate);
    
    echo "   Ví dụ:\n";
    echo "   Doanh thu: " . number_format($testRevenue, 0) . " ₫\n";
    echo "   Tỉ lệ: " . $testCommissionRate . "%\n";
    echo "   Kết quả: (" . number_format($testRevenue, 0) . " × " . $testCommissionRate . ") / 100\n";
    echo "   = " . number_format($commission, 0) . " ₫\n";
    echo "\n";
    
    // 5. Kiểm tra bảng cơ sở dữ liệu
    echo "5️⃣  TRẠNG THÁI CƠ SỞ DỮ LIỆU:\n";
    echo "-" . str_repeat("-", 48) . "\n";
    
    try {
        $db = connectDB();
        
        // Kiểm tra bảng hdv_salary
        $checkSalary = $db->query("SELECT COUNT(*) as count FROM hdv_salary")->fetch();
        echo "   ✅ Bảng hdv_salary: " . $checkSalary['count'] . " bản ghi\n";
        
        // Kiểm tra bảng hdv_bonus
        $checkBonus = $db->query("SELECT COUNT(*) as count FROM hdv_bonus")->fetch();
        echo "   ✅ Bảng hdv_bonus: " . $checkBonus['count'] . " bản ghi\n";
        
        // Kiểm tra cột commission_percentage trong nhan_su
        $checkColumn = $db->query("SHOW COLUMNS FROM nhan_su LIKE 'commission_percentage'")->fetch();
        if ($checkColumn) {
            echo "   ✅ Cột commission_percentage trong nhan_su: CÓ\n";
        } else {
            echo "   ❌ Cột commission_percentage trong nhan_su: KHÔNG\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi kiểm tra database: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "✅ TEST HOÀN THÀNH\n";
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n";
echo "📌 HƯỚNG DẪN TIẾP THEO:\n";
echo "   1. Nếu chưa có dữ liệu: Chạy sample_data_hdv_salary.sql\n";
echo "   2. Để test giao diện: Truy cập http://localhost/du_an_1/...\n";
echo "   3. Và click 'Lương & Thưởng' ở menu HDV dashboard\n";
echo "\n";
?>
