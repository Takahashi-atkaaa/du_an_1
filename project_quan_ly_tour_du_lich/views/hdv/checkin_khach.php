<?php
$pageTitle = 'Điểm danh khách - HDV';
$currentPage = 'checkInKhach';
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

    .status-badge.checkin {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .status-badge.pending {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-badge.checkout {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
    }

    .checkin-form-inline {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .checkin-form-inline textarea {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 8px;
        font-size: 12px;
        border-radius: 2px;
        min-width: 180px;
        font-family: inherit;
    }

    .checkin-form-inline textarea:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .checkin-form-inline select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 8px;
        font-size: 12px;
        border-radius: 2px;
        font-family: inherit;
    }

    .checkin-form-inline select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .checkin-form-inline button {
        background: var(--accent-gold);
        color: var(--primary-dark);
        border: none;
        padding: 8px 16px;
        border-radius: 2px;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s;
    }

    .checkin-form-inline button:hover {
        background: #c9a030;
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
            <h1>✓ Điểm danh khách theo tour</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Quản lý check-in/check-out khách hàng</p>
        </div>
        <div>
            <a href="index.php?act=hdv/lichLamViec" class="btn btn-secondary">
                ← Lịch làm việc
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

<?php if (!empty($lichKhoiHanhList)): ?>
    <div class="card" style="margin-bottom: 30px;">
        <div class="form-group">
            <label><strong>Chọn lịch khởi hành:</strong></label>
            <form method="GET" action="index.php" style="display: flex; gap: 10px; align-items: end;">
                <input type="hidden" name="act" value="hdv/checkInKhach">
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
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Khách/Booking</th>
                            <th>Liên hệ</th>
                            <th>Nhóm</th>
                            <th>Trạng thái điểm danh</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                            <?php
                                $khachHangId = (int)($khach['khach_hang_id'] ?? 0);
                                $soNguoi = (int)($khach['so_nguoi'] ?? 1);
                                $nhomLabel = $soNguoi >= 10 ? 'Đoàn lớn' : ($soNguoi >= 5 ? 'Nhóm' : ($soNguoi >= 3 ? 'Nhóm nhỏ' : ($soNguoi == 2 ? 'Cặp' : 'Khách lẻ')));
                                $checkin = $checkinMap[$khachHangId] ?? null;
                                $trangThai = $checkin['trang_thai'] ?? 'ChuaCheckIn';
                                $badgeClass = match ($trangThai) {
                                    'DaCheckIn' => 'status-badge checkin',
                                    'DaCheckOut' => 'status-badge checkout',
                                    default => 'status-badge pending'
                                };
                            ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong><br>
                                    <small style="color: var(--text-muted);">Booking #<?php echo $khach['booking_id']; ?></small><br>
                                    <?php if (!empty($khach['dia_chi'])): ?>
                                        <small style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($khach['dia_chi']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small style="color: var(--text-muted);">
                                        ✉ <?php echo htmlspecialchars($khach['email'] ?? ''); ?><br>
                                        📞 <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo $soNguoi; ?> khách<br>
                                    <small style="color: var(--text-muted);"><?php echo $nhomLabel; ?></small>
                                </td>
                                <td>
                                    <span class="<?php echo $badgeClass; ?>">
                                        <?php
                                            $labels = [
                                                'ChuaCheckIn' => 'Chưa check-in',
                                                'DaCheckIn' => 'Đã check-in',
                                                'DaCheckOut' => 'Đã check-out'
                                            ];
                                            echo $labels[$trangThai] ?? $trangThai;
                                        ?>
                                    </span>
                                    <?php if (!empty($checkin['checkin_time'])): ?>
                                        <br><small style="color: var(--text-muted); font-size: 11px;">Check-in: <?php echo date('d/m H:i', strtotime($checkin['checkin_time'])); ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($checkin['checkout_time'])): ?>
                                        <br><small style="color: var(--text-muted); font-size: 11px;">Check-out: <?php echo date('d/m H:i', strtotime($checkin['checkout_time'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="POST" action="index.php?act=hdv/updateCheckInKhach" class="checkin-form-inline">
                                        <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $selectedLich['id']; ?>">
                                        <input type="hidden" name="booking_id" value="<?php echo $khach['booking_id']; ?>">
                                        <input type="hidden" name="khach_hang_id" value="<?php echo $khachHangId; ?>">
                                        <select name="trang_thai" style="min-width: 140px;">
                                            <option value="ChuaCheckIn" <?php echo $trangThai === 'ChuaCheckIn' ? 'selected' : ''; ?>>Chưa check-in</option>
                                            <option value="DaCheckIn" <?php echo $trangThai === 'DaCheckIn' ? 'selected' : ''; ?>>Đã check-in</option>
                                            <option value="DaCheckOut" <?php echo $trangThai === 'DaCheckOut' ? 'selected' : ''; ?>>Đã check-out</option>
                                        </select>
                                        <textarea name="ghi_chu" rows="2" placeholder="Ghi chú ngắn..."><?php echo htmlspecialchars($checkin['ghi_chu'] ?? ''); ?></textarea>
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                ℹ️ Chưa có khách nào được đặt cho lịch khởi hành này.
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info">
            ℹ️ Vui lòng chọn lịch khởi hành để điểm danh khách.
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Bạn chưa được phân công lịch khởi hành nào.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
