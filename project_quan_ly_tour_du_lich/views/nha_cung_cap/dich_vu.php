<?php
$pageTitle = 'Dịch vụ - Nhà cung cấp';
$currentPage = 'dichVu';
ob_start();

$loaiDichVuMap = [
    'Xe' => 'Xe', 
    'KhachSan' => 'Khách sạn',
    'VeMayBay' => 'Vé máy bay',
    'NhaHang' => 'Nhà hàng',
    'DiemThamQuan' => 'Điểm tham quan',
    'Visa' => 'Visa',
    'BaoHiem' => 'Bảo hiểm',
    'Ve' => 'Vé',
    'Khac' => 'Khác'
];

$catalogStatusMap = [
    'HoatDong' => ['text' => 'Hoạt động', 'class' => 'success'],
    'TamDung' => ['text' => 'Tạm dừng', 'class' => 'warning'],
    'NgungHopTac' => ['text' => 'Ngưng hợp tác', 'class' => 'secondary'],
];

$statusMap = [
    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info']
];

$catalog = $catalogServices ?? [];
$assigned = $dichVu ?? [];
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

    .table-wrapper {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        margin-bottom: 30px;
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
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
    }

    .badge-warning {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .badge-success {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .badge-secondary {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    .badge-info {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
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
        min-height: 80px;
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

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: rgba(45, 45, 45, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        max-width: 900px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        backdrop-filter: blur(10px);
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

    @media (max-width: 768px) {
        .form-row,
        .form-row-3 {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>💼 Quản lý Dịch vụ</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Quản lý danh mục dịch vụ của bạn và các dịch vụ đang phục vụ tour</p>
        </div>
    </div>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ⚠ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php 
    $currentTab = 'dichVu';
    include __DIR__ . '/partials/main_nav.php';
?>

<div style="margin-bottom: 30px;">
    <button class="btn btn-primary" onclick="document.getElementById('modalAddCatalog').classList.add('show')">
        + Thêm dịch vụ
    </button>
</div>

<!-- Catalog services -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h5 style="margin: 0; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📦 Danh mục dịch vụ của bạn</h5>
            <small style="color: var(--text-muted); font-size: 11px;">Dùng để gửi báo giá nhanh cho đội điều hành</small>
        </div>
        <button class="btn btn-secondary btn-sm" onclick="document.getElementById('modalAddCatalog').classList.add('show')">
            + Nhập dịch vụ mới
        </button>
    </div>
    <?php if (empty($catalog)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Bạn chưa thêm dịch vụ nào. Nhấn "Nhập dịch vụ mới" để bắt đầu.</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên dịch vụ</th>
                    <th>Loại</th>
                    <th>Giá tham khảo</th>
                    <th>Công suất</th>
                    <th>Thời gian xử lý</th>
                    <th>Trạng thái</th>
                    <th style="text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($catalog as $service): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--text-light);"><?php echo htmlspecialchars($service['ten_dich_vu']); ?></strong>
                            <?php if (!empty($service['mo_ta'])): ?>
                                <div style="color: var(--text-muted); font-size: 11px; margin-top: 5px;">
                                    <?php echo nl2br(htmlspecialchars($service['mo_ta'])); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo $loaiDichVuMap[$service['loai_dich_vu']] ?? $service['loai_dich_vu']; ?></span>
                        </td>
                        <td>
                            <?php if ($service['gia_tham_khao']): ?>
                                <strong style="color: #0d6efd;"><?php echo number_format($service['gia_tham_khao'], 0, ',', '.'); ?>đ</strong>
                                <div style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($service['don_vi_tinh'] ?? ''); ?></div>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">Chưa nhập</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $service['cong_suat_toi_da'] ? $service['cong_suat_toi_da'] : '-'; ?></td>
                        <td><?php echo $service['thoi_gian_xu_ly'] ? htmlspecialchars($service['thoi_gian_xu_ly']) : '-'; ?></td>
                        <td>
                            <?php $st = $catalogStatusMap[$service['trang_thai']] ?? ['text' => $service['trang_thai'], 'class' => 'secondary']; ?>
                            <span class="badge badge-<?php echo $st['class']; ?>"><?php echo $st['text']; ?></span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 5px; justify-content: flex-end;">
                                <button class="btn btn-secondary btn-sm" 
                                        onclick="openEditModal(<?php echo $service['id']; ?>)"
                                        style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                                    ✏️
                                </button>
                                <form method="POST" action="index.php?act=nhaCungCap/deleteDichVu" onsubmit="return confirm('Bạn chắc chắn muốn xóa dịch vụ này?');" style="display: inline;">
                                    <input type="hidden" name="dich_vu_id" value="<?php echo $service['id']; ?>">
                                    <button type="submit" class="btn btn-secondary btn-sm"
                                            style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: rgba(220, 53, 69, 0.3);">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Assigned Services List -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h5 style="margin: 0; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📋 Dịch vụ đang phục vụ tour</h5>
    </div>
    <?php if (empty($assigned)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Hiện chưa có dịch vụ nào được phân bổ cho tour.</p>
        </div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tour</th>
                    <th>Loại dịch vụ</th>
                    <th>Tên dịch vụ</th>
                    <th>Số lượng</th>
                    <th>Ngày bắt đầu</th>
                    <th>Ngày kết thúc</th>
                    <th>Giá tiền</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assigned as $dv): ?>
                    <tr>
                        <td><strong style="color: var(--text-light);"><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong></td>
                        <td><span class="badge badge-info"><?php echo $loaiDichVuMap[$dv['loai_dich_vu']] ?? $dv['loai_dich_vu']; ?></span></td>
                        <td><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></td>
                        <td>
                            <?php echo $dv['so_luong']; ?>
                            <?php if ($dv['don_vi']): ?>
                                <small style="color: var(--text-muted); font-size: 11px;"><?php echo $dv['don_vi']; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $dv['ngay_bat_dau'] ? date('d/m/Y', strtotime($dv['ngay_bat_dau'])) : '<span style="color: var(--text-muted);">-</span>'; ?></td>
                        <td><?php echo $dv['ngay_ket_thuc'] ? date('d/m/Y', strtotime($dv['ngay_ket_thuc'])) : '<span style="color: var(--text-muted);">-</span>'; ?></td>
                        <td>
                            <?php if ($dv['gia_tien']): ?>
                                <strong style="color: #198754;"><?php echo number_format($dv['gia_tien'], 0, ',', '.'); ?>đ</strong>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">Chưa có giá</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php $status = $statusMap[$dv['trang_thai']] ?? ['text' => $dv['trang_thai'], 'class' => 'secondary']; ?>
                            <span class="badge badge-<?php echo $status['class']; ?>"><?php echo $status['text']; ?></span>
                        </td>
                        <td>
                            <a href="index.php?act=nhaCungCap/baoGia&trang_thai=<?php echo $dv['trang_thai']; ?>" 
                               class="btn btn-secondary btn-sm"
                               style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                                👁️ Theo dõi
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Modal add catalog -->
<div class="modal-overlay" id="modalAddCatalog" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">➕ Thêm dịch vụ mới</h5>
            <button onclick="document.getElementById('modalAddCatalog').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=nhaCungCap/storeDichVu">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Tên dịch vụ <span style="color: #dc3545;">*</span></label>
                    <input type="text" name="ten_dich_vu" class="form-group input" required>
                </div>
                <div class="form-group">
                    <label>Loại dịch vụ</label>
                    <select name="loai_dich_vu" class="form-group select">
                        <?php foreach ($loaiDichVuMap as $key => $label): ?>
                            <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Giá tham khảo</label>
                    <input type="number" name="gia_tham_khao" class="form-group input" min="0" step="1000" placeholder="VD: 1500000">
                </div>
                <div class="form-group">
                    <label>Đơn vị tính</label>
                    <input type="text" name="don_vi_tinh" class="form-group input" placeholder="phòng/đêm, suất, chuyến...">
                </div>
                <div class="form-group">
                    <label>Công suất tối đa</label>
                    <input type="number" name="cong_suat_toi_da" class="form-group input" min="0">
                </div>
                <div class="form-group">
                    <label>Thời gian xử lý</label>
                    <input type="text" name="thoi_gian_xu_ly" class="form-group input" placeholder="VD: 2 giờ">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Mô tả</label>
                    <textarea name="mo_ta" class="form-group textarea" rows="3"></textarea>
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Tài liệu / đường dẫn tham khảo</label>
                    <textarea name="tai_lieu_dinh_kem" class="form-group textarea" rows="2" placeholder="URL hoặc ghi chú thêm"></textarea>
                </div>
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="trang_thai" class="form-group select">
                        <option value="HoatDong">Hoạt động</option>
                        <option value="TamDung">Tạm dừng</option>
                        <option value="NgungHopTac">Ngưng hợp tác</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalAddCatalog').classList.remove('show')">Đóng</button>
                <button type="submit" class="btn btn-primary">💾 Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal edit catalog (dynamic) -->
<?php if (!empty($catalog)): ?>
    <?php foreach ($catalog as $service): ?>
    <div class="modal-overlay" id="modalEditCatalog<?php echo $service['id']; ?>" onclick="if(event.target === this) this.classList.remove('show')">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h5 style="margin: 0; color: var(--accent-gold);">✏️ Cập nhật dịch vụ: <?php echo htmlspecialchars($service['ten_dich_vu']); ?></h5>
                <button onclick="document.getElementById('modalEditCatalog<?php echo $service['id']; ?>').classList.remove('show')" 
                        style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
            </div>
            <form method="POST" action="index.php?act=nhaCungCap/updateDichVu">
                <input type="hidden" name="dich_vu_id" value="<?php echo $service['id']; ?>">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                    <div class="form-group">
                        <label>Tên dịch vụ <span style="color: #dc3545;">*</span></label>
                        <input type="text" name="ten_dich_vu" class="form-group input" value="<?php echo htmlspecialchars($service['ten_dich_vu']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Loại dịch vụ</label>
                        <select name="loai_dich_vu" class="form-group select">
                            <?php foreach ($loaiDichVuMap as $key => $label): ?>
                                <option value="<?php echo $key; ?>" <?php echo ($service['loai_dich_vu'] === $key) ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giá tham khảo</label>
                        <input type="number" name="gia_tham_khao" class="form-group input" min="0" step="1000" value="<?php echo $service['gia_tham_khao']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Đơn vị tính</label>
                        <input type="text" name="don_vi_tinh" class="form-group input" value="<?php echo htmlspecialchars($service['don_vi_tinh'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Công suất tối đa</label>
                        <input type="number" name="cong_suat_toi_da" class="form-group input" min="0" value="<?php echo $service['cong_suat_toi_da']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Thời gian xử lý</label>
                        <input type="text" name="thoi_gian_xu_ly" class="form-group input" value="<?php echo htmlspecialchars($service['thoi_gian_xu_ly'] ?? ''); ?>">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Mô tả</label>
                        <textarea name="mo_ta" class="form-group textarea" rows="3"><?php echo htmlspecialchars($service['mo_ta'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Tài liệu / đường dẫn tham khảo</label>
                        <textarea name="tai_lieu_dinh_kem" class="form-group textarea" rows="2"><?php echo htmlspecialchars($service['tai_lieu_dinh_kem'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai" class="form-group select">
                            <option value="HoatDong" <?php echo ($service['trang_thai'] === 'HoatDong') ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="TamDung" <?php echo ($service['trang_thai'] === 'TamDung') ? 'selected' : ''; ?>>Tạm dừng</option>
                            <option value="NgungHopTac" <?php echo ($service['trang_thai'] === 'NgungHopTac') ? 'selected' : ''; ?>>Ngưng hợp tác</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEditCatalog<?php echo $service['id']; ?>').classList.remove('show')">Đóng</button>
                    <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    function openEditModal(id) {
        document.getElementById('modalEditCatalog' + id).classList.add('show');
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
