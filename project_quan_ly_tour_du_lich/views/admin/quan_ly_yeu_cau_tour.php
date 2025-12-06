<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Yêu cầu Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .stat-card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05);
        }
        .table > :not(caption) > * > * {
            vertical-align: middle;
        }
        .request-note {
            max-width: 300px;
            white-space: pre-line;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Quản lý Yêu cầu Tour từ Khách hàng</h1>
                <p class="text-muted mb-0">Xem và phản hồi các yêu cầu tour theo mong muốn</p>
            </div>
            <div>
                <a href="index.php?act=admin/dashboard" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Dashboard
                </a>
            </div>
        </div>

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

        <!-- Thống kê -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stat-card border-start border-primary border-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Tổng yêu cầu</p>
                        <h3 class="mb-0"><?php echo $tongYeuCau; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-start border-warning border-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Chưa xử lý</p>
                        <h3 class="mb-0 text-warning"><?php echo $chuaXuLy; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-start border-success border-3">
                    <div class="card-body">
                        <p class="text-muted mb-1">Đã xử lý</p>
                        <h3 class="mb-0 text-success"><?php echo $tongYeuCau - $chuaXuLy; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc -->
        <form class="card shadow-sm mb-4" method="GET" action="">
            <input type="hidden" name="act" value="admin/quanLyYeuCauTour">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên khách hàng, địa điểm..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="DaGui" <?php echo (($filters['trang_thai'] ?? '') === 'DaGui') ? 'selected' : ''; ?>>Đã gửi</option>
                            <option value="ChuaGui" <?php echo (($filters['trang_thai'] ?? '') === 'ChuaGui') ? 'selected' : ''; ?>>Chưa gửi</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>Tìm kiếm
                        </button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="index.php?act=admin/quanLyYeuCauTour" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-2"></i>Làm mới
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Bảng danh sách -->
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (!empty($yeuCauList)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Thông tin yêu cầu</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($yeuCauList as $index => $yc): ?>
                                    <?php
                                        // Parse thông tin từ nội dung
                                        $thongTin = [];
                                        foreach (explode("\n", $yc['noi_dung'] ?? '') as $row) {
                                            $kv = explode(": ", $row, 2);
                                            if (count($kv) == 2) {
                                                $thongTin[$kv[0]] = $kv[1];
                                            }
                                        }
                                        $thoiGian = !empty($yc['created_at']) ? date('d/m/Y H:i', strtotime($yc['created_at'])) : 'N/A';
                                    ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($yc['nguoi_gui_ten'] ?? 'N/A'); ?></div>
                                            <small class="text-muted">
                                                <?php echo htmlspecialchars($yc['nguoi_gui_email'] ?? ''); ?><br>
                                                <?php echo htmlspecialchars($yc['nguoi_gui_phone'] ?? ''); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="request-note">
                                                <strong>Địa điểm:</strong> <?php echo htmlspecialchars($thongTin['Địa điểm'] ?? 'N/A'); ?><br>
                                                <strong>Thời gian:</strong> <?php echo htmlspecialchars($thongTin['Thời gian'] ?? 'N/A'); ?><br>
                                                <strong>Số người:</strong> <?php echo htmlspecialchars($thongTin['Số người'] ?? 'N/A'); ?><br>
                                                <?php if (!empty($thongTin['Yêu cầu đặc biệt'])): ?>
                                                    <strong>Yêu cầu:</strong> <?php echo htmlspecialchars($thongTin['Yêu cầu đặc biệt']); ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <small><?php echo $thoiGian; ?></small>
                                        </td>
                                        <td>
                                            <?php if ($yc['trang_thai'] === 'DaGui'): ?>
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            <?php elseif (strpos($yc['noi_dung'] ?? '', 'Đã xử lý') !== false): ?>
                                                <span class="badge bg-success">Đã xử lý</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($yc['trang_thai'] ?? 'N/A'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?act=admin/chiTietYeuCauTour&id=<?php echo $yc['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Xem & Phản hồi
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">Chưa có yêu cầu tour nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

