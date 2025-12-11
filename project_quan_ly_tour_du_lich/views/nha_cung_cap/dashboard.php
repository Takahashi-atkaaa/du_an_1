<?php
$pageTitle = 'Dashboard - Nhà cung cấp';
$currentPage = 'dashboard';
ob_start();
?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .stat-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.8;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.75;
    }

    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        display: flex;
        justify-content: space-between;
        align-items: center;
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

<div class="page-header-section">
    <div>
        <h1 style="font-size: 24px; letter-spacing: 1px; margin-bottom: 5px;">
            🏢 Dashboard - <?php echo htmlspecialchars($nhaCungCap['ten_don_vi'] ?? 'Nhà cung cấp'); ?>
        </h1>
    </div>
    <a href="index.php?act=auth/logout" class="btn btn-secondary">
        🚪 Đăng xuất
    </a>
</div>

<!-- Alert Messages -->
<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success">
    ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger">
    ⚠ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<?php 
$currentTab = 'dashboard';
include __DIR__ . '/partials/main_nav.php';
?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Chờ xác nhận</div>
        <div class="stat-number"><?php echo count($dichVuChoXacNhan ?? []); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✓</div>
        <div class="stat-label">Đã xác nhận</div>
        <div class="stat-number"><?php echo count($dichVuDaXacNhan ?? []); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Tổng dịch vụ</div>
        <div class="stat-number"><?php echo count($dichVuList ?? []); ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Công nợ</div>
        <div class="stat-number"><?php echo number_format($tongCongNo ?? 0); ?>đ</div>
    </div>
</div>

<!-- Recent Services -->
<?php if (!empty($dichVuGanDay)): ?>
<div class="card" style="margin-top: 30px;">
    <div style="padding: 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h3 style="font-size: 18px; letter-spacing: 1px;">📋 Dịch vụ gần đây</h3>
    </div>
    <div style="padding: 25px;">
        <!-- List of recent services -->
        <?php foreach(array_slice($dichVuGanDay, 0, 5) as $dv): ?>
        <div style="padding: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight: 600; margin-bottom: 5px;"><?php echo htmlspecialchars($dv['ten_dich_vu'] ?? 'N/A'); ?></div>
                    <small style="color: var(--text-muted);"><?php echo htmlspecialchars($dv['mo_ta'] ?? ''); ?></small>
                </div>
                <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 10px; border-radius: 2px;">
                    <?php echo htmlspecialchars($dv['trang_thai'] ?? 'N/A'); ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
