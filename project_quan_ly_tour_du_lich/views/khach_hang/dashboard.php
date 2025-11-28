<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 0.5rem 1.5rem rgba(102, 126, 234, 0.3);
        }
        
        .welcome-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: -3rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
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
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #f0f0f0;
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .feature-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.2);
        }
        
        .booking-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s;
        }
        
        .booking-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="container">
            <h1 class="mb-0"><i class="bi bi-person-circle me-2"></i>Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Khách hàng'); ?>!</h1>
            <p class="mb-0 mt-2">Chào mừng đến với cổng khách hàng</p>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2">Thông tin tài khoản</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-envelope me-2"></i><?php echo htmlspecialchars($khachHang['email'] ?? ''); ?><br>
                        <i class="bi bi-telephone me-2"></i><?php echo htmlspecialchars($khachHang['so_dien_thoai'] ?? ''); ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="index.php?act=khachHang/capNhatThongTin" class="btn btn-outline-primary">
                        <i class="bi bi-pencil-square me-2"></i>Cập nhật thông tin
                    </a>
                </div>
            </div>
        </div>

        <!-- Thống kê -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary text-white me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $tongBooking; ?></h3>
                            <small class="text-muted">Tổng booking</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning text-white me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $bookingChoXacNhan; ?></h3>
                            <small class="text-muted">Chờ xác nhận</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info text-white me-3">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $bookingDaCoc; ?></h3>
                            <small class="text-muted">Đã cọc</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success text-white me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0"><?php echo $bookingHoanTat; ?></h3>
                            <small class="text-muted">Hoàn tất</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if ($thongBaoChuaDoc > 0): ?>
            <div class="alert alert-info d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-bell-fill me-2"></i>
                    Bạn có <strong><?php echo $thongBaoChuaDoc; ?></strong> thông báo chưa đọc
                </div>
                <a href="index.php?act=khachHang/thongBao" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
        <?php endif; ?>

        <!-- Tour sắp tới -->
        <?php if (!empty($tourSapToi)): ?>
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-event me-2"></i>Tour sắp tới</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($tourSapToi as $tour): ?>
                        <div class="booking-card">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h5><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h5>
                                    <p class="text-muted mb-1">
                                        <i class="bi bi-calendar3 me-2"></i>Ngày khởi hành: <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'] ?? '')); ?><br>
                                        <i class="bi bi-people me-2"></i>Số người: <?php echo $tour['so_nguoi'] ?? 0; ?><br>
                                        <i class="bi bi-cash-coin me-2"></i>Tổng tiền: <?php echo number_format((float)($tour['tong_tien'] ?? 0)); ?> VNĐ
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge badge-status bg-<?php 
                                        echo match($tour['trang_thai']) {
                                            'ChoXacNhan' => 'warning',
                                            'DaCoc' => 'info',
                                            'HoanTat' => 'success',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo htmlspecialchars($tour['trang_thai'] ?? ''); ?>
                                    </span><br>
                                    <a href="index.php?act=khachHang/lichTrinhTour&booking_id=<?php echo $tour['booking_id']; ?>" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="bi bi-calendar3 me-1"></i>Xem lịch trình
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Các chức năng -->
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="index.php?act=khachHang/danhSachTour" class="feature-card">
                    <h5><i class="bi bi-search me-2 text-primary"></i>Tìm kiếm tour</h5>
                    <p class="text-muted mb-0">Xem danh sách tour và đặt tour mới</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=khachHang/hoaDon" class="feature-card">
                    <h5><i class="bi bi-receipt me-2 text-primary"></i>Hóa đơn & Thanh toán</h5>
                    <p class="text-muted mb-0">Xem hóa đơn và thanh toán online</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=khachHang/thongBao" class="feature-card">
                    <h5><i class="bi bi-bell me-2 text-primary"></i>Thông báo</h5>
                    <p class="text-muted mb-0">Xem thông báo từ hệ thống</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=khachHang/danhGia" class="feature-card">
                    <h5><i class="bi bi-star me-2 text-primary"></i>Đánh giá dịch vụ</h5>
                    <p class="text-muted mb-0">Đánh giá tour và dịch vụ</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=khachHang/guiYeuCauHoTro" class="feature-card">
                    <h5><i class="bi bi-question-circle me-2 text-primary"></i>Yêu cầu hỗ trợ</h5>
                    <p class="text-muted mb-0">Gửi yêu cầu hỗ trợ đến admin</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="index.php?act=auth/logout" class="feature-card">
                    <h5><i class="bi bi-box-arrow-right me-2 text-danger"></i>Đăng xuất</h5>
                    <p class="text-muted mb-0">Thoát khỏi tài khoản</p>
                </a>
            </div>
        </div>

        <!-- Danh sách booking -->
        <?php if (!empty($bookings)): ?>
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Lịch sử booking</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã booking</th>
                                    <th>Tour</th>
                                    <th>Ngày đặt</th>
                                    <th>Ngày khởi hành</th>
                                    <th>Số người</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>#<?php echo $booking['booking_id']; ?></td>
                                        <td><?php echo htmlspecialchars($booking['ten_tour'] ?? ''); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($booking['ngay_dat'] ?? '')); ?></td>
                                        <td><?php echo !empty($booking['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($booking['ngay_khoi_hanh'])) : 'Chưa xác định'; ?></td>
                                        <td><?php echo $booking['so_nguoi'] ?? 0; ?></td>
                                        <td><?php echo number_format((float)($booking['tong_tien'] ?? 0)); ?> VNĐ</td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo match($booking['trang_thai']) {
                                                    'ChoXacNhan' => 'warning',
                                                    'DaCoc' => 'info',
                                                    'HoanTat' => 'success',
                                                    'Huy' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo htmlspecialchars($booking['trang_thai'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?act=khachHang/hoaDon&booking_id=<?php echo $booking['booking_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                            <?php if (in_array($booking['trang_thai'], ['ChoXacNhan', 'DaCoc'])): ?>
                                                <a href="index.php?act=khachHang/thanhToan&booking_id=<?php echo $booking['booking_id']; ?>" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-credit-card"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





