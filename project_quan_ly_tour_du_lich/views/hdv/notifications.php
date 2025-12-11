<?php
$pageTitle = 'Thông báo - HDV';
$currentPage = 'notifications';
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

    .notification-item {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .notification-item:hover {
        border-color: var(--accent-gold);
        transform: translateX(4px);
    }

    .notification-item.unread {
        border-left-color: #0d6efd;
        background: rgba(13, 110, 253, 0.1);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>🔔 Thông báo</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Tất cả thông báo của bạn</p>
        </div>
        <div>
            <a href="javascript:window.history.back();" class="btn btn-secondary">
                ← Quay lại
            </a>
        </div>
    </div>
</div>

<!-- Notifications List -->
<?php if (!empty($notifications)): ?>
    <?php foreach($notifications as $notif): ?>
    <div class="notification-item <?php echo !$notif['da_xem'] ? 'unread' : ''; ?>">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
            <h5 style="margin: 0; font-size: 18px; color: var(--text-light);">
                <?php echo htmlspecialchars($notif['tieu_de']); ?>
                <?php if (!$notif['da_xem']): ?>
                <span class="badge badge-danger" style="margin-left: 10px;">Mới</span>
                <?php endif; ?>
            </h5>
            <small style="color: var(--text-muted); font-size: 12px;">
                🕐 <?php echo date('d/m/Y H:i', strtotime($notif['ngay_gui'])); ?>
            </small>
        </div>
        <p style="margin: 0; color: var(--text-light); line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($notif['noi_dung'])); ?>
        </p>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Bạn chưa có thông báo nào.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
