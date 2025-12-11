<?php
$pageTitle = 'Danh sách Khách - HDV';
$currentPage = 'danhSachKhach';
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
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>👥 Danh sách khách trong đoàn</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Xem danh sách khách hàng theo lịch khởi hành</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="index.php?act=hdv/lichLamViec" class="btn btn-secondary btn-sm">← Lịch làm việc</a>
            <a href="index.php?act=hdv/checkInKhach" class="btn btn-primary btn-sm">✓ Điểm danh</a>
        </div>
    </div>
</div>

<?php if (!empty($lichKhoiHanhList)): ?>
    <div class="card" style="margin-bottom: 30px;">
        <div class="form-group">
            <label><strong>Chọn lịch khởi hành:</strong></label>
            <form method="GET" action="index.php" style="display: flex; gap: 10px; align-items: end;">
                <input type="hidden" name="act" value="hdv/danhSachKhach">
                <select name="lich_id" id="lich_id" onchange="this.form.submit()" class="form-group select" style="flex: 1;">
                    <option value="">-- Chọn lịch khởi hành --</option>
                    <?php foreach ($lichKhoiHanhList as $lich): ?>
                        <option value="<?php echo $lich['id']; ?>" <?php echo (isset($selectedLich) && $selectedLich && $selectedLich['id'] == $lich['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                            (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <?php if ($selectedLich): ?>
        <div class="card" style="margin-bottom: 30px;">
            <h3 style="margin: 0 0 15px 0; color: var(--accent-gold);"><?php echo htmlspecialchars($selectedLich['ten_tour'] ?? 'Tour'); ?></h3>
            <div style="color: var(--text-light); line-height: 1.8;">
                <div><strong>Thời gian:</strong> 
                    <?php echo !empty($selectedLich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($selectedLich['ngay_khoi_hanh'])) : 'N/A'; ?>
                    →
                    <?php echo !empty($selectedLich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($selectedLich['ngay_ket_thuc'])) : 'N/A'; ?>
                </div>
                <div><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($selectedLich['diem_tap_trung'] ?? 'Chưa cập nhật'); ?></div>
            </div>
        </div>

        <?php if (!empty($danhSachKhach)): ?>
            <div style="margin-bottom: 15px; color: var(--accent-gold); font-weight: 600;">
                Tổng số khách: <?php echo count($danhSachKhach); ?> người
            </div>
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
                        <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong>
                                </td>
                                <td>
                                    <?php if (!empty($khach['so_cmnd'])): ?>
                                        CMND: <?php echo htmlspecialchars($khach['so_cmnd']); ?><br>
                                    <?php endif; ?>
                                    <?php if (!empty($khach['so_passport'])): ?>
                                        Passport: <?php echo htmlspecialchars($khach['so_passport']); ?>
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
                                        <small style="color: var(--text-muted);">✉ <?php echo htmlspecialchars($khach['email']); ?></small><br>
                                    <?php endif; ?>
                                    <?php if (!empty($khach['so_dien_thoai'])): ?>
                                        <small style="color: var(--text-muted);">📞 <?php echo htmlspecialchars($khach['so_dien_thoai']); ?></small>
                                    <?php endif; ?>
                                    <?php if (empty($khach['email']) && empty($khach['so_dien_thoai'])): ?>
                                        <span style="color: var(--text-muted);">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo !empty($khach['dia_chi']) ? htmlspecialchars($khach['dia_chi']) : 'N/A'; ?>
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
                    Tổng số: <strong><?php echo count($danhSachKhach); ?></strong> khách
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                ℹ️ Chưa có khách nào trong danh sách. Vui lòng thêm khách vào lịch khởi hành này.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            ℹ️ Vui lòng chọn lịch khởi hành để xem danh sách khách.
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Bạn chưa được phân công tour nào.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
