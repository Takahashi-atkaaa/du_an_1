<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch trình chi tiết - HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.75rem;
            top: 0.5rem;
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 50%;
            background: var(--primary-color);
            border: 4px solid white;
            box-shadow: 0 0 0 3px var(--primary-color);
            z-index: 1;
        }
        
        .timeline-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            transition: all 0.3s;
            border-left: 4px solid var(--primary-color);
        }
        
        .timeline-card:hover {
            transform: translateX(5px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        
        .info-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
        }
        
        .day-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .action-buttons {
            position: sticky;
            top: 2rem;
        }
        
        .quick-action-btn {
            width: 100%;
            margin-bottom: 0.75rem;
            border-radius: 0.5rem;
            padding: 0.75rem;
            text-align: left;
            transition: all 0.3s;
        }
        
        .quick-action-btn:hover {
            transform: translateX(5px);
        }
    </style>
</head>
<body>
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-calendar3"></i> Lịch trình chi tiết
                    </h3>
                    <p class="mb-0 opacity-75"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour'); ?></p>
                </div>
                <button onclick="window.history.back();" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin tour -->
                <div class="info-card">
                    <h5 class="mb-3"><i class="bi bi-info-circle text-primary"></i> Thông tin Tour</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Tên tour</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour không xác định'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Loại tour</div>
                            <div><?php echo htmlspecialchars($tour['loai_tour'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                <i class="bi bi-calendar-event"></i> Ngày khởi hành
                            </div>
                            <div class="fw-bold">
                                <?php 
                                if (!empty($tour['ngay_khoi_hanh'])) {
                                    echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh']));
                                    if (!empty($tour['gio_xuat_phat'])) {
                                        echo ' ' . date('H:i', strtotime($tour['gio_xuat_phat']));
                                    }
                                } else {
                                    echo 'Chưa xác định';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                <i class="bi bi-calendar-check"></i> Ngày kết thúc
                            </div>
                            <div class="fw-bold">
                                <?php 
                                if (!empty($tour['ngay_ket_thuc'])) {
                                    echo date('d/m/Y', strtotime($tour['ngay_ket_thuc']));
                                    if (!empty($tour['gio_ket_thuc'])) {
                                        echo ' ' . date('H:i', strtotime($tour['gio_ket_thuc']));
                                    }
                                } else {
                                    echo 'Chưa xác định';
                                }
                                ?>
                            </div>
                        </div>
                        <?php if (!empty($tour['diem_tap_trung'])): ?>
                        <div class="col-md-12 mb-3">
                            <div class="text-muted small">
                                <i class="bi bi-geo-alt"></i> Điểm tập trung
                            </div>
                            <div><?php echo htmlspecialchars($tour['diem_tap_trung']); ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">Trạng thái</div>
                            <div>
                                <span class="badge bg-<?php 
                                    echo match($tour['trang_thai'] ?? '') {
                                        'SapKhoiHanh' => 'primary',
                                        'DangChay' => 'warning',
                                        'HoanThanh' => 'success',
                                        'DaHuy' => 'danger',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php 
                                    echo match($tour['trang_thai'] ?? '') {
                                        'SapKhoiHanh' => 'Sắp khởi hành',
                                        'DangChay' => 'Đang chạy',
                                        'HoanThanh' => 'Hoàn thành',
                                        'DaHuy' => 'Đã hủy',
                                        default => $tour['trang_thai'] ?? 'N/A'
                                    };
                                    ?>
                                </span>
                            </div>
                        </div>
                        <?php if (!empty($tour['so_cho'])): ?>
                        <div class="col-md-6 mb-3">
                            <div class="text-muted small">
                                <i class="bi bi-people"></i> Số chỗ
                            </div>
                            <div><?php echo $tour['so_cho']; ?> chỗ</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($tour['mo_ta'])): ?>
                    <hr>
                    <div class="mt-3">
                        <h6 class="text-muted mb-2">Mô tả tour</h6>
                        <p><?php echo nl2br(htmlspecialchars($tour['mo_ta'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Lịch trình chi tiết -->
                <?php if (!empty($lichTrinhList)): ?>
                <div class="info-card">
                    <h5 class="mb-4">
                        <i class="bi bi-list-check text-primary"></i> Lịch trình chi tiết từng ngày
                    </h5>
                    <div class="timeline">
                        <?php foreach ($lichTrinhList as $index => $lichTrinh): ?>
                            <div class="timeline-item">
                                <div class="timeline-card">
                                    <span class="day-badge">
                                        <i class="bi bi-calendar-day"></i> Ngày <?php echo htmlspecialchars($lichTrinh['ngay_thu'] ?? ($index + 1)); ?>
                                    </span>
                                    
                                    <?php if (!empty($lichTrinh['dia_diem'])): ?>
                                    <div class="mb-3">
                                        <h6 class="text-primary mb-2">
                                            <i class="bi bi-geo-alt-fill"></i> Địa điểm
                                        </h6>
                                        <p class="mb-0"><?php echo htmlspecialchars($lichTrinh['dia_diem']); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($lichTrinh['hoat_dong'])): ?>
                                    <div>
                                        <h6 class="text-primary mb-2">
                                            <i class="bi bi-activity"></i> Hoạt động
                                        </h6>
                                        <div class="lh-lg">
                                            <?php echo nl2br(htmlspecialchars($lichTrinh['hoat_dong'])); ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="info-card">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle"></i> Chưa có lịch trình chi tiết cho tour này.
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="action-buttons">
                    <div class="info-card">
                        <h5 class="mb-3"><i class="bi bi-lightning-charge text-warning"></i> Hành động nhanh</h5>
                        
                        <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-primary quick-action-btn">
                            <i class="bi bi-info-circle"></i> Chi tiết tour
                        </a>
                        
                        <a href="index.php?act=hdv/khach&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-success quick-action-btn">
                            <i class="bi bi-people"></i> Danh sách khách
                        </a>
                        
                        <a href="index.php?act=hdv/checkin&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-info quick-action-btn">
                            <i class="bi bi-check2-square"></i> Check-in & Điểm danh
                        </a>
                        
                        <a href="index.php?act=hdv/nhat_ky&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-warning quick-action-btn">
                            <i class="bi bi-journal-text"></i> Nhật ký tour
                        </a>
                        
                        <a href="index.php?act=hdv/yeu_cau_dac_biet&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-danger quick-action-btn">
                            <i class="bi bi-exclamation-triangle"></i> Yêu cầu đặc biệt
                        </a>
                        
                        <?php if ($tour['trang_thai'] === 'HoanThanh'): ?>
                        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-outline-secondary quick-action-btn">
                            <i class="bi bi-star"></i> Đánh giá & Phản hồi
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($tour['ghi_chu'])): ?>
                    <div class="info-card">
                        <h6 class="mb-2"><i class="bi bi-sticky"></i> Ghi chú</h6>
                        <p class="small mb-0"><?php echo nl2br(htmlspecialchars($tour['ghi_chu'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
