<?php
$pageTitle = 'Báo giá - Nhà cung cấp';
$currentPage = 'baoGia';
ob_start();

$baoGiaStats = $baoGiaStats ?? ['cho_xac_nhan' => 0, 'da_xac_nhan' => 0, 'tu_choi' => 0, 'hoan_tat' => 0, 'tong' => 0];
$filterLoai = $filterLoai ?? null;
$keyword = $keyword ?? '';
$lichKhoiHanhOptions = $lichKhoiHanhOptions ?? [];
$catalogServices = $catalogServices ?? [];
$statusMap = [
    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info'],
    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
];
$loaiDichVuMap = [
    'Xe' => 'Xe',
    'KhachSan' => 'Khách sạn',
    'Ve' => 'Vé',
    'VeMayBay' => 'Vé máy bay',
    'NhaHang' => 'Nhà hàng',
    'DiemThamQuan' => 'Điểm tham quan',
    'Visa' => 'Visa',
    'BaoHiem' => 'Bảo hiểm',
    'Khac' => 'Khác'
];
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stats-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        border-color: var(--accent-gold);
    }

    .stats-card.warning { border-left-color: #ffc107; }
    .stats-card.success { border-left-color: #198754; }
    .stats-card.danger { border-left-color: #dc3545; }
    .stats-card.info { border-left-color: #0dcaf0; }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-light);
        margin: 10px 0;
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 2px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid;
    }

    .filter-btn.active {
        background: var(--accent-gold);
        color: var(--primary-dark);
        border-color: var(--accent-gold);
    }

    .filter-btn:not(.active) {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .filter-btn:not(.active):hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
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
        letter-spacing: 0.5px;
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

    .badge-info {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
    }

    .badge-secondary {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
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

    .form-group .input:focus,
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
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        align-items: end;
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

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>📋 Quản lý Báo giá</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Quản lý và theo dõi các báo giá dịch vụ</p>
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
    $currentTab = 'baoGia';
    include __DIR__ . '/partials/main_nav.php';
?>

<!-- Stats -->
<div class="stats-grid">
    <div class="stats-card warning">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Chờ xác nhận</div>
        <div class="stats-number"><?php echo $baoGiaStats['cho_xac_nhan'] ?? 0; ?></div>
    </div>
    <div class="stats-card success">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Đã xác nhận</div>
        <div class="stats-number"><?php echo $baoGiaStats['da_xac_nhan'] ?? 0; ?></div>
    </div>
    <div class="stats-card danger">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Từ chối</div>
        <div class="stats-number"><?php echo $baoGiaStats['tu_choi'] ?? 0; ?></div>
    </div>
    <div class="stats-card info">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Hoàn tất</div>
        <div class="stats-number"><?php echo $baoGiaStats['hoan_tat'] ?? 0; ?></div>
    </div>
</div>

<div style="margin-bottom: 30px;">
    <button class="btn btn-primary" onclick="document.getElementById('modalBaoGiaThuCong').classList.add('show')">
        + Gửi báo giá thủ công
    </button>
</div>

<!-- Filter Section -->
<div class="card" style="margin-bottom: 30px;">
    <div style="padding: 25px;">
        <div style="margin-bottom: 20px;">
            <h5 style="margin-bottom: 15px; color: var(--accent-gold); font-size: 14px; letter-spacing: 1px;">Lọc theo trạng thái:</h5>
            <div class="filter-buttons">
                <a href="index.php?act=nhaCungCap/baoGia" class="filter-btn <?php echo !$trangThai ? 'active' : ''; ?>">
                    Tất cả (<?php echo $baoGiaStats['tong'] ?? 0; ?>)
                </a>
                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=ChoXacNhan" class="filter-btn <?php echo $trangThai === 'ChoXacNhan' ? 'active' : ''; ?>">
                    Chờ xác nhận
                </a>
                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=DaXacNhan" class="filter-btn <?php echo $trangThai === 'DaXacNhan' ? 'active' : ''; ?>">
                    Đã xác nhận
                </a>
                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=TuChoi" class="filter-btn <?php echo $trangThai === 'TuChoi' ? 'active' : ''; ?>">
                    Từ chối
                </a>
                <a href="index.php?act=nhaCungCap/baoGia&trang_thai=HoanTat" class="filter-btn <?php echo $trangThai === 'HoanTat' ? 'active' : ''; ?>">
                    Hoàn tất
                </a>
            </div>
        </div>
        
        <form method="GET" action="index.php">
            <input type="hidden" name="act" value="nhaCungCap/baoGia">
            <?php if ($trangThai): ?><input type="hidden" name="trang_thai" value="<?php echo htmlspecialchars($trangThai); ?>"><?php endif; ?>
            <div class="form-row">
                <div class="form-group">
                    <label>Loại dịch vụ</label>
                    <select name="loai" class="form-group select">
                        <option value="">Tất cả</option>
                        <?php foreach ($loaiDichVuMap as $key => $label): ?>
                            <option value="<?php echo $key; ?>" <?php echo ($filterLoai === $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Từ khóa</label>
                    <input type="text" name="keyword" class="form-group input" placeholder="Tìm tour hoặc dịch vụ" value="<?php echo htmlspecialchars($keyword); ?>">
                </div>
                <div class="form-group" style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">🔍 Lọc</button>
                    <a href="index.php?act=nhaCungCap/baoGia" class="btn btn-secondary" style="flex: 1;">🔄 Đặt lại</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Services List -->
<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
        <h5 style="margin: 0; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">📋 Danh sách dịch vụ</h5>
    </div>
    <?php if (empty($dichVu)): ?>
        <div style="text-align: center; padding: 60px 20px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Không có dịch vụ nào phù hợp bộ lọc.</p>
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
                <?php foreach ($dichVu as $dv): ?>
                    <tr>
                        <td>
                            <strong style="color: var(--text-light);"><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong>
                            <?php if ($dv['ngay_khoi_hanh']): ?>
                                <br><small style="color: var(--text-muted); font-size: 11px;">📅 <?php echo date('d/m/Y', strtotime($dv['ngay_khoi_hanh'])); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-info"><?php echo $loaiDichVuMap[$dv['loai_dich_vu']] ?? $dv['loai_dich_vu']; ?></span>
                        </td>
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
                            <span class="badge badge-<?php echo $status['class']; ?>" style="display: block; text-align: center;">
                                <?php echo $status['text']; ?>
                                <?php if (!empty($dv['thoi_gian_xac_nhan'])): ?>
                                    <br><small style="font-size: 10px; opacity: 0.8;"><?php echo date('d/m/Y H:i', strtotime($dv['thoi_gian_xac_nhan'])); ?></small>
                                <?php endif; ?>
                                <?php if ($dv['trang_thai'] === 'ChoXacNhan'): ?>
                                    <br><small style="font-size: 10px; opacity: 0.8;">Đợi điều hành phê duyệt</small>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <a href="index.php?act=nhaCungCap/chiTietDichVu&id=<?php echo $dv['id']; ?>" 
                               class="btn btn-secondary btn-sm"
                               style="background: rgba(13, 202, 240, 0.2); color: #0dcaf0; border-color: rgba(13, 202, 240, 0.3);">
                                👁️
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalBaoGiaThuCong" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">📝 Gửi báo giá thủ công</h5>
            <button onclick="document.getElementById('modalBaoGiaThuCong').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=nhaCungCap/storeBaoGiaThuCong">
            <div class="alert alert-info" style="margin-bottom: 20px;">
                ℹ️ Form này dùng khi bạn chủ động đề xuất dịch vụ cho một lịch khởi hành cụ thể.<br>
                <small>Sau khi gửi, yêu cầu sẽ chuyển sang điều hành để phê duyệt.</small>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                <div class="form-group">
                    <label>Lịch khởi hành <span style="color: #dc3545;">*</span></label>
                    <select name="lich_khoi_hanh_id" class="form-group select" required>
                        <option value="">-- Chọn --</option>
                        <?php foreach (($lichKhoiHanhOptions ?? []) as $opt): ?>
                            <option value="<?php echo $opt['id']; ?>">
                                #<?php echo $opt['id']; ?> - <?php echo htmlspecialchars($opt['ten_tour']); ?> (<?php echo date('d/m', strtotime($opt['ngay_khoi_hanh'])); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Chọn từ danh mục có sẵn</label>
                    <select id="catalogTemplateSelect" class="form-group select">
                        <option value="">-- Không chọn --</option>
                        <?php foreach (($catalogServices ?? []) as $service): ?>
                            <option value="<?php echo $service['id']; ?>"
                                data-name="<?php echo htmlspecialchars($service['ten_dich_vu'], ENT_QUOTES); ?>"
                                data-loai="<?php echo $service['loai_dich_vu']; ?>"
                                data-gia="<?php echo $service['gia_tham_khao']; ?>"
                                data-donvi="<?php echo htmlspecialchars($service['don_vi_tinh'] ?? '', ENT_QUOTES); ?>"
                                data-mota="<?php echo htmlspecialchars($service['mo_ta'] ?? '', ENT_QUOTES); ?>">
                                <?php echo htmlspecialchars($service['ten_dich_vu']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: var(--text-muted); font-size: 11px;">Chọn dịch vụ mẫu để tự động điền thông tin.</small>
                </div>
                <div class="form-group">
                    <label>Tên dịch vụ <span style="color: #dc3545;">*</span></label>
                    <input type="text" id="formBaoGiaTenDichVu" name="ten_dich_vu" class="form-group input" required>
                </div>
                <div class="form-group">
                    <label>Loại dịch vụ</label>
                    <select name="loai_dich_vu" id="formBaoGiaLoaiDichVu" class="form-group select">
                        <?php foreach ($loaiDichVuMap as $key => $label): ?>
                            <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Số lượng</label>
                    <input type="number" id="formBaoGiaSoLuong" name="so_luong" class="form-group input" min="1" value="1">
                </div>
                <div class="form-group">
                    <label>Đơn vị</label>
                    <input type="text" id="formBaoGiaDonVi" name="don_vi" class="form-group input" placeholder="phòng, suất, chuyến...">
                </div>
                <div class="form-group">
                    <label>Giá đề xuất (VND)</label>
                    <input type="number" id="formBaoGiaGiaTien" name="gia_tien" class="form-group input" min="0" step="1000">
                </div>
                <div class="form-group">
                    <label>Ngày bắt đầu</label>
                    <input type="date" name="ngay_bat_dau" class="form-group input">
                </div>
                <div class="form-group">
                    <label>Ngày kết thúc</label>
                    <input type="date" name="ngay_ket_thuc" class="form-group input">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Ghi chú</label>
                    <textarea name="ghi_chu" id="formBaoGiaMoTa" class="form-group input" rows="3" style="resize: vertical; min-height: 80px;"></textarea>
                </div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalBaoGiaThuCong').classList.remove('show')">Đóng</button>
                <button type="submit" class="btn btn-primary">📤 Gửi báo giá</button>
            </div>
        </form>
    </div>
</div>

<script>
    const templateSelect = document.getElementById('catalogTemplateSelect');
    if (templateSelect) {
        templateSelect.addEventListener('change', function () {
            const option = this.selectedOptions[0];
            if (!option || !option.dataset.name) return;

            document.getElementById('formBaoGiaTenDichVu').value = option.dataset.name || '';
            document.getElementById('formBaoGiaLoaiDichVu').value = option.dataset.loai || 'Khac';
            document.getElementById('formBaoGiaGiaTien').value = option.dataset.gia || '';
            document.getElementById('formBaoGiaDonVi').value = option.dataset.donvi || '';
            document.getElementById('formBaoGiaMoTa').value = option.dataset.mota || '';
        });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
