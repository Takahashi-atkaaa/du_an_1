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
    <title>Lịch sử xóa booking - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .booking-info {
            font-size: 0.9em;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?act=admin/dashboard">
                    <i class="bi bi-house"></i> Dashboard
                </a>
                <a class="nav-link" href="index.php?act=admin/quanLyBooking">
                    <i class="bi bi-calendar-check"></i> Quản lý Booking
                </a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="bi bi-clock-history text-danger"></i> Lịch sử xóa booking
            </h2>
            <a href="index.php?act=admin/quanLyBooking" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (!empty($lichSuXoa)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Booking ID</th>
                                    <th>Tour</th>
                                    <th>Khách hàng</th>
                                    <th>Thông tin booking</th>
                                    <th>Người xóa</th>
                                    <th>Lý do xóa</th>
                                    <th>Thời gian xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lichSuXoa as $idx => $item): ?>
                                    <tr>
                                        <td><?php echo $idx + 1; ?></td>
                                        <td>
                                            <span class="badge bg-secondary">#<?php echo $item['booking_id'] ?? 'N/A'; ?></span>
                                        </td>
                                        <td>
                                            <?php if ($item['ten_tour']): ?>
                                                <strong><?php echo htmlspecialchars($item['ten_tour']); ?></strong>
                                            <?php else: ?>
                                                <span class="text-muted">Tour đã bị xóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['ten_khach_hang']): ?>
                                                <?php echo htmlspecialchars($item['ten_khach_hang']); ?>
                                                <?php if ($item['email_khach_hang']): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($item['email_khach_hang']); ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $thongTin = json_decode($item['thong_tin_booking'] ?? '{}', true);
                                            if ($thongTin):
                                            ?>
                                                <div class="booking-info">
                                                    <strong>Số người:</strong> <?php echo $thongTin['so_nguoi'] ?? 0; ?><br>
                                                    <strong>Tổng tiền:</strong> <?php echo number_format($thongTin['tong_tien'] ?? 0, 0, ',', '.'); ?> VNĐ<br>
                                                    <?php if ($thongTin['ngay_khoi_hanh']): ?>
                                                        <strong>Ngày khởi hành:</strong> <?php echo date('d/m/Y', strtotime($thongTin['ngay_khoi_hanh'])); ?><br>
                                                    <?php endif; ?>
                                                    <strong>Trạng thái:</strong> 
                                                    <?php
                                                    $statusLabels = [
                                                        'ChoXacNhan' => 'Chờ xác nhận',
                                                        'DaCoc' => 'Đã cọc',
                                                        'HoanTat' => 'Hoàn tất',
                                                        'Huy' => 'Hủy'
                                                    ];
                                                    echo $statusLabels[$thongTin['trang_thai']] ?? $thongTin['trang_thai'] ?? 'N/A';
                                                    ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Không có thông tin</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['nguoi_xoa']): ?>
                                                <strong><?php echo htmlspecialchars($item['nguoi_xoa']); ?></strong>
                                                <?php if ($item['email_nguoi_xoa']): ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($item['email_nguoi_xoa']); ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['ly_do_xoa']): ?>
                                                <span class="text-danger"><?php echo nl2br(htmlspecialchars($item['ly_do_xoa'])); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Không có</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?php echo $item['thoi_gian_xoa'] ? date('d/m/Y H:i:s', strtotime($item['thoi_gian_xoa'])) : 'N/A'; ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 opacity-25"></i>
                        <p class="mt-3">Chưa có lịch sử xóa booking nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

