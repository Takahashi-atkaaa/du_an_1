<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử hợp tác - Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-check"></i> Lịch sử hợp tác
            </h1>
            <div>
                <a href="index.php?act=nhaCungCap/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php 
            $currentTab = 'hopDong';
            include __DIR__ . '/partials/main_nav.php';
        ?>

        <!-- History List -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử hợp tác</h5>
            </div>
            <div class="card-body">
                <?php if (empty($lichSu)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                        <p class="mt-3">Chưa có lịch sử hợp tác</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tour</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Giá tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $statusMap = [
                                    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
                                    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
                                    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
                                    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
                                    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info']
                                ];
                                
                                $loaiDichVuMap = [
                                    'Xe' => 'Xe',
                                    'KhachSan' => 'Khách sạn',
                                    'VeMayBay' => 'Vé máy bay',
                                    'NhaHang' => 'Nhà hàng',
                                    'DiemThamQuan' => 'Điểm tham quan',
                                    'Visa' => 'Visa',
                                    'BaoHiem' => 'Bảo hiểm',
                                    'Khac' => 'Khác'
                                ];
                                
                                foreach ($lichSu as $ls): 
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ls['ten_tour'] ?? 'N/A'); ?></strong>
                                        <?php if ($ls['so_booking']): ?>
                                            <br><small class="text-muted">
                                                <i class="bi bi-people"></i> <?php echo $ls['so_booking']; ?> booking
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $loaiDichVuMap[$ls['loai_dich_vu']] ?? $ls['loai_dich_vu']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($ls['ten_dich_vu']); ?></td>
                                    <td>
                                        <?php echo $ls['so_luong']; ?>
                                        <?php if ($ls['don_vi']): ?>
                                            <small class="text-muted"><?php echo $ls['don_vi']; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($ls['ngay_bat_dau']): ?>
                                            <?php echo date('d/m/Y', strtotime($ls['ngay_bat_dau'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($ls['ngay_ket_thuc']): ?>
                                            <?php echo date('d/m/Y', strtotime($ls['ngay_ket_thuc'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($ls['gia_tien']): ?>
                                            <strong class="text-success">
                                                <?php echo number_format($ls['gia_tien'], 0, ',', '.'); ?>đ
                                            </strong>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $statusMap[$ls['trang_thai']] ?? ['text' => $ls['trang_thai'], 'class' => 'secondary'];
                                        ?>
                                        <span class="badge bg-<?php echo $status['class']; ?>">
                                            <?php echo $status['text']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($ls['created_at']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($ls['created_at'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
