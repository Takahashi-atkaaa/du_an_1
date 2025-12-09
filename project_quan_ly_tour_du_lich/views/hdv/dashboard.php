<?php
$pageTitle = 'Trang chủ HDV';
$currentPage = 'dashboard';
ob_start();
?>

<style>
    .welcome-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .welcome-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-dark);
        font-weight: bold;
        margin-bottom: 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .stat-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
        background: rgba(212, 175, 55, 0.1);
        color: var(--accent-gold);
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .feature-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        text-decoration: none;
        color: var(--text-light);
        transition: all 0.3s;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
        transition: left 0.5s;
    }

    .feature-card:hover::before {
        left: 100%;
    }

    .feature-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
        background: rgba(212, 175, 55, 0.1);
        color: var(--accent-gold);
    }

    .feature-card:hover .feature-icon {
        background: var(--accent-gold);
        color: var(--primary-dark);
    }

    .feature-card h5 {
        font-size: 16px;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .feature-card p {
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .tours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .tour-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 20px;
        backdrop-filter: blur(10px);
        border-left: 3px solid var(--accent-gold);
    }

    .tour-status {
        padding: 4px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-upcoming {
        background: rgba(102, 126, 234, 0.2);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .section-title {
        font-size: 18px;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notifications-list {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        backdrop-filter: blur(10px);
    }

    .notification-item {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .badge-new {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        padding: 2px 8px;
        border-radius: 2px;
        font-size: 10px;
        font-weight: 600;
        margin-left: 8px;
    }
</style>

<div class="welcome-section">
    <div class="welcome-avatar">
        <?php echo strtoupper(substr($hdv_info['ho_ten'] ?? 'H', 0, 1)); ?>
    </div>
    <h2>Xin chào, <?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'HDV'); ?>!</h2>
    <p style="color: var(--text-muted); margin-top: 10px;">
        📅 Hôm nay: <?php echo date('d/m/Y'); ?>
        <?php if (!empty($today_tours)): ?>
        | <span style="color: var(--accent-gold);">🚩 Bạn có <?php echo count($today_tours); ?> tour hôm nay</span>
        <?php endif; ?>
    </p>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value"><?php echo $stats['upcoming_tours'] ?? 0; ?></div>
        <div class="stat-label">Tour sắp tới</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">▶</div>
        <div class="stat-value"><?php echo $stats['ongoing_tours'] ?? 0; ?></div>
        <div class="stat-label">Tour đang chạy</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✓</div>
        <div class="stat-value"><?php echo $stats['completed_tours'] ?? 0; ?></div>
        <div class="stat-label">Tour hoàn thành</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value"><?php echo number_format($stats['rating'] ?? 0, 1); ?></div>
        <div class="stat-label">Đánh giá TB</div>
    </div>
</div>

<!-- Quick Actions -->
<h3 class="section-title">⚡ Chức năng chính</h3>
<div class="features-grid">
    <a href="index.php?act=hdv/tours" class="feature-card">
        <div class="feature-icon">🗺️</div>
        <h5>Lịch trình Tour</h5>
        <p>Xem lịch trình và lịch làm việc của bạn</p>
    </a>
    <a href="index.php?act=hdv/checkin" class="feature-card">
        <div class="feature-icon">✓</div>
        <h5>Check-in & Điểm danh</h5>
        <p>Xác nhận và điểm danh khách</p>
    </a>
    <a href="index.php?act=hdv/nhat_ky" class="feature-card">
        <div class="feature-icon">📝</div>
        <h5>Nhật ký Tour</h5>
        <p>Ghi chú hành trình, sự cố, phản hồi</p>
    </a>
    <a href="index.php?act=hdv/yeu_cau_dac_biet" class="feature-card">
        <div class="feature-icon">⚠️</div>
        <h5>Yêu cầu đặc biệt</h5>
        <p>Cập nhật ăn chay, bệnh lý, v.v.</p>
    </a>
    <a href="index.php?act=hdv/phan_hoi" class="feature-card">
        <div class="feature-icon">⭐</div>
        <h5>Đánh giá & Phản hồi</h5>
        <p>Gửi đánh giá tour, dịch vụ</p>
    </a>
</div>

<!-- Upcoming Tours -->
<h3 class="section-title">📅 Tour sắp tới</h3>
<?php if (!empty($upcoming_tours)): ?>
    <div class="tours-grid">
        <?php foreach(array_slice($upcoming_tours, 0, 3) as $tour): ?>
        <div class="tour-card">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <h5 style="margin: 0; flex: 1;"><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                <span class="tour-status status-upcoming">Sắp tới</span>
            </div>
            <div style="color: var(--text-muted); font-size: 12px; line-height: 1.8;">
                <div>📅 <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?></div>
                <div>👥 <?php echo $tour['so_nguoi'] ?? 'N/A'; ?> khách</div>
                <div>📍 <?php echo htmlspecialchars($tour['diem_tap_trung'] ?? 'Chưa xác định'); ?></div>
            </div>
            <div style="margin-top: 15px;">
                <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['tour_id']; ?>" 
                   class="btn btn-secondary btn-sm" style="width: 100%;">
                    Xem chi tiết
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card" style="padding: 30px; text-align: center; color: var(--text-muted);">
        📭 Hiện tại bạn chưa có tour nào sắp tới.
    </div>
<?php endif; ?>

<!-- Recent Notifications -->
<?php if (!empty($recent_notifications)): ?>
<h3 class="section-title" style="margin-top: 40px;">🔔 Thông báo mới</h3>
<div class="notifications-list">
    <?php foreach(array_slice($recent_notifications, 0, 5) as $notif): ?>
    <div class="notification-item">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div style="flex: 1;">
                <h6 style="margin: 0 0 8px 0; font-size: 14px;">
                    <?php echo htmlspecialchars($notif['tieu_de']); ?>
                    <?php if (!$notif['da_xem']): ?>
                    <span class="badge-new">Mới</span>
                    <?php endif; ?>
                </h6>
                <p style="margin: 0 0 8px 0; font-size: 12px; color: var(--text-muted);">
                    <?php echo htmlspecialchars($notif['noi_dung']); ?>
                </p>
                <small style="color: var(--text-muted); font-size: 11px;">
                    🕐 <?php echo date('d/m/Y H:i', strtotime($notif['ngay_gui'])); ?>
                </small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div style="padding: 15px 20px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05);">
        <a href="index.php?act=hdv/notifications" style="color: var(--accent-gold); text-decoration: none; font-size: 12px;">
            Xem tất cả thông báo →
        </a>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
