<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour - Khách hàng</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Đặt Tour</h1>
        <?php if (isset($tour)): ?>
            <form method="POST" action="index.php?act=booking/create">
                <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                <div class="form-group">
                    <label>Số lượng người:</label>
                    <input type="number" name="so_luong_nguoi" min="1" required>
                </div>
                <div class="form-group">
                    <label>Ngày khởi hành:</label>
                    <input type="date" name="ngay_khoi_hanh" required>
                </div>
                <div class="form-group">
                    <label>Tổng tiền:</label>
                    <input type="text" name="tong_tien" readonly>
                </div>
                <button type="submit">Xác nhận đặt tour</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>


