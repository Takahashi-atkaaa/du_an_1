<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin - Khách hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .profile-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-gear me-2"></i>Cập nhật thông tin cá nhân</h2>
            <a href="index.php?act=khachHang/dashboard" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="profile-card">
            <form method="POST" action="index.php?act=khachHang/capNhatThongTin">
                <h4 class="mb-4">Thông tin tài khoản</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ho_ten" value="<?php echo htmlspecialchars($nguoiDung['ho_ten'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($nguoiDung['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="so_dien_thoai" value="<?php echo htmlspecialchars($nguoiDung['so_dien_thoai'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giới tính</label>
                        <select class="form-select" name="gioi_tinh">
                            <option value="">Chọn giới tính</option>
                            <option value="Nam" <?php echo (isset($khachHang['gioi_tinh']) && $khachHang['gioi_tinh'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nu" <?php echo (isset($khachHang['gioi_tinh']) && $khachHang['gioi_tinh'] === 'Nu') ? 'selected' : ''; ?>>Nữ</option>
                            <option value="Khac" <?php echo (isset($khachHang['gioi_tinh']) && $khachHang['gioi_tinh'] === 'Khac') ? 'selected' : ''; ?>>Khác</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" name="ngay_sinh" value="<?php echo !empty($khachHang['ngay_sinh']) ? date('Y-m-d', strtotime($khachHang['ngay_sinh'])) : ''; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea class="form-control" name="dia_chi" rows="2"><?php echo htmlspecialchars($khachHang['dia_chi'] ?? ''); ?></textarea>
                    </div>
                </div>

                <hr class="my-4">

                <h4 class="mb-4">Đổi mật khẩu</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="mat_khau_moi" placeholder="Để trống nếu không đổi">
                        <small class="text-muted">Chỉ điền nếu muốn đổi mật khẩu</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" name="xac_nhan_mat_khau" placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle me-2"></i>Cập nhật thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


