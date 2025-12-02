<!-- Form sửa thông tin khách hàng booking -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h3>Sửa thông tin khách hàng</h3>
    <form method="post" action="">
        <div class="mb-3">
            <label class="form-label">Họ tên *</label>
            <input type="text" name="ho_ten" class="form-control" value="<?= htmlspecialchars($khach['ten_khach_hang'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($khach['gmail'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số điện thoại *</label>
            <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($khach['so_dien_thoai'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Địa chỉ</label>
            <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($khach['dia_chi'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày sinh</label>
            <input type="date" name="ngay_sinh" class="form-control" value="<?= !empty($khach['ngay_sinh']) ? $khach['ngay_sinh'] : '' ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Giới tính</label>
            <select name="gioi_tinh" class="form-select">
                <option value="">Chọn giới tính</option>
                <option value="Nam" <?= ($khach['gioi_tinh'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
                <option value="Nữ" <?= ($khach['gioi_tinh'] ?? '') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                <option value="Khác" <?= ($khach['gioi_tinh'] ?? '') === 'Khác' ? 'selected' : '' ?>>Khác</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="index.php?act=admin/danhSachKhachBooking&booking_id=<?= htmlspecialchars($_GET['booking_id'] ?? '') ?>" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
