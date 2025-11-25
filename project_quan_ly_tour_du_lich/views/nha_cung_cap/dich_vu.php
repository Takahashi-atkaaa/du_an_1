<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dịch vụ - Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-briefcase"></i> Quản lý Dịch vụ
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

        <!-- Navigation -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/baoGia">
                    <i class="bi bi-file-earmark-text"></i> Báo giá
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?act=nhaCungCap/dichVu">
                    <i class="bi bi-briefcase"></i> Dịch vụ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/congNo">
                    <i class="bi bi-cash-stack"></i> Công nợ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/hopDong">
                    <i class="bi bi-file-earmark-check"></i> Lịch sử hợp tác
                </a>
            </li>
        </ul>

        <!-- Services List -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Tất cả dịch vụ</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dichVu)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                        <p class="mt-3">Không có dịch vụ nào</p>
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
                                    <th>Thao tác</th>
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
                                
                                foreach ($dichVu as $dv): 
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $loaiDichVuMap[$dv['loai_dich_vu']] ?? $dv['loai_dich_vu']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></td>
                                    <td>
                                        <?php echo $dv['so_luong']; ?>
                                        <?php if ($dv['don_vi']): ?>
                                            <small class="text-muted"><?php echo $dv['don_vi']; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['ngay_bat_dau']): ?>
                                            <?php echo date('d/m/Y', strtotime($dv['ngay_bat_dau'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['ngay_ket_thuc']): ?>
                                            <?php echo date('d/m/Y', strtotime($dv['ngay_ket_thuc'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['gia_tien']): ?>
                                            <strong class="text-success">
                                                <?php echo number_format($dv['gia_tien'], 0, ',', '.'); ?>đ
                                            </strong>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $statusMap[$dv['trang_thai']] ?? ['text' => $dv['trang_thai'], 'class' => 'secondary'];
                                        ?>
                                        <span class="badge bg-<?php echo $status['class']; ?>">
                                            <?php echo $status['text']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="index.php?act=nhaCungCap/baoGia" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
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
