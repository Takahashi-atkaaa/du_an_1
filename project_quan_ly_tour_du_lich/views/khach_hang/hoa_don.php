<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn - Khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .invoice-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-receipt me-2"></i>Hóa đơn & Thanh toán</h2>
            <a href="index.php?act=khachHang/dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($booking) && $booking): ?>
            <div class="invoice-card">
                <div class="invoice-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>Hóa đơn #<?php echo $booking['booking_id']; ?></h3>
                            <p class="text-muted mb-0">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($booking['ngay_dat'] ?? '')); ?></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <span class="badge bg-<?php 
                                echo match($booking['trang_thai']) {
                                    'ChoXacNhan' => 'warning',
                                    'DaCoc' => 'info',
                                    'HoanTat' => 'success',
                                    'Huy' => 'danger',
                                    default => 'secondary'
                                };
                            ?> fs-6">
                                <?php echo htmlspecialchars($booking['trang_thai'] ?? ''); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Thông tin khách hàng</h5>
                        <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($booking['ho_ten'] ?? ''); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($booking['email'] ?? ''); ?></p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($booking['so_dien_thoai'] ?? ''); ?></p>
                        <?php if (!empty($booking['dia_chi'])): ?>
                            <p class="mb-0"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($booking['dia_chi']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5>Thông tin tour</h5>
                        <p class="mb-1"><strong>Tour:</strong> <?php echo htmlspecialchars($booking['ten_tour'] ?? ''); ?></p>
                        <p class="mb-1"><strong>Loại tour:</strong> <?php echo htmlspecialchars($booking['loai_tour'] ?? ''); ?></p>
                        <p class="mb-1"><strong>Ngày khởi hành:</strong> <?php echo !empty($booking['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'Chưa xác định'; ?></p>
                        <p class="mb-0"><strong>Số người:</strong> <?php echo $booking['so_nguoi'] ?? 0; ?> người</p>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Mô tả</th>
                                <th class="text-end">Số lượng</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['ten_tour'] ?? ''); ?></td>
                                <td class="text-end"><?php echo $booking['so_nguoi'] ?? 0; ?> người</td>
                                <td class="text-end"><?php echo number_format((float)($booking['gia_co_ban'] ?? 0)); ?> VNĐ</td>
                                <td class="text-end"><strong><?php echo number_format((float)($booking['tong_tien'] ?? 0)); ?> VNĐ</strong></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng:</th>
                                <th class="text-end"><?php echo number_format((float)($booking['tong_tien'] ?? 0)); ?> VNĐ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php if (!empty($giaoDichList)): ?>
                    <h5 class="mb-3">Lịch sử thanh toán</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngày</th>
                                    <th>Mô tả</th>
                                    <th class="text-end">Số tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $tongDaThanhToan = 0;
                                foreach ($giaoDichList as $gd): 
                                    if ($gd['loai'] === 'Thu') {
                                        $tongDaThanhToan += (float)$gd['so_tien'];
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($gd['ngay_giao_dich'] ?? '')); ?></td>
                                        <td><?php echo htmlspecialchars($gd['mo_ta'] ?? ''); ?></td>
                                        <td class="text-end <?php echo $gd['loai'] === 'Thu' ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo $gd['loai'] === 'Thu' ? '+' : '-'; ?>
                                            <?php echo number_format((float)($gd['so_tien'] ?? 0)); ?> VNĐ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Tổng đã thanh toán:</th>
                                    <th class="text-end text-success"><?php echo number_format($tongDaThanhToan); ?> VNĐ</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Còn nợ:</th>
                                    <th class="text-end <?php echo ($booking['tong_tien'] - $tongDaThanhToan) > 0 ? 'text-danger' : 'text-success'; ?>">
                                        <?php echo number_format(max(0, (float)$booking['tong_tien'] - $tongDaThanhToan)); ?> VNĐ
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>

                <?php if (in_array($booking['trang_thai'], ['ChoXacNhan', 'DaCoc'])): ?>
                    <div class="text-end">
                        <a href="index.php?act=khachHang/thanhToan&booking_id=<?php echo $booking['booking_id']; ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-credit-card me-2"></i>Thanh toán online
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($booking['ghi_chu'])): ?>
                    <div class="mt-4">
                        <h5>Ghi chú</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($booking['ghi_chu'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (isset($bookings) && !empty($bookings)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chọn hóa đơn để xem</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã booking</th>
                                    <th>Tour</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td>#<?php echo $b['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($b['ten_tour'] ?? ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($b['ngay_dat'] ?? '')); ?></td>
                                        <td><?php echo number_format((float)($b['tong_tien'] ?? 0)); ?> VNĐ</td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo match($b['trang_thai']) {
                                                    'ChoXacNhan' => 'warning',
                                                    'DaCoc' => 'info',
                                                    'HoanTat' => 'success',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo htmlspecialchars($b['trang_thai'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?act=khachHang/hoaDon&booking_id=<?php echo $b['booking_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>Bạn chưa có booking nào.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
