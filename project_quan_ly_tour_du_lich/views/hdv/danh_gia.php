<?php
$pageTitle = 'Đánh giá & Phản hồi - HDV';
$currentPage = 'danhGia';
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
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>⭐ Đánh giá & Phản hồi</h1>
            <?php if ($tour): ?>
            <p style="color: var(--text-muted); margin-top: 10px;"><?php echo htmlspecialchars($tour['ten_tour']); ?></p>
            <?php endif; ?>
        </div>
        <div>
            <a href="javascript:window.history.back();" class="btn btn-secondary">
                ← Quay lại
            </a>
        </div>
    </div>
</div>

<!-- Info Message -->
<div class="alert alert-info">
    ℹ️ Chức năng đánh giá và phản hồi đang được phát triển.
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
