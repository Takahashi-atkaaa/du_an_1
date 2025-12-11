<?php
$pageTitle = 'Chi tiết dịch vụ - Nhà cung cấp';
$currentPage = 'chiTietDichVu';
ob_start();

$statusMap = [
    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info'],
    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
];

$loaiDichVuMap = [
    'Xe' => 'Xe',
    'KhachSan' => 'Khách sạn',
    'Ve' => 'Vé',
    'VeMayBay' => 'Vé máy bay',
    'NhaHang' => 'Nhà hàng',
    'DiemThamQuan' => 'Điểm tham quan',
    'Visa' => 'Visa',
    'BaoHiem' => 'Bảo hiểm',
    'Khac' => 'Khác'
];
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

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .info-row {
        display: grid;
        grid-template-columns: 150px 1fr;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: var(--text-light);
        font-size: 14px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-warning {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .badge-success {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .badge-secondary {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    .badge-info {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
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

    @media (max-width: 768px) {
        .info-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>ℹ️ Chi tiết dịch vụ</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Thông tin chi tiết về dịch vụ</p>
        </div>
        <div>
            <a href="index.php?act=nhaCungCap/baoGia" class="btn btn-secondary">
                ← Quay lại
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
    $currentTab = 'baoGia';
    include __DIR__ . '/partials/main_nav.php';
?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <!-- Service Info -->
    <div class="info-card">
        <h5 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">📋 Thông tin dịch vụ</h5>
        
        <div class="info-row">
            <div class="info-label">Tên dịch vụ:</div>
            <div class="info-value"><?php echo htmlspecialchars($dichVu['ten_dich_vu']); ?></div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Loại dịch vụ:</div>
            <div class="info-value">
                <span class="badge badge-info">
                    <?php echo $loaiDichVuMap[$dichVu['loai_dich_vu']] ?? $dichVu['loai_dich_vu']; ?>
                </span>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Số lượng:</div>
            <div class="info-value">
                <?php echo $dichVu['so_luong']; ?>
                <?php if ($dichVu['don_vi']): ?>
                    <span style="color: var(--text-muted);"><?php echo htmlspecialchars($dichVu['don_vi']); ?></span>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($dichVu['ngay_bat_dau'] || $dichVu['ngay_ket_thuc']): ?>
        <div class="info-row">
            <div class="info-label">Thời gian:</div>
            <div class="info-value">
                <?php if ($dichVu['ngay_bat_dau']): ?>
                    <div>📅 Bắt đầu: <?php echo date('d/m/Y', strtotime($dichVu['ngay_bat_dau'])); ?>
                        <?php if ($dichVu['gio_bat_dau']): ?>
                            <?php echo date('H:i', strtotime($dichVu['gio_bat_dau'])); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($dichVu['ngay_ket_thuc']): ?>
                    <div>📅 Kết thúc: <?php echo date('d/m/Y', strtotime($dichVu['ngay_ket_thuc'])); ?>
                        <?php if ($dichVu['gio_ket_thuc']): ?>
                            <?php echo date('H:i', strtotime($dichVu['gio_ket_thuc'])); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($dichVu['dia_diem']): ?>
        <div class="info-row">
            <div class="info-label">Địa điểm:</div>
            <div class="info-value">📍 <?php echo htmlspecialchars($dichVu['dia_diem']); ?></div>
        </div>
        <?php endif; ?>
        
        <div class="info-row">
            <div class="info-label">Giá tiền:</div>
            <div class="info-value">
                <?php if ($dichVu['gia_tien']): ?>
                    <strong style="color: #198754; font-size: 20px;"><?php echo number_format($dichVu['gia_tien'], 0, ',', '.'); ?>đ</strong>
                <?php else: ?>
                    <span style="color: var(--text-muted);">Chưa có giá</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="info-row">
            <div class="info-label">Trạng thái:</div>
            <div class="info-value">
                <?php $status = $statusMap[$dichVu['trang_thai']] ?? ['text' => $dichVu['trang_thai'], 'class' => 'secondary']; ?>
                <span class="badge badge-<?php echo $status['class']; ?>" style="font-size: 13px;">
                    <?php echo $status['text']; ?>
                </span>
                <?php if (!empty($dichVu['thoi_gian_xac_nhan'])): ?>
                    <br><small style="color: var(--text-muted); font-size: 11px; margin-top: 5px; display: block;">
                        Xác nhận lúc: <?php echo date('d/m/Y H:i', strtotime($dichVu['thoi_gian_xac_nhan'])); ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($dichVu['ghi_chu']): ?>
        <div class="info-row">
            <div class="info-label">Ghi chú:</div>
            <div class="info-value" style="padding: 15px; background: rgba(255, 255, 255, 0.05); border-radius: 2px; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($dichVu['ghi_chu'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Tour Info & History -->
    <div>
        <div class="info-card">
            <h5 style="margin-bottom: 20px; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📅 Thông tin tour</h5>
            <div style="margin-bottom: 20px;">
                <div class="info-label" style="margin-bottom: 5px;">Tên tour:</div>
                <div style="font-size: 16px; font-weight: 600; color: var(--text-light);">
                    <?php echo htmlspecialchars($dichVu['ten_tour'] ?? 'N/A'); ?>
                </div>
            </div>
            <?php if ($dichVu['ngay_khoi_hanh']): ?>
            <div style="margin-bottom: 20px;">
                <div class="info-label" style="margin-bottom: 5px;">Ngày khởi hành:</div>
                <div style="color: var(--text-light);">📅 <?php echo date('d/m/Y', strtotime($dichVu['ngay_khoi_hanh'])); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($dichVu['ngay_ket_thuc']): ?>
            <div style="margin-bottom: 20px;">
                <div class="info-label" style="margin-bottom: 5px;">Ngày kết thúc:</div>
                <div style="color: var(--text-light);">📅 <?php echo date('d/m/Y', strtotime($dichVu['ngay_ket_thuc'])); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($dichVu['tour_mo_ta']): ?>
            <div>
                <div class="info-label" style="margin-bottom: 5px;">Mô tả tour:</div>
                <div style="color: var(--text-muted); font-size: 12px; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($dichVu['tour_mo_ta'])); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="info-card">
            <h5 style="margin-bottom: 20px; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">🕐 Lịch sử</h5>
            <?php if ($dichVu['created_at']): ?>
            <div style="margin-bottom: 15px;">
                <div class="info-label" style="margin-bottom: 5px;">Tạo lúc:</div>
                <div style="color: var(--text-light);">📅 <?php echo date('d/m/Y H:i', strtotime($dichVu['created_at'])); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($dichVu['updated_at']): ?>
            <div>
                <div class="info-label" style="margin-bottom: 5px;">Cập nhật lúc:</div>
                <div style="color: var(--text-light);">✏️ <?php echo date('d/m/Y H:i', strtotime($dichVu['updated_at'])); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
