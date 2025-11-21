<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Booking - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
    <style>
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .filter-section form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }
        .filter-group {
            flex: 1;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .filter-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-filter {
            padding: 8px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-filter:hover {
            background: #0056b3;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .status-ChoXacNhan {
            background: #fff3cd;
            color: #856404;
        }
        .status-DaCoc {
            background: #d1ecf1;
            color: #0c5460;
        }
        .status-HoanTat {
            background: #d4edda;
            color: #155724;
        }
        .status-Huy {
            background: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .btn-action {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        .btn-edit {
            background: #ffc107;
            color: #212529;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        .btn-action:hover {
            opacity: 0.8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Quản lý Booking</h1>
        <nav>
            <a href="index.php?act=admin/dashboard">← Quay lại Dashboard</a>
            <a href="index.php?act=booking/datTourChoKhach">Đặt tour cho khách</a>
        </nav>

        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <h2>Danh sách Booking</h2>

            <!-- Bộ lọc -->
            <div class="filter-section">
                <form method="GET" action="index.php">
                    <input type="hidden" name="act" value="admin/quanLyBooking">
                    <div class="filter-group">
                        <label>Lọc theo trạng thái:</label>
                        <select name="trang_thai">
                            <option value="">Tất cả</option>
                            <option value="ChoXacNhan" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'ChoXacNhan') ? 'selected' : ''; ?>>Chờ xác nhận</option>
                            <option value="DaCoc" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'DaCoc') ? 'selected' : ''; ?>>Đã cọc</option>
                            <option value="HoanTat" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'HoanTat') ? 'selected' : ''; ?>>Hoàn tất</option>
                            <option value="Huy" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'Huy') ? 'selected' : ''; ?>>Hủy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-filter">Lọc</button>
                    <?php if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])): ?>
                        <a href="index.php?act=admin/quanLyBooking" class="btn-action btn-filter" style="text-decoration: none;">Xóa bộ lọc</a>
                    <?php endif; ?>
                </form>
            </div>

            <?php if (isset($bookings) && !empty($bookings)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Khách hàng</th>
                            <th>Số lượng</th>
                            <th>Ngày khởi hành</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?><br>
                                    <small><?php echo htmlspecialchars($booking['email'] ?? ''); ?></small>
                                </td>
                                <td><?php echo $booking['so_nguoi']; ?> người</td>
                                <td><?php echo $booking['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                                <td><?php echo number_format($booking['tong_tien'] ?? 0); ?> VNĐ</td>
                                <td>
                                    <span class="status-badge status-<?php echo $booking['trang_thai']; ?>">
                                        <?php
                                        $statusLabels = [
                                            'ChoXacNhan' => 'Chờ xác nhận',
                                            'DaCoc' => 'Đã cọc',
                                            'HoanTat' => 'Hoàn tất',
                                            'Huy' => 'Hủy'
                                        ];
                                        echo $statusLabels[$booking['trang_thai']] ?? $booking['trang_thai'];
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="index.php?act=booking/chiTiet&id=<?php echo $booking['booking_id']; ?>" class="btn-action btn-view">Xem</a>
                                        <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'HDV')): ?>
                                            <a href="index.php?act=booking/chiTiet&id=<?php echo $booking['booking_id']; ?>" class="btn-action btn-edit">Sửa</a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                                            <a href="index.php?act=booking/delete&id=<?php echo $booking['booking_id']; ?>" 
                                               class="btn-action btn-delete" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?');">Xóa</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có booking nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>