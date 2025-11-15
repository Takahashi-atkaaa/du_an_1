<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết lịch trình</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .card { border: 1px solid #ddd; padding: 16px; margin-bottom: 16px; border-radius: 8px; }
        .row { display: flex; gap: 16px; flex-wrap: wrap; }
        .col { flex: 1 1 320px; }
        .actions a { margin-right: 12px; }
    </style>
    </head>
<body>
    <div class="container">
        <h2>Chi tiết lịch trình</h2>

        <div class="row">
            <div class="col">
                <div class="card">
                    <h3>Thông tin tour</h3>
                    <p><strong>Mã tour:</strong> <?php echo htmlspecialchars($tour['tour_id']); ?></p>
                    <p><strong>Tên tour:</strong> <?php echo htmlspecialchars($tour['ten_tour']); ?></p>
                    <p><strong>Loại tour:</strong> <?php echo htmlspecialchars($tour['loai_tour']); ?></p>
                    <p><strong>Giá cơ bản:</strong> <?php echo number_format((float)$tour['gia_co_ban'], 0, ',', '.'); ?> đ</p>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <h3>Hóa đơn & Thanh toán</h3>
                    <p><strong>Mã booking:</strong> <?php echo htmlspecialchars($booking['booking_id']); ?></p>
                    <p><strong>Ngày đặt:</strong> <?php echo htmlspecialchars($booking['ngay_dat']); ?></p>
                    <p><strong>Ngày khởi hành:</strong> <?php echo htmlspecialchars($booking['ngay_khoi_hanh']); ?></p>
                    <p><strong>Số người:</strong> <?php echo htmlspecialchars($booking['so_nguoi']); ?></p>
                    <p><strong>Tổng tiền:</strong> <?php echo number_format((float)$booking['tong_tien'], 0, ',', '.'); ?> đ</p>
                    <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($booking['trang_thai']); ?></p>
                    <div class="actions">
                        <a href="<?php echo BASE_URL; ?>index.php?act=booking/show&id=<?php echo urlencode($booking['booking_id']); ?>">Xem hóa đơn</a>
                        <a href="<?php echo BASE_URL; ?>index.php?act=payment/start&booking_id=<?php echo urlencode($booking['booking_id']); ?>">Thanh toán online</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3>Hỗ trợ & Ưu đãi</h3>
            <p><a href="<?php echo BASE_URL; ?>index.php?act=support/request&booking_id=<?php echo urlencode($booking['booking_id']); ?>">Gửi yêu cầu hỗ trợ</a></p>
            <p><a href="<?php echo BASE_URL; ?>index.php?act=offers/subscribe&khach_hang_id=<?php echo urlencode($booking['khach_hang_id']); ?>">Nhận ưu đãi qua email</a></p>
        </div>

        <div class="card">
            <h3>Đánh giá dịch vụ sau tour</h3>
            <p><a href="<?php echo BASE_URL; ?>index.php?act=khachHang/danhGia&booking_id=<?php echo urlencode($booking['booking_id']); ?>">Viết đánh giá</a></p>
        </div>

        <p><a href="<?php echo BASE_URL; ?>index.php?act=auth/profile">Cập nhật thông tin cá nhân</a></p>
        <p><a href="<?php echo BASE_URL; ?>index.php?act=khachHang/traCuu">Tra cứu khác</a></p>
    </div>
</body>
</html>


