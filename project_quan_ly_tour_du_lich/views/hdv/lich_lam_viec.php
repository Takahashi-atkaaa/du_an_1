<?php
$pageTitle = 'Lịch làm việc - HDV';
$currentPage = 'lichLamViec';
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
        margin-bottom: 30px;
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

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 30px 0 20px 0;
        letter-spacing: 1px;
        color: var(--accent-gold);
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

    .card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .form-group select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 10px;
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
    <h1>📅 Lịch làm việc của tôi</h1>
    <p style="color: var(--text-muted); margin-top: 10px;">Xem và quản lý lịch làm việc, nhiệm vụ được phân công</p>
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

<!-- Yêu cầu đặc biệt của khách -->
<?php if (!empty($lichKhoiHanhList) && !empty($yeuCauDacBietTheoLich)): ?>
    <h3 class="section-title">⚠️ Quản lý yêu cầu đặc biệt của khách</h3>
    <?php foreach ($lichKhoiHanhList as $lich): ?>
        <?php
            $lichId = (int)($lich['id'] ?? 0);
            $danhSachKhach = $yeuCauDacBietTheoLich[$lichId] ?? [];
        ?>
        <?php if (!empty($danhSachKhach)): ?>
            <div class="card">
                <h4 style="margin: 0 0 20px 0; color: var(--accent-gold);">
                    <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                    (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                </h4>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                                <th>Yêu cầu đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSachKhach as $khach): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong><br>
                                    <small style="color: var(--text-muted);">Booking #<?php echo $khach['booking_id']; ?></small><br>
                                    <small style="color: var(--text-muted);"><?php echo (int)($khach['so_nguoi'] ?? 1); ?> khách</small>
                                </td>
                                <td>
                                    <small style="color: var(--text-muted);">
                                        ✉ <?php echo htmlspecialchars($khach['email'] ?? ''); ?><br>
                                        📞 <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <div style="max-width: 300px; word-wrap: break-word; color: var(--text-light);">
                                        <?php echo nl2br(htmlspecialchars($khach['yeu_cau_dac_biet'] ?? 'Chưa có yêu cầu đặc biệt')); ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Lịch khởi hành được phân công -->
<h3 class="section-title">📋 Lịch khởi hành được phân công (HDV chính)</h3>
<?php if (isset($lichKhoiHanhList) && !empty($lichKhoiHanhList)): ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Giờ xuất phát</th>
                    <th>Ngày kết thúc</th>
                    <th>Giờ kết thúc</th>
                    <th>Điểm tập trung</th>
                    <th>Số chỗ</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; foreach($lichKhoiHanhList as $lich): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($lich['ten_tour'] ?? 'N/A'); ?></td>
                    <td><?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                    <td><?php echo $lich['gio_xuat_phat'] ?? 'N/A'; ?></td>
                    <td><?php echo !empty($lich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?></td>
                    <td><?php echo $lich['gio_ket_thuc'] ?? 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($lich['diem_tap_trung'] ?? ''); ?></td>
                    <td style="text-align: center;"><?php echo $lich['so_cho'] ?? 50; ?></td>
                    <td>
                        <?php
                        $statusLabels = [
                            'SapKhoiHanh' => 'Sắp khởi hành',
                            'DangChay' => 'Đang chạy',
                            'HoanThanh' => 'Hoàn thành'
                        ];
                        $trangThai = $lich['trang_thai'] ?? null;
                        $statusText = $trangThai ? ($statusLabels[$trangThai] ?? $trangThai) : 'N/A';
                        $statusClass = [
                            'SapKhoiHanh' => 'status-warning',
                            'DangChay' => 'status-info',
                            'HoanThanh' => 'status-success'
                        ];
                        ?>
                        <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 10px; border-radius: 2px; font-size: 11px;">
                            <?php echo $statusText; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Chưa có lịch khởi hành nào được phân công.
    </div>
<?php endif; ?>

<!-- Phân bổ nhân sự -->
<h3 class="section-title">👥 Phân bổ nhân sự (HDV phụ, tài xế, ...)</h3>
<?php if (isset($phanBoNhanSuList) && !empty($phanBoNhanSuList)): ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Ngày kết thúc</th>
                    <th>Vai trò</th>
                    <th>Trạng thái phân bổ</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; foreach($phanBoNhanSuList as $pb): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($pb['ten_tour'] ?? 'N/A'); ?></td>
                    <td><?php echo !empty($pb['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($pb['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                    <td><?php echo !empty($pb['ngay_ket_thuc']) ? date('d/m/Y', strtotime($pb['ngay_ket_thuc'])) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($pb['vai_tro'] ?? ''); ?></td>
                    <td>
                        <?php
                        $statusLabels = [
                            'ChoXacNhan' => 'Chờ xác nhận',
                            'DaXacNhan' => 'Đã xác nhận',
                            'TuChoi' => 'Từ chối',
                            'Huy' => 'Hủy'
                        ];
                        $trangThaiPb = $pb['trang_thai'] ?? null;
                        $statusText = $trangThaiPb ? ($statusLabels[$trangThaiPb] ?? $trangThaiPb) : 'N/A';
                        ?>
                        <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 10px; border-radius: 2px; font-size: 11px;">
                            <?php echo $statusText; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($pb['ghi_chu'] ?? ''); ?></td>
                    <td>
                        <form method="POST" action="index.php?act=lichKhoiHanh/updateTrangThaiNhanSu" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $pb['id']; ?>">
                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $pb['lich_khoi_hanh_id']; ?>">
                            <select name="trang_thai" onchange="this.form.submit()" class="form-group select" style="width: auto; min-width: 150px;">
                                <option value="ChoXacNhan" <?php echo $pb['trang_thai'] == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                <option value="DaXacNhan" <?php echo $pb['trang_thai'] == 'DaXacNhan' ? 'selected' : ''; ?>>Đã xác nhận</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Chưa có phân bổ nhân sự nào.
    </div>
<?php endif; ?>

<!-- Chi tiết tour & nhiệm vụ -->
<?php if (!empty($lichKhoiHanhList)): ?>
    <h3 class="section-title">🗺️ Chi tiết tour & nhiệm vụ của tôi</h3>
    <?php foreach ($lichKhoiHanhList as $lich): ?>
        <?php
            $tourId = $lich['tour_id'] ?? null;
            $lichTrinh = ($tourId && isset($lichTrinhTheoTour[$tourId])) ? $lichTrinhTheoTour[$tourId] : [];
            $nhiemVu = isset($nhiemVuTheoLich[$lich['id']]) ? $nhiemVuTheoLich[$lich['id']] : null;
        ?>
        <div class="card">
            <h4 style="margin: 0 0 20px 0; color: var(--accent-gold);"><?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?></h4>
            <div style="margin-bottom: 15px; color: var(--text-light);">
                <strong>Thời gian:</strong> 
                <?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>
                →
                <?php echo !empty($lich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?>
            </div>
            <div style="margin-bottom: 15px; color: var(--text-light);">
                <strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($lich['diem_tap_trung'] ?? 'Chưa cập nhật'); ?>
            </div>
            <div style="margin-bottom: 20px; color: var(--text-light);">
                <strong>Nhiệm vụ của tôi:</strong> 
                <?php 
                    if ($nhiemVu) {
                        echo htmlspecialchars($nhiemVu['vai_tro'] ?? 'HDV');
                        if (!empty($nhiemVu['ghi_chu'])) {
                            echo ' - ' . htmlspecialchars($nhiemVu['ghi_chu']);
                        }
                    } else {
                        echo 'HDV chính phụ trách xuyên suốt tour';
                    }
                ?>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
                    <strong style="color: var(--accent-gold);">Lịch trình từng ngày:</strong>
                    <a href="index.php?act=hdv/lich_trinh_chi_tiet&id=<?php echo $lich['id']; ?>" 
                       class="btn btn-primary btn-sm">
                        📅 Xem chi tiết
                    </a>
                </div>
                <?php if (!empty($lichTrinh)): ?>
                    <ol style="color: var(--text-light); line-height: 2; padding-left: 25px;">
                        <?php foreach ($lichTrinh as $ngay): ?>
                            <li style="margin-bottom: 15px;">
                                <strong>Ngày <?php echo (int)($ngay['ngay_thu'] ?? 0); ?>:</strong>
                                <?php if (!empty($ngay['dia_diem'])): ?>
                                    <em style="color: var(--accent-gold);"><?php echo htmlspecialchars($ngay['dia_diem']); ?></em><br>
                                <?php endif; ?>
                                <?php 
                                $hoatDong = htmlspecialchars($ngay['hoat_dong'] ?? '');
                                if (strlen($hoatDong) > 200) {
                                    echo nl2br(substr($hoatDong, 0, 200)) . '...';
                                } else {
                                    echo nl2br($hoatDong);
                                }
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <p style="color: var(--text-muted);">Chưa có lịch trình chi tiết cho tour này.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
