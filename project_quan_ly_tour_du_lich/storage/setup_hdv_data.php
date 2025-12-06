<?php
/**
 * SCRIPT: Kiểm Tra & Nhập Dữ Liệu HDV
 * File: storage/setup_hdv_data.php
 * 
 * Hướng dẫn:
 * 1. Mở: http://localhost/du_an_1/project_quan_ly_tour_du_lich/storage/setup_hdv_data.php
 * 2. Hoặc: php storage/setup_hdv_data.php
 */

require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

echo "=== KIỂM TRA & NHẬP DỮ LIỆU HDV ===\n\n";

try {
    $db = connectDB();
    
    // 1. Kiểm tra HDV hiện tại
    echo "1️⃣  DANH SÁCH HDV HIỆN CÓ:\n";
    echo "-" . str_repeat("-", 60) . "\n";
    
    $sql = "SELECT ns.nhan_su_id, nd.ho_ten, nd.so_dien_thoai, nd.email, ns.commission_percentage 
            FROM nhan_su ns
            JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
            WHERE ns.vai_tro = 'HDV'
            ORDER BY ns.nhan_su_id";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $hdvList = $stmt->fetchAll();
    
    if (empty($hdvList)) {
        echo "⚠️  Không tìm thấy HDV nào!\n";
    } else {
        foreach ($hdvList as $hdv) {
            echo "   ID: {$hdv['nhan_su_id']} | Tên: {$hdv['ho_ten']} | Email: {$hdv['email']}\n";
            echo "                | Điện thoại: {$hdv['so_dien_thoai']} | Hoa hồng: {$hdv['commission_percentage']}%\n\n";
        }
    }
    
    // 2. Kiểm tra Tour
    echo "2️⃣  DANH SÁCH TOUR:\n";
    echo "-" . str_repeat("-", 60) . "\n";
    
    $sql = "SELECT tour_id, ten_tour, gia_co_ban FROM tour LIMIT 5";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $tours = $stmt->fetchAll();
    
    if (empty($tours)) {
        echo "⚠️  Không tìm thấy tour nào!\n";
    } else {
        foreach ($tours as $tour) {
            echo "   Tour ID: {$tour['tour_id']} | {$tour['ten_tour']}\n";
            echo "                | Giá: " . number_format($tour['gia_co_ban'], 0) . " ₫\n\n";
        }
    }
    
    // 3. Kiểm tra Lịch khởi hành
    echo "3️⃣  DANH SÁCH LỊCH KHỞI HÀNH:\n";
    echo "-" . str_repeat("-", 60) . "\n";
    
    $sql = "SELECT lkh.id, t.ten_tour, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc 
            FROM lich_khoi_hanh lkh
            JOIN tour t ON lkh.tour_id = t.tour_id
            LIMIT 5";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $schedules = $stmt->fetchAll();
    
    if (empty($schedules)) {
        echo "⚠️  Không tìm thấy lịch khởi hành nào!\n";
    } else {
        foreach ($schedules as $schedule) {
            echo "   Lịch ID: {$schedule['id']} | {$schedule['ten_tour']}\n";
            echo "              | Từ: {$schedule['ngay_khoi_hanh']} đến {$schedule['ngay_ket_thuc']}\n\n";
        }
    }
    
    // 4. Kiểm tra Booking
    echo "4️⃣  DANH SÁCH BOOKING (Doanh Thu):\n";
    echo "-" . str_repeat("-", 60) . "\n";
    
    $sql = "SELECT b.booking_id, b.tour_id, t.ten_tour, b.so_nguoi, b.tong_tien, b.trang_thai
            FROM booking b
            JOIN tour t ON b.tour_id = t.tour_id
            WHERE b.trang_thai IN ('HoanTat', 'DaCoc')
            LIMIT 5";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $bookings = $stmt->fetchAll();
    
    if (empty($bookings)) {
        echo "⚠️  Không tìm thấy booking nào!\n";
    } else {
        foreach ($bookings as $booking) {
            echo "   Booking ID: {$booking['booking_id']} | {$booking['ten_tour']}\n";
            echo "                    | Số người: {$booking['so_nguoi']} | Tổng tiền: " . number_format($booking['tong_tien'], 0) . " ₫\n\n";
        }
    }
    
    // 5. Gợi ý nhập dữ liệu
    echo "5️⃣  GỢI Ý NHẬP DỮ LIỆU:\n";
    echo "-" . str_repeat("-", 60) . "\n";
    
    if (!empty($hdvList)) {
        $hdv = $hdvList[0];
        $hdvId = $hdv['nhan_su_id'];
        
        if (!empty($tours)) {
            $tour = $tours[0];
            $tourId = $tour['tour_id'];
        } else {
            $tourId = 6;
        }
        
        if (!empty($schedules)) {
            $schedule = $schedules[0];
            $scheduleId = $schedule['id'];
        } else {
            $scheduleId = 10;
        }
        
        if (!empty($bookings)) {
            $booking = $bookings[0];
            $tourRevenue = $booking['tong_tien'];
        } else {
            $tourRevenue = 100000000;
        }
        
        echo "\n📋 SQL để nhập lương mẫu:\n\n";
        echo "INSERT INTO hdv_salary (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status, notes)\n";
        echo "VALUES \n";
        echo "($hdvId, $tourId, $scheduleId, 5000000.00, 5.00, $tourRevenue, " . ($tourRevenue * 5 / 100) . ", 0, " . (5000000 + ($tourRevenue * 5 / 100)) . ", 'Pending', 'Lương tour mẫu');\n";
        echo "\n";
        
        // Thực thi INSERT
        echo "⚙️  THỰC HIỆN INSERT...\n\n";
        
        try {
            $insertSql = "INSERT INTO hdv_salary (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status, notes)
                         VALUES ($hdvId, $tourId, $scheduleId, 5000000.00, 5.00, $tourRevenue, " . ($tourRevenue * 5 / 100) . ", 0, " . (5000000 + ($tourRevenue * 5 / 100)) . ", 'Pending', 'Lương tour mẫu')";
            
            $insertStmt = $db->prepare($insertSql);
            if ($insertStmt->execute()) {
                echo "✅ Thêm lương thành công!\n";
                
                // Thêm thưởng mẫu
                $bonusInsert = "INSERT INTO hdv_bonus (nhan_su_id, bonus_type, amount, reason, award_date, approval_status)
                               VALUES ($hdvId, 'KhenThuong', 1000000.00, 'Dẫn tour xuất sắc', CURDATE(), 'DuyetPhep')";
                
                $bonusStmt = $db->prepare($bonusInsert);
                if ($bonusStmt->execute()) {
                    echo "✅ Thêm thưởng thành công!\n";
                } else {
                    echo "❌ Lỗi thêm thưởng\n";
                }
            } else {
                echo "❌ Lỗi thêm lương\n";
            }
        } catch (PDOException $e) {
            echo "❌ Lỗi: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "✅ HOÀN THÀNH\n";
    
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n";
}

echo "\n📌 HƯỚNG DẪN TIẾP THEO:\n";
echo "   1. Kiểm tra dữ liệu ở trên\n";
echo "   2. Truy cập: http://localhost/du_an_1/project_quan_ly_tour_du_lich/index.php?act=hdv/luongThuong\n";
echo "   3. Hoặc chạy: php storage/test_salary_calculation.php\n";
echo "\n";
?>
