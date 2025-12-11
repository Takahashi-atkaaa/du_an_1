<?php
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($nhanSu)) {
    header('Location: index.php?act=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lương, Thưởng & Hoa Hồng - HDV</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .stat-card.salary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card.commission {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stat-card.bonus {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .stat-card.total {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        .stat-card h6 {
            font-size: 0.875rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.5;
        }
        .tab-content {
            padding: 1.5rem 0;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-approved {
            background-color: #28a745;
        }
        .badge-paid {
            background-color: #17a2b8;
        }
        .currency {
            font-weight: 600;
            color: #667eea;
        }
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link:hover {
            border-bottom-color: #667eea;
            color: #667eea;
        }
        .nav-tabs .nav-link.active {
            color: #667eea;
            background-color: transparent;
            border-bottom-color: #667eea;
            font-weight: 600;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        .empty-state p {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h2">
                        <i class="bi bi-wallet2 me-2"></i>Lương, Thưởng & Hoa Hồng
                    </h1>
                    <a href="index.php?act=hdv/dashboard" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card salary">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>Lương Cơ Bản</h6>
                            <div class="stat-value">
                                <?php echo number_format($summary['base_salary'], 0, ',', '.'); ?>
                            </div>
                            <small class="opacity-75">₫</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-cash"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card commission">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>Hoa Hồng</h6>
                            <div class="stat-value">
                                <?php echo number_format($summary['commission'], 0, ',', '.'); ?>
                            </div>
                            <small class="opacity-75">₫</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-percent"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card bonus">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>Thưởng</h6>
                            <div class="stat-value">
                                <?php echo number_format($summary['total_bonus'], 0, ',', '.'); ?>
                            </div>
                            <small class="opacity-75">₫</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-gift"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="stat-card total">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>Tổng Cộng</h6>
                            <div class="stat-value">
                                <?php echo number_format($summary['grand_total'], 0, ',', '.'); ?>
                            </div>
                            <small class="opacity-75">₫</small>
                        </div>
                        <div class="stat-icon">
                            <i class="bi bi-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-content" type="button" role="tab">
                                    <i class="bi bi-briefcase me-2"></i>Lương Theo Tour
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-content" type="button" role="tab">
                                    <i class="bi bi-star me-2"></i>Danh Sách Thưởng
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Salary Tab -->
                            <div class="tab-pane fade show active" id="salary-content" role="tabpanel">
                                <?php if (!empty($salary_by_tour)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Tour</th>
                                                    <th>Ngày Khởi Hành</th>
                                                    <th class="text-end">Doanh Thu Tour</th>
                                                    <th class="text-end">Hoa Hồng (%)</th>
                                                    <th class="text-end">Tiền Hoa Hồng</th>
                                                    <th class="text-end">Lương Cơ Bản</th>
                                                    <th class="text-end">Thưởng</th>
                                                    <th class="text-end">Tổng Cộng</th>
                                                    <th>Trạng Thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($salary_by_tour as $item): ?>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-500"><?php echo htmlspecialchars($item['ten_tour'] ?? 'N/A'); ?></span>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $ngay = $item['ngay_khoi_hanh'] ?? null;
                                                            echo $ngay ? date('d/m/Y', strtotime($ngay)) : 'N/A'; 
                                                            ?>
                                                        </td>
                                                        <td class="text-end currency">
                                                            <?php echo number_format($item['tour_revenue'] ?? 0, 0, ',', '.'); ?> ₫
                                                        </td>
                                                        <td class="text-end">
                                                            <?php echo number_format($item['commission_percentage'] ?? 0, 2, ',', '.'); ?>%
                                                        </td>
                                                        <td class="text-end currency">
                                                            <?php echo number_format($item['commission_amount'] ?? 0, 0, ',', '.'); ?> ₫
                                                        </td>
                                                        <td class="text-end currency">
                                                            <?php echo number_format($item['base_salary'] ?? 0, 0, ',', '.'); ?> ₫
                                                        </td>
                                                        <td class="text-end currency">
                                                            <?php echo number_format($item['bonus_amount'] ?? 0, 0, ',', '.'); ?> ₫
                                                        </td>
                                                        <td class="text-end currency">
                                                            <strong><?php echo number_format($item['total_amount'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $status = $item['payment_status'] ?? 'Pending';
                                                            $badgeClass = 'badge-pending';
                                                            $statusText = 'Chưa Duyệt';
                                                            
                                                            if ($status === 'Approved') {
                                                                $badgeClass = 'badge-approved';
                                                                $statusText = 'Đã Duyệt';
                                                            } elseif ($status === 'Paid') {
                                                                $badgeClass = 'badge-paid';
                                                                $statusText = 'Đã Thanh Toán';
                                                            }
                                                            ?>
                                                            <span class="badge <?php echo $badgeClass; ?>">
                                                                <?php echo $statusText; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Không có dữ liệu lương theo tour</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Bonus Tab -->
                            <div class="tab-pane fade" id="bonus-content" role="tabpanel">
                                <?php if (!empty($bonuses)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Loại Thưởng</th>
                                                    <th>Lý Do</th>
                                                    <th class="text-end">Số Tiền</th>
                                                    <th>Ngày Thưởng</th>
                                                    <th>Trạng Thái Duyệt</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($bonuses as $bonus): ?>
                                                    <tr>
                                                        <td>
                                                            <span class="fw-500">
                                                                <?php 
                                                                $bonusType = $bonus['bonus_type'] ?? 'KhongXacDinh';
                                                                echo htmlspecialchars($bonusType); 
                                                                ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($bonus['reason'] ?? '-'); ?>
                                                        </td>
                                                        <td class="text-end currency">
                                                            <?php echo number_format($bonus['amount'] ?? 0, 0, ',', '.'); ?> ₫
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $awardDate = $bonus['award_date'] ?? null;
                                                            echo $awardDate ? date('d/m/Y', strtotime($awardDate)) : 'N/A'; 
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $approvalStatus = $bonus['approval_status'] ?? 'ChoPheDuyet';
                                                            $badgeClass = 'badge-pending';
                                                            $statusText = 'Chờ Phê Duyệt';
                                                            
                                                            if ($approvalStatus === 'DuyetPhep') {
                                                                $badgeClass = 'badge-approved';
                                                                $statusText = 'Đã Phê Duyệt';
                                                            } elseif ($approvalStatus === 'TuChoi') {
                                                                $badgeClass = 'bg-danger';
                                                                $statusText = 'Từ Chối';
                                                            }
                                                            ?>
                                                            <span class="badge <?php echo $badgeClass; ?>">
                                                                <?php echo $statusText; ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>Không có dữ liệu thưởng</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
