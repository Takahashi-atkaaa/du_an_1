<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Lịch Khởi Hành - Admin</title>
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
        .filter-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
        }
        .schedule-card {
            transition: all 0.3s;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .schedule-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .tour-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .date-badge {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem;
            text-align: center;
        }
        .date-day {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
            line-height: 1;
        }
        .date-month {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
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
        .stats-card {
            border-left: 4px solid;
            transition: all 0.3s;
        }
        .stats-card:hover {
            transform: translateX(4px);
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
                        <a class="nav-link" href="index.php?act=admin/quanLyTour">
                            <i class="bi bi-compass"></i> Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?act=admin/quanLyLichKhoiHanh">
                            <i class="bi bi-calendar-check"></i> Lịch khởi hành
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
                            <i class="bi bi-calendar-check"></i> Quản lý Lịch Khởi Hành
                        </h1>
                        <p class="lead mb-0 opacity-75">Theo dõi và quản lý tất cả các lịch khởi hành tour</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?act=admin/dashboard" class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> Dashboard
                        </a>
                        <a href="index.php?act=lichKhoiHanh/create" class="btn btn-warning">
                            <i class="bi bi-plus-circle"></i> Tạo lịch mới
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <?php if (isset($lichKhoiHanhList) && !empty($lichKhoiHanhList)): ?>
        <div class="row g-3 mb-4">
            <?php
            $totalSchedules = count($lichKhoiHanhList);
            $upcomingSchedules = count(array_filter($lichKhoiHanhList, fn($l) => $l['trang_thai'] === 'SapKhoiHanh'));
            $ongoingSchedules = count(array_filter($lichKhoiHanhList, fn($l) => $l['trang_thai'] === 'DangChay'));
            $completedSchedules = count(array_filter($lichKhoiHanhList, fn($l) => $l['trang_thai'] === 'HoanThanh'));
            ?>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm" style="border-left-color: #0d6efd !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Tổng số lịch</p>
                                <h3 class="mb-0 fw-bold"><?php echo $totalSchedules; ?></h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-calendar-event text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm" style="border-left-color: #0dcaf0 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Sắp khởi hành</p>
                                <h3 class="mb-0 fw-bold"><?php echo $upcomingSchedules; ?></h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="bi bi-clock-history text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm" style="border-left-color: #198754 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Đang chạy</p>
                                <h3 class="mb-0 fw-bold"><?php echo $ongoingSchedules; ?></h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="bi bi-play-circle text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card border-0 shadow-sm" style="border-left-color: #6c757d !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Hoàn thành</p>
                                <h3 class="mb-0 fw-bold"><?php echo $completedSchedules; ?></h3>
                            </div>
                            <div class="bg-secondary bg-opacity-10 p-3 rounded">
                                <i class="bi bi-check-circle text-secondary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filter-card">
            <form method="get" action="index.php">
                <input type="hidden" name="act" value="admin/quanLyLichKhoiHanh">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Tìm kiếm</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Tên tour, điểm tập trung..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="SapKhoiHanh" <?php echo (isset($filters['trang_thai']) && $filters['trang_thai'] === 'SapKhoiHanh') ? 'selected' : ''; ?>>Sắp khởi hành</option>
                            <option value="DangChay" <?php echo (isset($filters['trang_thai']) && $filters['trang_thai'] === 'DangChay') ? 'selected' : ''; ?>>Đang chạy</option>
                            <option value="HoanThanh" <?php echo (isset($filters['trang_thai']) && $filters['trang_thai'] === 'HoanThanh') ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="<?php echo htmlspecialchars($filters['tu_ngay'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold text-muted">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="<?php echo htmlspecialchars($filters['den_ngay'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Schedule List -->
        <?php if (isset($lichKhoiHanhList) && !empty($lichKhoiHanhList)): ?>
            <div class="row g-4">
                <?php foreach ($lichKhoiHanhList as $lich): ?>
                    <div class="col-lg-6">
                        <div class="card schedule-card h-100">
                            <div class="card-body">
                                <div class="d-flex gap-3">
                                    <!-- Date Badge -->
                                    <div class="date-badge">
                                        <div class="date-day">
                                            <?php echo $lich['ngay_khoi_hanh'] ? date('d', strtotime($lich['ngay_khoi_hanh'])) : '--'; ?>
                                        </div>
                                        <div class="date-month">
                                            <?php 
                                            if ($lich['ngay_khoi_hanh']) {
                                                $months = ['', 'Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6', 
                                                          'Thg 7', 'Thg 8', 'Thg 9', 'Thg 10', 'Thg 11', 'Thg 12'];
                                                echo $months[(int)date('n', strtotime($lich['ngay_khoi_hanh']))];
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="tour-badge">#<?php echo $lich['id']; ?></span>
                                                <h5 class="card-title mb-1 mt-2">
                                                    <?php echo htmlspecialchars($lich['ten_tour'] ?? 'N/A'); ?>
                                                </h5>
                                            </div>
                                            <span class="status-badge <?php 
                                                echo match($lich['trang_thai']) {
                                                    'SapKhoiHanh' => 'bg-info text-dark',
                                                    'DangChay' => 'bg-success',
                                                    'HoanThanh' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                            ?>">
                                                <?php
                                                $statusLabels = [
                                                    'SapKhoiHanh' => 'Sắp khởi hành',
                                                    'DangChay' => 'Đang chạy',
                                                    'HoanThanh' => 'Hoàn thành'
                                                ];
                                                echo $statusLabels[$lich['trang_thai']] ?? $lich['trang_thai'];
                                                ?>
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <div class="row g-2 text-muted small">
                                                <div class="col-6">
                                                    <i class="bi bi-calendar-event text-primary"></i>
                                                    <strong>Khởi hành:</strong>
                                                    <?php echo $lich['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-clock text-info"></i>
                                                    <strong>Giờ:</strong>
                                                    <?php echo $lich['gio_xuat_phat'] ?? 'N/A'; ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-calendar-check text-success"></i>
                                                    <strong>Kết thúc:</strong>
                                                    <?php echo $lich['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?>
                                                </div>
                                                <div class="col-6">
                                                    <i class="bi bi-clock text-warning"></i>
                                                    <strong>Giờ:</strong>
                                                    <?php echo $lich['gio_ket_thuc'] ?? 'N/A'; ?>
                                                </div>
                                                <div class="col-12">
                                                    <i class="bi bi-geo-alt text-danger"></i>
                                                    <strong>Điểm tập trung:</strong>
                                                    <?php echo htmlspecialchars($lich['diem_tap_trung'] ?? 'Chưa xác định'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="bi bi-people"></i> <?php echo $lich['so_cho'] ?? 50; ?> chỗ
                                                </span>
                                            </div>
                                            <a href="index.php?act=lichKhoiHanh/chiTiet&id=<?php echo $lich['id']; ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i> Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h4 class="mb-3">Chưa có lịch khởi hành nào</h4>
                        <p class="text-muted mb-4">Hãy tạo lịch khởi hành đầu tiên để bắt đầu quản lý tour</p>
                        <a href="index.php?act=lichKhoiHanh/create" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle"></i> Tạo lịch khởi hành mới
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
