<?php
$pageTitle = 'Lịch sử hợp tác - Nhà cung cấp';
$currentPage = 'hopDong';
ob_start();

$statusMap = [
    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info']
];

$loaiDichVuMap = [
    'Xe' => 'Xe',
    'KhachSan' => 'Khách sạn',
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
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>📄 Lịch sử hợp tác</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Xem lại lịch sử các dịch vụ đã hợp tác</p>
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
    $currentTab = 'hopDong';
    include __DIR__ . '/partials/main_nav.php';
?>

<!-- History List -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h5 style="margin: 0; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📋 Lịch sử hợp tác</h5>
    </div>
    <?php if (empty($lichSu)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Chưa có lịch sử hợp tác</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Loại dịch vụ</th>
                    <th>Tên dịch vụ</th>
                    <th>Số lượng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Giá tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lichSu as $ls): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--text-light);"><?php echo htmlspecialchars($ls['ten_tour'] ?? 'N/A'); ?></strong>
                            <?php if ($ls['so_booking']): ?>
                                <br><small style="color: var(--text-muted); font-size: 11px;">👥 <?php echo $ls['so_booking']; ?> booking</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo $loaiDichVuMap[$ls['loai_dich_vu']] ?? $ls['loai_dich_vu']; ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($ls['ten_dich_vu']); ?></td>
                        <td>
                            <?php echo $ls['so_luong']; ?>
                            <?php if ($ls['don_vi']): ?>
                                <small style="color: var(--text-muted); font-size: 11px;"><?php echo $ls['don_vi']; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ls['ngay_bat_dau']): ?>
                                <?php echo date('d/m/Y', strtotime($ls['ngay_bat_dau'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ls['ngay_ket_thuc']): ?>
                                <?php echo date('d/m/Y', strtotime($ls['ngay_ket_thuc'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ls['gia_tien']): ?>
                                <strong style="color: #198754; font-size: 15px;">
                                    <?php echo number_format($ls['gia_tien'], 0, ',', '.'); ?>đ
                                </strong>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">Chưa có giá</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $status = $statusMap[$ls['trang_thai']] ?? ['text' => $ls['trang_thai'], 'class' => 'secondary']; ?>
                            <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['text']; ?></span>
                        </td>
                        <td>
                            <?php if ($ls['created_at']): ?>
                                <?php echo date('d/m/Y H:i', strtotime($ls['created_at'])); ?>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
