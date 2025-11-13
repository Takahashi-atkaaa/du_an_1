<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn - Khách hàng</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Hóa đơn</h1>
        <?php if (isset($booking)): ?>
            <div class="invoice">
                <p><strong>Mã booking:</strong> #<?php echo $booking['booking_id']; ?></p>
                <p><strong>Tour:</strong> <?php echo htmlspecialchars($tour['ten_tour'] ?? $booking['tour_id']); ?></p>
                <p><strong>Ngày đặt:</strong> <?php echo $booking['ngay_dat'] ?? ''; ?></p>
                <p><strong>Số lượng người:</strong> <?php echo $booking['so_nguoi'] ?? 0; ?></p>
                <p><strong>Ngày khởi hành:</strong> <?php echo $booking['ngay_khoi_hanh'] ?? ''; ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format((float)$booking['tong_tien']); ?> VNĐ</p>
                <p><strong>Trạng thái:</strong> <?php echo $booking['trang_thai'] ?? ''; ?></p>
                <?php if (!empty($booking['ghi_chu'])): ?>
                    <p><strong>Ghi chú:</strong> <?php echo nl2br(htmlspecialchars($booking['ghi_chu'])); ?></p>
                <?php endif; ?>
            </div>
            <a href="index.php?act=tour/index">Xem thêm tour</a>
        <?php endif; ?>
    </div>
</body>
</html>


