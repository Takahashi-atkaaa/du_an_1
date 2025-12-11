<?php
$pageTitle = 'Quản lý Yêu cầu đặc biệt - HDV';
$currentPage = 'quanLyYeuCauDacBiet';
ob_start();

$priorityMap = [
    'khan_cap' => ['label' => 'Khẩn cấp', 'badge' => 'danger'],
    'cao' => ['label' => 'Cao', 'badge' => 'warning'],
    'trung_binh' => ['label' => 'Trung bình', 'badge' => 'info'],
    'thap' => ['label' => 'Thấp', 'badge' => 'secondary'],
];
$statusMap = [
    'moi' => ['label' => 'Mới', 'badge' => 'secondary'],
    'dang_xu_ly' => ['label' => 'Đang xử lý', 'badge' => 'primary'],
    'da_giai_quyet' => ['label' => 'Đã giải quyết', 'badge' => 'success'],
    'khong_the_thuc_hien' => ['label' => 'Không thể thực hiện', 'badge' => 'danger'],
];
$stats = $stats ?? [];
$totalRequests = (int)(($stats['khan_cap'] ?? 0) + ($stats['cao'] ?? 0) + ($stats['trung_binh'] ?? 0) + ($stats['thap'] ?? 0));
$requests = $requests ?? [];
$histories = $histories ?? [];
$tourList = $tourList ?? [];
$bookingList = $bookingList ?? [];
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

    .stat-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateX(4px);
        border-color: var(--accent-gold);
    }

    .stat-card.border-danger { border-left-color: #dc3545; }
    .stat-card.border-warning { border-left-color: #ffc107; }
    .stat-card.border-success { border-left-color: #198754; }
    .stat-card.border-secondary { border-left-color: #6c757d; }

    .badge-priority {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .badge-warning {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
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

    .badge-primary {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.3);
    }

    .badge-success {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
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

    .request-note {
        max-width: 320px;
        white-space: pre-line;
        color: var(--text-light);
        font-size: 12px;
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

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
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
        max-width: 800px;
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
        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>⚠️ Quản lý yêu cầu đặc biệt</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Theo dõi và xử lý các yêu cầu cá nhân của khách hàng</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-primary" onclick="document.getElementById('createRequestModal').classList.add('show')">
                + Tạo yêu cầu
            </button>
            <a href="index.php?act=hdv/dashboard" class="btn btn-secondary">
                ← Dashboard
            </a>
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

<!-- Stats -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
    <div class="stat-card border-danger">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Khẩn cấp</div>
        <h3 style="margin: 0; font-size: 28px; color: #dc3545;"><?php echo (int)($stats['khan_cap'] ?? 0); ?></h3>
    </div>
    <div class="stat-card border-warning">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Đang xử lý</div>
        <h3 style="margin: 0; font-size: 28px; color: #ffc107;"><?php echo (int)($stats['trang_thai_dang_xu_ly'] ?? 0); ?></h3>
    </div>
    <div class="stat-card border-success">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Đã giải quyết</div>
        <h3 style="margin: 0; font-size: 28px; color: #198754;"><?php echo (int)($stats['trang_thai_da_giai_quyet'] ?? 0); ?></h3>
    </div>
    <div class="stat-card border-secondary">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Tổng yêu cầu</div>
        <h3 style="margin: 0; font-size: 28px;"><?php echo $totalRequests; ?></h3>
    </div>
</div>

<!-- Filter Form -->
<div class="card" style="margin-bottom: 30px;">
    <form method="GET" action="">
        <input type="hidden" name="act" value="hdv/quanLyYeuCauDacBiet">
        <div class="filter-form">
            <div class="form-group">
                <label>Từ khóa</label>
                <input type="text" name="keyword" class="form-group input" placeholder="Tên khách, tour, số điện thoại" value="<?php echo htmlspecialchars($filters['keyword'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Ưu tiên</label>
                <select name="muc_do_uu_tien" class="form-group select">
                    <option value="">Tất cả</option>
                    <?php foreach ($priorityMap as $key => $info): ?>
                        <option value="<?php echo $key; ?>" <?php echo (($filters['muc_do_uu_tien'] ?? '') === $key) ? 'selected' : ''; ?>><?php echo $info['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Trạng thái</label>
                <select name="trang_thai" class="form-group select">
                    <option value="">Tất cả</option>
                    <?php foreach ($statusMap as $key => $info): ?>
                        <option value="<?php echo $key; ?>" <?php echo (($filters['trang_thai'] ?? '') === $key) ? 'selected' : ''; ?>><?php echo $info['label']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Loại yêu cầu</label>
                <select name="loai_yeu_cau" class="form-group select">
                    <option value="">Tất cả</option>
                    <?php $types = ['an_uong' => 'Ăn uống', 'suc_khoe' => 'Sức khỏe', 'di_chuyen' => 'Di chuyển', 'phong_o' => 'Phòng ở', 'hoat_dong' => 'Hoạt động', 'khac' => 'Khác'];
                    foreach ($types as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo (($filters['loai_yeu_cau'] ?? '') === $key) ? 'selected' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tour</label>
                <select name="tour_id" class="form-group select">
                    <option value="0">Tất cả tour</option>
                    <?php foreach ($tourList as $tourId): ?>
                        <?php
                        $tourName = '';
                        foreach ($requests as $req) {
                            if ($req['tour_id'] == $tourId) {
                                $tourName = $req['ten_tour'] ?? 'Tour #' . $tourId;
                                break;
                            }
                        }
                        ?>
                        <option value="<?php echo $tourId; ?>" <?php echo ((int)($filters['tour_id'] ?? 0) === (int)$tourId) ? 'selected' : ''; ?>><?php echo htmlspecialchars($tourName ?: 'Tour #' . $tourId); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Từ ngày</label>
                <input type="date" name="date_from" class="form-group input" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Đến ngày</label>
                <input type="date" name="date_to" class="form-group input" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Lọc</button>
            </div>
            <div class="form-group">
                <a href="index.php?act=hdv/quanLyYeuCauDacBiet" class="btn btn-secondary" style="width: 100%;">🔄 Đặt lại</a>
            </div>
        </div>
    </form>
</div>

<!-- Requests Table -->
<?php if (!empty($requests)): ?>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Khách hàng</th>
                    <th>Tour</th>
                    <th>Chi tiết yêu cầu</th>
                    <th>Ưu tiên</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request):
                    $priorityInfo = $priorityMap[$request['muc_do_uu_tien'] ?? 'trung_binh'] ?? $priorityMap['trung_binh'];
                    $statusInfo = $statusMap[$request['trang_thai'] ?? 'moi'] ?? $statusMap['moi'];
                    $historyData = htmlspecialchars(json_encode($histories[$request['id']] ?? [], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                ?>
                    <tr>
                        <td>
                            <strong style="color: var(--accent-gold);">#<?php echo $request['id']; ?></strong><br>
                            <small style="color: var(--text-muted); font-size: 11px;"><?php echo date('d/m/Y H:i', strtotime($request['ngay_tao'] ?? 'now')); ?></small>
                        </td>
                        <td>
                            <strong style="color: var(--text-light);"><?php echo htmlspecialchars($request['khach_ten'] ?? 'N/A'); ?></strong>
                            <div style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($request['khach_phone'] ?? ''); ?></div>
                            <div style="color: var(--text-muted); font-size: 11px;"><?php echo htmlspecialchars($request['khach_email'] ?? ''); ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--text-light);"><?php echo htmlspecialchars($request['ten_tour'] ?? 'Chưa rõ'); ?></div>
                            <small style="color: var(--text-muted); font-size: 11px;">Khởi hành: <?php echo !empty($request['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($request['ngay_khoi_hanh'])) : 'N/A'; ?></small>
                        </td>
                        <td class="request-note">
                            <div style="font-weight: 600; margin-bottom: 5px; color: var(--accent-gold);"><?php echo htmlspecialchars($request['tieu_de'] ?? 'Yêu cầu'); ?></div>
                            <div style="color: var(--text-light);"><?php echo nl2br(htmlspecialchars($request['mo_ta'] ?? '')); ?></div>
                            <?php if (!empty($request['ghi_chu_hdv'])): ?>
                                <div style="margin-top: 10px; padding: 8px; background: rgba(13, 110, 253, 0.1); border-left: 3px solid #0d6efd; border-radius: 2px;">
                                    <strong style="font-size: 11px; color: #0d6efd;">📝 Ghi chú HDV:</strong>
                                    <div style="font-size: 11px; margin-top: 5px;"><?php echo nl2br(htmlspecialchars($request['ghi_chu_hdv'])); ?></div>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $priorityInfo['badge']; ?> badge-priority"><?php echo $priorityInfo['label']; ?></span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $statusInfo['badge']; ?>"><?php echo $statusInfo['label']; ?></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-secondary btn-sm" 
                                    onclick='openUpdateModal(<?php echo $request['id']; ?>, "<?php echo htmlspecialchars($request['trang_thai']); ?>", "<?php echo htmlspecialchars($request['muc_do_uu_tien']); ?>", <?php echo json_encode($request['ghi_chu_hdv'] ?? '', JSON_HEX_APOS | JSON_HEX_QUOT); ?>, "<?php echo htmlspecialchars($request['khach_ten'] ?? '', ENT_QUOTES); ?>", "<?php echo htmlspecialchars($request['ten_tour'] ?? '', ENT_QUOTES); ?>", <?php echo $historyData; ?>)'
                                    style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                                ✏️ Cập nhật
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="card">
        <div style="text-align: center; padding: 40px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📦</div>
            <p style="color: var(--text-muted);">Không tìm thấy yêu cầu nào phù hợp với bộ lọc hiện tại.</p>
        </div>
    </div>
<?php endif; ?>

<!-- Modal tạo mới yêu cầu -->
<div class="modal-overlay" id="createRequestModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">+ Tạo yêu cầu đặc biệt mới</h5>
            <button onclick="document.getElementById('createRequestModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_yeu_cau">
            <div class="form-group">
                <label>Booking / Khách hàng <span style="color: #dc3545;">*</span></label>
                <select name="booking_id" class="form-group select" required>
                    <option value="">-- Chọn booking --</option>
                    <?php if (!empty($bookingList)): ?>
                        <?php foreach ($bookingList as $bk): ?>
                            <?php
                                $label = sprintf(
                                    '#%d - %s | %s | KH: %s (%s)',
                                    $bk['booking_id'],
                                    !empty($bk['ten_tour']) ? $bk['ten_tour'] : 'Tour',
                                    !empty($bk['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($bk['ngay_khoi_hanh'])) : 'N/A',
                                    $bk['ho_ten'] ?? 'N/A',
                                    $bk['so_dien_thoai'] ?? ''
                                );
                            ?>
                            <option value="<?php echo (int)$bk['booking_id']; ?>">
                                <?php echo htmlspecialchars($label); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Loại yêu cầu</label>
                    <select name="loai_yeu_cau" class="form-group select">
                        <?php $types = ['an_uong' => 'Ăn uống', 'suc_khoe' => 'Sức khỏe', 'di_chuyen' => 'Di chuyển', 'phong_o' => 'Phòng ở', 'hoat_dong' => 'Hoạt động', 'khac' => 'Khác'];
                        foreach ($types as $key => $label): ?>
                            <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Mức ưu tiên</label>
                    <select name="muc_do_uu_tien" class="form-group select">
                        <?php foreach ($priorityMap as $key => $info): ?>
                            <option value="<?php echo $key; ?>"><?php echo $info['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Trạng thái</label>
                    <select name="trang_thai" class="form-group select">
                        <?php foreach ($statusMap as $key => $info): ?>
                            <option value="<?php echo $key; ?>"><?php echo $info['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tiêu đề</label>
                    <input type="text" name="tieu_de" class="form-group input" placeholder="Tiêu đề yêu cầu (tuỳ chọn)">
                </div>
            </div>
            <div class="form-group">
                <label>Nội dung yêu cầu</label>
                <textarea name="mo_ta" rows="4" class="form-group textarea" placeholder="Mô tả chi tiết yêu cầu đặc biệt của khách"></textarea>
            </div>
            <div class="form-group">
                <label>Ghi chú xử lý (nếu có)</label>
                <textarea name="ghi_chu_hdv" rows="3" class="form-group textarea" placeholder="Ghi chú nội bộ cho HDV / bộ phận vận hành"></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('createRequestModal').classList.remove('show')">Đóng</button>
                <button type="submit" class="btn btn-primary">💾 Lưu yêu cầu</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal cập nhật yêu cầu -->
<div class="modal-overlay" id="updateModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div>
                <h5 style="margin: 0; color: var(--accent-gold);">✏️ Cập nhật yêu cầu</h5>
                <div style="color: var(--text-muted); font-size: 12px; margin-top: 5px;" id="modalSubTitle"></div>
            </div>
            <button onclick="document.getElementById('updateModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_yeu_cau">
            <input type="hidden" name="yeu_cau_id" id="modalYeuCauId">
            <div class="form-row">
                <div class="form-group">
                    <label>Mức ưu tiên <span style="color: #dc3545;">*</span></label>
                    <select name="muc_do_uu_tien" id="modalUuTien" class="form-group select" required>
                        <?php foreach ($priorityMap as $key => $info): ?>
                            <option value="<?php echo $key; ?>"><?php echo $info['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Trạng thái <span style="color: #dc3545;">*</span></label>
                    <select name="trang_thai" id="modalTrangThai" class="form-group select" required>
                        <?php foreach ($statusMap as $key => $info): ?>
                            <option value="<?php echo $key; ?>"><?php echo $info['label']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Ghi chú xử lý</label>
                <textarea name="ghi_chu_hdv" id="modalGhiChu" rows="4" class="form-group textarea" placeholder="Ghi lại cách xử lý, thông tin đã trao đổi với khách..."></textarea>
            </div>
            <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <h6 style="color: var(--text-muted); margin-bottom: 15px; font-size: 12px;">📋 Lịch sử cập nhật</h6>
                <div id="historyList" style="max-height: 200px; overflow-y: auto; color: var(--text-light); font-size: 12px;"></div>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('updateModal').classList.remove('show')">Đóng</button>
                <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openUpdateModal(id, trangThai, uuTien, ghiChu, khach, tour, history) {
        document.getElementById('modalYeuCauId').value = id;
        document.getElementById('modalTrangThai').value = trangThai;
        document.getElementById('modalUuTien').value = uuTien;
        document.getElementById('modalGhiChu').value = ghiChu || '';
        document.getElementById('modalSubTitle').textContent = (khach || '') + ' - ' + (tour || '');
        
        const historyWrapper = document.getElementById('historyList');
        historyWrapper.innerHTML = '';
        try {
            const historyData = history ? JSON.parse(history) : [];
            if (historyData.length === 0) {
                historyWrapper.innerHTML = '<p style="color: var(--text-muted); margin: 0;">Chưa có lịch sử.</p>';
            } else {
                historyData.forEach(item => {
                    const time = item.ngay_thuc_hien ? new Date(item.ngay_thuc_hien).toLocaleString('vi-VN') : '';
                    const note = item.noi_dung || '';
                    const actor = item.ho_ten || 'Hệ thống';
                    historyWrapper.insertAdjacentHTML('beforeend',
                        `<div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                            <strong style="color: var(--accent-gold);">${actor}</strong> 
                            <span style="color: var(--text-muted); font-size: 11px;">• ${time}</span><br>
                            <span style="margin-top: 5px; display: block;">${note}</span>
                        </div>`
                    );
                });
            }
        } catch (e) {
            historyWrapper.innerHTML = '<p style="color: var(--text-muted); margin: 0;">Không thể tải lịch sử.</p>';
        }
        
        document.getElementById('updateModal').classList.add('show');
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
