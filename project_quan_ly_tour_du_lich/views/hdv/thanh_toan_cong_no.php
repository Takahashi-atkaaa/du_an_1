<?php
$pageTitle = 'Thanh toán công nợ tour - HDV';
$currentPage = 'thanhToanCongNo';
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

    .form-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 600;
    }

    .form-group .input,
    .form-group textarea,
    .form-group select {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        padding: 12px 10px;
        font-size: 13px;
        border-radius: 2px;
        transition: all 0.3s;
        width: 100%;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group .input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .form-group .select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23d4af37' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
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

    .divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
        margin: 30px 0;
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <h1>💰 Thanh toán công nợ tour</h1>
    <p style="color: var(--text-muted); margin-top: 10px;">Gửi hóa đơn và quản lý công nợ</p>
</div>

<!-- Form Section -->
<div class="form-section">
    <h3 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">Gửi hóa đơn</h3>
    <form method="POST" action="index.php?act=hdv/guiHoaDon" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tour_id">Chọn tour <span style="color: #dc3545;">*</span></label>
            <select name="tour_id" id="tour_id" class="form-group select" required>
                <option value="">-- Chọn tour --</option>
                <?php foreach($tours as $tour): ?>
                    <option value="<?php echo $tour['tour_id']; ?>"><?php echo htmlspecialchars($tour['ten_tour']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="so_tien">Số tiền cần thanh toán <span style="color: #dc3545;">*</span></label>
            <input type="number" name="so_tien" id="so_tien" class="form-group input" required>
        </div>
        <div class="form-group">
            <label for="loai_cong_no">Loại hóa đơn <span style="color: #dc3545;">*</span></label>
            <select name="loai_cong_no" id="loai_cong_no" class="form-group select" required>
                <option value="Thu">Gửi hóa đơn thu</option>
                <option value="Chi">Gửi hóa đơn chi</option>
            </select>
        </div>
        <div class="form-group">
            <label for="anh_hoa_don">Ảnh hóa đơn (bill/chuyển khoản) <span style="color: #dc3545;">*</span></label>
            <input type="file" name="anh_hoa_don" id="anh_hoa_don" class="form-group input" required accept="image/*">
        </div>
        <div class="form-group">
            <label for="ghi_chu">Ghi chú</label>
            <textarea name="ghi_chu" id="ghi_chu" class="form-group textarea"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">📤 Gửi hóa đơn</button>
    </form>
</div>

<div class="divider"></div>

<!-- Lịch sử hóa đơn -->
<h3 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">📋 Lịch sử hóa đơn đã gửi</h3>
<?php if (!empty($congNoHDVs)): ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Số tiền</th>
                    <th>Loại</th>
                    <th>Ảnh hóa đơn</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($congNoHDVs as $hd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hd['tour_id']); ?></td>
                        <td style="font-weight: 600; color: var(--accent-gold);"><?php echo number_format($hd['so_tien']); ?>đ</td>
                        <td>
                            <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 10px; border-radius: 2px; font-size: 11px;">
                                <?php echo htmlspecialchars($hd['loai_cong_no']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($hd['anh_hoa_don']): ?>
                                <a href="<?php echo htmlspecialchars($hd['anh_hoa_don']); ?>" target="_blank" class="btn btn-secondary btn-sm">
                                    👁️ Xem ảnh
                                </a>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(212, 175, 55, 0.2); color: var(--accent-gold); padding: 4px 10px; border-radius: 2px; font-size: 11px;">
                                <?php echo htmlspecialchars($hd['trang_thai']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($hd['ngay_gui']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Chưa có hóa đơn nào được gửi.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
