<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .info-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }
        .info-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 200px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            color: #212529;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
            display: inline-block;
        }
        .status-ChoXacNhan {
            background: #fff3cd;
            color: #856404;
        }
        .status-DaCoc {
            background: #cfe2ff;
            color: #084298;
        }
        .status-HoanTat {
            background: #d1e7dd;
            color: #0f5132;
        }
        .status-Huy {
            background: #f8d7da;
            color: #842029;
        }
        .form-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }
        .form-card .card-header {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #667eea;
        }
        .table-history {
            margin-bottom: 0;
        }
        .table-history thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-history tbody tr {
            transition: all 0.2s;
        }
        .table-history tbody tr:hover {
            background: #f8f9fa;
        }
        .booking-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #667eea;
            font-size: 1.25rem;
        }
        .empty-history {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-history i {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/quanLyBooking">
                            <i class="bi bi-calendar-check"></i> Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-eye"></i> Chi tiết
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="bi bi-file-text-fill"></i> Chi Tiết Booking
                        </h1>
                        <p class="mb-0 opacity-75">
                            Mã booking: <span class="booking-id">#<?php echo $booking['booking_id']; ?></span>
                        </p>
                    </div>
                    <div>
                        <a href="index.php?act=booking/xuatTaiLieu&id=<?php echo $booking['booking_id']; ?>" class="btn btn-success btn-lg me-2">
                            <i class="bi bi-file-earmark-pdf"></i> Xuất tài liệu
                        </a>
                        <a href="index.php?act=admin/quanLyBooking" class="btn btn-light btn-lg">
                            <i class="bi bi-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Column - Booking Information -->
            <div class="col-lg-6">
                <!-- Thông tin booking -->
                <div class="info-card card">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Thông tin Booking
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-hash text-primary"></i> Mã Booking
                            </div>
                            <div class="info-value">
                                <span class="booking-id">#<?php echo $booking['booking_id']; ?></span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-geo-alt text-success"></i> Tour
                            </div>
                            <div class="info-value fw-semibold">
                                <?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-people text-primary"></i> Số lượng người
                            </div>
                            <div class="info-value">
                                <span class="badge bg-primary rounded-pill">
                                    <?php echo $booking['so_nguoi']; ?> người
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-plus text-info"></i> Ngày đặt
                            </div>
                            <div class="info-value">
                                <?php echo date('d/m/Y', strtotime($booking['ngay_dat'])); ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-event text-warning"></i> Ngày khởi hành
                            </div>
                            <div class="info-value">
                                <?php echo $booking['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'N/A'; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-calendar-check text-success"></i> Ngày kết thúc
                            </div>
                            <div class="info-value">
                                <?php 
                                    $endDate = $booking['ngay_ket_thuc'] ?? $booking['ngay_khoi_hanh'];
                                    echo $endDate ? date('d/m/Y', strtotime($endDate)) : 'N/A'; 
                                ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-cash-coin text-success"></i> Tổng tiền
                            </div>
                            <div class="info-value">
                                <strong class="text-success fs-5">
                                    <?php echo number_format($booking['tong_tien'] ?? 0); ?> ₫
                                </strong>
                            </div>
                        </div>
                        <?php
                        // Tính toán thông tin tiền cọc
                        $tongTien = (float)($booking['tong_tien'] ?? 0);
                        $tienCoc = (float)($booking['tien_coc'] ?? ($booking['so_tien_coc'] ?? 0));
                        
                        // Nếu trạng thái là "Hoàn tất", tiền cọc = tổng tiền (đã thanh toán đủ)
                        if ($booking['trang_thai'] == 'HoanTat' && $tongTien > 0) {
                            $tienCoc = $tongTien;
                            $trangThaiCoc = 'HoanTat';
                        } else {
                            // Nếu chưa có tiền cọc trong DB, tính 30% tổng tiền làm mặc định
                            if ($tienCoc == 0 && $tongTien > 0) {
                                $tienCoc = round($tongTien * 0.3);
                            }
                            $trangThaiCoc = $booking['trang_thai_coc'] ?? ($booking['trang_thai'] == 'DaCoc' ? 'DaCoc' : 'ChuaCoc');
                        }
                        $tienConLai = max(0, $tongTien - $tienCoc);
                        ?>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-wallet2 text-primary"></i> Số tiền cọc
                            </div>
                            <div class="info-value">
                                <strong class="text-primary fs-5">
                                    <?php echo number_format($tienCoc); ?> ₫
                                </strong>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-check-circle text-info"></i> Trạng thái cọc
                            </div>
                            <div class="info-value">
                                <?php
                                $trangThaiCocLabels = [
                                    'DaCoc' => 'Đã cọc',
                                    'ChuaCoc' => 'Chưa cọc',
                                    'HoanTat' => 'Hoàn tất'
                                ];
                                $trangThaiCocBadge = $trangThaiCoc == 'DaCoc' ? 'success' : ($trangThaiCoc == 'HoanTat' ? 'success' : 'warning');
                                ?>
                                <span class="badge bg-<?php echo $trangThaiCocBadge; ?> rounded-pill">
                                    <?php echo $trangThaiCocLabels[$trangThaiCoc] ?? $trangThaiCoc; ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-cash-stack text-warning"></i> Số tiền còn lại
                            </div>
                            <div class="info-value">
                                <strong class="text-warning fs-5">
                                    <?php echo number_format($tienConLai); ?> ₫
                                </strong>
                                <?php if ($tienConLai > 0): ?>
                                    <small class="text-muted d-block mt-1">
                                        (<?php echo round(($tienConLai / $tongTien) * 100, 1); ?>% tổng tiền)
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-flag text-danger"></i> Trạng thái booking
                            </div>
                            <div class="info-value">
                                <?php
                                $statusLabels = [
                                    'ChoXacNhan' => 'Chờ xác nhận',
                                    'DaCoc' => 'Đã cọc',
                                    'HoanTat' => 'Hoàn tất',
                                    'Huy' => 'Hủy'
                                ];
                                $currentStatus = $booking['trang_thai'];
                                ?>
                                <span class="status-badge status-<?php echo $currentStatus; ?>">
                                    <?php echo $statusLabels[$currentStatus] ?? $currentStatus; ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-pencil-square text-secondary"></i> Ghi chú
                            </div>
                            <div class="info-value">
                                <?php echo nl2br(htmlspecialchars($booking['ghi_chu'] ?? 'Không có ghi chú')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin khách hàng -->
                <div class="info-card card">
                    <div class="card-header">
                        <i class="bi bi-person-circle"></i> Thông tin người đặt
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-person text-primary"></i> Họ tên
                            </div>
                            <div class="info-value fw-semibold">
                                <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-envelope text-info"></i> Email
                            </div>
                            <div class="info-value">
                                <a href="mailto:<?php echo htmlspecialchars($booking['email'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-phone text-success"></i> Số điện thoại
                            </div>
                            <div class="info-value">
                                <a href="tel:<?php echo htmlspecialchars($booking['so_dien_thoai'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($booking['so_dien_thoai'] ?? 'N/A'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-geo text-danger"></i> Địa chỉ
                            </div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($booking['dia_chi'] ?? 'N/A'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Forms -->
            <div class="col-lg-6">
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'HDV')): ?>
                    <!-- Cập nhật thông tin Booking -->
                    <div class="form-card card">
                        <div class="card-header">
                            <i class="bi bi-pencil-square"></i> Cập nhật thông tin Booking
                        </div>
                        <div class="card-body">
                            <form method="POST" action="index.php?act=booking/update">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-people text-primary"></i> Số lượng người
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" name="so_nguoi" class="form-control" 
                                               value="<?php echo $booking['so_nguoi']; ?>" min="1" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-cash-coin text-success"></i> Tổng tiền
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="tong_tien" id="tongTienInput" class="form-control" 
                                                   value="<?php echo $booking['tong_tien']; ?>" step="1000" min="0" required>
                                            <span class="input-group-text">₫</span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-calendar-event text-warning"></i> Ngày khởi hành
                                        </label>
                                        <input type="date" name="ngay_khoi_hanh" class="form-control" 
                                               value="<?php echo $booking['ngay_khoi_hanh'] ?? ''; ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-calendar-check text-success"></i> Ngày kết thúc
                                        </label>
                                        <input type="date" name="ngay_ket_thuc" class="form-control"
                                               value="<?php echo $booking['ngay_ket_thuc'] ?? $booking['ngay_khoi_hanh'] ?? ''; ?>">
                                        <small class="text-muted">Để trống sẽ dùng ngày khởi hành</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-wallet2 text-primary"></i> Tiền cọc
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="tien_coc" id="tienCocInput" class="form-control" 
                                                   value="<?php echo $tienCoc; ?>" step="1000" min="0">
                                            <span class="input-group-text">₫</span>
                                        </div>
                                        <small class="text-muted">Để trống sẽ tự động tính 30% tổng tiền</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-flag text-danger"></i> Trạng thái Booking
                                        </label>
                                        <select name="trang_thai" id="trangThaiSelect" class="form-select">
                                            <option value="ChoXacNhan" <?php echo $currentStatus == 'ChoXacNhan' ? 'selected' : ''; ?>>
                                                Chờ xác nhận
                                            </option>
                                            <option value="DaCoc" <?php echo $currentStatus == 'DaCoc' ? 'selected' : ''; ?>>
                                                Đã cọc
                                            </option>
                                            <option value="HoanTat" <?php echo $currentStatus == 'HoanTat' ? 'selected' : ''; ?>>
                                                Hoàn tất
                                            </option>
                                            <option value="Huy" <?php echo $currentStatus == 'Huy' ? 'selected' : ''; ?>>
                                                Hủy
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-check-circle text-info"></i> Trạng thái cọc
                                        </label>
                                        <select name="trang_thai_coc" id="trangThaiCocSelect" class="form-select">
                                            <option value="ChuaCoc" <?php echo $trangThaiCoc == 'ChuaCoc' ? 'selected' : ''; ?>>
                                                Chưa cọc
                                            </option>
                                            <option value="DaCoc" <?php echo $trangThaiCoc == 'DaCoc' ? 'selected' : ''; ?>>
                                                Đã cọc
                                            </option>
                                            <option value="HoanTat" <?php echo $trangThaiCoc == 'HoanTat' ? 'selected' : ''; ?>>
                                                Hoàn tất thanh toán
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <div id="tienCocWarning" class="alert alert-info mb-0" style="display: none;">
                                            <i class="bi bi-info-circle"></i> 
                                            <strong>Lưu ý:</strong> Khi chọn "Hoàn tất", tiền cọc sẽ tự động bằng tổng tiền (đã thanh toán đủ).
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="bi bi-pencil-square text-secondary"></i> Ghi chú
                                        </label>
                                        <textarea name="ghi_chu" class="form-control" rows="3"><?php echo htmlspecialchars($booking['ghi_chu'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success w-100 btn-lg">
                                            <i class="bi bi-save"></i> Lưu thay đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lịch sử thay đổi -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i> Lịch sử thay đổi trạng thái
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($history)): ?>
                    <div class="table-responsive">
                        <table class="table table-history table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Trạng thái cũ</th>
                                    <th>Trạng thái mới</th>
                                    <th>Người thay đổi</th>
                                    <th>Vai trò</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $item): ?>
                                    <tr>
                                        <td>
                                            <small>
                                                <i class="bi bi-clock text-primary"></i>
                                                <?php echo date('d/m/Y H:i:s', strtotime($item['thoi_gian'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($item['trang_thai_cu']): ?>
                                                <span class="status-badge status-<?php echo $item['trang_thai_cu']; ?>">
                                                    <?php echo $statusLabels[$item['trang_thai_cu']] ?? $item['trang_thai_cu']; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $item['trang_thai_moi']; ?>">
                                                <?php echo $statusLabels[$item['trang_thai_moi']] ?? $item['trang_thai_moi']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($item['nguoi_thay_doi'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($item['vai_tro'] ?? 'N/A'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo nl2br(htmlspecialchars($item['ghi_chu'] ?? '-')); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-history">
                        <i class="bi bi-inbox"></i>
                        <p class="mb-0">Chưa có lịch sử thay đổi nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Tự động đồng bộ trạng thái khi thay đổi tiền cọc hoặc trạng thái
        (function() {
            const tienCocInput = document.getElementById('tienCocInput');
            const tongTienInput = document.getElementById('tongTienInput');
            const trangThaiSelect = document.getElementById('trangThaiSelect');
            const trangThaiCocSelect = document.getElementById('trangThaiCocSelect');
            const tienCocWarning = document.getElementById('tienCocWarning');
            
            function getTongTien() {
                return parseFloat(tongTienInput?.value) || 0;
            }
            
            function updateWarning() {
                if (!tienCocWarning) return;
                const tongTien = getTongTien();
                const trangThai = trangThaiSelect?.value || '';
                const tienCoc = parseFloat(tienCocInput?.value) || 0;
                
                if (trangThai === 'HoanTat' || (tongTien > 0 && Math.abs(tienCoc - tongTien) < 0.01)) {
                    tienCocWarning.style.display = 'block';
                } else {
                    tienCocWarning.style.display = 'none';
                }
            }
            
            // Khi thay đổi tiền cọc
            if (tienCocInput) {
                tienCocInput.addEventListener('input', function() {
                    const tongTien = getTongTien();
                    const tienCoc = parseFloat(this.value) || 0;
                    
                    if (tongTien > 0 && Math.abs(tienCoc - tongTien) < 0.01) {
                        // Tiền cọc = tổng tiền → Hoàn tất
                        if (trangThaiSelect) trangThaiSelect.value = 'HoanTat';
                        if (trangThaiCocSelect) trangThaiCocSelect.value = 'HoanTat';
                    } else if (tienCoc > 0 && tienCoc < tongTien) {
                        // Đã cọc một phần
                        if (trangThaiSelect && trangThaiSelect.value === 'ChoXacNhan') {
                            trangThaiSelect.value = 'DaCoc';
                        }
                        if (trangThaiCocSelect && trangThaiCocSelect.value === 'ChuaCoc') {
                            trangThaiCocSelect.value = 'DaCoc';
                        }
                    }
                    updateWarning();
                });
            }
            
            // Khi thay đổi tổng tiền
            if (tongTienInput) {
                tongTienInput.addEventListener('input', function() {
                    const tongTien = getTongTien();
                    const tienCoc = parseFloat(tienCocInput?.value) || 0;
                    
                    // Nếu tiền cọc > tổng tiền mới, giảm tiền cọc xuống
                    if (tienCoc > tongTien && tongTien > 0) {
                        if (tienCocInput) tienCocInput.value = tongTien;
                    }
                    updateWarning();
                });
            }
            
            // Khi thay đổi trạng thái booking
            if (trangThaiSelect) {
                trangThaiSelect.addEventListener('change', function() {
                    const tongTien = getTongTien();
                    if (this.value === 'HoanTat' && tongTien > 0) {
                        // Hoàn tất → set tiền cọc = tổng tiền
                        if (tienCocInput) tienCocInput.value = tongTien;
                        if (trangThaiCocSelect) trangThaiCocSelect.value = 'HoanTat';
                    }
                    updateWarning();
                });
            }
            
            // Khi thay đổi trạng thái cọc
            if (trangThaiCocSelect) {
                trangThaiCocSelect.addEventListener('change', function() {
                    const tongTien = getTongTien();
                    if (this.value === 'HoanTat' && tongTien > 0) {
                        if (tienCocInput) tienCocInput.value = tongTien;
                        if (trangThaiSelect) trangThaiSelect.value = 'HoanTat';
                    }
                    updateWarning();
                });
            }
            
            // Kiểm tra khi trang load
            updateWarning();
        })();
    </script>
</body>
</html>

