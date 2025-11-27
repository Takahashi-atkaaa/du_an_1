<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán online - Khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .payment-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
        }
        .payment-summary {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-credit-card me-2"></i>Thanh toán online</h2>
            <a href="index.php?act=khachHang/hoaDon&booking_id=<?php echo $booking['booking_id']; ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($booking) && $booking): ?>
            <div class="row">
                <div class="col-md-8">
                    <div class="payment-card">
                        <h4 class="mb-4">Thông tin thanh toán</h4>
                        <form method="POST" action="index.php?act=khachHang/thanhToan&booking_id=<?php echo $booking['booking_id']; ?>">
                            <div class="mb-3">
                                <label class="form-label">Số tiền thanh toán <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="so_tien" 
                                           value="<?php echo $conNo > 0 ? $conNo : $booking['tong_tien']; ?>" 
                                           min="10000" step="10000" max="<?php echo $conNo > 0 ? $conNo : $booking['tong_tien']; ?>" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <small class="text-muted">Số tiền tối đa: <?php echo number_format($conNo > 0 ? $conNo : (float)$booking['tong_tien']); ?> VNĐ</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phương thức thanh toán</label>
                                <select class="form-select" name="phuong_thuc">
                                    <option value="Online" selected>Thanh toán online (VNPay/MoMo)</option>
                                    <option value="ChuyenKhoan">Chuyển khoản ngân hàng</option>
                                    <option value="TienMat">Tiền mặt (tại văn phòng)</option>
                                </select>
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Lưu ý:</strong> Hiện tại hệ thống chỉ ghi nhận giao dịch. Để tích hợp thanh toán thực tế qua VNPay/MoMo, cần cấu hình thêm payment gateway.
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-credit-card me-2"></i>Xác nhận thanh toán
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-summary">
                        <h5 class="mb-3">Tóm tắt thanh toán</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tour:</span>
                                <strong><?php echo htmlspecialchars($booking['ten_tour'] ?? ''); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Mã booking:</span>
                                <strong>#<?php echo $booking['booking_id']; ?></strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Số người:</span>
                                <strong><?php echo $booking['so_nguoi'] ?? 0; ?> người</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng tiền:</span>
                                <strong><?php echo number_format((float)$booking['tong_tien']); ?> VNĐ</strong>
                            </div>
                            <?php if ($tongDaThanhToan > 0): ?>
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Đã thanh toán:</span>
                                    <strong>- <?php echo number_format($tongDaThanhToan); ?> VNĐ</strong>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Còn nợ:</strong></span>
                                <strong class="<?php echo $conNo > 0 ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo number_format($conNo); ?> VNĐ
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>Không tìm thấy thông tin booking.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

