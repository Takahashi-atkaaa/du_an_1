<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Tour - Khách hàng</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <a href="index.php?act=tour/index">← Quay lại danh sách</a>
        <?php if (isset($tour)): ?>
            <div class="tour-detail">
                <h1><?php echo htmlspecialchars($tour['ten_tour']); ?></h1>
                <p><?php echo htmlspecialchars($tour['mo_ta']); ?></p>
                <p><strong>Loại tour:</strong> <?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></p>
                <p><strong>Giá cơ bản:</strong> <?php echo number_format((float)$tour['gia_co_ban']); ?> VNĐ</p>
                <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($tour['trang_thai'] ?? ''); ?></p>
                <a href="index.php?act=booking/create&tour_id=<?php echo $tour['tour_id']; ?>">Đặt tour</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


