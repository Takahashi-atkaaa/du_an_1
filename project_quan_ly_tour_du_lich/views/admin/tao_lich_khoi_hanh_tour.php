<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Lịch Khởi Hành - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Tạo Lịch Khởi Hành cho Tour: <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h1>
        <nav>
            <a href="index.php?act=admin/chiTietTour&id=<?php echo $tour['tour_id']; ?>">← Quay lại chi tiết tour</a>
        </nav>

        <div class="content">
            <?php if (isset($_SESSION['error'])): ?>
                <div style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?act=tour/taoLichKhoiHanh&tour_id=<?php echo $tour['tour_id']; ?>">
                <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id']; ?>">
                <table>
                    <tr>
                        <th>Ngày khởi hành</th>
                        <td><input type="date" name="ngay_khoi_hanh" required></td>
                    </tr>
                    <tr>
                        <th>Giờ xuất phát</th>
                        <td><input type="time" name="gio_xuat_phat"></td>
                    </tr>
                    <tr>
                        <th>Ngày kết thúc</th>
                        <td><input type="date" name="ngay_ket_thuc"></td>
                    </tr>
                    <tr>
                        <th>Giờ kết thúc</th>
                        <td><input type="time" name="gio_ket_thuc"></td>
                    </tr>
                    <tr>
                        <th>Điểm tập trung</th>
                        <td><input type="text" name="diem_tap_trung" style="width: 100%;"></td>
                    </tr>
                    <tr>
                        <th>Số chỗ</th>
                        <td><input type="number" name="so_cho" value="50" min="1" required></td>
                    </tr>
                    <tr>
                        <th>HDV chính</th>
                        <td>
                            <select name="hdv_id">
                                <option value="">-- Chọn HDV --</option>
                                <?php foreach ($hdvList as $hdv): ?>
                                    <option value="<?php echo $hdv['nhan_su_id']; ?>">
                                        <?php echo htmlspecialchars($hdv['ho_ten'] ?? 'N/A'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <select name="trang_thai">
                                <option value="SapKhoiHanh">Sắp khởi hành</option>
                                <option value="DangChay">Đang chạy</option>
                                <option value="HoanThanh">Hoàn thành</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td><textarea name="ghi_chu" rows="3" style="width: 100%;"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Tạo lịch khởi hành</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>

