<?php
$pageTitle = 'Dashboard Admin';
$currentPage = 'dashboard';
ob_start();
?>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .feature-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        transition: all 0.3s;
        text-decoration: none;
        color: var(--text-light);
        display: block;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
        transition: left 0.5s;
    }

    .feature-card:hover::before {
        left: 100%;
    }

    .feature-card:hover {
        border-color: var(--accent-gold);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 20px;
        background: rgba(212, 175, 55, 0.1);
        color: var(--accent-gold);
        transition: all 0.3s;
    }

    .feature-card:hover .feature-icon {
        background: var(--accent-gold);
        color: var(--primary-dark);
        transform: scale(1.1) rotate(5deg);
    }

    .feature-card h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .feature-card p {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .welcome-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .welcome-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary-dark);
        font-weight: bold;
        margin-bottom: 20px;
    }

    .welcome-section h2 {
        font-size: 28px;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }

    .welcome-section p {
        color: var(--text-muted);
        font-size: 14px;
    }

    .notification-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
</style>

<div class="welcome-section">
    <div class="welcome-avatar">
        <?php echo strtoupper(mb_substr($_SESSION['user_name'] ?? 'A', 0, 1, 'UTF-8')); ?>
    </div>
    <h2>Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrator'); ?>!</h2>
    <p>Quản trị viên hệ thống - Quản lý tour du lịch</p>
</div>

<div class="dashboard-grid">
    <a href="index.php?act=admin/quanLyTour" class="feature-card">
        <span class="notification-badge">12</span>
        <div class="feature-icon">📍</div>
        <h5>Quản lý Tour</h5>
        <p>Quản lý danh sách tour, lịch trình và thông tin chi tiết các chuyến đi</p>
    </a>

    <a href="index.php?act=admin/quanLyBooking" class="feature-card">
        <span class="notification-badge">8</span>
        <div class="feature-icon">📋</div>
        <h5>Quản lý Booking</h5>
        <p>Xem và quản lý đặt chỗ, xác nhận booking của khách hàng</p>
    </a>

    <a href="index.php?act=lichKhoiHanh/index" class="feature-card">
        <span class="notification-badge">5</span>
        <div class="feature-icon">📅</div>
        <h5>Lịch Khởi Hành</h5>
        <p>Theo dõi và quản lý lịch khởi hành, phân công nhân sự cho tour</p>
    </a>

    <a href="index.php?act=admin/nhanSu" class="feature-card">
        <div class="feature-icon">👥</div>
        <h5>Quản lý Nhân sự</h5>
        <p>Quản lý hướng dẫn viên, điều hành và toàn bộ nhân viên</p>
    </a>

    <a href="index.php?act=admin/quanLyNguoiDung" class="feature-card">
        <div class="feature-icon">👤</div>
        <h5>Quản lý Người dùng</h5>
        <p>Quản lý tài khoản, phân quyền và cấp quyền truy cập</p>
    </a>

    <a href="index.php?act=admin/nhaCungCap" class="feature-card">
        <div class="feature-icon">🏢</div>
        <h5>Quản lý Nhà cung cấp</h5>
        <p>Theo dõi báo giá, dịch vụ và duyệt yêu cầu từ nhà cung cấp</p>
    </a>

    <a href="index.php?act=admin/baoCaoTaiChinh" class="feature-card">
        <div class="feature-icon">📊</div>
        <h5>Báo cáo Tài chính</h5>
        <p>Thống kê doanh thu, chi phí và báo cáo tài chính tổng quan</p>
    </a>

    <a href="index.php?act=admin/danhGia" class="feature-card">
        <span class="notification-badge">3</span>
        <div class="feature-icon">⭐</div>
        <h5>Đánh giá & Phản hồi</h5>
        <p>Quản lý đánh giá và phản hồi từ khách hàng về dịch vụ</p>
    </a>

    <a href="index.php?act=auth/logout" class="feature-card" style="background: rgba(220, 53, 69, 0.2); border-color: rgba(220, 53, 69, 0.3);">
        <div class="feature-icon" style="background: rgba(220, 53, 69, 0.2); color: #dc3545;">🚪</div>
        <h5>Đăng xuất</h5>
        <p>Thoát khỏi hệ thống quản trị một cách an toàn</p>
    </a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
