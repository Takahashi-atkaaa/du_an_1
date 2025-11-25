<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-actions a, .table-actions button { margin-right: 4px; }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-hoatdong { background-color: #d4edda; color: #155724; }
        .status-ngung { background-color: #f8d7da; color: #721c24; }
        .tour-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.375rem;
        }
        .price-tag {
            font-weight: 600;
            color: #0d6efd;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?act=admin/quanLyTour">
                            <i class="bi bi-compass"></i> Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/quanLyBooking">
                            <i class="bi bi-calendar-check"></i> Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/nhanSu">
                            <i class="bi bi-people"></i> Nhân sự
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1"><i class="bi bi-compass text-primary"></i> Quản lý Tour</h3>
                <p class="text-muted mb-0">Quản lý thông tin các tour du lịch</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo BASE_URL; ?>index.php?act=tour/create" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Thêm tour mới
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="get" action="index.php" class="row g-3">
                    <input type="hidden" name="act" value="admin/quanLyTour">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Loại tour</label>
                        <select name="loai_tour" class="form-select form-select-sm">
                            <option value="">Tất cả</option>
                            <option value="TrongNuoc">Trong nước</option>
                            <option value="NuocNgoai">Nước ngoài</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Trạng thái</label>
                        <select name="trang_thai" class="form-select form-select-sm">
                            <option value="">Tất cả</option>
                            <option value="HoatDong">Hoạt động</option>
                            <option value="TamDung">Ngừng hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tìm kiếm</label>
                        <input type="search" name="search" class="form-control form-control-sm" placeholder="Tên tour, điểm đến...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
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

        <!-- Tours Table -->
        <?php if (!empty($tours)): ?>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">ID</th>
                                    <th>Tên tour</th>
                                    <th width="120">Loại tour</th>
                                    <th width="150" class="text-end">Giá cơ bản</th>
                                    <th width="120" class="text-center">Trạng thái</th>
                                    <th width="300" class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tours as $tour) : ?>
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">#<?php echo htmlspecialchars($tour['tour_id']); ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($tour['ten_tour']); ?></div>
                                        <small class="text-muted">
                                            <?php if (!empty($tour['diem_khoi_hanh'])): ?>
                                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($tour['diem_khoi_hanh']); ?>
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($tour['loai_tour'] === 'TrongNuoc'): ?>
                                            <span class="badge bg-info">
                                                <i class="bi bi-house-door"></i> Trong nước
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-globe"></i> Nước ngoài
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <span class="price-tag"><?php echo number_format((float)$tour['gia_co_ban'], 0, ',', '.'); ?>đ</span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($tour['trang_thai'] === 'HoatDong'): ?>
                                            <span class="status-badge status-hoatdong">
                                                <i class="bi bi-check-circle"></i> Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge status-ngung">
                                                <i class="bi bi-x-circle"></i> Ngừng
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center table-actions">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/update&id=<?php echo urlencode($tour['tour_id']); ?>" 
                                               class="btn btn-outline-primary" title="Sửa tour">
                                                <i class="bi bi-pencil"></i> Sửa
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>index.php?act=admin/chiTietTour&id=<?php echo urlencode($tour['tour_id']); ?>" 
                                               class="btn btn-outline-info" title="Chi tiết tour">
                                                <i class="bi bi-eye"></i> Chi tiết
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>index.php?act=admin/danhSachKhachTheoTour&tour_id=<?php echo urlencode($tour['tour_id']); ?>" 
                                               class="btn btn-outline-success" title="Danh sách khách">
                                                <i class="bi bi-people"></i> Khách
                                            </a>
                                        </div>
                                        <button onclick="if(confirm('Bạn có chắc muốn xóa tour này?')) window.location.href='<?php echo BASE_URL; ?>index.php?act=tour/delete&id=<?php echo urlencode($tour['tour_id']); ?>'" 
                                                class="btn btn-sm btn-outline-danger ms-1" title="Xóa tour">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light text-muted small">
                    <i class="bi bi-info-circle"></i> Tổng số: <strong><?php echo count($tours); ?></strong> tour
                </div>
            </div>
        <?php else: ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Chưa có tour nào</h5>
                    <p class="text-muted">Bắt đầu bằng cách thêm tour mới</p>
                    <a href="<?php echo BASE_URL; ?>index.php?act=tour/create" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Thêm tour đầu tiên
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
