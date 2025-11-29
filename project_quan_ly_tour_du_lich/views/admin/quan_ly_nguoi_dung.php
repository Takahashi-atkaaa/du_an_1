<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <div class="p-4 mb-4 rounded" style="background: linear-gradient(90deg, #4f8cff 0%, #6a82fb 100%); color: #fff; position: relative;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-people-fill" style="font-size:2.5rem;"></i>
                <div>
                    <h2 class="mb-0 fw-bold">Quản lý Người dùng</h2>
                    <div class="fs-5">Theo dõi và quản lý tất cả tài khoản hệ thống</div>
                </div>
            </div>
            <a href="index.php?act=admin/dashboard" class="btn btn-light fw-bold"><i class="bi bi-arrow-left"></i> Dashboard</a>
        </div>
    </div>
    <form method="get" action="index.php" class="row g-3 mb-4 align-items-end bg-white p-3 rounded shadow-sm">
        <input type="hidden" name="act" value="admin/quanLyNguoiDung">
        <div class="col-md-3">
            <label class="form-label fw-semibold">Tìm kiếm</label>
            <input type="text" name="search" class="form-control" placeholder="Tên, email, số điện thoại...">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Tháng tạo</label>
            <input type="month" name="search_month" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Ngày tạo</label>
            <input type="date" name="search_date" class="form-control">
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold">Vai trò</label>
            <select name="search_role" class="form-select">
                <option value="">-- Vai trò --</option>
                <option value="Admin">Admin</option>
                <option value="KhachHang">Khách hàng</option>
                <option value="HDV">Hướng dẫn viên</option>
                <option value="NhaCungCap">Nhà cung cấp</option>
                <option value="Khac">Khác</option>
            </select>
        </div>
        <div class="col-md-3 text-end">
            <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="bi bi-funnel"></i> Lọc dữ liệu</button>
        </div>
    </form>
    <div class="card shadow-sm rounded">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($nguoiDungList)):
                        $search = $_GET['search'] ?? '';
                        $firstRow = true;
                        foreach ($nguoiDungList as $nd): ?>
                        <?php
                        $isHighlighted = false;
                        if ($search !== '' && $firstRow) {
                            $isHighlighted = true;
                        }
                        ?>
                        <tr<?= $isHighlighted ? ' style="background-color: #d4edda;"' : '' ?> >
                            <td class="fw-bold text-primary"><?= $nd['id'] ?></td>
                            <td><?= htmlspecialchars($nd['ho_ten']) ?></td>
                            <td><?= htmlspecialchars($nd['email']) ?></td>
                            <td><?= htmlspecialchars($nd['so_dien_thoai']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($nd['vai_tro']) ?></span></td>
                            <td><?= htmlspecialchars($nd['ngay_tao']) ?></td>
                            <td>
                                <a href="index.php?act=admin/xemChiTietNguoiDung&id=<?= $nd['id'] ?>" class="btn btn-sm btn-info" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        <?php $firstRow = false; ?>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7" class="text-center py-4">Không có người dùng nào!</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


