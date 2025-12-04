
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Quản lý Người dùng - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"/>

    <style>
        body {
            background-color: #f5f6fa;
            font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        /* Thanh điều hướng quản trị (giữa page top) */
        .admin-header {
            background: #0d6efd;
            color: white;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 600;
            border-bottom: 3px solid #0956d6;
        }
        .admin-header a {
            color: white;
            text-decoration: none;
            font-weight: 700;
        }

        /* Banner */
        .admin-banner {
            background: linear-gradient(90deg, #4b6ef6, #7c42d6);
            padding: 34px 26px;
            border-radius: 12px;
            color: white;
            margin: 20px 0;
            box-shadow: 0 8px 30px rgba(108,99,255,0.08);
        }
        .admin-banner h1 {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            display: inline-flex;
            gap: 12px;
            align-items: center;
        }
        .admin-banner p {
            margin: 6px 0 0 0;
            opacity: 0.95;
        }

        /* action buttons on banner */
        .banner-actions { margin-top: 14px; }
        .banner-actions .btn {
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            font-weight: 600;
        }

        /* Search box */
        .search-box {
            background: white;
            padding: 16px;
            border-radius: 10px;
            box-shadow: 0 4px 14px rgba(16,24,40,0.04);
            margin-bottom: 22px;
        }
        .search-box input, .search-box select {
            border-radius: 8px;
        }

        /* Table area */
        .table-wrap {
            background: white;
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(16,24,40,0.04);
        }
        .table thead th {
            font-weight: 700;
            color: #344054;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.4px;
        }
        .table tbody tr:hover {
            background: #f5f9ff;
        }
        .badge-role {
            padding: 6px 10px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        .badge-Admin { background: linear-gradient(90deg,#ff7b7b,#ff4d4d); color: #fff; }
        .badge-HDV { background: linear-gradient(90deg,#66b9ff,#3a8dff); color: #fff; }
        .badge-KhachHang { background: linear-gradient(90deg,#63e6be,#20c997); color: #fff; }
        .badge-NhaCungCap { background: linear-gradient(90deg,#ffd873,#ffb703); color: #222; }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            .admin-banner h1 { font-size: 26px; }
            .admin-header { font-size: 16px; padding: 12px 16px; }
        }
    </style>
</head>
<body>

<!-- Admin header (link to dashboard) -->
<div class="admin-header">
    <a href="index.php?act=admin/dashboard" class="d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2 fs-4"></i>
        <span>Quản trị</span>
    </a>
    <span style="opacity:0.6;">›</span>
    <div>Người dùng</div>
</div>

<div class="container py-4">
    <!-- Banner -->
    <div class="admin-banner">
        <h1><i class="bi bi-people-fill"></i> Quản lý Người dùng</h1>
        <p class="mb-0">Quản lý thông tin nhân viên, khách hàng và nhà cung cấp</p>

       
    </div>

    <!-- Flash -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'] ?? 'info') ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['flash']['message'] ?? '') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Search & Filter form -->
    <div class="search-box">
    <form method="get" action="">
        <input type="hidden" name="act" value="admin/quanLyNguoiDung">
        <div class="row g-3 align-items-end">
            
            <div class="col-md-5"> 
                <label class="form-label fw-bold">Lọc theo vai trò</label>
                <select name="role" class="form-select">
                    <option value="">-- Tất cả --</option>
                    <option value="Admin" <?= ($role ?? '') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="HDV" <?= ($role ?? '') === 'HDV' ? 'selected' : '' ?>>HDV</option>
                    <option value="KhachHang" <?= ($role ?? '') === 'KhachHang' ? 'selected' : '' ?>>Khách hàng</option>
                    <option value="NhaCungCap" <?= ($role ?? '') === 'NhaCungCap' ? 'selected' : '' ?>>Nhà cung cấp</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold d-block invisible">Áp dụng</label>
                <button class="btn btn-primary w-100"><i class="bi bi-filter"></i> Áp dụng bộ lọc</button>
            </div>

            <div class="col-md-3 text-end">
                <label class="form-label fw-bold d-block invisible">Reset</label>
                <a href="index.php?act=admin/quanLyNguoiDung" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                </a>
            </div>
        </div>
    </form>
</div>

    <!-- Table -->
    <div class="table-wrap">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Danh sách Người dùng</h5>
            <small class="text-muted"><?= count($users) ?> kết quả</small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px;">ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th style="width:150px;">Vai trò</th>
                        <th style="width:150px;">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= htmlspecialchars($u['id']) ?></td>
                            <td><?= htmlspecialchars($u['ten_dang_nhap'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['ho_ten'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['so_dien_thoai'] ?? '-') ?></td>
                            <td>
                                <?php $v = $u['vai_tro'] ?? 'KhachHang'; ?>
                                <span class="badge-role <?= 'badge-' . htmlspecialchars($v) ?>"><?= htmlspecialchars($v) ?></span>
                            </td>
                            <td><?= htmlspecialchars($u['ngay_tao'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Không có dữ liệu phù hợp.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Nếu muốn: pagination area (hiện tạm) -->
        <!--
        <nav class="mt-3">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><a class="page-link">Trước</a></li>
                <li class="page-item active"><a class="page-link">1</a></li>
                <li class="page-item"><a class="page-link">2</a></li>
                <li class="page-item"><a class="page-link">Tiếp</a></li>
            </ul>
        </nav>
        -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
