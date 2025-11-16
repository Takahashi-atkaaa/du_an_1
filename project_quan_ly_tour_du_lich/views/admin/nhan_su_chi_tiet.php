<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sơ yếu lý lịch - <?php echo htmlspecialchars($nhanSu['ho_ten'] ?? 'Nhân sự'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .info-section {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .info-section h5 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            width: 200px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        .status-locked {
            background-color: #f8d7da;
            color: #721c24;
        }
        .print-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        @media print {
            .no-print { display: none; }
            .profile-header { background: #667eea !important; }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary no-print">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/nhanSu">
                            <i class="bi bi-arrow-left"></i> Quay lại danh sách
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($error): ?>
        <div class="container mt-4">
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
            <a href="index.php?act=admin/nhanSu" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    <?php else: ?>
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        <?php if (!empty($nhanSu['avatar'])): ?>
                            <img src="<?php echo htmlspecialchars($nhanSu['avatar']); ?>" alt="Avatar" class="profile-avatar">
                        <?php else: ?>
                            <div class="profile-avatar d-flex align-items-center justify-content-center bg-light">
                                <i class="bi bi-person-fill" style="font-size: 4rem; color: #667eea;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <h2 class="mb-2"><?php echo htmlspecialchars($nhanSu['ho_ten'] ?? 'N/A'); ?></h2>
                        <p class="mb-1 fs-5">
                            <i class="bi bi-briefcase"></i> 
                            <strong>Vai trò:</strong> <?php echo htmlspecialchars($nhanSu['vai_tro'] ?? 'N/A'); ?>
                        </p>
                        <p class="mb-0">
                            <span class="status-badge <?php echo ($nhanSu['trang_thai'] ?? '') === 'HoatDong' ? 'status-active' : 'status-locked'; ?>">
                                <?php echo ($nhanSu['trang_thai'] ?? '') === 'HoatDong' ? 'Đang hoạt động' : 'Bị khóa'; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <!-- Thông tin cá nhân -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="bi bi-person-badge"></i> Thông tin cá nhân</h5>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-hash"></i> Mã nhân sự:</div>
                            <div class="info-value"><?php echo htmlspecialchars($nhanSu['nhan_su_id'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-person"></i> Họ và tên:</div>
                            <div class="info-value"><?php echo htmlspecialchars($nhanSu['ho_ten'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-envelope"></i> Email:</div>
                            <div class="info-value">
                                <?php if (!empty($nhanSu['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($nhanSu['email']); ?>">
                                        <?php echo htmlspecialchars($nhanSu['email']); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-telephone"></i> Điện thoại:</div>
                            <div class="info-value">
                                <?php if (!empty($nhanSu['so_dien_thoai'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars($nhanSu['so_dien_thoai']); ?>">
                                        <?php echo htmlspecialchars($nhanSu['so_dien_thoai']); ?>
                                    </a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-person-circle"></i> Tên đăng nhập:</div>
                            <div class="info-value"><?php echo htmlspecialchars($nhanSu['ten_dang_nhap'] ?? 'N/A'); ?></div>
                        </div>
                    </div>

                    <!-- Sức khỏe -->
                    <div class="info-section">
                        <h5><i class="bi bi-heart-pulse"></i> Tình trạng sức khỏe</h5>
                        <div class="info-row">
                            <div class="info-value">
                                <?php echo !empty($nhanSu['suc_khoe']) ? nl2br(htmlspecialchars($nhanSu['suc_khoe'])) : '<em class="text-muted">Chưa cập nhật</em>'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin nghiệp vụ -->
                <div class="col-md-6">
                    <div class="info-section">
                        <h5><i class="bi bi-award"></i> Trình độ & Chứng chỉ</h5>
                        <div class="info-row">
                            <div class="info-value">
                                <?php echo !empty($nhanSu['chung_chi']) ? nl2br(htmlspecialchars($nhanSu['chung_chi'])) : '<em class="text-muted">Chưa cập nhật</em>'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h5><i class="bi bi-translate"></i> Ngoại ngữ</h5>
                        <div class="info-row">
                            <div class="info-value">
                                <?php echo !empty($nhanSu['ngon_ngu']) ? nl2br(htmlspecialchars($nhanSu['ngon_ngu'])) : '<em class="text-muted">Chưa cập nhật</em>'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h5><i class="bi bi-graph-up"></i> Kinh nghiệm làm việc</h5>
                        <div class="info-row">
                            <div class="info-value">
                                <?php echo !empty($nhanSu['kinh_nghiem']) ? nl2br(htmlspecialchars($nhanSu['kinh_nghiem'])) : '<em class="text-muted">Chưa cập nhật</em>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin tài khoản -->
            <div class="row">
                <div class="col-12">
                    <div class="info-section">
                        <h5><i class="bi bi-shield-lock"></i> Thông tin tài khoản</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label"><i class="bi bi-key"></i> Vai trò hệ thống:</div>
                                    <div class="info-value">
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($nhanSu['vai_tro_nguoi_dung'] ?? 'N/A'); ?></span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><i class="bi bi-calendar-check"></i> Ngày tạo tài khoản:</div>
                                    <div class="info-value">
                                        <?php 
                                        if (!empty($nhanSu['ngay_tao'])) {
                                            $date = new DateTime($nhanSu['ngay_tao']);
                                            echo $date->format('d/m/Y H:i');
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <div class="info-label"><i class="bi bi-toggle-on"></i> Trạng thái:</div>
                                    <div class="info-value">
                                        <span class="status-badge <?php echo ($nhanSu['trang_thai'] ?? '') === 'HoatDong' ? 'status-active' : 'status-locked'; ?>">
                                            <i class="bi bi-<?php echo ($nhanSu['trang_thai'] ?? '') === 'HoatDong' ? 'check-circle' : 'x-circle'; ?>"></i>
                                            <?php echo ($nhanSu['trang_thai'] ?? '') === 'HoatDong' ? 'Hoạt động' : 'Bị khóa'; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><i class="bi bi-star"></i> Quyền cao cấp:</div>
                                    <div class="info-value">
                                        <?php echo !empty($nhanSu['quyen_cap_cao']) ? '<span class="badge bg-warning text-dark">Có</span>' : '<span class="badge bg-secondary">Không</span>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="row no-print">
                <div class="col-12 text-center mt-3">
                    <a href="index.php?act=admin/nhanSu" class="btn btn-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
                    <button onclick="window.print()" class="btn btn-primary btn-lg">
                        <i class="bi bi-printer"></i> In sơ yếu lý lịch
                    </button>
                </div>
            </div>
        </div>

        <!-- Floating Print Button -->
        <button onclick="window.print()" class="btn btn-primary print-btn no-print" title="In sơ yếu lý lịch">
            <i class="bi bi-printer-fill" style="font-size: 1.5rem;"></i>
        </button>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
