<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lương & Thưởng - <?php echo htmlspecialchars($hdv_info['ho_ten'] ?? $_SESSION['user_name'] ?? 'HDV'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        
        .stat-card.salary::before {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .stat-card.commission::before {
            background: linear-gradient(90deg, #f093fb, #f5576c);
        }
        
        .stat-card.bonus::before {
            background: linear-gradient(90deg, #4facfe, #00f2fe);
        }
        
        .stat-card.total::before {
            background: linear-gradient(90deg, #43e97b, #38f9d7);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        
        .stat-icon.salary {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
        }
        
        .stat-icon.commission {
            background: rgba(240, 147, 251, 0.1);
            color: #f5576c;
        }
        
        .stat-icon.bonus {
            background: rgba(79, 172, 254, 0.1);
            color: #4facfe;
        }
        
        .stat-icon.total {
            background: rgba(67, 233, 123, 0.1);
            color: #43e97b;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        
        .content-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        }
        
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        
        .nav-tabs .nav-link {
            color: #718096;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1.5rem;
            background: transparent;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: transparent;
            font-weight: 600;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background: #f7fafc;
        }
        
        .table th {
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
        }
        
        .table td {
            padding: 1rem;
            color: #4a5568;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table tbody tr:hover {
            background: #f7fafc;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-paid {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .currency {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #718096;
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: #cbd5e0;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="bi bi-wallet2"></i> Lương & Thưởng
                    </h4>
                    <p class="mb-0 opacity-75">Xem lương, thưởng và hoa hồng của bạn</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="index.php?act=hdv/dashboard" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card salary">
                    <div class="stat-icon salary">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-label">Lương Cơ Bản</div>
                    <div class="stat-value">
                        <?php echo number_format($summary['base_salary'] ?? 0, 0, ',', '.'); ?> ₫
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card commission">
                    <div class="stat-icon commission">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-label">Hoa Hồng</div>
                    <div class="stat-value">
                        <?php echo number_format($summary['commission'] ?? 0, 0, ',', '.'); ?> ₫
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card bonus">
                    <div class="stat-icon bonus">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="stat-label">Thưởng</div>
                    <div class="stat-value">
                        <?php echo number_format($summary['total_bonus'] ?? 0, 0, ',', '.'); ?> ₫
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card total">
                    <div class="stat-icon total">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="stat-label">Tổng Cộng</div>
                    <div class="stat-value">
                        <?php echo number_format($summary['grand_total'] ?? 0, 0, ',', '.'); ?> ₫
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="content-card">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-content" type="button" role="tab">
                        <i class="bi bi-briefcase"></i> Lương Theo Tour
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-content" type="button" role="tab">
                        <i class="bi bi-star"></i> Danh Sách Thưởng
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4">
                <!-- Salary Tab -->
                <div class="tab-pane fade show active" id="salary-content" role="tabpanel">
                    <?php if (!empty($salary_by_tour)): ?>
                        <div class="table-responsive">
                            <table class="table">
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
                                                <strong><?php echo htmlspecialchars($item['ten_tour'] ?? 'N/A'); ?></strong>
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
                            <table class="table">
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
                                                <strong>
                                                    <?php 
                                                    $bonusType = $bonus['bonus_type'] ?? 'KhongXacDinh';
                                                    echo htmlspecialchars($bonusType); 
                                                    ?>
                                                </strong>
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
                                                    $badgeClass = 'badge-danger';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap tabs fallback
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.nav-link[data-bs-toggle="tab"]');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all buttons and panes
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active');
                    });
                    tabPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    // Show corresponding pane
                    const targetId = this.getAttribute('data-bs-target');
                    if (targetId) {
                        const targetPane = document.querySelector(targetId);
                        if (targetPane) {
                            targetPane.classList.add('show', 'active');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
