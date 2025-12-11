<?php
$pageTitle = 'Công nợ - Nhà cung cấp';
$currentPage = 'congNo';
ob_start();
?>

<style>
    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .summary-card {
        background: rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.3);
        border-left: 4px solid rgba(102, 126, 234, 0.8);
        border-radius: 2px;
        padding: 30px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        border-color: var(--accent-gold);
    }

    .summary-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 10px 0;
    }

    .table-wrapper {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: rgba(212, 175, 55, 0.1);
    }

    .table th {
        padding: 15px;
        text-align: left;
        font-size: 12px;
        letter-spacing: 1px;
        color: var(--accent-gold);
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-light);
        font-size: 13px;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .table tfoot {
        background: rgba(212, 175, 55, 0.1);
    }

    .table tfoot th {
        color: var(--accent-gold);
        font-weight: 700;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-info {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
    }

    .badge-success {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-success {
        background: rgba(25, 135, 84, 0.1);
        border-color: rgba(25, 135, 84, 0.3);
        color: #198754;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #dc3545;
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>💰 Quản lý Công nợ</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Theo dõi công nợ từ các dịch vụ đã xác nhận</p>
        </div>
        <div>
            <a href="index.php?act=nhaCungCap/dashboard" class="btn btn-secondary">
                ← Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ⚠ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php 
    $currentTab = 'congNo';
    include __DIR__ . '/partials/main_nav.php';
?>

<!-- Summary -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px;">
    <div class="summary-card">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 3rem; opacity: 0.8;">💰</div>
            <div>
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Tổng công nợ</div>
                <div class="summary-number"><?php echo number_format($congNo['tong_cong_no'] ?? 0, 0, ',', '.'); ?>đ</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div style="padding: 25px;">
            <h5 style="margin-bottom: 20px; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📊 Thống kê</h5>
            <div style="margin-bottom: 10px;">
                <strong style="color: var(--text-light);">Số dịch vụ đã xác nhận:</strong>
                <span style="color: var(--accent-gold); font-size: 18px; font-weight: 600; margin-left: 10px;"><?php echo $congNo['so_dich_vu'] ?? 0; ?></span>
            </div>
            <div>
                <strong style="color: var(--text-light);">Tổng số dịch vụ:</strong>
                <span style="color: var(--text-light); font-size: 18px; font-weight: 600; margin-left: 10px;"><?php echo count($dichVuDaXacNhan); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Debt List -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h5 style="margin: 0; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📋 Danh sách công nợ</h5>
    </div>
    <?php if (empty($dichVuDaXacNhan)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Không có công nợ nào</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Dịch vụ</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Thời gian xác nhận</th>
                    <th style="text-align: right;">Số tiền</th>
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
                            <strong style="color: var(--text-light);"><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo htmlspecialchars($dv['loai_dich_vu']); ?></span>
                            <br><small style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></small>
                        </td>
                        <td>
                            <?php if ($dv['ngay_bat_dau']): ?>
                                <?php echo date('d/m/Y', strtotime($dv['ngay_bat_dau'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dv['ngay_ket_thuc']): ?>
                                <?php echo date('d/m/Y', strtotime($dv['ngay_ket_thuc'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($dv['thoi_gian_xac_nhan']): ?>
                                <?php echo date('d/m/Y H:i', strtotime($dv['thoi_gian_xac_nhan'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <strong style="color: #198754; font-size: 15px;">
                                <?php echo number_format($dv['gia_tien'] ?? 0, 0, ',', '.'); ?>đ
                            </strong>
                        </td>
                        <td>
                            <span class="badge badge-success">Đã xác nhận</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right; color: var(--accent-gold);">Tổng cộng:</th>
                    <th style="text-align: right; color: var(--accent-gold); font-size: 18px;">
                        <strong><?php echo number_format($tongTien, 0, ',', '.'); ?>đ</strong>
                    </th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
