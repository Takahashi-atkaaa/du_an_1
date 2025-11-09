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
                <p><strong>Giá:</strong> <?php echo number_format($tour['gia']); ?> VNĐ</p>
                <p><strong>Số ngày:</strong> <?php echo $tour['so_ngay']; ?> ngày</p>
                <p><strong>Điểm khởi hành:</strong> <?php echo htmlspecialchars($tour['diem_khoi_hanh']); ?></p>
                <p><strong>Điểm đến:</strong> <?php echo htmlspecialchars($tour['diem_den']); ?></p>
                <a href="index.php?act=booking/create&tour_id=<?php echo $tour['id']; ?>">Đặt tour</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


