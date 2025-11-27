<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Tài chính - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .stat-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        .stat-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .positive { color: #198754; }
        .negative { color: #dc3545; }
        .neutral { color: #0d6efd; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-graph-up text-primary"></i> Báo cáo Tài chính</h3>
                <p class="text-muted mb-0">Theo dõi thu - chi và lãi/lỗ từng tour</p>
            </div>
            <a href="index.php?act=admin/dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="get" action="index.php" class="row g-3">
                    <input type="hidden" name="act" value="admin/baoCaoTaiChinh">
                    <div class="col-md-3">
                        <label class="form-label small">Từ ngày</label>
                        <input type="date" name="start_date" class="form-control form-control-sm" 
                               value="<?php echo htmlspecialchars(isset($startDate) ? $startDate : ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Đến ngày</label>
                        <input type="date" name="end_date" class="form-control form-control-sm" 
                               value="<?php echo htmlspecialchars(isset($endDate) ? $endDate : ''); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Tour</label>
                        <select name="tour_id" class="form-select form-select-sm">
                            <option value="0">Tất cả tour</option>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['tour_id']; ?>" 
                                        <?php echo ($tourId == $tour['tour_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tour['ten_tour']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Tổng Thu</div>
                        <div class="stat-value positive">
                            <?php echo number_format($thongKeTongHop['tong_thu'], 0, ',', '.'); ?>đ
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Tổng Chi</div>
                        <div class="stat-value negative">
                            <?php echo number_format($thongKeTongHop['tong_chi'], 0, ',', '.'); ?>đ
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Lãi/Lỗ</div>
                        <div class="stat-value <?php echo $thongKeTongHop['lai_lo'] >= 0 ? 'positive' : 'negative'; ?>">
                            <?php echo number_format($thongKeTongHop['lai_lo'], 0, ',', '.'); ?>đ
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo tour -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Thống kê theo Tour</h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($thongKeTheoTour)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tour</th>
                                    <th class="text-end">Tổng Thu</th>
                                    <th class="text-end">Tổng Chi</th>
                                    <th class="text-end">Lãi/Lỗ</th>
                                    <th class="text-center">Số GD</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($thongKeTheoTour as $tk): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($tk['ten_tour']); ?></strong>
                                    </td>
                                    <td class="text-end positive">
                                        <?php echo number_format($tk['tong_thu'], 0, ',', '.'); ?>đ
                                    </td>
                                    <td class="text-end negative">
                                        <?php echo number_format($tk['tong_chi'], 0, ',', '.'); ?>đ
                                    </td>
                                    <td class="text-end <?php echo $tk['lai_lo'] >= 0 ? 'positive' : 'negative'; ?>">
                                        <strong><?php echo number_format($tk['lai_lo'], 0, ',', '.'); ?>đ</strong>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?php echo $tk['so_giao_dich']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?act=admin/baoCaoTaiChinh&tour_id=<?php echo $tk['tour_id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-1"></i>
                        <p class="mt-3">Chưa có dữ liệu giao dịch</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chi tiết giao dịch (nếu chọn tour) -->
        <?php if ($tourId > 0 && !empty($giaoDichList)): ?>
        <?php 
        $selectedTour = null;
        foreach ($tours as $t) {
            if ($t['tour_id'] == $tourId) {
                $selectedTour = $t;
                break;
            }
        }
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Chi tiết giao dịch - 
                    <?php echo htmlspecialchars($selectedTour['ten_tour'] ?? 'N/A'); ?>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Loại</th>
                                <th class="text-end">Số tiền</th>
                                <th>Mô tả</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($giaoDichList as $gd): ?>
                            <tr>
                                <td><?php echo $gd['ngay_giao_dich'] ? date('d/m/Y', strtotime($gd['ngay_giao_dich'])) : 'N/A'; ?></td>
                                <td>
                                    <?php if ($gd['loai'] === 'Thu'): ?>
                                        <span class="badge bg-success">Thu</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Chi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end <?php echo $gd['loai'] === 'Thu' ? 'positive' : 'negative'; ?>">
                                    <strong><?php echo number_format($gd['so_tien'], 0, ',', '.'); ?>đ</strong>
                                </td>
                                <td><?php echo htmlspecialchars($gd['mo_ta'] ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
