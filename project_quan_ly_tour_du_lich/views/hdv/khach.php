<?php
$pageTitle = 'Danh sách Khách - HDV';
$currentPage = 'khach';
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
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-ChuaCheckIn {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-DaCheckIn {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .status-DaCheckOut {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    .card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .form-group select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 12px 10px;
        font-size: 13px;
        border-radius: 2px;
        width: 100%;
        font-family: inherit;
    }

    .form-group select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
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
        font-size: 12px;
        font-weight: 600;
    }

    .badge-primary {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.3);
    }
</style>
<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>👥 Danh sách Khách</h1>
            <?php if ($tour): ?>
            <p style="color: var(--text-muted); margin-top: 10px;"><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></p>
            <?php endif; ?>
        </div>
        <div>
            <a href="javascript:window.history.back();" class="btn btn-secondary">
                ← Quay lại
            </a>
        </div>
    </div>
</div>

<!-- Tour Selector -->
<?php if (empty($tour)): ?>
<div class="card">
    <h5 style="margin-bottom: 20px; color: var(--accent-gold);">Chọn tour để xem danh sách khách</h5>
    <div class="form-group">
        <select onchange="if(this.value) window.location.href='index.php?act=hdv/khach&tour_id=' + this.value" class="form-group select">
            <option value="">-- Chọn tour --</option>
            <?php foreach($tours_list as $t): ?>
            <option value="<?php echo $t['id']; ?>">
                <?php echo htmlspecialchars($t['ten_tour'] ?? ''); ?> 
                (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'] ?? 'now')); ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php else: ?>

<!-- Tour Info -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h5 style="margin-bottom: 10px; font-size: 18px;"><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h5>
            <div style="color: var(--text-muted); font-size: 13px;">
                📅 <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'] ?? 'now')); ?>
                - <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'] ?? 'now')); ?>
            </div>
        </div>
        <div>
            <span class="badge badge-primary" style="font-size: 14px; padding: 8px 16px;">
                👥 <?php echo count($khach_list); ?> khách
            </span>
        </div>
    </div>
</div>

<!-- Customer List -->
<?php if (!empty($khach_list)): ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ tên</th>
                    <th>CMND/Passport</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Quốc tịch</th>
                    <th>Liên hệ</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Booking ID</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($khach_list as $index => $khach): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong>
                    </td>
                    <td>
                        <?php if (!empty($khach['so_cmnd'])): ?>
                            CMND: <?php echo htmlspecialchars($khach['so_cmnd'] ?? ''); ?><br>
                        <?php endif; ?>
                        <?php if (!empty($khach['so_passport'])): ?>
                            Passport: <?php echo htmlspecialchars($khach['so_passport'] ?? ''); ?>
                        <?php endif; ?>
                        <?php if (empty($khach['so_cmnd']) && empty($khach['so_passport'])): ?>
                            <span style="color: var(--text-muted);">Chưa cập nhật</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo !empty($khach['ngay_sinh']) ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : 'N/A'; ?>
                    </td>
                    <td>
                        <?php 
                        $gioiTinhLabels = ['Nam' => 'Nam', 'Nu' => 'Nữ', 'Khac' => 'Khác'];
                        echo $gioiTinhLabels[$khach['gioi_tinh']] ?? $khach['gioi_tinh'] ?? 'N/A';
                        ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($khach['quoc_tich'] ?? 'Việt Nam'); ?>
                    </td>
                    <td>
                        <?php if (!empty($khach['email'])): ?>
                            <small style="color: var(--text-muted);">✉ <?php echo htmlspecialchars($khach['email'] ?? ''); ?></small><br>
                        <?php endif; ?>
                        <?php if (!empty($khach['so_dien_thoai'])): ?>
                            <small style="color: var(--text-muted);">📞 <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?></small>
                        <?php endif; ?>
                        <?php if (empty($khach['email']) && empty($khach['so_dien_thoai'])): ?>
                            <span style="color: var(--text-muted);">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo !empty($khach['dia_chi']) ? htmlspecialchars($khach['dia_chi'] ?? '') : 'N/A'; ?>
                    </td>
                    <td>
                        <?php
                        $trangThaiLabels = [
                            'ChuaCheckIn' => 'Chưa check-in',
                            'DaCheckIn' => 'Đã check-in',
                            'DaCheckOut' => 'Đã check-out'
                        ];
                        $trangThai = $khach['trang_thai'] ?? 'ChuaCheckIn';
                        ?>
                        <span class="status-badge status-<?php echo $trangThai; ?>">
                            <?php echo $trangThaiLabels[$trangThai] ?? $trangThai; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($khach['booking_id'])): ?>
                            <span style="font-family: monospace; font-weight: 600; color: var(--accent-gold);">
                                #<?php echo $khach['booking_id']; ?>
                            </span>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div style="padding: 15px 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); color: var(--text-muted); font-size: 12px;">
            Tổng số: <strong><?php echo count($khach_list); ?></strong> khách
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Chưa có khách nào trong danh sách. Vui lòng thêm khách vào lịch khởi hành này.
    </div>
<?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
