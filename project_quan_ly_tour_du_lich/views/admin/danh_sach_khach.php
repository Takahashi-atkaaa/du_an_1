<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Khách Theo Tour - Quản Lý Tour Du Lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        .stats-card {
            border-left: 4px solid;
            transition: all 0.3s;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .tour-info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 2px solid #dee2e6;
        }
        .customer-table-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .table-custom thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-custom tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .schedule-select-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        .schedule-select-card:hover {
            border-color: #667eea;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.2);
            transform: translateX(8px);
        }
        .signature-section {
            margin-top: 3rem;
            display: none;
            page-break-inside: avoid;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 11pt;
            }
            .page-header {
                background: none !important;
                color: black !important;
                border: 2px solid black;
                padding: 1rem !important;
            }
            .stats-card {
                border: 1px solid black !important;
                box-shadow: none !important;
            }
            table {
                page-break-inside: auto;
                border: 1px solid black !important;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                background: #f0f0f0 !important;
                color: black !important;
            }
            .signature-section {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                margin-top: 3rem;
            }
            .signature-box {
                text-align: center;
            }
            .signature-line {
                margin-top: 4rem;
                border-top: 1px solid black;
                padding-top: 0.5rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary no-print">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/quanLyTour">
                            <i class="bi bi-compass"></i> Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-people"></i> Danh sách khách
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
                            <i class="bi bi-people-fill"></i> Danh Sách Khách Theo Tour
                        </h1>
                        <p class="lead mb-0 opacity-75">Quản lý thông tin khách hàng, check-in và phân phòng</p>
                    </div>
                    <div class="d-flex gap-2 no-print">
                        <a href="<?php echo BASE_URL; ?>index.php?act=admin/dashboard" class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" class="btn btn-outline-light">
                            <i class="bi bi-compass"></i> Quản lý Tour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($lichKhoiHanh && $tour): ?>
            <!-- Tour Info -->
            <div class="tour-info-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-3">
                            <i class="bi bi-compass text-primary"></i>
                            <?php echo htmlspecialchars($tour['ten_tour'] ?? 'N/A'); ?>
                        </h2>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <small class="text-muted d-block"><i class="bi bi-hash"></i> Mã tour</small>
                                <span class="badge bg-primary">#<?php echo htmlspecialchars($tour['tour_id'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block"><i class="bi bi-calendar-event"></i> Khởi hành</small>
                                <strong><?php echo date('d/m/Y', strtotime($lichKhoiHanh['ngay_khoi_hanh'])); ?></strong>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block"><i class="bi bi-calendar-check"></i> Kết thúc</small>
                                <strong><?php echo date('d/m/Y', strtotime($lichKhoiHanh['ngay_ket_thuc'])); ?></strong>
                            </div>
                            <?php if (isset($tour['gia_co_ban']) && $tour['gia_co_ban']): ?>
                            <div class="col-md-3">
                                <small class="text-muted d-block"><i class="bi bi-currency-dollar"></i> Giá tour</small>
                                <strong class="text-success"><?php echo number_format($tour['gia_co_ban'], 0, ',', '.'); ?> VNĐ</strong>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-4 text-end no-print">
                        <button class="btn btn-lg btn-dark" onclick="window.print()">
                            <i class="bi bi-printer"></i> In Danh Sách Đoàn
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stats-card h-100" style="border-left-color: #0d6efd !important;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-1 small">Tổng booking</p>
                                    <h3 class="mb-0 fw-bold"><?php echo count($bookingList); ?></h3>
                                </div>
                                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-file-text"></i>
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
                                    <p class="text-muted mb-1 small">Đã check-in</p>
                                    <h3 class="mb-0 fw-bold text-success"><?php echo $checkinStats['total_checkin'] ?? 0; ?></h3>
                                </div>
                                <div class="stats-icon bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle"></i>
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
                                    <p class="text-muted mb-1 small">Chưa check-in</p>
                                    <h3 class="mb-0 fw-bold text-warning"><?php echo count($bookingList) - ($checkinStats['total_checkin'] ?? 0); ?></h3>
                                </div>
                                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="bi bi-clock-history"></i>
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
                                    <p class="text-muted mb-1 small">Đã phân phòng</p>
                                    <h3 class="mb-0 fw-bold text-info"><?php echo $roomStats['total_rooms'] ?? 0; ?></h3>
                                </div>
                                <div class="stats-icon bg-info bg-opacity-10 text-info">
                                    <i class="bi bi-building"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Table -->
            <div class="card customer-table-card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-table"></i> Danh sách khách hàng
                    </h5>
                    <?php if ($lichKhoiHanhId): ?>
                        <a href="index.php?act=admin/themKhachLichKhoiHanh&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                           class="btn btn-primary btn-sm no-print">
                            <i class="bi bi-plus-circle"></i> Thêm khách
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($bookingList)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                            <p class="mt-3 text-muted">Chưa có booking nào cho lịch khởi hành này</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">STT</th>
                                        <th>Mã Booking</th>
                                        <th>Tên Khách Hàng</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th style="width: 100px;">Số người</th>
                                        <th style="width: 150px;">Trạng thái</th>
                                        <th class="no-print" style="width: 200px;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookingList as $index => $booking): ?>
                                        <tr>
                                            <td class="fw-bold text-center"><?php echo $index + 1; ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    #<?php echo htmlspecialchars($booking['booking_id'] ?? 'N/A'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($booking['khach_ho_ten'] ?? 'N/A'); ?></div>
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="bi bi-envelope"></i>
                                                    <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="bi bi-phone"></i>
                                                    <?php echo htmlspecialchars($booking['so_dien_thoai'] ?? 'N/A'); ?>
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">
                                                    <i class="bi bi-people"></i> <?php echo ($booking['so_nguoi'] ?? 0); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($booking['checkin_id']): ?>
                                                    <span class="status-badge bg-success text-white">
                                                        <i class="bi bi-check-circle"></i>
                                                        <?php 
                                                            $status = $booking['checkin_status'];
                                                            echo $status === 'DaCheckIn' ? 'Đã check-in' : 
                                                                 ($status === 'DaCheckOut' ? 'Đã check-out' : 'Chưa check-in');
                                                        ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="status-badge bg-warning text-dark">
                                                        <i class="bi bi-clock"></i> Chưa check-in
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="no-print">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="index.php?act=admin/suaKhachLichKhoiHanh&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                       class="btn btn-info" title="Sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if (!$booking['checkin_id']): ?>
                                                        <a href="index.php?act=admin/checkInKhach&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                           class="btn btn-success" title="Check-in">
                                                            <i class="bi bi-check-circle"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="index.php?act=admin/checkInKhach&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                           class="btn btn-primary" title="Chi tiết">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="index.php?act=admin/phanPhongKhachSan&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                       class="btn btn-warning" title="Phân phòng">
                                                        <i class="bi bi-building"></i>
                                                    </a>
                                                    <a href="index.php?act=admin/xoaKhachLichKhoiHanh&booking_id=<?php echo $booking['booking_id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                                                       class="btn btn-danger" 
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?');"
                                                       title="Xóa">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Signature Section (for print) -->
            <div class="signature-section">
                <div class="signature-box">
                    <p class="fw-bold">NGƯỜI LẬP DANH SÁCH</p>
                    <p class="signature-line">(Ký và ghi rõ họ tên)</p>
                </div>
                <div class="signature-box">
                    <p class="fw-bold">TRƯỞNG ĐOÀN</p>
                    <p class="signature-line">(Ký và ghi rõ họ tên)</p>
                </div>
            </div>
            
        <?php elseif ($tour): ?>
            <!-- Select Schedule -->
            <div class="tour-info-card">
                <h2 class="fw-bold mb-3">
                    <i class="bi bi-compass text-primary"></i>
                    <?php echo htmlspecialchars($tour['ten_tour']); ?>
                </h2>
                <p class="lead">Vui lòng chọn lịch khởi hành để xem danh sách khách:</p>
                
                <?php if (!empty($lichKhoiHanhList)): ?>
                    <div class="mt-4">
                        <?php foreach ($lichKhoiHanhList as $lkh): ?>
                            <a href="index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=<?php echo $lkh['id']; ?>" 
                               class="schedule-select-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-2">
                                            <i class="bi bi-calendar-event text-primary"></i>
                                            <?php echo date('d/m/Y', strtotime($lkh['ngay_khoi_hanh'])); ?> 
                                            <i class="bi bi-arrow-right mx-2"></i>
                                            <?php echo date('d/m/Y', strtotime($lkh['ngay_ket_thuc'])); ?>
                                        </h5>
                                        <?php if (isset($lkh['gia_co_ban']) && $lkh['gia_co_ban']): ?>
                                            <p class="mb-0 text-success fw-bold">
                                                <i class="bi bi-currency-dollar"></i>
                                                <?php echo number_format($lkh['gia_co_ban'], 0, ',', '.'); ?> VNĐ
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <i class="bi bi-chevron-right fs-4 text-primary"></i>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        Tour này chưa có lịch khởi hành nào.
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <!-- Select Tour -->
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-compass fs-1 text-muted opacity-25"></i>
                    <p class="mt-3 text-muted">
                        Vui lòng chọn tour từ trang 
                        <a href="index.php?act=admin/quanLyTour" class="fw-bold">Quản lý tour</a> 
                        để xem danh sách khách.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
