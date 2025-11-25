<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhật ký Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        
        .diary-entry {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .entry-type-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .type-hanh_trinh { background: #e3f2fd; color: #1565c0; }
        .type-su_co { background: #ffebee; color: #c62828; }
        .type-phan_hoi { background: #f3e5f5; color: #6a1b9a; }
        .type-hoat_dong { background: #e8f5e9; color: #2e7d32; }
        
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .image-gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .image-gallery img:hover {
            transform: scale(1.05);
        }
        
        .filter-card {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.875rem;
            margin-top: 0.5rem;
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
                        <a class="nav-link active" href="index.php?act=admin/quanLyNhatKyTour">
                            <i class="bi bi-journal-text"></i> Nhật ký Tour
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
                            <i class="bi bi-journal-text"></i> Quản lý Nhật ký Tour
                        </h1>
                        <p class="lead mb-0 opacity-75">Theo dõi diễn biến, sự cố, phản hồi và hoạt động của các tour</p>
                    </div>
                  
                    </a>
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

        <!-- Thống kê -->
        <div class="stats-card">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['tong']; ?></div>
                        <div class="stat-label">Tổng nhật ký</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['hanh_trinh']; ?></div>
                        <div class="stat-label">📍 Hành trình</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['su_co']; ?></div>
                        <div class="stat-label">⚠️ Sự cố</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo $stats['phan_hoi']; ?></div>
                        <div class="stat-label">💬 Phản hồi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="filter-card">
            <form method="GET" action="index.php" class="row g-3">
                <input type="hidden" name="act" value="admin/quanLyNhatKyTour">
                <div class="col-md-3">
                    <label class="form-label"><strong>Tour:</strong></label>
                    <select name="tour_id" class="form-select">
                        <option value="">Tất cả tour</option>
                        <?php foreach ($tours as $tour): ?>
                            <option value="<?php echo $tour['tour_id']; ?>" <?php echo $tourId == $tour['tour_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tour['ten_tour']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><strong>HDV:</strong></label>
                    <select name="hdv_id" class="form-select">
                        <option value="">Tất cả HDV</option>
                        <?php foreach ($hdvList as $hdv): ?>
                            <option value="<?php echo $hdv['nhan_su_id']; ?>" <?php echo $hdvId == $hdv['nhan_su_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><strong>Loại:</strong></label>
                    <select name="loai_nhat_ky" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="hanh_trinh" <?php echo $loaiNhatKy == 'hanh_trinh' ? 'selected' : ''; ?>>📍 Hành trình</option>
                        <option value="su_co" <?php echo $loaiNhatKy == 'su_co' ? 'selected' : ''; ?>>⚠️ Sự cố</option>
                        <option value="phan_hoi" <?php echo $loaiNhatKy == 'phan_hoi' ? 'selected' : ''; ?>>💬 Phản hồi</option>
                        <option value="hoat_dong" <?php echo $loaiNhatKy == 'hoat_dong' ? 'selected' : ''; ?>>🎯 Hoạt động</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label"><strong>Từ ngày:</strong></label>
                    <input type="date" name="tu_ngay" class="form-control" value="<?php echo htmlspecialchars($tuNgay); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label"><strong>Đến ngày:</strong></label>
                    <input type="date" name="den_ngay" class="form-control" value="<?php echo htmlspecialchars($denNgay); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                    <a href="index.php?act=admin/quanLyNhatKyTour" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Xóa bộ lọc
                    </a>
                </div>
            </form>
        </div>

        <!-- Danh sách nhật ký -->
        <div class="row">
            <div class="col-12">
                <?php if (empty($nhatKyList)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Chưa có nhật ký nào. 
                        <a href="index.php?act=admin/formNhatKyTour" class="alert-link">Thêm nhật ký đầu tiên</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($nhatKyList as $entry): ?>
                        <div class="diary-entry">
                            <div class="entry-header">
                                <div>
                                    <h5 class="mb-1"><?php echo htmlspecialchars($entry['tieu_de'] ?? 'Không có tiêu đề'); ?></h5>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($entry['ngay_ghi'])); ?>
                                        <?php if ($entry['ten_tour']): ?>
                                            | <i class="bi bi-compass"></i> <?php echo htmlspecialchars($entry['ten_tour']); ?>
                                        <?php endif; ?>
                                        <?php if ($entry['hdv_ten']): ?>
                                            | <i class="bi bi-person"></i> <?php echo htmlspecialchars($entry['hdv_ten']); ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div>
                                    <span class="entry-type-badge type-<?php echo $entry['loai_nhat_ky']; ?>">
                                        <?php
                                        $types = [
                                            'hanh_trinh' => '📍 Hành trình',
                                            'su_co' => '⚠️ Sự cố',
                                            'phan_hoi' => '💬 Phản hồi',
                                            'hoat_dong' => '🎯 Hoạt động'
                                        ];
                                        echo $types[$entry['loai_nhat_ky']] ?? $entry['loai_nhat_ky'];
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="entry-content">
                                <p><?php echo nl2br(htmlspecialchars($entry['noi_dung'])); ?></p>
                                
                                <?php if (!empty($entry['cach_xu_ly']) && $entry['loai_nhat_ky'] === 'su_co'): ?>
                                    <div class="alert alert-warning mt-3">
                                        <strong><i class="bi bi-lightbulb"></i> Cách xử lý:</strong><br>
                                        <?php echo nl2br(htmlspecialchars($entry['cach_xu_ly'])); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($entry['hinh_anh'])): ?>
                                    <?php 
                                    $images = json_decode($entry['hinh_anh'], true);
                                    if ($images && is_array($images) && !empty($images)):
                                    ?>
                                        <div class="image-gallery">
                                            <?php foreach ($images as $img): ?>
                                                <img src="<?php echo BASE_URL . $img; ?>" alt="Hình ảnh" onclick="window.open('<?php echo BASE_URL . $img; ?>', '_blank')">
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="entry-actions mt-3">
                                <a href="index.php?act=admin/formNhatKyTour&id=<?php echo $entry['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="index.php?act=admin/deleteNhatKyTour&id=<?php echo $entry['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc muốn xóa nhật ký này?');">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

