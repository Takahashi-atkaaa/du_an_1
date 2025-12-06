<?php
/**
 * ADMIN PANEL: Quản Lý Lương, Hoa Hồng & Thưởng HDV
 * File: views/admin/quan_ly_luong_hdv.php
 */
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Lương HDV - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        
        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            padding: 1.5rem;
            margin-bottom: 2rem;
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
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .currency {
            font-weight: 600;
            color: var(--primary);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="header">
            <h1 class="h2 mb-0">
                <i class="bi bi-wallet2 me-2"></i>Quản Lý Lương & Thưởng HDV
            </h1>
            <small class="opacity-75">Duyệt lương, thưởng và thanh toán cho hướng dẫn viên</small>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-content" type="button" role="tab">
                    <i class="bi bi-briefcase me-2"></i>Lương Tour (<?php echo count($salary_list ?? []); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-content" type="button" role="tab">
                    <i class="bi bi-gift me-2"></i>Thưởng (<?php echo count($bonus_list ?? []); ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-content" type="button" role="tab">
                    <i class="bi bi-graph-up me-2"></i>Thống Kê
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Salary Tab -->
            <div class="tab-pane fade show active" id="salary-content" role="tabpanel">
                <div class="table-container">
                    <h5 class="mb-3">Danh Sách Lương Theo Tour</h5>
                    
                    <?php if (empty($salary_list)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chưa có dữ liệu lương. Vui lòng kiểm tra hoặc nhập dữ liệu mẫu.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>HDV</th>
                                        <th>Tour</th>
                                        <th class="text-end">Lương Cơ Bản</th>
                                        <th class="text-end">Hoa Hồng</th>
                                        <th class="text-end">Tổng</th>
                                        <th>Trạng Thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salary_list as $salary): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($salary['ho_ten'] ?? 'N/A'); ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?php echo $salary['nhan_su_id']; ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($salary['ten_tour'] ?? 'N/A'); ?></td>
                                            <td class="text-end currency">
                                                <?php echo number_format($salary['base_salary'] ?? 0, 0, ',', '.'); ?> ₫
                                            </td>
                                            <td class="text-end currency">
                                                <?php echo number_format($salary['commission_amount'] ?? 0, 0, ',', '.'); ?> ₫
                                                <br>
                                                <small class="text-muted">(<?php echo number_format($salary['commission_percentage'] ?? 0, 1); ?>%)</small>
                                            </td>
                                            <td class="text-end currency">
                                                <strong><?php echo number_format($salary['total_amount'] ?? 0, 0, ',', '.'); ?> ₫</strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $status = $salary['payment_status'] ?? 'Pending';
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
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($status === 'Pending'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="approvePayment(<?php echo $salary['id']; ?>, 'Approved')">
                                                            <i class="bi bi-check-circle"></i> Duyệt
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($status === 'Approved'): ?>
                                                        <button class="btn btn-sm btn-info" onclick="approvePayment(<?php echo $salary['id']; ?>, 'Paid')">
                                                            <i class="bi bi-credit-card"></i> Thanh Toán
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bonus Tab -->
            <div class="tab-pane fade" id="bonus-content" role="tabpanel">
                <div class="table-container">
                    <h5 class="mb-3">Danh Sách Thưởng Chờ Phê Duyệt</h5>
                    
                    <?php if (empty($bonus_list)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chưa có thưởng chờ phê duyệt.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>HDV</th>
                                        <th>Loại Thưởng</th>
                                        <th>Lý Do</th>
                                        <th class="text-end">Số Tiền</th>
                                        <th>Ngày Thưởng</th>
                                        <th>Trạng Thái</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bonus_list as $bonus): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($bonus['ho_ten'] ?? 'N/A'); ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?php echo $bonus['nhan_su_id']; ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($bonus['bonus_type'] ?? 'N/A'); ?></td>
                                            <td><?php echo htmlspecialchars($bonus['reason'] ?? '-'); ?></td>
                                            <td class="text-end currency">
                                                <?php echo number_format($bonus['amount'] ?? 0, 0, ',', '.'); ?> ₫
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($bonus['award_date'])); ?></td>
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
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($approvalStatus === 'ChoPheDuyet'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="approveBonus(<?php echo $bonus['id']; ?>, 'DuyetPhep')">
                                                            <i class="bi bi-check-circle"></i> Phê Duyệt
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="approveBonus(<?php echo $bonus['id']; ?>, 'TuChoi')">
                                                            <i class="bi bi-x-circle"></i> Từ Chối
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div class="tab-pane fade" id="stats-content" role="tabpanel">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="table-container text-center">
                            <h6 class="text-muted mb-2">Tổng Lương Đang Chờ</h6>
                            <h3 class="currency"><?php echo number_format($stats['pending_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="table-container text-center">
                            <h6 class="text-muted mb-2">Tổng Lương Đã Duyệt</h6>
                            <h3 class="currency"><?php echo number_format($stats['approved_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="table-container text-center">
                            <h6 class="text-muted mb-2">Tổng Lương Đã Thanh Toán</h6>
                            <h3 class="currency"><?php echo number_format($stats['paid_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="table-container text-center">
                            <h6 class="text-muted mb-2">Tổng Thưởng Chờ Duyệt</h6>
                            <h3 class="currency"><?php echo number_format($stats['pending_bonus'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <h5 class="mb-3">Thống Kê Theo HDV</h5>
                    
                    <?php if (empty($summary_list)): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Chưa có dữ liệu.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>HDV</th>
                                        <th class="text-end">Số Tour</th>
                                        <th class="text-end">Lương Cơ Bản</th>
                                        <th class="text-end">Hoa Hồng</th>
                                        <th class="text-end">Thưởng</th>
                                        <th class="text-end">TỔNG CỘNG</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($summary_list as $summary): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($summary['ho_ten']); ?></strong></td>
                                            <td class="text-end"><?php echo $summary['so_tour']; ?></td>
                                            <td class="text-end currency"><?php echo number_format($summary['tong_luong_co_ban'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                            <td class="text-end currency"><?php echo number_format($summary['tong_hoa_hong'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                            <td class="text-end currency"><?php echo number_format($summary['tong_thuong'] ?? 0, 0, ',', '.'); ?> ₫</td>
                                            <td class="text-end currency"><strong><?php echo number_format($summary['grand_total'] ?? 0, 0, ',', '.'); ?> ₫</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function approvePayment(salaryId, status) {
            if (confirm('Bạn có chắc chắn muốn ' + (status === 'Approved' ? 'duyệt' : 'thanh toán') + ' lương này?')) {
                // Gửi request AJAX
                fetch('index.php?act=admin/approveSalary', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        salary_id: salaryId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cập nhật thành công!');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
            }
        }

        function approveBonus(bonusId, status) {
            if (confirm('Bạn có chắc chắn muốn ' + (status === 'DuyetPhep' ? 'phê duyệt' : 'từ chối') + ' thưởng này?')) {
                fetch('index.php?act=admin/approveBonus', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        bonus_id: bonusId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cập nhật thành công!');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
            }
        }
    </script>
</body>
</html>
