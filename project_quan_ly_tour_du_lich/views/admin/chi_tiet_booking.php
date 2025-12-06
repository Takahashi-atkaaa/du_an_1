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
                        <div class="info-row">
                            <div class="info-label">
                                <i class="bi bi-flag text-danger"></i> Trạng thái
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
                    <ul class="nav nav-tabs mb-3" id="bookingTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-trangthai" data-bs-toggle="tab" data-bs-target="#tab-pane-trangthai" type="button" role="tab">Cập nhật trạng thái</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-thongtin" data-bs-toggle="tab" data-bs-target="#tab-pane-thongtin" type="button" role="tab">Cập nhật thông tin</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-thanhtoan" data-bs-toggle="tab" data-bs-target="#tab-pane-thanhtoan" type="button" role="tab">Thanh toán thêm</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="bookingTabContent">
                        <div class="tab-pane fade show active" id="tab-pane-trangthai" role="tabpanel">
                            <!-- Cập nhật trạng thái -->
                            <div class="form-card card mb-4">
                                <div class="card-header">
                                    <i class="bi bi-arrow-repeat"></i> Cập nhật trạng thái
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="index.php?act=booking/updateTrangThai">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-flag text-primary"></i> Trạng thái mới
                                                <span class="text-danger">*</span>
                                            </label>
                                            <select name="trang_thai" class="form-select" required>
                                                <option value="ChoXacNhan" <?php echo $currentStatus == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                                <option value="DaCoc" <?php echo $currentStatus == 'DaCoc' ? 'selected' : ''; ?>>Đã cọc</option>
                                                <option value="HoanTat" <?php echo $currentStatus == 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                                <option value="Huy" <?php echo $currentStatus == 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-cash-coin text-success"></i> Số tiền cọc
                                            </label>
                                            <input type="number" name="so_tien_coc" class="form-control" min="0" step="1000" placeholder="Nhập số tiền cọc" value="<?php echo isset($booking['so_tien_coc']) ? $booking['so_tien_coc'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-calendar-event text-info"></i> Ngày cọc
                                            </label>
                                            <input type="date" name="ngay_coc" class="form-control" value="<?php echo isset($booking['ngay_coc']) ? $booking['ngay_coc'] : ''; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-wallet2 text-warning"></i> Số tiền còn lại
                                            </label>
                                            <input type="number" class="form-control" value="<?php echo isset($booking['so_tien_con_lai']) ? $booking['so_tien_con_lai'] : ''; ?>" disabled>
                                            <small class="text-muted">Số tiền còn lại sẽ tự động tính khi nhập số tiền cọc và lưu vào hệ thống.</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-pencil text-secondary"></i> Ghi chú
                                            </label>
                                            <textarea name="ghi_chu" class="form-control" rows="3" placeholder="Thêm ghi chú về việc thay đổi trạng thái..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-thongtin" role="tabpanel">
                            <!-- Cập nhật thông tin -->
                            <div class="form-card card mb-4">
                                <div class="card-header">
                                    <i class="bi bi-pencil-square"></i> Cập nhật thông tin
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
                                                <input type="number" name="so_nguoi" class="form-control" value="<?php echo $booking['so_nguoi']; ?>" min="1" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-calendar-event text-warning"></i> Ngày khởi hành
                                                </label>
                                                <input type="date" name="ngay_khoi_hanh" class="form-control" value="<?php echo $booking['ngay_khoi_hanh'] ?? ''; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-calendar-check text-success"></i> Ngày kết thúc
                                                </label>
                                                <input type="date" name="ngay_ket_thuc" class="form-control" value="<?php echo $booking['ngay_ket_thuc'] ?? $booking['ngay_khoi_hanh'] ?? ''; ?>">
                                                <small class="text-muted">Để trống sẽ dùng ngày khởi hành</small>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-cash-coin text-success"></i> Tổng tiền
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" name="tong_tien" class="form-control" value="<?php echo $booking['tong_tien']; ?>" step="1000" min="0" required>
                                                    <span class="input-group-text">₫</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-flag text-danger"></i> Trạng thái
                                                </label>
                                                <select name="trang_thai" class="form-select">
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
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">
                                                    <i class="bi bi-pencil-square text-secondary"></i> Ghi chú
                                                </label>
                                                <textarea name="ghi_chu" class="form-control" rows="3"><?php echo htmlspecialchars($booking['ghi_chu'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="bi bi-save"></i> Lưu thay đổi
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-pane-thanhtoan" role="tabpanel">
                            <!-- Thanh toán thêm -->
                            <div class="form-card card mb-4">
                                <div class="card-header">
                                    <i class="bi bi-cash-coin"></i> Thanh toán thêm
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="index.php?act=booking/thanhToanThem">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-cash-coin text-success"></i> Số tiền thanh toán thêm
                                            </label>
                                            <input type="number" name="so_tien_thanh_toan" class="form-control" min="1000" step="1000" placeholder="Nhập số tiền thanh toán thêm" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-calendar-event text-info"></i> Ngày thanh toán
                                            </label>
                                            <input type="date" name="ngay_thanh_toan" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">
                                                <i class="bi bi-pencil text-secondary"></i> Ghi chú
                                            </label>
                                            <textarea name="mo_ta" class="form-control" rows="2" placeholder="Ghi chú cho lần thanh toán này..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-cash-stack"></i> Xác nhận thanh toán
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lịch sử thay đổi -->
        <div class="card border-0 shadow-sm mb-4">
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
                        <span class="ms-2">Chưa có lịch sử thay đổi trạng thái.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lịch sử thanh toán/cọc -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #197d2b;">
                <h5 class="mb-0">
                    <i class="bi bi-cash-coin"></i> Lịch sử thanh toán/cọc
                </h5>
            </div>
            <div class="card-body p-0">
                <?php 
                    $giaoDichModel = new GiaoDich();
                    $giaoDichList = $giaoDichModel->getByBookingId($booking['booking_id']);
                    $tongThu = 0;
                ?>
                <?php if (!empty($giaoDichList)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Ngày giao dịch</th>
                                    <th>Loại</th>
                                    <th>Số tiền</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($giaoDichList as $gd): ?>
                                    <?php if ($gd['loai'] === 'Thu') $tongThu += (float)$gd['so_tien']; ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($gd['ngay_giao_dich'])); ?></td>
                                        <td><span class="badge bg-success"><?php echo $gd['loai']; ?></span></td>
                                        <td class="text-end text-success">+<?php echo number_format($gd['so_tien']); ?> ₫</td>
                                        <td><?php echo htmlspecialchars($gd['mo_ta']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Tổng đã thu:</th>
                                    <th class="text-end text-success"><?php echo number_format($tongThu); ?> ₫</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-end">Số tiền còn lại:</th>
                                    <th class="text-end text-danger">
                                        <?php echo number_format(max(0, $booking['tong_tien'] - $tongThu)); ?> ₫
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-history">
                        <i class="bi bi-inbox"></i>
                        <span class="ms-2">Chưa có giao dịch thanh toán nào.</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
                        <p class="mb-0">Chưa có lịch sử thay đổi nào</p>
                    </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

