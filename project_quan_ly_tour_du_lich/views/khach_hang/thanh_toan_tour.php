<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .payment-box { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px; max-width: 520px; margin: 48px auto; }
        .tour-summary { background: #e9f7fe; border-radius: 12px; padding: 16px; margin-bottom: 24px; }
        .btn-pay { font-size: 1.2rem; padding: 12px 32px; }
    </style>
</head>
<body>
    <div class="payment-box">
        <h2 class="mb-4 text-center text-primary">Thanh toán đặt tour</h2>
        <div class="tour-summary mb-3">
            <h5 class="mb-2">Thông tin tour</h5>
            <div><b>Tên tour:</b> <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></div>
            <div><b>Loại tour:</b> <?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></div>
            <div><b>Giá tour:</b> <span class="text-danger fw-bold"><?php echo number_format($tour['gia_tour'] ?? $tour['gia_co_ban'] ?? 0); ?>đ</span></div>
        </div>
        <form method="post" action="index.php?act=khachHang/thanhToanTour&id=<?php echo $tour['tour_id'] ?? $tour['id']; ?>">
            <div class="mb-3">
                <label for="so_luong" class="form-label">Số lượng người</label>
                <input type="number" class="form-control" id="so_luong" name="so_luong" min="1" value="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tên khách hàng</label>
                <input type="text" class="form-control" name="ten_khach_hang" value="<?php echo htmlspecialchars($nguoiDung['ho_ten'] ?? ''); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($nguoiDung['email'] ?? ''); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($nguoiDung['so_dien_thoai'] ?? ''); ?>" readonly>
            </div>
            <div class="mb-4">
                <div class="alert alert-info">Vui lòng quét mã QR bên dưới để thanh toán trước. Sau khi thanh toán, bạn sẽ nhận được xác nhận qua email.</div>
                <div class="text-center">
                    <img src="/public/uploads/qr/qr_le_van_quan.jpg" alt="QR Thanh toán" style="max-width:260px; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.12);">
                    <div class="mt-2 fw-bold">LE VAN QUAN - 9436528853438</div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-pay w-100">Xác nhận đã thanh toán & Đặt tour</button>
        </form>
        <div class="mt-3 text-center">
            <a href="index.php?act=khachHang/dashboard" class="btn btn-outline-secondary">Quay lại trang chủ</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
