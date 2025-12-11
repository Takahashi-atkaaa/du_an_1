<?php
$pageTitle = 'Danh sách Tour';
$currentPage = 'tours';
ob_start();
?>

<style>
    .tours-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    .tour-card {
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
        padding: 25px;
    }

    .tour-card-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 15px;
        letter-spacing: 0.5px;
    }

    .tour-card-text {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .tour-price {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent-gold);
        margin-bottom: 20px;
    }

    .page-header-section {
        margin-bottom: 40px;
    }

    .page-header-section h1 {
        font-size: 28px;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }

    .page-header-section p {
        color: var(--text-muted);
        font-size: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<div class="page-header-section">
    <h1>🌍 Danh sách Tour</h1>
    <p>Khám phá các tour du lịch hấp dẫn</p>
</div>

<?php if (isset($tours) && !empty($tours)): ?>
    <div class="tours-grid">
        <?php foreach ($tours as $tour): ?>
            <div class="tour-card">
                <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=600&q=80'); ?>" 
                     alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>">
                <div class="tour-card-body">
                    <h3 class="tour-card-title"><?php echo htmlspecialchars($tour['ten_tour']); ?></h3>
                    <p class="tour-card-text">
                        <?php 
                        $moTa = $tour['mo_ta'] ?? $tour['mo_ta_ngan'] ?? '';
                        $moTaRutGon = mb_strlen($moTa) > 120 ? mb_substr($moTa, 0, 120) . '...' : $moTa;
                        echo htmlspecialchars($moTaRutGon); 
                        ?>
                    </p>
                    <div class="tour-price">
                        Giá chỉ từ <?php echo number_format((float)($tour['gia_co_ban'] ?? $tour['gia_tour'] ?? 0)); ?>đ
                    </div>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="index.php?act=khachHang/chiTietTour&id=<?php echo $tour['tour_id']; ?>" 
                           class="btn btn-primary" style="flex: 1; min-width: 120px;">
                            Xem chi tiết
                        </a>
                        <a href="index.php?act=khachHang/datTour&id=<?php echo $tour['tour_id']; ?>" 
                           class="btn btn-secondary">
                            Đặt tour
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="card" style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
        <div class="empty-state-icon">📭</div>
        <h4 style="margin-bottom: 15px;">Chưa có tour nào</h4>
        <p>Hiện tại chưa có tour nào được đăng tải.</p>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
