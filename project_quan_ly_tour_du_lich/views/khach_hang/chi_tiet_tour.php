<?php
$pageTitle = 'Chi tiết Tour';
$currentPage = 'tours';
ob_start();
?>

<style>
    .tour-hero {
        position: relative;
        height: 400px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)),
                    url('<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=1200&q=80'); ?>');
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 40px;
        border-radius: 2px;
        overflow: hidden;
    }

    .tour-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
    }

    .tour-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--text-light);
    }

    .tour-hero-content h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 15px;
        letter-spacing: -1px;
    }

    .tour-hero-content .tour-type {
        font-size: 16px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .tour-hero-content .tour-price {
        font-size: 32px;
        font-weight: 700;
        color: var(--accent-gold);
    }

    .tour-content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .tour-image-section img {
        width: 100%;
        border-radius: 2px;
        margin-bottom: 15px;
    }

    .tour-gallery {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tour-gallery img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 2px;
        border: 2px solid rgba(255, 255, 255, 0.1);
        cursor: pointer;
        transition: all 0.3s;
    }

    .tour-gallery img:hover {
        border-color: var(--accent-gold);
        transform: scale(1.1);
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 30px 0 20px 0;
        letter-spacing: 1px;
        color: var(--accent-gold);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table th {
        font-size: 12px;
        letter-spacing: 1px;
        color: var(--accent-gold);
        font-weight: 600;
    }

    .table td {
        color: var(--text-light);
        font-size: 13px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .tour-content-grid {
            grid-template-columns: 1fr;
        }
        .tour-hero-content h1 {
            font-size: 32px;
        }
    }
</style>

<!-- Tour Hero -->
<div class="tour-hero">
    <div class="tour-hero-content">
        <h1><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tên tour'); ?></h1>
        <div class="tour-type">Loại tour: <strong><?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></strong></div>
        <div class="tour-price">Giá chỉ từ <?php echo number_format($tour['gia_tour'] ?? $tour['gia_co_ban'] ?? 0); ?>đ</div>
    </div>
</div>

<div class="tour-content-grid">
    <!-- Images -->
    <div class="tour-image-section">
        <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=800&q=80'); ?>" 
             alt="Ảnh tour" id="mainImage">
        <?php if (!empty($hinhAnhList)): ?>
        <div class="tour-gallery">
            <?php foreach ($hinhAnhList as $ha): ?>
                <img src="<?php echo htmlspecialchars($ha['url_anh']); ?>" 
                     alt="Hình ảnh tour"
                     onclick="document.getElementById('mainImage').src = this.src">
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Info -->
    <div>
        <div class="info-card">
            <h3 class="section-title" style="margin-top: 0;">📝 Mô tả tour</h3>
            <div style="line-height: 1.8; color: var(--text-light);">
                <?php echo nl2br(htmlspecialchars($tour['mo_ta'] ?? 'Chưa có mô tả.')); ?>
            </div>
        </div>

        <div class="info-card">
            <h3 class="section-title" style="margin-top: 0;">📅 Thông tin khởi hành</h3>
            <?php if (!empty($lichKhoiHanhList)): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ngày khởi hành</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm tập trung</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lichKhoiHanhList as $lk): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($lk['ngay_khoi_hanh'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($lk['ngay_ket_thuc'])); ?></td>
                            <td><?php echo htmlspecialchars($lk['diem_tap_trung']); ?></td>
                            <td>
                                <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 8px; border-radius: 2px; font-size: 11px;">
                                    <?php echo htmlspecialchars($lk['trang_thai']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: var(--text-muted);">Chưa có lịch khởi hành.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($hdvInfo)): ?>
        <div class="info-card">
            <h3 class="section-title" style="margin-top: 0;">👤 Hướng dẫn viên</h3>
            <div style="line-height: 1.8;">
                <div style="font-weight: 600; margin-bottom: 5px;"><?php echo htmlspecialchars($hdvInfo['ho_ten'] ?? ''); ?></div>
                <div style="color: var(--text-muted); font-size: 13px;">
                    📧 <?php echo htmlspecialchars($hdvInfo['email'] ?? ''); ?><br>
                    📞 <?php echo htmlspecialchars($hdvInfo['so_dien_thoai'] ?? ''); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lịch trình chi tiết -->
<h3 class="section-title">🗺️ Lịch trình chi tiết</h3>
<?php if (!empty($lichTrinhList)): ?>
    <div class="info-card">
        <table class="table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Địa điểm</th>
                    <th>Hoạt động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lichTrinhList as $lt): ?>
                <tr>
                    <td><strong>Ngày <?php echo $lt['ngay_thu']; ?></strong></td>
                    <td><?php echo htmlspecialchars($lt['dia_diem']); ?></td>
                    <td><?php echo htmlspecialchars($lt['hoat_dong']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="info-card">
        <p style="color: var(--text-muted);">Chưa cập nhật lịch trình.</p>
    </div>
<?php endif; ?>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="index.php?act=khachHang/datTour&id=<?php echo $tour['tour_id'] ?? ''; ?>" 
       class="btn btn-primary" style="flex: 1; min-width: 200px;">
        📋 Đặt tour ngay
    </a>
    <a href="index.php?act=khachHang/thanhToanTour&id=<?php echo $tour['tour_id'] ?? ''; ?>" 
       class="btn btn-primary" style="flex: 1; min-width: 200px;">
        💳 Đặt & Thanh toán
    </a>
    <a href="index.php?act=khachHang/danhSachTour" 
       class="btn btn-secondary">
        ← Quay lại danh sách
    </a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
