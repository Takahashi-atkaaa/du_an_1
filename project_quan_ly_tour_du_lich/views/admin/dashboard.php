<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Quản lý Tour Du lịch</title>
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
            overflow-x: hidden;
        }
        
        /* Animated Background Particles */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(102, 126, 234, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
            animation: particleFloat 20s ease-in-out infinite;
        }
        
        @keyframes particleFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }
        
        /* Header */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 0.5rem 1.5rem rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        /* Header Animated Gradient */
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: headerShine 8s linear infinite;
        }
        
        @keyframes headerShine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .dashboard-header .container {
            position: relative;
            z-index: 1;
        }
        
        /* Welcome Card */
        .welcome-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-top: -3rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.6s ease-out;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Shimmer effect on welcome card */
        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
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
            position: relative;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            animation: avatarPulse 3s ease-in-out infinite;
        }
        
        @keyframes avatarPulse {
            0%, 100% { 
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 6px 25px rgba(118, 75, 162, 0.5);
                transform: scale(1.05);
            }
        }
        
        /* Avatar Ring Animation */
        .avatar-circle::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: var(--primary-color);
            border-right-color: var(--secondary-color);
            animation: avatarRotate 3s linear infinite;
            opacity: 0.6;
        }
        
        @keyframes avatarRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid #f0f0f0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        /* Background image for cards */
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
        
        /* Staggered animation delay */
        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }
        .feature-card:nth-child(5) { animation-delay: 0.5s; }
        .feature-card:nth-child(6) { animation-delay: 0.6s; }
        .feature-card:nth-child(7) { animation-delay: 0.7s; }
        .feature-card:nth-child(8) { animation-delay: 0.8s; }
        .feature-card:nth-child(9) { animation-delay: 0.9s; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Card Glow Effect on Hover */
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.4s ease;
            border-radius: 1rem;
        }
        
        .feature-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 1rem 3rem rgba(102, 126, 234, 0.2);
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
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        
        /* Icon Bounce on Hover */
        .feature-card:hover .feature-icon i {
            animation: iconBounce 0.6s ease;
        }
        
        @keyframes iconBounce {
            0%, 100% { transform: translateY(0); }
            25% { transform: translateY(-10px); }
            50% { transform: translateY(0); }
            75% { transform: translateY(-5px); }
        }
        
        .feature-card h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1a202c;
            position: relative;
            z-index: 1;
            transition: color 0.3s ease;
        }
        
        .feature-card:hover h5 {
            color: var(--primary-color);
        }
        
        .feature-card p {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
            transition: color 0.3s ease;
        }
        
        .feature-card:hover p {
            color: #4a5568;
        }
        
        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-radius: 50%;
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 0 6px;
            box-shadow: 0 3px 8px rgba(220, 53, 69, 0.4);
            animation: badgePulse 2s ease-in-out infinite;
            z-index: 2;
        }
        
        @keyframes badgePulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 3px 8px rgba(220, 53, 69, 0.4);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6);
            }
        }
        
        /* Badge Ring Animation */
        .notification-badge::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            border: 2px solid #dc3545;
            opacity: 0;
            animation: badgeRing 2s ease-in-out infinite;
        }
        
        @keyframes badgeRing {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.3);
                opacity: 0;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        
        /* Icon Colors */
        .icon-blue { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .icon-green { background: rgba(72, 187, 120, 0.1); color: #48bb78; }
        .icon-purple { background: rgba(159, 122, 234, 0.1); color: #9f7aea; }
        .icon-orange { background: rgba(237, 137, 54, 0.1); color: #ed8936; }
        .icon-teal { background: rgba(56, 178, 172, 0.1); color: #38b2ac; }
        .icon-pink { background: rgba(237, 100, 166, 0.1); color: #ed64a6; }
        .icon-indigo { background: rgba(102, 126, 234, 0.1); color: #667eea; }
        .icon-yellow { background: rgba(236, 201, 75, 0.1); color: #ecc94b; }
        .icon-red { background: rgba(245, 101, 101, 0.1); color: #f56565; }
        
        /* Logout Card */
        .feature-card.logout-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-color: transparent;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated gradient background */
        .feature-card.logout-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            animation: logoutShine 3s linear infinite;
        }
        
        @keyframes logoutShine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .feature-card.logout-card:hover {
            border-color: transparent;
            box-shadow: 0 1rem 3rem rgba(245, 87, 108, 0.4);
            transform: translateY(-8px) scale(1.02);
        }
        
        .feature-card.logout-card h5,
        .feature-card.logout-card p {
            color: #1a202c;
            position: relative;
            z-index: 1;
        }
        
        .feature-card.logout-card .feature-icon {
            background: rgba(255, 255, 255, 0.3);
            color: #1a202c;
            backdrop-filter: blur(10px);
        }
        
        .feature-card.logout-card:hover .feature-icon {
            background: rgba(255, 255, 255, 0.4);
            color: #c53030;
            transform: scale(1.15) rotate(-5deg);
        }
        
        /* Smooth Page Load */
        .container {
            animation: containerFadeIn 0.8s ease-out;
        }
        
        @keyframes containerFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        /* Button Hover Effect */
        .btn-light {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-light::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-light:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Responsive Grid */
        @media (max-width: 768px) {
            .welcome-card {
                margin-top: 0;
            }
            
            .feature-card:hover {
                transform: translateY(-4px) scale(1);
            }
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">
                        <i class="bi bi-speedometer2"></i> Dashboard Admin
                    </h4>
                    <p class="mb-0 opacity-75">Hệ thống quản lý tour du lịch</p>
                </div>
                <a href="index.php?act=auth/logout" class="btn btn-light">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="d-flex align-items-center">
                <div class="avatar-circle me-3">
                    <?php echo strtoupper(mb_substr($_SESSION['user_name'] ?? 'A', 0, 1, 'UTF-8')); ?>
                </div>
                <div>
                    <h5 class="mb-1">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrator'); ?>!</h5>
                    <p class="text-muted mb-0">
                        <i class="bi bi-shield-check text-success"></i> Quản trị viên hệ thống
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Feature Grid -->
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/quanLyTour" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400');"></div>
                    <div class="feature-icon icon-blue">
                        <i class="bi bi-geo-alt"></i>
                        <span class="notification-badge">12</span>
                    </div>
                    <h5>Quản lý Tour</h5>
                    <p>Quản lý danh sách tour, lịch trình và thông tin chi tiết các chuyến đi</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/nhaCungCap" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=400');"></div>
                    <div class="feature-icon icon-purple">
                        <i class="bi bi-building-gear"></i>
                    </div>
                    <h5>Quản lý Nhà cung cấp</h5>
                    <p>Theo dõi báo giá, dịch vụ và duyệt yêu cầu từ nhà cung cấp</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=lichKhoiHanh/index" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400');"></div>
                    <div class="feature-icon icon-green">
                        <i class="bi bi-calendar-event"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <h5>Lịch Khởi Hành</h5>
                    <p>Theo dõi và quản lý lịch khởi hành, phân công nhân sự cho tour</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/nhanSu" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=400');"></div>
                    <div class="feature-icon icon-purple">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Quản lý Nhân sự</h5>
                    <p>Quản lý hướng dẫn viên, điều hành và toàn bộ nhân viên</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/quanLyNguoiDung" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1511632765486-a01980e01a18?w=400');"></div>
                    <div class="feature-icon icon-orange">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h5>Quản lý Người dùng</h5>
                    <p>Quản lý tài khoản, phân quyền và cấp quyền truy cập</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/quanLyBooking" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=400');"></div>
                    <div class="feature-icon icon-teal">
                        <i class="bi bi-journal-check"></i>
                        <span class="notification-badge">8</span>
                    </div>
                    <h5>Quản lý Booking</h5>
                    <p>Xem và quản lý đặt chỗ, xác nhận booking của khách hàng</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=booking/datTourChoKhach" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400');"></div>
                    <div class="feature-icon icon-pink">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h5>Đặt tour cho khách</h5>
                    <p>Tạo booking mới và đặt tour trực tiếp cho khách hàng</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/baoCaoTaiChinh" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=400');"></div>
                    <div class="feature-icon icon-indigo">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h5>Báo cáo Tài chính</h5>
                    <p>Thống kê doanh thu, chi phí và báo cáo tài chính tổng quan</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=admin/danhGia" class="feature-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400');"></div>
                    <div class="feature-icon icon-yellow">
                        <i class="bi bi-star"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <h5>Đánh giá & Phản hồi</h5>
                    <p>Quản lý đánh giá và phản hồi từ khách hàng về dịch vụ</p>
                </a>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <a href="index.php?act=auth/logout" class="feature-card logout-card">
                    <div class="card-bg-image" style="background-image: url('https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=400');"></div>
                    <div class="feature-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>
                    <h5>Đăng xuất</h5>
                    <p>Thoát khỏi hệ thống quản trị một cách an toàn</p>
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
