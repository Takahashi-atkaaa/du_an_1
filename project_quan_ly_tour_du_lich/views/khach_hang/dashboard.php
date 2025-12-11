<?php
$pageTitle = 'Trang chủ';
$currentPage = 'dashboard';
ob_start();
?>

<style>
    .hero-banner {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)),
                    url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 40px;
        border-radius: 2px;
        overflow: hidden;
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--text-light);
    }

    .hero-content h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .hero-content p {
        font-size: 18px;
        margin-bottom: 30px;
        color: var(--text-muted);
    }

    .tour-section {
        margin-bottom: 50px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tours-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 20px;
        scrollbar-width: thin;
        scrollbar-color: var(--accent-gold) var(--secondary-dark);
    }

    .tours-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .tours-scroll::-webkit-scrollbar-track {
        background: var(--secondary-dark);
        border-radius: 4px;
    }

    .tours-scroll::-webkit-scrollbar-thumb {
        background: var(--accent-gold);
        border-radius: 4px;
    }

    .tour-card {
        min-width: 320px;
        max-width: 340px;
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .tour-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
    }

    .tour-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .tour-card-body {
        padding: 20px;
    }

    .tour-card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .tour-card-text {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .tour-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--accent-gold);
        margin-bottom: 15px;
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
        text-align: center;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--accent-gold);
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        letter-spacing: 0.5px;
    }

    .review-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 20px;
        backdrop-filter: blur(10px);
    }

    .review-text {
        font-style: italic;
        color: var(--text-light);
        margin-bottom: 15px;
        line-height: 1.6;
    }

    .review-author {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .review-author img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .badge-custom {
        padding: 4px 10px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        margin-right: 5px;
    }

    .badge-info {
        background: rgba(102, 126, 234, 0.2);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .badge-success {
        background: rgba(72, 187, 120, 0.2);
        color: #48bb78;
        border: 1px solid rgba(72, 187, 120, 0.3);
    }
</style>

<!-- Hero Banner -->
<div class="hero-banner">
    <div class="hero-content">
        <h1>Khám phá thế giới cùng AVENTURA</h1>
        <p>Đặt tour dễ dàng, nhận ưu đãi hấp dẫn, trải nghiệm tuyệt vời!</p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="index.php?act=khachHang/danhSachTour" class="btn btn-primary">
                Xem tour hot
            </a>
            <a href="index.php?act=khachHang/yeuCauTour" class="btn btn-secondary">
                Đặt tour theo yêu cầu
            </a>
        </div>
    </div>
</div>

<!-- Statistics -->
<?php if (isset($tongBooking) || isset($bookingChoXacNhan)): ?>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?php echo $tongBooking ?? 0; ?></div>
        <div class="stat-label">Tổng booking</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $bookingChoXacNhan ?? 0; ?></div>
        <div class="stat-label">Chờ xác nhận</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $bookingDaCoc ?? 0; ?></div>
        <div class="stat-label">Đã cọc</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $bookingHoanTat ?? 0; ?></div>
        <div class="stat-label">Hoàn tất</div>
    </div>
</div>
<?php endif; ?>

<!-- Tour trong nước -->
<div class="tour-section">
    <h2 class="section-title">🏞️ Tour trong nước</h2>
    <?php if (!empty($tourTrongNuoc)): ?>
        <div class="tours-scroll">
            <?php foreach ($tourTrongNuoc as $tour): ?>
            <div class="tour-card">
                <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=600&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>">
                <div class="tour-card-body">
                    <h5 class="tour-card-title"><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                    <?php 
                    $moTa = $tour['mo_ta_ngan'] ?? $tour['mo_ta'] ?? '';
                    $moTaRutGon = mb_strlen($moTa) > 80 ? mb_substr($moTa, 0, 80) . '...' : $moTa;
                    $gia = isset($tour['gia_tour']) && $tour['gia_tour'] !== null ? $tour['gia_tour'] : (isset($tour['gia_co_ban']) && $tour['gia_co_ban'] !== null ? $tour['gia_co_ban'] : 0);
                    ?>
                    <p class="tour-card-text"><?php echo htmlspecialchars($moTaRutGon); ?></p>
                    <div class="tour-price">Giá chỉ từ <?php echo number_format((float)$gia); ?>đ</div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="index.php?act=khachHang/thanhToanTour&id=<?php echo $tour['tour_id'] ?? ''; ?>" 
                           class="btn btn-primary" style="flex: 1; min-width: 120px;">
                            Đặt ngay
                        </a>
                        <a href="index.php?act=khachHang/chiTietTour&id=<?php echo $tour['tour_id']; ?>" 
                           class="btn btn-secondary">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card" style="padding: 30px; text-align: center; color: var(--text-muted);">
            Hiện chưa có tour trong nước nào.
        </div>
    <?php endif; ?>
</div>

<!-- Tour quốc tế -->
<div class="tour-section">
    <h2 class="section-title">🌍 Tour quốc tế</h2>
    <?php if (!empty($tourQuocTe)): ?>
        <div class="tours-scroll">
            <?php foreach ($tourQuocTe as $tour): ?>
            <div class="tour-card">
                <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=600&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>">
                <div class="tour-card-body">
                    <h5 class="tour-card-title"><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                    <?php 
                    $moTaQT = $tour['mo_ta_ngan'] ?? $tour['mo_ta'] ?? '';
                    $moTaRutGonQT = mb_strlen($moTaQT) > 80 ? mb_substr($moTaQT, 0, 80) . '...' : $moTaQT;
                    ?>
                    <p class="tour-card-text"><?php echo htmlspecialchars($moTaRutGonQT); ?></p>
                    <div class="tour-price">Giá chỉ từ <?php echo number_format($tour['gia_tour'] ?? $tour['gia_co_ban'] ?? 0); ?>đ</div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="index.php?act=khachHang/thanhToanTour&id=<?php echo $tour['id'] ?? $tour['tour_id']; ?>" 
                           class="btn btn-primary" style="flex: 1; min-width: 120px;">
                            Đặt ngay
                        </a>
                        <a href="index.php?act=khachHang/chiTietTour&id=<?php echo $tour['id'] ?? $tour['tour_id']; ?>" 
                           class="btn btn-secondary">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="card" style="padding: 30px; text-align: center; color: var(--text-muted);">
            Hiện chưa có tour quốc tế nào.
        </div>
    <?php endif; ?>
</div>

<!-- Đánh giá khách hàng -->
<?php if (!empty($danhGiaTot)): ?>
<div class="tour-section">
    <h2 class="section-title">⭐ Đánh giá khách hàng</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
        <?php foreach ($danhGiaTot as $dg): ?>
        <div class="review-card">
            <p class="review-text">"<?php echo htmlspecialchars($dg['noi_dung'] ?? $dg['noi_dung'] ?? ''); ?>"</p>
            <div class="review-author">
                <img src="<?php echo htmlspecialchars($dg['anh'] ?? ($dg['anh_dai_dien'] ?? 'https://randomuser.me/api/portraits/men/1.jpg')); ?>" 
                     alt="Avatar">
                <div>
                    <div style="font-weight: 600; margin-bottom: 5px;">
                        <?php echo htmlspecialchars($dg['ten_khach_hang'] ?? $dg['ten'] ?? 'Ẩn danh'); ?>
                    </div>
                    <div>
                        <span class="badge-custom badge-info">
                            <?php echo htmlspecialchars($dg['tieu_chi'] ?? $dg['loai_danh_gia'] ?? ''); ?>
                        </span>
                        <span class="badge-custom badge-success">
                            <?php echo htmlspecialchars($dg['diem'] ?? $dg['diem'] ?? ''); ?>⭐
                        </span>
                    </div>
                </div>
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
