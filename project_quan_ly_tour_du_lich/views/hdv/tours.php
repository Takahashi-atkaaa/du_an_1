<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch trình Tour - HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .tour-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .tour-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .tour-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .tour-card-body {
            padding: 1.5rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-SapKhoiHanh {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .status-DangChay {
            background: #fff3e0;
            color: #e65100;
        }
        
        .status-HoanThanh {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-DaHuy {
            background: #ffebee;
            color: #c62828;
        }
        
        .filter-tabs {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        }
        
        .filter-tabs .nav-link {
            border-radius: 0.5rem;
            color: #666;
            font-weight: 500;
        }
        
        .filter-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-map"></i> Lịch trình Tour
                    </h3>
                    <p class="mb-0 opacity-75">Quản lý lịch trình và tour của bạn</p>
                </div>
                <a href="index.php?act=hdv/dashboard" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filters -->
        <div class="filter-tabs">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?php echo (!isset($_GET['status']) || $_GET['status'] === 'all') ? 'active' : ''; ?>" 
                       href="index.php?act=hdv/tours&status=all">
                        <i class="bi bi-list-ul"></i> Tất cả
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['status']) && $_GET['status'] === 'SapKhoiHanh') ? 'active' : ''; ?>" 
                       href="index.php?act=hdv/tours&status=SapKhoiHanh">
                        <i class="bi bi-calendar-event"></i> Sắp khởi hành
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['status']) && $_GET['status'] === 'DangChay') ? 'active' : ''; ?>" 
                       href="index.php?act=hdv/tours&status=DangChay">
                        <i class="bi bi-play-circle"></i> Đang chạy
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['status']) && $_GET['status'] === 'HoanThanh') ? 'active' : ''; ?>" 
                       href="index.php?act=hdv/tours&status=HoanThanh">
                        <i class="bi bi-check-circle"></i> Hoàn thành
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tours List -->
        <?php if (!empty($tours)): ?>
            <div class="row">
                <?php foreach($tours as $tour): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="tour-card">
                        <div class="tour-card-header">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0"><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                                <span class="status-badge status-<?php echo $tour['trang_thai']; ?>">
                                    <?php 
                                    $statusText = [
                                        'SapKhoiHanh' => 'Sắp khởi hành',
                                        'DangChay' => 'Đang chạy',
                                        'HoanThanh' => 'Hoàn thành',
                                        'DaHuy' => 'Đã hủy'
                                    ];
                                    echo $statusText[$tour['trang_thai']] ?? $tour['trang_thai'];
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="tour-card-body">
                            <div class="mb-3">
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-calendar3"></i>
                                    <strong>Khởi hành:</strong> 
                                    <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?>
                                </div>
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-calendar-check"></i>
                                    <strong>Kết thúc:</strong> 
                                    <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'])); ?>
                                </div>
                                <?php if (!empty($tour['diem_tap_trung'])): ?>
                                <div class="text-muted small mb-2">
                                    <i class="bi bi-geo-alt"></i>
                                    <strong>Điểm tập trung:</strong> 
                                    <?php echo htmlspecialchars($tour['diem_tap_trung']); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($tour['so_nguoi'])): ?>
                                <div class="text-muted small">
                                    <i class="bi bi-people"></i>
                                    <strong>Số khách:</strong> 
                                    <?php echo $tour['so_nguoi']; ?> người
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['id']; ?>" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                                <?php if ($tour['trang_thai'] === 'DangChay' || $tour['trang_thai'] === 'SapKhoiHanh'): ?>
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?act=hdv/khach&tour_id=<?php echo $tour['id']; ?>" 
                                       class="btn btn-outline-success">
                                        <i class="bi bi-people"></i> Khách
                                    </a>
                                    <a href="index.php?act=hdv/checkin&tour_id=<?php echo $tour['id']; ?>" 
                                       class="btn btn-outline-info">
                                        <i class="bi bi-check2-square"></i> Check-in
                                    </a>
                                    <a href="index.php?act=hdv/nhat_ky&tour_id=<?php echo $tour['id']; ?>" 
                                       class="btn btn-outline-warning">
                                        <i class="bi bi-journal-text"></i> Nhật ký
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <?php 
                $filter = $_GET['status'] ?? 'all';
                if ($filter === 'all') {
                    echo 'Hiện tại bạn chưa có tour nào.';
                } else {
                    echo 'Không có tour nào trong trạng thái này.';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
