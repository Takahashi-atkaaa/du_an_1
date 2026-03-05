<?php
$pageTitle = 'Lương, Thưởng & Hoa Hồng';
$currentPage = 'luongThuong';
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
        padding: 25px;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--accent-gold);
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
    
    .stat-card h6 {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 0;
    }
    
    .stat-card .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.2;
        color: var(--accent-gold);
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
        display: flex;
        gap: 0;
    }
    
    .nav-tabs .nav-link {
        color: var(--text-muted);
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        background: transparent;
        transition: all 0.3s;
        cursor: pointer;
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
            <i class="fas fa-wallet" style="color: var(--accent-gold);"></i> Lương, Thưởng & Hoa Hồng
        </h1>
        <a href="index.php?act=hdv/dashboard" class="btn" style="background: rgba(255, 255, 255, 0.1); color: var(--text-light);">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card salary">
            <div style="position: relative;">
                <h6>Lương Cơ Bản</h6>
                <div class="stat-value">
                    <?php echo number_format($summary['base_salary'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <div class="stat-card commission">
            <div style="position: relative;">
                <h6>Hoa Hồng</h6>
                <div class="stat-value">
                    <?php echo number_format($summary['commission'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-icon">
                    <i class="fas fa-percent"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bonus">
            <div style="position: relative;">
                <h6>Thưởng</h6>
                <div class="stat-value">
                    <?php echo number_format($summary['total_bonus'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-icon">
                    <i class="fas fa-gift"></i>
                </div>
            </div>
        </div>

        <div class="stat-card total">
            <div style="position: relative;">
                <h6>Tổng Cộng</h6>
                <div class="stat-value">
                    <?php echo number_format($summary['grand_total'], 0, ',', '.'); ?> ₫
                </div>
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="report-card">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary-content" type="button" role="tab">
                    <i class="fas fa-briefcase"></i> Lương Theo Tour
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus-content" type="button" role="tab">
                    <i class="fas fa-star"></i> Danh Sách Thưởng
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Salary Tab -->
            <div class="tab-pane fade show active" id="salary-content" role="tabpanel">
                <?php if (!empty($salary_by_tour)): ?>
                    <div style="overflow-x: auto; margin-top: 20px;">
                        <table>
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
                        <i class="fas fa-inbox"></i>
                        <p>Không có dữ liệu lương theo tour</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bonus Tab -->
            <div class="tab-pane fade" id="bonus-content" role="tabpanel">
                <?php if (!empty($bonuses)): ?>
                    <div style="overflow-x: auto; margin-top: 20px;">
                        <table>
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
                        <i class="fas fa-inbox"></i>
                        <p>Không có dữ liệu thưởng</p>
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
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../../../views/layouts/aventura.php';
?>
