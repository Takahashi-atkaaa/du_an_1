<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Lịch Khởi Hành - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Quản lý Lịch Khởi Hành</h1>
        <nav>
            <a href="index.php?act=admin/dashboard">← Quay lại Dashboard</a>
            <a href="index.php?act=lichKhoiHanh/create">Tạo lịch khởi hành mới</a>
        </nav>

        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
                <div style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <h2>Danh sách Lịch Khởi Hành</h2>

            <?php if (isset($lichKhoiHanhList) && !empty($lichKhoiHanhList)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Giờ xuất phát</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm tập trung</th>
                            <th>Số chỗ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lichKhoiHanhList as $lich): ?>
                            <tr>
                                <td>#<?php echo $lich['id']; ?></td>
                                <td><?php echo htmlspecialchars($lich['ten_tour'] ?? 'N/A'); ?></td>
                                <td><?php echo $lich['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                                <td><?php echo $lich['gio_xuat_phat'] ?? 'N/A'; ?></td>
                                <td><?php echo $lich['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?></td>
                                <td><?php echo htmlspecialchars($lich['diem_tap_trung'] ?? ''); ?></td>
                                <td><?php echo $lich['so_cho'] ?? 50; ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'SapKhoiHanh' => 'Sắp khởi hành',
                                        'DangChay' => 'Đang chạy',
                                        'HoanThanh' => 'Hoàn thành'
                                    ];
                                    echo $statusLabels[$lich['trang_thai']] ?? $lich['trang_thai'];
                                    ?>
                                </td>
                                <td>
                                    <a href="index.php?act=lichKhoiHanh/chiTiet&id=<?php echo $lich['id']; ?>">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có lịch khởi hành nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

