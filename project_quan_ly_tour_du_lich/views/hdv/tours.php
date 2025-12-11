<?php
$pageTitle = 'Lịch trình Tour';
$currentPage = 'tours';
ob_start();
?>

<style>
    .filter-tabs {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 15px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        color: var(--text-light);
        text-decoration: none;
        font-size: 13px;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .filter-tab:hover,
    .filter-tab.active {
        background: var(--accent-gold);
        color: var(--primary-dark);
        border-color: var(--accent-gold);
    }

    .tour-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
        overflow: hidden;
    }

    .tour-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
    }

    .tour-card-header {
        padding: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .tour-card-body {
        padding: 20px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-SapKhoiHanh {
        background: rgba(102, 126, 234, 0.2);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.3);
    }

    .status-DangChay {
        background: rgba(237, 137, 54, 0.2);
        color: #ed8936;
        border: 1px solid rgba(237, 137, 54, 0.3);
    }

    .status-HoanThanh {
        background: rgba(72, 187, 120, 0.2);
        color: #48bb78;
        border: 1px solid rgba(72, 187, 120, 0.3);
    }

    .status-DaHuy {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .tour-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .info-item {
        font-size: 13px;
        color: var(--text-muted);
    }

    .info-item strong {
        color: var(--text-light);
        margin-right: 5px;
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h2 style="font-size: 24px; letter-spacing: 1px;">🗺️ Lịch trình Tour</h2>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="index.php?act=hdv/tours" class="filter-tab <?php echo (!isset($_GET['trang_thai']) || $_GET['trang_thai'] === '') ? 'active' : ''; ?>">
        Tất cả
    </a>
    <a href="index.php?act=hdv/tours&trang_thai=SapKhoiHanh" class="filter-tab <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] === 'SapKhoiHanh') ? 'active' : ''; ?>">
        Sắp khởi hành
    </a>
    <a href="index.php?act=hdv/tours&trang_thai=DangChay" class="filter-tab <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] === 'DangChay') ? 'active' : ''; ?>">
        Đang chạy
    </a>
    <a href="index.php?act=hdv/tours&trang_thai=HoanThanh" class="filter-tab <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] === 'HoanThanh') ? 'active' : ''; ?>">
        Hoàn thành
    </a>
</div>

<!-- Tours List -->
<?php if (!empty($tours)): ?>
    <?php foreach ($tours as $tour): ?>
    <div class="tour-card">
        <div class="tour-card-header">
            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 18px; letter-spacing: 0.5px;">
                    <?php echo htmlspecialchars($tour['ten_tour'] ?? 'N/A'); ?>
                </h4>
                <div style="font-size: 12px; color: var(--text-muted);">
                    Lịch khởi hành ID: #<?php echo htmlspecialchars($tour['id'] ?? 'N/A'); ?>
                </div>
            </div>
            <span class="status-badge status-<?php echo htmlspecialchars($tour['trang_thai'] ?? ''); ?>">
                <?php 
                $statusLabels = [
                    'SapKhoiHanh' => 'Sắp khởi hành',
                    'DangChay' => 'Đang chạy',
                    'HoanThanh' => 'Hoàn thành',
                    'DaHuy' => 'Đã hủy'
                ];
                echo $statusLabels[$tour['trang_thai'] ?? ''] ?? $tour['trang_thai'] ?? 'N/A';
                ?>
            </span>
        </div>
        <div class="tour-card-body">
            <div class="tour-info">
                <div class="info-item">
                    <strong>📅 Ngày khởi hành:</strong>
                    <?php echo !empty($tour['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])) : 'N/A'; ?>
                </div>
                <div class="info-item">
                    <strong>👥 Số người:</strong>
                    <?php echo htmlspecialchars($tour['so_nguoi'] ?? 'N/A'); ?>
                </div>
                <div class="info-item">
                    <strong>📍 Điểm tập trung:</strong>
                    <?php echo htmlspecialchars($tour['diem_tap_trung'] ?? 'Chưa xác định'); ?>
                </div>
                <?php if (!empty($tour['ghi_chu'])): ?>
                <div class="info-item" style="grid-column: 1 / -1;">
                    <strong>📝 Ghi chú:</strong>
                    <?php echo htmlspecialchars($tour['ghi_chu']); ?>
                </div>
                <?php endif; ?>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['tour_id'] ?? ''; ?>" 
                   class="btn btn-primary btn-sm">
                    Xem chi tiết
                </a>
                <a href="index.php?act=hdv/lich_trinh_chi_tiet&id=<?php echo $tour['id'] ?? ''; ?>" 
                   class="btn btn-secondary btn-sm">
                    Lịch trình chi tiết
                </a>
                <?php if (isset($tour['phan_bo_trang_thai']) && $tour['phan_bo_trang_thai'] === 'ChoXacNhan'): ?>
                <a href="index.php?act=hdv/xacNhanPhanBo&id=<?php echo $tour['phan_bo_id'] ?? ''; ?>" 
                   class="btn btn-secondary btn-sm" 
                   style="background: rgba(72, 187, 120, 0.2); color: #48bb78; border-color: rgba(72, 187, 120, 0.3);">
                    Xác nhận phân bổ
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card" style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
        <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">📭</div>
        <h5 style="margin-bottom: 10px;">Chưa có tour nào</h5>
        <p>Hiện tại bạn chưa được phân bổ tour nào.</p>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
