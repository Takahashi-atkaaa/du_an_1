<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đánh giá & Phản hồi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        .header-section h2 {
            margin: 0;
            font-weight: 600;
        }
        .header-section p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-left: 4px solid;
            background: white;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .stat-card h6 {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .stat-card h3 {
            font-weight: 600;
            margin: 0;
        }
        .rating-stars {
            color: #ffc107;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            background: white;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem;
        }
        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .btn {
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }
        .table tbody tr {
            transition: all 0.3s;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }
        .review-card {
            border-left: 3px solid;
        }
        .badge-rating {
            font-size: 1.1em;
            padding: 8px 12px;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0.6rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?act=admin/dashboard"><i class="bi bi-house-door"></i> Dashboard</a>
                <a class="nav-link" href="index.php?act=auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid" style="padding: 2rem;">
        <!-- Header Section -->
        <div class="header-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="bi bi-star-fill"></i> Quản lý Đánh giá & Phản hồi</h2>
                    <p>Theo dõi và quản lý phản hồi đánh giá từ khách hàng</p>
                </div>
                <div>
                    <a href="index.php?act=admin/danhGia/baoCao" class="btn btn-light">
                        <i class="bi bi-file-earmark-bar-graph"></i> Báo cáo tổng hợp
                    </a>
                </div>
            </div>
        </div>
                
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 10px; border-left: 4px solid #28a745;">
                <i class="bi bi-check-circle-fill"></i> <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 10px; border-left: 4px solid #dc3545;">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <!-- Thống kê -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="border-left-color: #667eea;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6>Tổng đánh giá</h6>
                            <h3 class="text-primary"><?= number_format($stats['tong_danh_gia']) ?></h3>
                        </div>
                        <div class="icon-wrapper ms-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="bi bi-chat-quote"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="border-left-color: #ffc107;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6>Điểm trung bình</h6>
                            <h3 class="rating-stars">
                                <?= number_format($stats['diem_trung_binh'], 1) ?> <i class="bi bi-star-fill"></i>
                            </h3>
                        </div>
                        <div class="icon-wrapper ms-auto" style="background: #ffc107; color: white;">
                            <i class="bi bi-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="border-left-color: #28a745;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6>Khách hài lòng (≥4★)</h6>
                            <h3 class="text-success"><?= number_format($stats['hai_long']) ?></h3>
                            <small class="text-muted">
                                <?= $stats['tong_danh_gia'] > 0 ? round(($stats['hai_long']/$stats['tong_danh_gia'])*100, 1) : 0 ?>%
                            </small>
                        </div>
                        <div class="icon-wrapper ms-auto" style="background: #28a745; color: white;">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="border-left-color: #dc3545;">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6>Không hài lòng (≤2★)</h6>
                            <h3 class="text-danger"><?= number_format($stats['khong_hai_long']) ?></h3>
                            <small class="text-muted">
                                <?= $stats['tong_danh_gia'] > 0 ? round(($stats['khong_hai_long']/$stats['tong_danh_gia'])*100, 1) : 0 ?>%
                            </small>
                        </div>
                        <div class="icon-wrapper ms-auto" style="background: #dc3545; color: white;">
                            <i class="bi bi-emoji-frown"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                
        <!-- Bộ lọc -->
        <div class="filter-section">
            <h5 class="mb-3"><i class="bi bi-funnel"></i> Bộ lọc</h5>
            <form method="GET" action="index.php">
                <input type="hidden" name="act" value="admin/danhGia">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Loại đánh giá</label>
                        <select name="loai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="Tour" <?= ($_GET['loai'] ?? '') === 'Tour' ? 'selected' : '' ?>>Tour</option>
                            <option value="NhaCungCap" <?= ($_GET['loai'] ?? '') === 'NhaCungCap' ? 'selected' : '' ?>>Nhà cung cấp</option>
                            <option value="NhanSu" <?= ($_GET['loai'] ?? '') === 'NhanSu' ? 'selected' : '' ?>>Nhân sự</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Điểm tối thiểu</label>
                        <select name="diem_min" class="form-select">
                            <option value="">Tất cả</option>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= ($_GET['diem_min'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?>★</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Điểm tối đa</label>
                        <select name="diem_max" class="form-select">
                            <option value="">Tất cả</option>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <option value="<?= $i ?>" <?= ($_GET['diem_max'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?>★</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Từ ngày</label>
                        <input type="date" name="tu_ngay" class="form-control" value="<?= $_GET['tu_ngay'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Đến ngày</label>
                        <input type="date" name="den_ngay" class="form-control" value="<?= $_GET['den_ngay'] ?? '' ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên, nội dung..." value="<?= $_GET['search'] ?? '' ?>">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                    <a href="index.php?act=admin/danhGia" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Danh sách đánh giá -->
        <div class="card">
            <div class="card-header text-white">
                <h5 class="mb-0"><i class="bi bi-chat-left-quote"></i> Danh sách Đánh giá (<?= count($danhGiaList) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($danhGiaList)): ?>
                    <div class="alert alert-info text-center m-4" style="border-radius: 10px;">
                        <i class="bi bi-info-circle fs-3"></i>
                        <p class="mb-0 mt-2">Không có đánh giá nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="padding: 1rem;">Ngày</th>
                                    <th>Khách hàng</th>
                                    <th>Loại</th>
                                    <th>Đối tượng</th>
                                    <th>Điểm</th>
                                    <th>Nội dung</th>
                                    <th>Phản hồi</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>
                                    <tbody>
                                        <?php foreach ($danhGiaList as $dg): ?>
                                            <tr class="review-card" style="border-left-color: <?= $dg['diem'] >= 4 ? '#28a745' : ($dg['diem'] <= 2 ? '#dc3545' : '#ffc107') ?>;">
                                                <td><?= date('d/m/Y', strtotime($dg['ngay_danh_gia'])) ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($dg['ten_khach_hang'] ?? 'N/A') ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($dg['email_khach_hang'] ?? '') ?></small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $loaiBadge = [
                                                        'Tour' => 'primary',
                                                        'NhaCungCap' => 'info',
                                                        'NhanSu' => 'success'
                                                    ];
                                                    $loaiText = [
                                                        'Tour' => 'Tour',
                                                        'NhaCungCap' => 'Nhà cung cấp',
                                                        'NhanSu' => 'Nhân sự'
                                                    ];
                                                    ?>
                                                    <span class="badge bg-<?= $loaiBadge[$dg['loai_danh_gia']] ?? 'secondary' ?>">
                                                        <?= $loaiText[$dg['loai_danh_gia']] ?? $dg['loai_danh_gia'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($dg['loai_danh_gia'] === 'Tour'): ?>
                                                        <?= htmlspecialchars($dg['ten_tour'] ?? 'N/A') ?>
                                                    <?php elseif ($dg['loai_danh_gia'] === 'NhaCungCap'): ?>
                                                        Nhà cung cấp #<?= $dg['nha_cung_cap_id'] ?? 'N/A' ?>
                                                    <?php elseif ($dg['loai_danh_gia'] === 'NhanSu'): ?>
                                                        Nhân sự #<?= $dg['nhan_su_id'] ?? 'N/A' ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-rating bg-<?= $dg['diem'] >= 4 ? 'success' : ($dg['diem'] <= 2 ? 'danger' : 'warning') ?>">
                                                        <?= $dg['diem'] ?> <i class="bi bi-star-fill"></i>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div style="max-width: 300px;">
                                                        <?= htmlspecialchars(mb_substr($dg['noi_dung'] ?? '', 0, 100)) ?>
                                                        <?= mb_strlen($dg['noi_dung'] ?? '') > 100 ? '...' : '' ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($dg['phan_hoi_admin'])): ?>
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã trả lời</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Chưa trả lời</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="index.php?act=admin/danhGia/chiTiet&id=<?= $dg['danh_gia_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="index.php?act=admin/danhGia/xoa&id=<?= $dg['danh_gia_id'] ?>" 
                                                   class="btn btn-sm btn-outline-danger"
                                                   onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')"
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
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
</html>
