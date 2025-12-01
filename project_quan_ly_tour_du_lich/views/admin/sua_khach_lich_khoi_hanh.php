<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Khách - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Sửa Thông Tin Khách</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <div class="mb-3">
                    <strong>Khách hàng:</strong> <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?><br>
                    <strong>Tour:</strong> <?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?><br>
                    <strong>Booking ID:</strong> #<?php echo $booking['booking_id']; ?>
                </div>

                <form method="POST" action="index.php?act=admin/suaKhachLichKhoiHanh&booking_id=<?php echo $bookingId; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Số người <span class="text-danger">*</span></label>
                            <input type="number" name="so_nguoi" class="form-control" value="<?php echo $booking['so_nguoi'] ?? 1; ?>" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tổng tiền</label>
                            <input type="number" name="tong_tien" class="form-control" value="<?php echo $booking['tong_tien'] ?? 0; ?>" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="ChoXacNhan" <?php echo ($booking['trang_thai'] ?? '') === 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                <option value="DaCoc" <?php echo ($booking['trang_thai'] ?? '') === 'DaCoc' ? 'selected' : ''; ?>>Đã cọc</option>
                                <option value="HoanTat" <?php echo ($booking['trang_thai'] ?? '') === 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                <option value="Huy" <?php echo ($booking['trang_thai'] ?? '') === 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"><?php echo htmlspecialchars($booking['ghi_chu'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Lưu thay đổi</button>
                        <a href="index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

