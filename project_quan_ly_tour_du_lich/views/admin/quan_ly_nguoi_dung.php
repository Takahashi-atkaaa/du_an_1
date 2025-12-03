<?php
require_once __DIR__ . '/../../commons/function.php';
requireLogin();
requireRole('Admin');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .table-responsive {
            background: white;
            border-radius: 8px;
        }
        .badge {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php
    $navbarPath = __DIR__ . '/../../commons/navbar.php';
    if (file_exists($navbarPath)) {
        include $navbarPath;
    } else {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php?act=admin/dashboard">Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=admin/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php?act=admin/quanLyNguoiDung">Người dùng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?act=admin/baoCaoTaiChinh">Tài chính</a>
                        </li>
                    </ul>
                    <span class="navbar-text text-white-50">
                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                    </span>
                </div>
            </div>
        </nav>
        <?php
    }
    ?>
    
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h1>Quản lý Người dùng</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php?act=admin/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Quản lý Người dùng</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <?php if (isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?> alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['flash']['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách Người dùng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Vai trò</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                                            <td><?php echo htmlspecialchars($user['ten_dang_nhap'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($user['ho_ten'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($user['email'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($user['so_dien_thoai'] ?? ''); ?></td>
                                            <td>
                                                <?php
                                                $vaiTro = $user['vai_tro'] ?? 'KhachHang';
                                                $badgeClass = [
                                                    'Admin' => 'bg-danger',
                                                    'HDV' => 'bg-primary',
                                                    'KhachHang' => 'bg-success',
                                                    'NhaCungCap' => 'bg-warning'
                                                ];
                                                $class = $badgeClass[$vaiTro] ?? 'bg-secondary';
                                                ?>
                                                <span class="badge <?php echo $class; ?>"><?php echo htmlspecialchars($vaiTro); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['ngay_tao'] ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Không có người dùng nào.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

