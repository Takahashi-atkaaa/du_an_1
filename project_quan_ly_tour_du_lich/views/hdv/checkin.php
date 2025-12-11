<?php
$pageTitle = 'Check-in & Điểm danh - HDV';
$currentPage = 'checkin';
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

    .checkpoint-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 20px;
        margin-bottom: 10px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: var(--text-light);
        display: block;
    }

    .checkpoint-card:hover {
        border-color: var(--accent-gold);
        transform: translateX(4px);
    }

    .checkpoint-card.active {
        border-left-color: var(--accent-gold);
        background: rgba(212, 175, 55, 0.1);
    }

    .checkpoint-type {
        padding: 4px 10px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 8px;
    }

    .type-tap_trung { background: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3); }
    .type-tham_quan { background: rgba(111, 66, 193, 0.2); color: #6f42c1; border: 1px solid rgba(111, 66, 193, 0.3); }
    .type-an_uong { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .type-nghi_ngoi { background: rgba(13, 202, 240, 0.2); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.3); }
    .type-khac { background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

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
        transform: translateX(4px);
        border-color: var(--accent-gold);
    }

    .stats-card.border-success { border-left-color: #198754; }
    .stats-card.border-warning { border-left-color: #ffc107; }
    .stats-card.border-danger { border-left-color: #dc3545; }

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

    .status-badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-chua_checkin { background: rgba(108, 117, 125, 0.2); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.3); }
    .status-da_checkin { background: rgba(25, 135, 84, 0.2); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3); }
    .status-vang_mat { background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }
    .status-re_gio { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }

    .btn-group {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
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
    }

    .modal-overlay.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: rgba(45, 45, 45, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
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
        min-height: 80px;
    }

    .form-group .input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
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

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>✓ Check-in & Điểm danh</h1>
            <?php if (isset($tour) && $tour): ?>
            <p style="color: var(--text-muted); margin-top: 10px;">
                <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>
                <span style="margin: 0 10px;">•</span>
                <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'] ?? 'now')); ?>
            </p>
            <?php endif; ?>
        </div>
        <div>
            <a href="javascript:window.history.back();" class="btn btn-secondary">
                ← Quay lại
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

<!-- Tour Selector -->
<?php if (empty($tour)): ?>
<div class="card">
    <h5 style="margin-bottom: 20px; color: var(--accent-gold);">Chọn tour để check-in</h5>
    <div class="form-group">
        <select onchange="if(this.value) window.location.href='index.php?act=hdv/checkin&tour_id=' + this.value" class="form-group select">
            <option value="">-- Chọn tour --</option>
            <?php if(isset($tours_list)) foreach($tours_list as $t): ?>
            <option value="<?php echo $t['id']; ?>">
                <?php echo htmlspecialchars($t['ten_tour'] ?? ''); ?> 
                (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'] ?? 'now')); ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php else: ?>

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; align-items: start;">
    <!-- Left: Checkpoints List -->
    <div>
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                <h5 style="margin: 0; color: var(--accent-gold);">📍 Điểm check-in</h5>
                <button class="btn btn-primary btn-sm" onclick="document.getElementById('addCheckpointModal').classList.add('show')">
                    + Thêm
                </button>
            </div>
            <div>
                <?php if (!empty($diem_checkin_list)): ?>
                    <?php foreach($diem_checkin_list as $diem): ?>
                    <a href="index.php?act=hdv/checkin&tour_id=<?php echo $_GET['tour_id']; ?>&diem_id=<?php echo $diem['id']; ?>" 
                       class="checkpoint-card <?php echo (isset($diem_hien_tai) && $diem_hien_tai && $diem['id'] == $diem_hien_tai['id']) ? 'active' : ''; ?>">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                    <span class="badge badge-secondary"><?php echo $diem['thu_tu'] ?? ''; ?></span>
                                    <strong><?php echo htmlspecialchars($diem['ten_diem'] ?? ''); ?></strong>
                                </div>
                                <div>
                                    <span class="checkpoint-type type-<?php echo $diem['loai_diem']; ?>">
                                        <?php 
                                        $types = [
                                            'tap_trung' => '📍 Tập trung',
                                            'tham_quan' => '🏛️ Tham quan',
                                            'an_uong' => '🍽️ Ăn uống',
                                            'nghi_ngoi' => '🏨 Nghỉ ngơi',
                                            'khac' => '📌 Khác'
                                        ];
                                        echo $types[$diem['loai_diem']] ?? $diem['loai_diem'];
                                        ?>
                                    </span>
                                </div>
                                <?php if ($diem['thoi_gian_du_kien']): ?>
                                <div style="color: var(--text-muted); font-size: 11px; margin-top: 8px;">
                                    🕐 <?php echo date('H:i d/m', strtotime($diem['thoi_gian_du_kien'])); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-secondary btn-sm" 
                                    onclick="event.preventDefault(); if(confirm('Xóa điểm này?')) window.location.href='index.php?act=hdv/delete_diem_checkin&id=<?php echo $diem['id']; ?>&tour_id=<?php echo $_GET['tour_id']; ?>'"
                                    style="margin-left: 10px;">
                                🗑️
                            </button>
                        </div>
                    </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                        <div style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;">📍</div>
                        <p>Chưa có điểm check-in nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Right: Customer List -->
    <div>
        <?php if (isset($diem_hien_tai) && $diem_hien_tai): ?>
        
        <!-- Stats Summary -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
            <?php 
            $total = count($khach_list);
            $da_checkin = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 'da_checkin'; }));
            $vang_mat = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 'vang_mat'; }));
            $re_gio = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 're_gio'; }));
            $chua_checkin = $total - $da_checkin - $vang_mat - $re_gio;
            ?>
            <div class="stats-card">
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Tổng số khách</div>
                <h3 style="margin: 0; font-size: 28px;"><?php echo $total; ?></h3>
            </div>
            <div class="stats-card border-success">
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Đã check-in</div>
                <h3 style="margin: 0; font-size: 28px; color: #198754;"><?php echo $da_checkin; ?></h3>
            </div>
            <div class="stats-card border-warning">
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Chưa check-in</div>
                <h3 style="margin: 0; font-size: 28px; color: #ffc107;"><?php echo $chua_checkin; ?></h3>
            </div>
            <div class="stats-card border-danger">
                <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Vắng mặt</div>
                <h3 style="margin: 0; font-size: 28px; color: #dc3545;"><?php echo $vang_mat; ?></h3>
            </div>
        </div>
        
        <!-- Customer List -->
        <div class="card">
            <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                <h5 style="margin: 0; color: var(--accent-gold);">
                    👥 Danh sách khách hàng
                    <span class="badge badge-primary" style="margin-left: 10px;"><?php echo $total; ?> người</span>
                </h5>
            </div>
            <div>
                <?php if (!empty($khach_list)): ?>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>Liên hệ</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($khach_list as $index => $khach): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'N/A'); ?></strong>
                                    <?php if (!empty($khach['checkin_note'])): ?>
                                    <br><small style="color: var(--text-muted);"><?php echo htmlspecialchars($khach['checkin_note'] ?? ''); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small style="color: var(--text-muted);">
                                        📞 <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? 'N/A'); ?><br>
                                        ✉ <?php echo htmlspecialchars($khach['email'] ?? 'N/A'); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $khach['checkin_status'] ?? 'chua_checkin'; ?>">
                                        <?php 
                                        $statuses = [
                                            'chua_checkin' => '⏳ Chưa check-in',
                                            'da_checkin' => '✅ Đã check-in',
                                            'vang_mat' => '❌ Vắng mặt',
                                            're_gio' => '⏰ Đến trễ'
                                        ];
                                        echo $statuses[$khach['checkin_status'] ?? 'chua_checkin'];
                                        ?>
                                    </span>
                                    <?php if (!empty($khach['thoi_gian_checkin'])): ?>
                                    <br><small style="color: var(--text-muted); font-size: 11px;"><?php echo date('H:i', strtotime($khach['thoi_gian_checkin'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm" 
                                                onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 'da_checkin')"
                                                style="background: rgba(25, 135, 84, 0.2); color: #198754; border-color: rgba(25, 135, 84, 0.3);">
                                            ✓
                                        </button>
                                        <button class="btn btn-secondary btn-sm" 
                                                onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 're_gio')"
                                                style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border-color: rgba(255, 193, 7, 0.3);">
                                            ⏰
                                        </button>
                                        <button class="btn btn-secondary btn-sm" 
                                                onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 'vang_mat')"
                                                style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: rgba(220, 53, 69, 0.3);">
                                            ✕
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                    <div style="font-size: 64px; margin-bottom: 20px; opacity: 0.5;">👥</div>
                    <p style="margin-bottom: 10px;">
                        <?php if (isset($coBookingNhungChuaCoTourCheckin) && $coBookingNhungChuaCoTourCheckin): ?>
                            <strong>Chưa có khách nào trong danh sách check-in</strong><br>
                            <small>Vui lòng liên hệ Admin để thêm khách vào danh sách check-in từ trang quản lý lịch khởi hành.</small>
                        <?php else: ?>
                            <strong>Chưa có khách hàng nào đăng ký tour này</strong>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php else: ?>
        <div class="alert alert-info">
            ℹ️ Vui lòng chọn điểm check-in bên trái để xem danh sách khách hàng
        </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

<!-- Add Checkpoint Modal -->
<div class="modal-overlay" id="addCheckpointModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">+ Thêm điểm check-in</h5>
            <button onclick="document.getElementById('addCheckpointModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_diem_checkin">
            <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id'] ?? ''; ?>">
            <input type="hidden" name="diem_id" value="">
            
            <div class="form-group">
                <label>Tên điểm <span style="color: #dc3545;">*</span></label>
                <input type="text" class="form-group input" name="ten_diem" 
                       placeholder="VD: Điểm tập trung Hà Nội" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Loại điểm <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="loai_diem" required>
                        <option value="tap_trung">📍 Tập trung</option>
                        <option value="tham_quan">🏛️ Tham quan</option>
                        <option value="an_uong">🍽️ Ăn uống</option>
                        <option value="nghi_ngoi">🏨 Nghỉ ngơi</option>
                        <option value="khac">📌 Khác</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Thứ tự</label>
                    <input type="number" class="form-group input" name="thu_tu" value="<?php echo (count($diem_checkin_list ?? []) + 1); ?>" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <label>Thời gian dự kiến</label>
                <input type="datetime-local" class="form-group input" name="thoi_gian_du_kien">
            </div>
            
            <div class="form-group">
                <label>Ghi chú</label>
                <textarea class="form-group textarea" name="ghi_chu" rows="3" 
                          placeholder="Thông tin bổ sung về điểm check-in..."></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addCheckpointModal').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-primary">💾 Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCheckin(diemId, bookingId, trangThai) {
        var ghi_chu = null;
        
        if (trangThai === 'vang_mat') {
            ghi_chu = prompt('Lý do vắng mặt (không bắt buộc):');
        } else if (trangThai === 're_gio') {
            ghi_chu = prompt('Ghi chú về việc đến trễ (không bắt buộc):');
        }
        
        var formData = new FormData();
        formData.append('diem_checkin_id', diemId);
        formData.append('booking_id', bookingId);
        formData.append('trang_thai', trangThai);
        if (ghi_chu) formData.append('ghi_chu', ghi_chu);
        
        fetch('index.php?act=hdv/save_checkin_khach', {
            method: 'POST',
            body: formData
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(function(error) {
            alert('Lỗi kết nối: ' + error);
        });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
