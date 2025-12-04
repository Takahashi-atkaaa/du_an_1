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
    <title>Xác nhận xóa booking - Admin</title>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Xác nhận xóa booking</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác. Booking sẽ bị xóa vĩnh viễn.
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold">Thông tin booking:</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Booking ID:</th>
                                    <td>#<?php echo $booking['booking_id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tour:</th>
                                    <td><?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Khách hàng:</th>
                                    <td><?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Số người:</th>
                                    <td><?php echo $booking['so_nguoi'] ?? 0; ?></td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td><?php echo number_format($booking['tong_tien'] ?? 0, 0, ',', '.'); ?> VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Ngày khởi hành:</th>
                                    <td><?php echo $booking['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái:</th>
                                    <td>
                                        <?php
                                        $statusLabels = [
                                            'ChoXacNhan' => 'Chờ xác nhận',
                                            'DaCoc' => 'Đã cọc',
                                            'HoanTat' => 'Hoàn tất',
                                            'Huy' => 'Hủy'
                                        ];
                                        echo $statusLabels[$booking['trang_thai']] ?? $booking['trang_thai'];
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <form method="POST" action="index.php?act=booking/delete&id=<?php echo $booking['booking_id']; ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-shield-lock"></i> Nhập mật khẩu Admin để xác nhận <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="mat_khau" class="form-control" required autofocus>
                                <div class="form-text">Vui lòng nhập mật khẩu tài khoản Admin của bạn.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-file-text"></i> Lý do xóa (tùy chọn)
                                </label>
                                <textarea name="ly_do_xoa" class="form-control" rows="3" placeholder="Nhập lý do xóa booking này..."></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Xác nhận xóa
                                </button>
                                <a href="index.php?act=admin/quanLyBooking" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

