<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ HDV - <?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'HDV'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        
        .welcome-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: -3rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
        }
        
        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            font-weight: bold;
        }
        
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid #f0f0f0;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            overflow: hidden;
        }
        
        /* Background image for feature cards */
        .feature-card .card-bg-image {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 1;
            background-size: cover;
            background-position: center;
            transition: all 0.4s ease;
            z-index: 0;
            border-radius: 1rem;
        }
        
        .feature-card:hover .card-bg-image {
            opacity: 1;
            transform: scale(1.05);
        }
        
        /* Add overlay to make text readable */
        .feature-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.65));
            border-radius: 1rem;
            z-index: 0;
        }
        
        .feature-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1.5rem rgba(102, 126, 234, 0.15);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        
        .feature-card h5, .feature-card p {
            position: relative;
            z-index: 1;
        }
        
        .tour-card {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .tour-status {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-upcoming { background: #e3f2fd; color: #1565c0; }
        .status-ongoing { background: #fff3e0; color: #e65100; }
        .status-completed { background: #e8f5e9; color: #2e7d32; }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="bi bi-house-door"></i> Trang chủ HDV
                    </h4>
                    <p class="mb-0 opacity-75">Chào mừng trở lại!</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="index.php?act=hdv/notifications" class="btn btn-light position-relative">
                        <i class="bi bi-bell"></i>
                        <?php if (isset($notifications_count) && $notifications_count > 0): ?>
                        <span class="notification-badge"><?php echo $notifications_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="index.php?act=hdv/profile" class="btn btn-light">
                        <i class="bi bi-person-circle"></i> Hồ sơ
                    </a>
                    <a href="index.php?act=auth/logout" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($hdv_info['ho_ten'] ?? 'H', 0, 1)); ?>
                    </div>
                </div>
                <div class="col">
                    <h3 class="mb-1">Xin chào, <?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'HDV'); ?>!</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar-check"></i> 
                        Hôm nay: <?php echo date('d/m/Y'); ?>
                        <?php if (!empty($today_tours)): ?>
                        | <span class="text-primary fw-semibold">
                            <i class="bi bi-flag"></i> Bạn có <?php echo count($today_tours); ?> tour hôm nay
                        </span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $stats['upcoming_tours'] ?? 0; ?></h3>
                            <small class="text-muted">Tour sắp tới</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-play-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $stats['ongoing_tours'] ?? 0; ?></h3>
                            <small class="text-muted">Tour đang chạy</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $stats['completed_tours'] ?? 0; ?></h3>
                            <small class="text-muted">Tour hoàn thành</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo number_format($stats['rating'] ?? 0, 1); ?></h3>
                            <small class="text-muted">Đánh giá TB</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h5 class="mb-3">
            <i class="bi bi-lightning-charge-fill text-warning"></i> Chức năng chính
        </h5>
        <div class="row mb-4">
            <div class="col-md-4">
                <a href="index.php?act=hdv/tours" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400');"></div>
                    <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                        <i class="bi bi-map"></i>
                    </div>
                    <h5 class="text-center mb-2">Lịch trình Tour</h5>
                    <p class="text-center text-muted mb-0 small">
                        Xem lịch trình và lịch làm việc của bạn
                    </p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=hdv/checkin" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=400');"></div>
                    <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                        <i class="bi bi-check2-square"></i>
                    </div>
                    <h5 class="text-center mb-2">Check-in & Điểm danh</h5>
                    <p class="text-center text-muted mb-0 small">
                        Xác nhận và điểm danh khách
                    </p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=hdv/nhat_ky" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1455390582262-044cdead277a?w=400');"></div>
                    <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                        <i class="bi bi-journal-text"></i>
                    </div>
                    <h5 class="text-center mb-2">Nhật ký Tour</h5>
                    <p class="text-center text-muted mb-0 small">
                        Ghi chú hành trình, sự cố, phản hồi
                    </p>
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <a href="index.php?act=hdv/luongThuong" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1556821552-23dede6e6d0d?w=400');"></div>
                    <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h5 class="text-center mb-2">Lương & Thưởng</h5>
                    <p class="text-center text-muted mb-0 small">
                        Xem lương, hoa hồng, thưởng của bạn
                    </p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=hdv/yeu_cau_dac_biet" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=400');"></div>
                    <div class="feature-icon bg-danger bg-opacity-10 text-danger mx-auto">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <h5 class="text-center mb-2">Yêu cầu đặc biệt</h5>
                    <p class="text-center text-muted mb-0 small">
                        Cập nhật ăn chay, bệnh lý, v.v.
                    </p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=hdv/phan_hoi" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400');"></div>
                    <div class="feature-icon bg-secondary bg-opacity-10 text-secondary mx-auto">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5 class="text-center mb-2">Đánh giá & Phản hồi</h5>
                    <p class="text-center text-muted mb-0 small">
                        Gửi đánh giá tour, dịch vụ
                    </p>
                </a>
            </div>
        </div>

        <!-- Upcoming Tours -->
        <h5 class="mb-3">
            <i class="bi bi-calendar-event text-primary"></i> Tour sắp tới
        </h5>
        <div class="row">
            <?php if (!empty($upcoming_tours)): ?>
                <?php foreach(array_slice($upcoming_tours, 0, 3) as $tour): ?>
                <div class="col-md-4">
                    <div class="tour-card">
                        <div class="p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0"><?php echo htmlspecialchars($tour['ten_tour']); ?></h6>
                                <span class="tour-status status-upcoming">
                                    Sắp tới
                                </span>
                            </div>
                            <div class="text-muted small">
                                <div class="mb-1">
                                    <i class="bi bi-calendar3"></i> 
                                    <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?>
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-people"></i> 
                                    <?php echo $tour['so_nguoi'] ?? 'N/A'; ?> khách
                                </div>
                                <div>
                                    <i class="bi bi-geo-alt"></i> 
                                    <?php echo htmlspecialchars($tour['diem_tap_trung'] ?? 'Chưa xác định'); ?>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['tour_id']; ?>" 
                                   class="btn btn-sm btn-outline-primary w-100">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Hiện tại bạn chưa có tour nào sắp tới.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Notifications -->
        <?php if (!empty($recent_notifications)): ?>
        <h5 class="mb-3 mt-4">
            <i class="bi bi-bell text-warning"></i> Thông báo mới
        </h5>
        <div class="card">
            <div class="list-group list-group-flush">
                <?php foreach(array_slice($recent_notifications, 0, 5) as $notif): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <?php echo htmlspecialchars($notif['tieu_de']); ?>
                                <?php if (!$notif['da_xem']): ?>
                                <span class="badge bg-danger">Mới</span>
                                <?php endif; ?>
                            </h6>
                            <p class="mb-1 small"><?php echo htmlspecialchars($notif['noi_dung']); ?></p>
                            <small class="text-muted">
                                <i class="bi bi-clock"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($notif['ngay_gui'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer text-center">
                <a href="index.php?act=hdv/notifications" class="text-decoration-none">
                    Xem tất cả thông báo <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <div class="mb-5"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
