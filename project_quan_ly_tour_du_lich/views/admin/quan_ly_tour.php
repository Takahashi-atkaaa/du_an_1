<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
      /* ======= Theme TEAL – hiện đại hơn, mềm hơn ======= */

.table-actions a, .table-actions button {
    margin-right: 6px;
    border-radius: 10px;
    padding: 6px 10px;
}

/* Status badge */
.status-badge {
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-hoatdong {
    background-color: #d1fae5; /* xanh lá pastel */
    color: #065f46;
}

.status-ngung {
    background-color: #fee2e2; /* đỏ pastel */
    color: #991b1b;
}

/* Tour image */
.tour-image {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Price */
.price-tag {
    font-weight: 700;
    color: #0d9488;  /* TEAL */
    font-size: 1rem;
}

/* Table */
.table-hover tbody tr:hover {
    background: #f0fdfa; /* pastel mint */
}
.table thead {
    background: #ecfdf5;
}
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.4px;
}
.table td {
    vertical-align: middle;
}

/* Card */
.card {
    border-radius: 16px;
    border: none;
    box-shadow: 0 4px 14px rgba(0,0,0,0.04);
}
.card-body {
    padding: 24px;
}
.card-footer {
    border-top: 1px solid #e2e8f0;
}

/* Filter form */
.form-select-sm, .form-control-sm {
    border-radius: 12px;
    padding: 10px 12px;
    border: 1px solid #cbd5e1;
    transition: 0.2s;
}
.form-select-sm:focus,
.form-control-sm:focus {
    border-color: #14b8a6; /* teal focus */
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.25);
}

/* Navbar */
.navbar-dark {
    background: linear-gradient(90deg, #0d9488, #115e59); /* teal đậm */
}

/* Buttons */
.btn {
    border-radius: 12px !important;
    font-weight: 500;
}

.btn-success {
    background: #0d9488;
    border-color: #0d9488;
}
.btn-success:hover {
    background: #0f766e;
}

/* outline buttons */
.btn-outline-primary {
    color: #0d9488;
    border-color: #0d9488;
}
.btn-outline-primary:hover {
    background: #ccfbf1;
}

.btn-outline-info {
    color: #0e7490;
    border-color: #0e7490;
}
.btn-outline-info:hover {
    background: #e0f7fa;
}

.btn-outline-warning {
    color: #ca8a04;
    border-color: #facc15;
}
.btn-outline-warning:hover {
    background: #fef9c3;
}

.btn-outline-secondary:hover {
    background: #f1f5f9;
}

.btn-outline-dark:hover {
    background: #e2e8f0;
}

/* Delete button */
.btn-outline-danger {
    color: #dc2626;
    border-color: #fca5a5;
}
.btn-outline-danger:hover {
    background: #fee2e2;
    color: #b91c1c;
}

/* Empty state */
.bi-inbox {
    opacity: 0.7;
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
                            <option value="TrongNuoc" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'TrongNuoc') ? 'selected' : ''; ?>>Trong nước</option>
                            <option value="QuocTe" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'QuocTe') ? 'selected' : ''; ?>>Quốc tế</option>
                            <option value="TheoYeuCau" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'TheoYeuCau') ? 'selected' : ''; ?>>Theo yêu cầu</option>
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
                                        <?php 
                                        $loaiTourLabels = [
                                            'TrongNuoc' => ['Trong nước', 'bg-info', 'bi-house-door'],
                                            'QuocTe' => ['Quốc tế', 'bg-warning text-dark', 'bi-globe'],
                                            'TheoYeuCau' => ['Theo yêu cầu', 'bg-secondary', 'bi-star']
                                        ];
                                        $loai = $tour['loai_tour'] ?? 'TrongNuoc';
                                        $label = $loaiTourLabels[$loai] ?? $loaiTourLabels['TrongNuoc'];
                                        ?>
                                        <span class="badge <?php echo $label[1]; ?>">
                                            <i class="bi <?php echo $label[2]; ?>"></i> <?php echo $label[0]; ?>
                                            </span>
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
                                        <?php $qrPath = !empty($tour['qr_code_path']) ? BASE_URL . $tour['qr_code_path'] : null; ?>
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
                                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/clone&id=<?php echo urlencode($tour['tour_id']); ?>" 
                                               class="btn btn-outline-warning" title="Sao chép tour" 
                                               onclick="return confirm('Bạn có muốn sao chép tour này?');">
                                                <i class="bi bi-files"></i> Clone
                                            </a>
                                            <?php if ($qrPath): ?>
                                                <a href="<?php echo $qrPath; ?>" class="btn btn-outline-secondary" target="_blank" rel="noopener" title="Xem mã QR">
                                                    <i class="bi bi-qr-code"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/generateQr&id=<?php echo urlencode($tour['tour_id']); ?>"
                                               class="btn btn-outline-dark" title="Tạo/Cập nhật mã QR">
                                                <i class="bi bi-arrow-repeat"></i> QR
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
