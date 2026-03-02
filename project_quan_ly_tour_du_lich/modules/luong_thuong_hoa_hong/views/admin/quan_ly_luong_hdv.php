<?php
$pageTitle = 'Quản Lý Lương & Thưởng HDV';
$currentPage = 'quanLyLuongHDV';
ob_start();
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 20px;
        backdrop-filter: blur(10px);
    }
    
    .stat-card h6 {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 10px;
    }
    
    .stat-card h3 {
        color: var(--accent-gold);
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    
    .report-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 30px;
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
    }
    
    .nav-tabs {
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 30px;
    }
    
    .nav-tabs .nav-link {
        color: var(--text-muted);
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        background: transparent;
        transition: all 0.3s;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--text-light);
        border-bottom-color: var(--accent-gold);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--accent-gold);
        border-bottom-color: var(--accent-gold);
        background: transparent;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        color: var(--text-light);
    }
    
    table thead {
        background: rgba(255, 255, 255, 0.05);
    }
    
    table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: var(--text-light);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }
    
    table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .badge-pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }
    
    .badge-approved {
        background: rgba(40, 167, 69, 0.2);
        color: #28a745;
    }
    
    .badge-paid {
        background: rgba(23, 162, 184, 0.2);
        color: #17a2b8;
    }
    
    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }
    
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.75rem;
    }
    
    .btn-success {
        background: var(--accent-gold);
        color: #000;
    }
    
    .btn-success:hover {
        background: #ffd700;
        transform: translateY(-2px);
    }
    
    .btn-info {
        background: rgba(23, 162, 184, 0.3);
        color: #17a2b8;
        border: 1px solid #17a2b8;
    }
    
    .btn-info:hover {
        background: rgba(23, 162, 184, 0.5);
    }
    
    .btn-danger {
        background: rgba(220, 53, 69, 0.3);
        color: #dc3545;
        border: 1px solid #dc3545;
    }
    
    .btn-danger:hover {
        background: rgba(220, 53, 69, 0.5);
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .currency {
        font-weight: 600;
        color: var(--accent-gold);
    }
    
    .text-end {
        text-align: right;
    }
    
    .text-muted {
        color: var(--text-muted);
        font-size: 0.875rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<div style="padding: 20px; max-width: 1400px; margin: 0 auto;">
    <div class="page-header-section" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin-bottom: 30px;">
        <h1 style="margin: 0; font-size: 2rem; color: var(--text-light);">
            <i class="fas fa-wallet" style="color: var(--accent-gold);"></i> Quản Lý Lương & Thưởng HDV
        </h1>
        <a href="index.php?act=admin/dashboard" class="btn" style="background: rgba(255, 255, 255, 0.1); color: var(--text-light);">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-content" type="button" role="tab">
                <i class="fas fa-briefcase"></i> Lương Tour (<?php echo count($salary_list ?? []); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-content" type="button" role="tab">
                <i class="fas fa-gift"></i> Thưởng (<?php echo count($bonus_list_all ?? $bonus_list ?? []); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-content" type="button" role="tab">
                <i class="fas fa-chart-line"></i> Thống Kê
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Salary Tab -->
        <div class="tab-pane fade show active" id="salary-content" role="tabpanel">
            <div class="report-card">
                <h5 style="color: var(--text-light); margin-bottom: 20px;">Danh Sách Lương Theo Tour</h5>
                
                <?php if (empty($salary_list)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu lương. Vui lòng kiểm tra hoặc nhập dữ liệu mẫu.</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>HDV</th>
                                    <th>Tour</th>
                                    <th class="text-end">Lương Cơ Bản</th>
                                    <th class="text-end">Hoa Hồng</th>
                                    <th class="text-end">Tổng</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
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
                                                        <i class="fas fa-check-circle"></i> Duyệt
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <?php if ($status === 'Approved'): ?>
                                                    <button class="btn btn-sm btn-info" onclick="approvePayment(<?php echo $salary['id']; ?>, 'Paid')">
                                                        <i class="fas fa-credit-card"></i> Thanh Toán
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
            <div class="report-card">
                <h5 style="color: var(--text-light); margin-bottom: 20px;">Danh Sách Thưởng</h5>
                
                <?php $displayBonuses = $bonus_list_all ?? $bonus_list ?? []; ?>
                <?php if (empty($displayBonuses)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu thưởng.</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
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
                                <?php foreach ($displayBonuses as $bonus): ?>
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
                                                $badgeClass = 'badge-danger';
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
                                                        <i class="fas fa-check-circle"></i> Phê Duyệt
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="approveBonus(<?php echo $bonus['id']; ?>, 'TuChoi')">
                                                        <i class="fas fa-times-circle"></i> Từ Chối
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
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <h6>Tổng Lương Đang Chờ</h6>
                    <h3><?php echo number_format($stats['pending_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                </div>
                <div class="stat-card">
                    <h6>Tổng Lương Đã Duyệt</h6>
                    <h3><?php echo number_format($stats['approved_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                </div>
                <div class="stat-card">
                    <h6>Tổng Lương Đã Thanh Toán</h6>
                    <h3><?php echo number_format($stats['paid_salary'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                </div>
                <div class="stat-card">
                    <h6>Tổng Thưởng Chờ Duyệt</h6>
                    <h3><?php echo number_format($stats['pending_bonus'] ?? 0, 0, ',', '.'); ?> ₫</h3>
                </div>
            </div>

            <div class="report-card">
                <h5 style="color: var(--text-light); margin-bottom: 20px;">Thống Kê Theo HDV</h5>
                
                <?php if (empty($summary_list)): ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có dữ liệu.</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table>
                            <thead>
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

<script>
    // Bootstrap tabs fallback (nếu không có Bootstrap JS)
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.nav-link');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all buttons and panes
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('show', 'active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding pane
                const targetId = this.getAttribute('data-bs-target') || this.getAttribute('href');
                if (targetId) {
                    const targetPane = document.querySelector(targetId);
                    if (targetPane) {
                        targetPane.classList.add('show', 'active');
                    }
                }
            });
        });
    });
    
    function approvePayment(salaryId, status) {
        if (confirm('Bạn có chắc chắn muốn ' + (status === 'Approved' ? 'duyệt' : 'thanh toán') + ' lương này?')) {
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

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layouts/aventura.php';
?>
