<?php
$pageTitle = 'Yêu cầu đặc biệt - HDV';
$currentPage = 'yeuCauDacBiet';
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

    .request-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .request-card:hover {
        border-color: var(--accent-gold);
        transform: translateX(4px);
    }

    .priority-khan_cap { border-left-color: #dc3545; background: rgba(220, 53, 69, 0.1); }
    .priority-cao { border-left-color: #fd7e14; background: rgba(253, 126, 20, 0.1); }
    .priority-trung_binh { border-left-color: #ffc107; background: rgba(255, 193, 7, 0.1); }
    .priority-thap { border-left-color: #6c757d; background: rgba(108, 117, 125, 0.1); }

    .badge-type {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-right: 10px;
    }

    .type-an_uong { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .type-suc_khoe { background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }
    .type-di_chuyen { background: rgba(13, 202, 240, 0.2); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.3); }
    .type-phong_o { background: rgba(111, 66, 193, 0.2); color: #6f42c1; border: 1px solid rgba(111, 66, 193, 0.3); }
    .type-hoat_dong { background: rgba(25, 135, 84, 0.2); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3); }
    .type-khac { background: rgba(108, 117, 125, 0.2); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.3); }

    .status-badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .status-moi { background: rgba(13, 110, 253, 0.2); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.3); }
    .status-dang_xu_ly { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .status-da_giai_quyet { background: rgba(25, 135, 84, 0.2); color: #198754; border: 1px solid rgba(25, 135, 84, 0.3); }
    .status-khong_the_thuc_hien { background: rgba(220, 53, 69, 0.2); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.3); }

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

    .stats-card.border-danger { border-left-color: #dc3545; }
    .stats-card.border-warning { border-left-color: #ffc107; }
    .stats-card.border-success { border-left-color: #198754; }

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
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>⭐ Yêu cầu đặc biệt</h1>
            <?php if (isset($tour) && $tour): ?>
            <p style="color: var(--text-muted); margin-top: 10px;">
                <?php echo htmlspecialchars($tour['ten_tour']); ?>
                <span style="margin: 0 10px;">•</span>
                <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?>
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

<!-- Stats Summary -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
    <?php 
    $totalRequests = (int)(($stats['khan_cap'] ?? 0) + ($stats['cao'] ?? 0) + ($stats['trung_binh'] ?? 0) + ($stats['thap'] ?? 0));
    ?>
    <div class="stats-card">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Tổng yêu cầu</div>
        <h3 style="margin: 0; font-size: 28px;"><?php echo $totalRequests; ?></h3>
    </div>
    <div class="stats-card border-danger">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Khẩn cấp</div>
        <h3 style="margin: 0; font-size: 28px; color: #dc3545;"><?php echo (int)($stats['khan_cap'] ?? 0); ?></h3>
    </div>
    <div class="stats-card border-warning">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Đang xử lý</div>
        <h3 style="margin: 0; font-size: 28px; color: #ffc107;"><?php echo (int)($stats['trang_thai_dang_xu_ly'] ?? 0); ?></h3>
    </div>
    <div class="stats-card border-success">
        <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 8px;">Đã giải quyết</div>
        <h3 style="margin: 0; font-size: 28px; color: #198754;"><?php echo (int)($stats['trang_thai_da_giai_quyet'] ?? 0); ?></h3>
    </div>
</div>

<!-- Add Request Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
    <h5 style="margin: 0; color: var(--accent-gold);">Danh sách yêu cầu đặc biệt (<?php echo count($yeu_cau_list); ?> yêu cầu)</h5>
    <button class="btn btn-primary" onclick="document.getElementById('addRequestModal').classList.add('show')">
        + Thêm yêu cầu
    </button>
</div>

<!-- Requests List -->
<?php if (!empty($yeu_cau_list)): ?>
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px;">
    <?php foreach($yeu_cau_list as $yc): ?>
    <div class="request-card priority-<?php echo $yc['muc_do_uu_tien']; ?>">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <span class="badge-type type-<?php echo $yc['loai_yeu_cau']; ?>">
                        <?php 
                        $types = [
                            'an_uong' => '🍽️ Ăn uống',
                            'suc_khoe' => '💊 Sức khỏe',
                            'di_chuyen' => '🚗 Di chuyển',
                            'phong_o' => '🏨 Phòng ở',
                            'hoat_dong' => '🎯 Hoạt động',
                            'khac' => '📌 Khác'
                        ];
                        echo $types[$yc['loai_yeu_cau']] ?? 'Khác';
                        ?>
                    </span>
                    <span class="status-badge status-<?php echo $yc['trang_thai']; ?>">
                        <?php 
                        $statuses = [
                            'moi' => '🆕 Mới',
                            'dang_xu_ly' => '⏳ Đang xử lý',
                            'da_giai_quyet' => '✅ Đã giải quyết',
                            'khong_the_thuc_hien' => '❌ Không thể thực hiện'
                        ];
                        echo $statuses[$yc['trang_thai']] ?? 'Mới';
                        ?>
                    </span>
                </div>
                <h6 style="margin: 0 0 10px 0; font-size: 16px; color: var(--text-light);"><?php echo htmlspecialchars($yc['tieu_de']); ?></h6>
                <div style="color: var(--text-muted); font-size: 12px; margin-bottom: 10px;">
                    👤 <?php echo htmlspecialchars($yc['khach_ten'] ?? $yc['ho_ten'] ?? 'N/A'); ?>
                    <span style="margin: 0 8px;">•</span>
                    #<?php echo htmlspecialchars($yc['booking_id'] ?? 'N/A'); ?>
                    <?php if (!empty($yc['ten_tour'])): ?>
                    <span style="margin: 0 8px;">•</span>
                    📍 <?php echo htmlspecialchars($yc['ten_tour']); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div style="display: flex; gap: 5px;">
                <button class="btn btn-secondary btn-sm" 
                        onclick="editRequest(<?php echo htmlspecialchars(json_encode($yc), ENT_QUOTES, 'UTF-8'); ?>)"
                        style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                    ✏️
                </button>
                <a href="index.php?act=hdv/delete_yeu_cau&id=<?php echo $yc['id']; ?>&tour_id=<?php echo $_GET['tour_id'] ?? 0; ?>" 
                   class="btn btn-secondary btn-sm"
                   style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: rgba(220, 53, 69, 0.3);"
                   onclick="return confirm('Xóa yêu cầu này?')">
                    🗑️
                </a>
            </div>
        </div>
        
        <?php if (!empty($yc['mo_ta'])): ?>
        <div style="margin-bottom: 15px;">
            <strong style="color: var(--accent-gold); font-size: 12px;">Mô tả:</strong>
            <p style="margin: 5px 0 0 0; color: var(--text-light); font-size: 13px; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($yc['mo_ta'])); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($yc['ghi_chu_hdv'])): ?>
        <div class="alert alert-info" style="margin-bottom: 15px; padding: 12px;">
            <strong style="font-size: 12px;">💬 Ghi chú HDV:</strong><br>
            <span style="font-size: 12px;"><?php echo nl2br(htmlspecialchars($yc['ghi_chu_hdv'])); ?></span>
        </div>
        <?php endif; ?>
        
        <div style="display: flex; justify-content: space-between; align-items: center; color: var(--text-muted); font-size: 11px; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
            <span>
                🚩 <?php 
                $priorities = [
                    'thap' => 'Thấp',
                    'trung_binh' => 'Trung bình',
                    'cao' => 'Cao',
                    'khan_cap' => 'Khẩn cấp'
                ];
                echo $priorities[$yc['muc_do_uu_tien']] ?? 'Trung bình';
                ?>
            </span>
            <span>
                🕐 <?php echo date('d/m/Y H:i', strtotime($yc['ngay_tao'])); ?>
            </span>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info">
    ℹ️ 
    <?php if (!empty($_GET['keyword']) || !empty($_GET['tour_id']) || !empty($_GET['muc_do_uu_tien']) || !empty($_GET['trang_thai']) || !empty($_GET['loai_yeu_cau'])): ?>
        Không tìm thấy yêu cầu nào phù hợp với bộ lọc hiện tại.
    <?php else: ?>
        Chưa có yêu cầu đặc biệt nào.
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Add/Edit Request Modal -->
<div class="modal-overlay" id="addRequestModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);" id="modalTitle">+ Thêm yêu cầu đặc biệt</h5>
            <button onclick="document.getElementById('addRequestModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_yeu_cau">
            <input type="hidden" name="tour_id" value="<?php echo $_GET['tour_id'] ?? ''; ?>">
            <input type="hidden" name="yeu_cau_id" id="yeu_cau_id" value="">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Khách hàng <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="booking_id" id="booking_id" required>
                        <option value="">-- Chọn khách hàng --</option>
                        <?php if(isset($bookings_list)) foreach($bookings_list as $b): ?>
                        <option value="<?php echo $b['booking_id']; ?>">
                            <?php echo htmlspecialchars($b['ho_ten']); ?> 
                            (Booking #<?php echo $b['booking_id']; ?> - <?php echo $b['so_nguoi']; ?> người)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Loại yêu cầu <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="loai_yeu_cau" id="loai_yeu_cau" required>
                        <option value="an_uong">🍽️ Ăn uống (ăn chay, dị ứng...)</option>
                        <option value="suc_khoe">💊 Sức khỏe (bệnh lý, thuốc...)</option>
                        <option value="di_chuyen">🚗 Di chuyển (xe lăn, chậm chân...)</option>
                        <option value="phong_o">🏨 Phòng ở (tầng thấp, gần thang máy...)</option>
                        <option value="hoat_dong">🎯 Hoạt động (không leo núi, không bơi...)</option>
                        <option value="khac">📌 Khác</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Tiêu đề <span style="color: #dc3545;">*</span></label>
                <input type="text" class="form-group input" name="tieu_de" id="tieu_de"
                       placeholder="VD: Ăn chay trường, Dị ứng hải sản, Đái tháo đường..." required>
            </div>
            
            <div class="form-group">
                <label>Mô tả chi tiết</label>
                <textarea class="form-group textarea" name="mo_ta" id="mo_ta" rows="4" 
                          placeholder="Mô tả chi tiết yêu cầu, lưu ý cần thiết..."></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Mức độ ưu tiên <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="muc_do_uu_tien" id="muc_do_uu_tien" required>
                        <option value="thap">⬇️ Thấp</option>
                        <option value="trung_binh" selected>➡️ Trung bình</option>
                        <option value="cao">⬆️ Cao</option>
                        <option value="khan_cap">🚨 Khẩn cấp</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Trạng thái <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="trang_thai" id="trang_thai" required>
                        <option value="moi" selected>🆕 Mới</option>
                        <option value="dang_xu_ly">⏳ Đang xử lý</option>
                        <option value="da_giai_quyet">✅ Đã giải quyết</option>
                        <option value="khong_the_thuc_hien">❌ Không thể thực hiện</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Ghi chú của HDV</label>
                <textarea class="form-group textarea" name="ghi_chu_hdv" id="ghi_chu_hdv" rows="3" 
                          placeholder="Ghi chú về cách xử lý, kết quả..."></textarea>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addRequestModal').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-primary">💾 Lưu yêu cầu</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editRequest(data) {
        document.getElementById('yeu_cau_id').value = data.id;
        document.getElementById('booking_id').value = data.booking_id;
        document.getElementById('loai_yeu_cau').value = data.loai_yeu_cau;
        document.getElementById('tieu_de').value = data.tieu_de || '';
        document.getElementById('mo_ta').value = data.mo_ta || '';
        document.getElementById('muc_do_uu_tien').value = data.muc_do_uu_tien;
        document.getElementById('trang_thai').value = data.trang_thai;
        document.getElementById('ghi_chu_hdv').value = data.ghi_chu_hdv || '';
        
        document.getElementById('modalTitle').textContent = '✏️ Cập nhật yêu cầu đặc biệt';
        
        document.getElementById('addRequestModal').classList.add('show');
    }
    
    // Reset form khi đóng modal
    var modal = document.getElementById('addRequestModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                document.getElementById('yeu_cau_id').value = '';
                document.querySelector('form').reset();
                document.getElementById('modalTitle').textContent = '+ Thêm yêu cầu đặc biệt';
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
