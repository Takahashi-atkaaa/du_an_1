<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Booking & Khách Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .stats-card {
            border: none;
            border-left: 4px solid;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: all 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .stats-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        .filter-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
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
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-custom tbody tr {
            transition: all 0.2s;
        }
        .table-custom tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.005);
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.3;
        }
        .booking-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #667eea;
        }
        .customer-info {
            line-height: 1.4;
        }
        .action-btn-group {
            display: flex;
            gap: 0.25rem;
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
                        <a class="nav-link active" href="#">
                            <i class="bi bi-calendar-check"></i> Booking
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
                        <h1 class="display-5 fw-bold mb-2">
                            <i class="bi bi-calendar-check-fill"></i> Quản Lý Booking
                        </h1>
                        <p class="lead mb-0 opacity-75">Quản lý booking, danh sách khách hàng từng booking, và xử lý đặt tour cho khách hàng</p>
                        <a href="index.php?act=admin/lichSuXoaBooking" class="btn btn-outline-danger btn-sm mt-2">
                            <i class="bi bi-clock-history"></i> Xem lịch sử xóa booking
                        </a>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?act=booking/datTourChoKhach" class="btn btn-warning btn-lg">
                            <i class="bi bi-plus-circle"></i> Đặt tour cho khách
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

        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <?php 
            $total = count($bookings ?? []);
            $choXacNhan = count(array_filter($bookings ?? [], fn($b) => $b['trang_thai'] === 'ChoXacNhan'));
            $daCoc = count(array_filter($bookings ?? [], fn($b) => $b['trang_thai'] === 'DaCoc'));
            $hoanTat = count(array_filter($bookings ?? [], fn($b) => $b['trang_thai'] === 'HoanTat'));
            $huy = count(array_filter($bookings ?? [], fn($b) => $b['trang_thai'] === 'Huy'));
            ?>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #0d6efd !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Tổng booking</p>
                                <h2 class="mb-0 fw-bold"><?php echo $total; ?></h2>
                            </div>
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #ffc107 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Chờ xác nhận</p>
                                <h2 class="mb-0 fw-bold text-warning"><?php echo $choXacNhan; ?></h2>
                            </div>
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #0dcaf0 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Đã cọc</p>
                                <h2 class="mb-0 fw-bold text-info"><?php echo $daCoc; ?></h2>
                            </div>
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #198754 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Hoàn tất</p>
                                <h2 class="mb-0 fw-bold text-success"><?php echo $hoanTat; ?></h2>
                            </div>
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="index.php">
                <input type="hidden" name="act" value="admin/quanLyBooking">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-funnel text-primary"></i> Lọc theo trạng thái
                        </label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="ChoXacNhan" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'ChoXacNhan') ? 'selected' : ''; ?>>
                                Chờ xác nhận
                            </option>
                            <option value="DaCoc" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'DaCoc') ? 'selected' : ''; ?>>
                                Đã cọc
                            </option>
                            <option value="HoanTat" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'HoanTat') ? 'selected' : ''; ?>>
                                Hoàn tất
                            </option>
                            <option value="Huy" <?php echo (isset($_GET['trang_thai']) && $_GET['trang_thai'] == 'Huy') ? 'selected' : ''; ?>>
                                Hủy
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Lọc
                        </button>
                        <?php if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])): ?>
                            <a href="index.php?act=admin/quanLyBooking" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Xóa bộ lọc
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Booking Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php if (isset($bookings) && !empty($bookings)): ?>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Mã booking</th>
                                    <th>Tour</th>
                                    <th>Khách hàng</th>
                                    <th style="width: 100px;">Số người</th>
                                    <th>Ngày khởi hành</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th style="width: 200px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>
                                            <span class="booking-id">#<?php echo $booking['booking_id']; ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($booking['ten_tour'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="fw-semibold"><?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?></div>
                                                <small class="text-muted">
                                                    <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                <i class="bi bi-people"></i> <?php echo $booking['so_nguoi']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $ngayKhoiHanh = !empty($booking['ngay_khoi_hanh']) ? $booking['ngay_khoi_hanh'] : ($booking['ngay_dat'] ?? null);
                                                $ngayKetThuc = isset($booking['ngay_ket_thuc']) && !empty($booking['ngay_ket_thuc']) ? $booking['ngay_ket_thuc'] : $ngayKhoiHanh;
                                            ?>
                                            <small class="d-block">
                                                <i class="bi bi-calendar-event text-primary"></i>
                                                Khởi hành: <?php echo $ngayKhoiHanh ? date('d/m/Y', strtotime($ngayKhoiHanh)) : 'N/A'; ?>
                                            </small>
                                            <small class="d-block">
                                                <i class="bi bi-calendar-check text-success"></i>
                                                Kết thúc: <?php echo $ngayKetThuc ? date('d/m/Y', strtotime($ngayKetThuc)) : 'N/A'; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <?php echo number_format($booking['tong_tien'] ?? 0); ?> ₫
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $booking['trang_thai']; ?>">
                                                <?php
                                                $statusLabels = [
                                                    'ChoXacNhan' => 'Chờ xác nhận',
                                                    'DaCoc' => 'Đã cọc',
                                                    'HoanTat' => 'Hoàn tất',
                                                    'Huy' => 'Hủy'
                                                ];
                                                echo $statusLabels[$booking['trang_thai']] ?? $booking['trang_thai'];
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-btn-group">
                                                <a href="index.php?act=booking/chiTiet&id=<?php echo $booking['booking_id']; ?>" 
                                                   class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?act=tour/phanBoNhanSuLichKhoiHanh&id=<?php echo $booking['tour_id']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Phân bổ nhân sự & dịch vụ">
                                                    <i class="bi bi-people-fill"></i>
                                                </a>
                                                <a href="index.php?act=admin/danhSachKhachBooking&booking_id=<?php echo $booking['booking_id']; ?>" class="btn btn-sm btn-success" title="Xem danh sách khách booking">
                                                    <i class="bi bi-people"></i>
                                                </a>
                                                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'HDV')): ?>
                                                    <a href="index.php?act=booking/chiTiet&id=<?php echo $booking['booking_id']; ?>" 
se="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                                                    <a href="index.php?act=booking/delete&id=<?php echo $booking['booking_id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?');"
                                                       title="Xóa">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h4 class="mb-3">Chưa có booking nào</h4>
                        <p class="text-muted mb-4">
                            <?php if (isset($_GET['trang_thai']) && !empty($_GET['trang_thai'])): ?>
                                Không tìm thấy booking với trạng thái này
                            <?php else: ?>
                                Hãy tạo booking đầu tiên cho khách hàng
                            <?php endif; ?>
                        </p>
                        <a href="index.php?act=booking/datTourChoKhach" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Đặt tour cho khách ngay
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>