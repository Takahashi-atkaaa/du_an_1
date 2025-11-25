<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stats-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .nav-pills .nav-link {
            color: #667eea;
            border-radius: 10px;
            margin-right: 10px;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-building"></i> Dashboard - <?php echo htmlspecialchars($nhaCungCap['ten_don_vi'] ?? 'Nhà cung cấp'); ?>
            </h1>
            <a href="index.php?act=auth/logout" class="btn btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> Đăng xuất
            </a>
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
                <a class="nav-link active" href="index.php?act=nhaCungCap/dashboard">
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

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.8;"></i>
                        <div class="ms-3">
                            <div class="text-uppercase small opacity-75">Chờ xác nhận</div>
                            <div class="stats-number"><?php echo count($dichVuChoXacNhan); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.8;"></i>
                        <div class="ms-3">
                            <div class="text-uppercase small opacity-75">Đã xác nhận</div>
                            <div class="stats-number"><?php echo count($dichVuDaXacNhan); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-coin" style="font-size: 3rem; opacity: 0.8;"></i>
                        <div class="ms-3">
                            <div class="text-uppercase small opacity-75">Tổng công nợ</div>
                            <div class="stats-number"><?php echo number_format($congNo['tong_cong_no'] ?? 0, 0, ',', '.'); ?>đ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-list-check" style="font-size: 3rem; opacity: 0.8;"></i>
                        <div class="ms-3">
                            <div class="text-uppercase small opacity-75">Dịch vụ</div>
                            <div class="stats-number"><?php echo $congNo['so_dich_vu'] ?? 0; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Services -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-bell"></i> Dịch vụ chờ xác nhận</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($dichVuChoXacNhan)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                <p class="mt-3">Không có dịch vụ nào chờ xác nhận</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tour</th>
                                            <th>Dịch vụ</th>
                                            <th>Ngày</th>
                                            <th>Giá</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_slice($dichVuChoXacNhan, 0, 5) as $dv): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></td>
                                            <td>
                                                <span class="badge bg-info"><?php echo htmlspecialchars($dv['loai_dich_vu']); ?></span>
                                                <br><small><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></small>
                                            </td>
                                            <td>
                                                <?php if ($dv['ngay_bat_dau']): ?>
                                                    <?php echo date('d/m/Y', strtotime($dv['ngay_bat_dau'])); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($dv['gia_tien']): ?>
                                                    <?php echo number_format($dv['gia_tien'], 0, ',', '.'); ?>đ
                                                <?php else: ?>
                                                    <span class="text-muted">Chưa có giá</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=ChoXacNhan" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=ChoXacNhan" class="btn btn-outline-primary">
                                    Xem tất cả <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử gần đây</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($lichSu)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                <p class="mt-3 small">Chưa có lịch sử</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach (array_slice($lichSu, 0, 5) as $ls): ?>
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($ls['ten_tour'] ?? 'N/A'); ?></h6>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($ls['ten_dich_vu']); ?>
                                                <br>
                                                <span class="badge bg-<?php echo $ls['trang_thai'] === 'DaXacNhan' ? 'success' : 'warning'; ?>">
                                                    <?php 
                                                    $statusMap = [
                                                        'ChoXacNhan' => 'Chờ xác nhận',
                                                        'DaXacNhan' => 'Đã xác nhận',
                                                        'TuChoi' => 'Từ chối',
                                                        'Huy' => 'Hủy',
                                                        'HoanTat' => 'Hoàn tất'
                                                    ];
                                                    echo $statusMap[$ls['trang_thai']] ?? $ls['trang_thai'];
                                                    ?>
                                                </span>
                                            </small>
                                        </div>
                                        <?php if ($ls['gia_tien']): ?>
                                        <div class="text-end">
                                            <strong class="text-success"><?php echo number_format($ls['gia_tien'], 0, ',', '.'); ?>đ</strong>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="index.php?act=nhaCungCap/hopDong" class="btn btn-sm btn-outline-success">
                                    Xem tất cả <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

