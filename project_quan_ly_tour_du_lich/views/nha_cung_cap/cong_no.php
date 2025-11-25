<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Công nợ - Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }
        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-cash-stack"></i> Quản lý Công nợ
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
                <a class="nav-link" href="index.php?act=nhaCungCap/dichVu">
                    <i class="bi bi-briefcase"></i> Dịch vụ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?act=nhaCungCap/congNo">
                    <i class="bi bi-cash-stack"></i> Công nợ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/hopDong">
                    <i class="bi bi-file-earmark-check"></i> Lịch sử hợp tác
                </a>
            </li>
        </ul>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="summary-card">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin" style="font-size: 3rem; opacity: 0.8;"></i>
                        <div class="ms-3">
                            <div class="text-uppercase small opacity-75">Tổng công nợ</div>
                            <div class="summary-number"><?php echo number_format($congNo['tong_cong_no'] ?? 0, 0, ',', '.'); ?>đ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-list-check"></i> Thống kê</h5>
                        <p class="mb-1"><strong>Số dịch vụ đã xác nhận:</strong> <?php echo $congNo['so_dich_vu'] ?? 0; ?></p>
                        <p class="mb-0"><strong>Tổng số dịch vụ:</strong> <?php echo count($dichVuDaXacNhan); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debt List -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách công nợ</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dichVuDaXacNhan)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                        <p class="mt-3">Không có công nợ nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tour</th>
                                    <th>Dịch vụ</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Thời gian xác nhận</th>
                                    <th class="text-end">Số tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $tongTien = 0;
                                foreach ($dichVuDaXacNhan as $dv): 
                                    $tongTien += $dv['gia_tien'] ?? 0;
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($dv['loai_dich_vu']); ?></span>
                                        <br><small><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></small>
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
                                        <?php if ($dv['thoi_gian_xac_nhan']): ?>
                                            <?php echo date('d/m/Y H:i', strtotime($dv['thoi_gian_xac_nhan'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">
                                            <?php echo number_format($dv['gia_tien'] ?? 0, 0, ',', '.'); ?>đ
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Đã xác nhận</span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="5" class="text-end">Tổng cộng:</th>
                                    <th class="text-end">
                                        <strong><?php echo number_format($tongTien, 0, ',', '.'); ?>đ</strong>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
