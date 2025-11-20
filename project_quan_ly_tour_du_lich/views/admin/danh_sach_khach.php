<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Khách Theo Tour - Quản Lý Tour Du Lịch</title>
    <link rel="stylesheet" href="public/assets/css/admin.css">
    <style>
        .customer-list-container {
            padding: 20px;
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .tour-info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .customer-table {
            width: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .customer-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .customer-table th {
            background: #343a40;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        .customer-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .customer-table tr:hover {
            background: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-checked-in {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-print {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .signature-section {
            margin-top: 30px;
            display: none;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12pt;
            }
            h1 {
                text-align: center;
                margin-bottom: 10px;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            .signature-section {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 50px;
                margin-top: 40px;
                page-break-inside: avoid;
            }
            .signature-box {
                text-align: center;
            }
            .signature-line {
                margin-top: 60px;
                font-style: italic;
            }
        }
    </style>
</head>
<body>
    <div class="customer-list-container">
        <div class="no-print" style="margin-bottom: 20px;">
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/dashboard" style="color: #007bff; text-decoration: none;">← Quay lại Dashboard</a> | 
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" style="color: #007bff; text-decoration: none;">Quản lý Tour</a>
        </div>
        <h1>Danh Sách Khách Theo Tour</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($lichKhoiHanh && $tour): ?>
            <!-- Thông tin tour -->
            <div class="tour-info-box">
                <h2><?php echo htmlspecialchars($tour['ten_tour'] ?? 'N/A'); ?></h2>
                <p><strong>Mã tour:</strong> <?php echo htmlspecialchars($tour['tour_id'] ?? 'N/A'); ?></p>
                <p><strong>Ngày khởi hành:</strong> <?php echo date('d/m/Y', strtotime($lichKhoiHanh['ngay_khoi_hanh'])); ?></p>
                <p><strong>Ngày kết thúc:</strong> <?php echo date('d/m/Y', strtotime($lichKhoiHanh['ngay_ket_thuc'])); ?></p>
                <?php if (isset($tour['gia_co_ban']) && $tour['gia_co_ban']): ?>
                    <p><strong>Giá:</strong> <?php echo number_format($tour['gia_co_ban'], 0, ',', '.'); ?> VNĐ</p>
                <?php endif; ?>
            </div>
            
            <!-- Thống kê -->
            <div class="stats-cards">
                <div class="stat-card">
                    <h3>Tổng booking</h3>
                    <div class="value"><?php echo count($bookingList); ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #28a745;">
                    <h3>Đã check-in</h3>
                    <div class="value"><?php echo $checkinStats['total_checkin'] ?? 0; ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #ffc107;">
                    <h3>Chưa check-in</h3>
                    <div class="value"><?php echo count($bookingList) - ($checkinStats['total_checkin'] ?? 0); ?></div>
                </div>
                <div class="stat-card" style="border-left-color: #17a2b8;">
                    <h3>Đã phân phòng</h3>
                    <div class="value"><?php echo $roomStats['total_rooms'] ?? 0; ?></div>
                </div>
            </div>
            
            <!-- Nút in danh sách -->
            <button class="btn-print no-print" onclick="printCustomerList()">
                📄 In Danh Sách Đoàn
            </button>
            
            <!-- Bảng danh sách khách -->
            <div class="customer-table">
                <table>
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã Booking</th>
                            <th>Tên Khách</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Số người</th>
                            <th>Trạng thái</th>
                            <th class="no-print">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookingList)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px;">
                                    Chưa có booking nào cho lịch khởi hành này
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookingList as $index => $booking): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($booking['khach_ho_ten'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($booking['so_dien_thoai'] ?? 'N/A'); ?></td>
                                    <td><?php echo ($booking['so_nguoi_lon'] ?? 0) + ($booking['so_tre_em'] ?? 0); ?> người</td>
                                    <td>
                                        <?php if ($booking['checkin_id']): ?>
                                            <span class="status-badge status-checked-in">
                                                <?php 
                                                    $status = $booking['checkin_status'];
                                                    echo $status === 'DaCheckIn' ? 'Đã check-in' : 
                                                         ($status === 'DaCheckOut' ? 'Đã check-out' : 'Chưa check-in');
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-pending">Chưa check-in</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="no-print">
                                        <div class="action-buttons">
                                            <?php if (!$booking['checkin_id']): ?>
                                                <a href="index.php?act=admin/checkInKhach&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                   class="btn-sm btn-success">
                                                    Check-in
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?act=admin/checkInKhach&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                   class="btn-sm btn-primary">
                                                    Xem chi tiết
                                                </a>
                                            <?php endif; ?>
                                            <a href="index.php?act=admin/phanPhongKhachSan&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                               class="btn-sm btn-warning">
                                                Phân phòng
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Signature section for print -->
            <div class="signature-section">
                <div class="signature-box">
                    <p><strong>Người lập danh sách</strong></p>
                    <p class="signature-line">(Ký và ghi rõ họ tên)</p>
                </div>
                <div class="signature-box">
                    <p><strong>Trưởng đoàn</strong></p>
                    <p class="signature-line">(Ký và ghi rõ họ tên)</p>
                </div>
            </div>
            
        <?php elseif ($tour): ?>
            <!-- Chọn lịch khởi hành -->
            <div class="tour-info-box">
                <h2><?php echo htmlspecialchars($tour['ten_tour']); ?></h2>
                <p>Vui lòng chọn lịch khởi hành để xem danh sách khách:</p>
                
                <?php if (!empty($lichKhoiHanhList)): ?>
                    <div style="margin-top: 15px;">
                        <?php foreach ($lichKhoiHanhList as $lkh): ?>
                            <a href="index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=<?php echo $lkh['id']; ?>" 
                               style="display: block; padding: 10px; margin: 5px 0; background: white; border-radius: 4px; text-decoration: none; color: #333;">
                                📅 <?php echo date('d/m/Y', strtotime($lkh['ngay_khoi_hanh'])); ?> - 
                                <?php echo date('d/m/Y', strtotime($lkh['ngay_ket_thuc'])); ?>
                                <?php if (isset($lkh['gia_co_ban']) && $lkh['gia_co_ban']): ?>
                                    (<?php echo number_format($lkh['gia_co_ban'], 0, ',', '.'); ?> VNĐ)
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="color: #dc3545;">Tour này chưa có lịch khởi hành nào.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Chọn tour -->
            <div class="tour-info-box">
                <p>Vui lòng chọn tour từ trang <a href="index.php?act=admin/quanLyTour">Quản lý tour</a> để xem danh sách khách.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function printCustomerList() {
            window.print();
        }
    </script>
</body>
</html>
