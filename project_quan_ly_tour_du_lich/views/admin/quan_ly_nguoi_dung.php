<?php
$pageTitle = 'Quản lý Người dùng';
$currentPage = 'nguoiDung';
ob_start();
?>

<style>
    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .filter-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .table-wrapper {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: rgba(212, 175, 55, 0.1);
    }

    .table th {
        padding: 15px;
        text-align: left;
        font-size: 12px;
        letter-spacing: 1px;
        color: var(--accent-gold);
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-light);
        font-size: 13px;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .badge-role {
        padding: 6px 12px;
        border-radius: 2px;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .badge-Admin {
        background: rgba(255, 123, 123, 0.2);
        color: #ff7b7b;
        border: 1px solid rgba(255, 123, 123, 0.3);
    }

    .badge-HDV {
        background: rgba(102, 185, 255, 0.2);
        color: #66b9ff;
        border: 1px solid rgba(102, 185, 255, 0.3);
    }

    .badge-KhachHang {
        background: rgba(99, 230, 190, 0.2);
        color: #63e6be;
        border: 1px solid rgba(99, 230, 190, 0.3);
    }

    .badge-NhaCungCap {
        background: rgba(255, 216, 115, 0.2);
        color: #ffd873;
        border: 1px solid rgba(255, 216, 115, 0.3);
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 600;
    }

    .form-group .select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 12px 10px;
        font-size: 13px;
        border-radius: 2px;
        transition: all 0.3s;
        width: 100%;
        font-family: inherit;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23d4af37' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
    }

    .form-group .select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div>
        <h1>👥 Quản lý Người dùng</h1>
        <p style="color: var(--text-muted); margin-top: 10px;">Quản lý thông tin nhân viên, khách hàng và nhà cung cấp</p>
    </div>
</div>

<!-- Flash -->
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($_SESSION['flash']['message'] ?? ''); ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Search & Filter form -->
<div class="filter-section">
    <form method="get" action="">
        <input type="hidden" name="act" value="admin/quanLyNguoiDung">
        <div class="filter-row">
            <div class="form-group">
                <label>Lọc theo vai trò</label>
                <select name="role" class="select">
                    <option value="">-- Tất cả --</option>
                    <option value="Admin" <?php echo (isset($role) && $role === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="HDV" <?php echo (isset($role) && $role === 'HDV') ? 'selected' : ''; ?>>HDV</option>
                    <option value="KhachHang" <?php echo (isset($role) && $role === 'KhachHang') ? 'selected' : ''; ?>>Khách hàng</option>
                    <option value="NhaCungCap" <?php echo (isset($role) && $role === 'NhaCungCap') ? 'selected' : ''; ?>>Nhà cung cấp</option>
                </select>
            </div>
            <div class="form-group">
                <label style="opacity: 0;">Áp dụng</label>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    🔍 Áp dụng bộ lọc
                </button>
            </div>
            <div class="form-group">
                <label style="opacity: 0;">Reset</label>
                <a href="index.php?act=admin/quanLyNguoiDung" class="btn btn-secondary" style="width: 100%;">
                    🔄 Đặt lại
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Table -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: space-between; align-items: center;">
        <h5 style="margin: 0; color: var(--text-light); font-size: 16px;">Danh sách Người dùng</h5>
        <small style="color: var(--text-muted); font-size: 12px;"><?php echo count($users ?? []); ?> kết quả</small>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th style="width: 150px;">Vai trò</th>
                    <th style="width: 150px;">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <td><?php echo htmlspecialchars($u['ten_dang_nhap'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['ho_ten'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['so_dien_thoai'] ?? '-'); ?></td>
                            <td>
                                <?php $v = $u['vai_tro'] ?? 'KhachHang'; ?>
                                <span class="badge-role badge-<?php echo htmlspecialchars($v); ?>">
                                    <?php echo htmlspecialchars($v); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($u['ngay_tao'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            Không có dữ liệu phù hợp.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
