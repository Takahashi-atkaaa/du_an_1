<?php
$pageTitle = 'Hồ sơ HDV';
$currentPage = 'profile';
ob_start();
?>

<style>
    .profile-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--accent-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        color: var(--primary-dark);
        margin: 0 auto 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--accent-gold);
        border-radius: 2px;
        padding: 30px;
        backdrop-filter: blur(10px);
    }

    .info-card h5 {
        font-size: 18px;
        letter-spacing: 1px;
        margin-bottom: 20px;
        color: var(--accent-gold);
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-table td {
        padding: 12px 0;
        font-size: 13px;
    }

    .info-table td:first-child {
        color: var(--text-muted);
        width: 40%;
    }

    .info-table td:last-child {
        font-weight: 600;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-success {
        background: rgba(25, 135, 84, 0.1);
        border-color: rgba(25, 135, 84, 0.3);
        color: #198754;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #dc3545;
    }
</style>

<div style="margin-bottom: 20px;">
    <a href="index.php?act=hdv/dashboard" class="btn btn-secondary btn-sm">
        ← Quay lại
    </a>
</div>

<div class="profile-header-section">
    <div class="profile-avatar">
        <?php echo strtoupper(substr($hdv_info['ho_ten'] ?? 'N', 0, 1)); ?>
    </div>
    <h2 style="margin-bottom: 10px;"><?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'N/A'); ?></h2>
    <p style="color: var(--text-muted);">Hướng dẫn viên du lịch</p>
</div>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success">
    ✓ <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<div class="alert alert-danger">
    ⚠ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<div class="info-grid">
    <!-- Thông tin cá nhân -->
    <div class="info-card">
        <h5>👤 Thông tin cá nhân</h5>
        <table class="info-table">
            <tr>
                <td>Mã nhân sự:</td>
                <td><?php echo htmlspecialchars($hdv_info['nhan_su_id'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Họ tên:</td>
                <td><?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?php echo htmlspecialchars($hdv_info['email'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td><?php echo htmlspecialchars($hdv_info['so_dien_thoai'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <td>Vai trò:</td>
                <td><?php echo htmlspecialchars($hdv_info['vai_tro'] ?? 'HDV'); ?></td>
            </tr>
        </table>
    </div>

    <!-- Thông tin bổ sung -->
    <?php if (!empty($hdv_info['ghi_chu']) || !empty($hdv_info['kinh_nghiem'])): ?>
    <div class="info-card">
        <h5>📝 Thông tin bổ sung</h5>
        <table class="info-table">
            <?php if (!empty($hdv_info['kinh_nghiem'])): ?>
            <tr>
                <td>Kinh nghiệm:</td>
                <td><?php echo htmlspecialchars($hdv_info['kinh_nghiem']); ?></td>
            </tr>
            <?php endif; ?>
            <?php if (!empty($hdv_info['ghi_chu'])): ?>
            <tr>
                <td>Ghi chú:</td>
                <td><?php echo htmlspecialchars($hdv_info['ghi_chu']); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Form cập nhật -->
<div class="card" style="margin-top: 30px;">
    <div style="padding: 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h3 style="font-size: 18px; letter-spacing: 1px;">✏️ Cập nhật thông tin</h3>
    </div>
    <div style="padding: 30px;">
        <form method="POST" action="index.php?act=hdv/update_profile">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="ho_ten" class="form-group input" 
                           value="<?php echo htmlspecialchars($hdv_info['ho_ten'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-group input" 
                           value="<?php echo htmlspecialchars($hdv_info['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="so_dien_thoai" class="form-group input" 
                           value="<?php echo htmlspecialchars($hdv_info['so_dien_thoai'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Ghi chú</label>
                <textarea name="ghi_chu" class="form-group input" rows="4" 
                          style="resize: vertical;"><?php echo htmlspecialchars($hdv_info['ghi_chu'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                💾 Lưu thay đổi
            </button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
