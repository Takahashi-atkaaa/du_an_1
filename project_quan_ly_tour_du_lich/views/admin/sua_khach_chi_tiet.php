<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Khách - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Sửa Thông Tin Khách</h5>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="index.php?act=lichKhoiHanh/suaKhachChiTiet&id=<?php echo $khach['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="ho_ten" class="form-control" value="<?php echo htmlspecialchars($khach['ho_ten'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Số CMND/CCCD</label>
                            <input type="text" name="so_cmnd" class="form-control" value="<?php echo htmlspecialchars($khach['so_cmnd'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Số Passport</label>
                            <input type="text" name="so_passport" class="form-control" value="<?php echo htmlspecialchars($khach['so_passport'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" class="form-control" value="<?php echo $khach['ngay_sinh'] ?? ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giới tính</label>
                            <select name="gioi_tinh" class="form-select">
                                <option value="Nam" <?php echo ($khach['gioi_tinh'] ?? '') === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                                <option value="Nu" <?php echo ($khach['gioi_tinh'] ?? '') === 'Nu' ? 'selected' : ''; ?>>Nữ</option>
                                <option value="Khac" <?php echo ($khach['gioi_tinh'] ?? '') === 'Khac' ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Quốc tịch</label>
                            <input type="text" name="quoc_tich" class="form-control" value="<?php echo htmlspecialchars($khach['quoc_tich'] ?? 'Việt Nam'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" class="form-control" value="<?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($khach['email'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="dia_chi" class="form-control" value="<?php echo htmlspecialchars($khach['dia_chi'] ?? ''); ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="3"><?php echo htmlspecialchars($khach['ghi_chu'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Lưu thay đổi</button>
                        <a href="index.php?act=lichKhoiHanh/chiTiet&id=<?php echo $lichKhoiHanhId; ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

