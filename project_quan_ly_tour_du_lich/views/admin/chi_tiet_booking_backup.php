<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Booking - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Chi tiết Booking #<?php echo $booking['booking_id']; ?></h1>
        <a href="index.php?act=admin/quanLyBooking">← Quay lại danh sách</a>

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

            <!-- Thông tin Booking -->
            <h2>Thông tin Booking</h2>
            <table>
                <tr>
                    <th>Mã Booking</th>
                    <td>#<?php echo $booking['booking_id']; ?></td>
                </tr>
                <tr>
                    <th>Tour</th>
                    <td><?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Khách hàng</th>
                    <td>
                        <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?><br>
                        Email: <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?><br>
                        SĐT: <?php echo htmlspecialchars($booking['so_dien_thoai'] ?? 'N/A'); ?><br>
                        Địa chỉ: <?php echo htmlspecialchars($booking['dia_chi'] ?? 'N/A'); ?>
                    </td>
                </tr>
                <tr>
                    <th>Số lượng người</th>
                    <td><?php echo $booking['so_nguoi']; ?> người</td>
                </tr>
                <tr>
                    <th>Ngày đặt</th>
                    <td><?php echo date('d/m/Y', strtotime($booking['ngay_dat'])); ?></td>
                </tr>
                <tr>
                    <th>Ngày khởi hành</th>
                    <td><?php echo $booking['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Tổng tiền</th>
                    <td><strong><?php echo number_format($booking['tong_tien'] ?? 0); ?> VNĐ</strong></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php
                        $statusLabels = [
                            'ChoXacNhan' => 'Chờ xác nhận',
                            'DaCoc' => 'Đã cọc',
                            'HoanTat' => 'Hoàn tất',
                            'Huy' => 'Hủy'
                        ];
                        $currentStatus = $booking['trang_thai'];
                        echo $statusLabels[$currentStatus] ?? $currentStatus;
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Ghi chú</th>
                    <td><?php echo nl2br(htmlspecialchars($booking['ghi_chu'] ?? '')); ?></td>
                </tr>
            </table>

            <!-- Form cập nhật trạng thái -->
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'HDV')): ?>
                <h2>Cập nhật trạng thái</h2>
                <form method="POST" action="index.php?act=booking/updateTrangThai" style="margin-bottom: 30px;">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    <table>
                        <tr>
                            <th>Trạng thái mới</th>
                            <td>
                                <select name="trang_thai" required>
                                    <option value="ChoXacNhan" <?php echo $currentStatus == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                    <option value="DaCoc" <?php echo $currentStatus == 'DaCoc' ? 'selected' : ''; ?>>Đã cọc</option>
                                    <option value="HoanTat" <?php echo $currentStatus == 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                    <option value="Huy" <?php echo $currentStatus == 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Ghi chú</th>
                            <td>
                                <textarea name="ghi_chu" rows="3" style="width: 100%;"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Cập nhật trạng thái</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <!-- Form cập nhật thông tin -->
                <h2>Cập nhật thông tin</h2>
                <form method="POST" action="index.php?act=booking/update" style="margin-bottom: 30px;">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    <table>
                        <tr>
                            <th>Số lượng người</th>
                            <td>
                                <input type="number" name="so_nguoi" value="<?php echo $booking['so_nguoi']; ?>" min="1" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày khởi hành</th>
                            <td>
                                <input type="date" name="ngay_khoi_hanh" value="<?php echo $booking['ngay_khoi_hanh'] ?? ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>Tổng tiền</th>
                            <td>
                                <input type="number" name="tong_tien" value="<?php echo $booking['tong_tien']; ?>" step="0.01" min="0" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <select name="trang_thai">
                                    <option value="ChoXacNhan" <?php echo $currentStatus == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                    <option value="DaCoc" <?php echo $currentStatus == 'DaCoc' ? 'selected' : ''; ?>>Đã cọc</option>
                                    <option value="HoanTat" <?php echo $currentStatus == 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                    <option value="Huy" <?php echo $currentStatus == 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Ghi chú</th>
                            <td>
                                <textarea name="ghi_chu" rows="3" style="width: 100%;"><?php echo htmlspecialchars($booking['ghi_chu'] ?? ''); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Cập nhật thông tin</button>
                            </td>
                        </tr>
                    </table>
                </form>
            <?php endif; ?>

            <!-- Lịch sử thay đổi -->
            <h2>Lịch sử thay đổi trạng thái</h2>
            <?php if (!empty($history)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Trạng thái cũ</th>
                            <th>Trạng thái mới</th>
                            <th>Người thay đổi</th>
                            <th>Vai trò</th>
                            <th>Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $item): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($item['thoi_gian'])); ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'ChoXacNhan' => 'Chờ xác nhận',
                                        'DaCoc' => 'Đã cọc',
                                        'HoanTat' => 'Hoàn tất',
                                        'Huy' => 'Hủy'
                                    ];
                                    echo $statusLabels[$item['trang_thai_cu']] ?? $item['trang_thai_cu'] ?? 'N/A';
                                    ?>
                                </td>
                                <td>
                                    <?php echo $statusLabels[$item['trang_thai_moi']] ?? $item['trang_thai_moi'] ?? 'N/A'; ?>
                                </td>
                                <td><?php echo htmlspecialchars($item['nguoi_thay_doi'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($item['vai_tro'] ?? 'N/A'); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($item['ghi_chu'] ?? '')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có lịch sử thay đổi.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

