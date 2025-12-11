<?php
$pageTitle = 'Phản hồi đánh giá - HDV';
$currentPage = 'phanHoi';
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

    .rating-stars { 
        display: inline-flex; 
        gap: 5px; 
        font-size: 24px; 
        cursor: pointer; 
    }
    .rating-stars i { 
        color: #ddd; 
        transition: color 0.2s; 
    }
    .rating-stars i.active, 
    .rating-stars i:hover { 
        color: var(--accent-gold); 
    }
    .rating-display { 
        display: inline-flex; 
        gap: 3px; 
        font-size: 18px; 
    }
    .rating-display i { 
        color: var(--accent-gold); 
    }
    .rating-display i.bi-star { 
        color: #ddd; 
    }
    .feedback-card { 
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }
    .feedback-card:hover { 
        transform: translateY(-2px); 
        border-color: var(--accent-gold);
    }
    .feedback-card.rating-5 { border-left-color: #198754; }
    .feedback-card.rating-4 { border-left-color: #6c757d; }
    .feedback-card.rating-3 { border-left-color: var(--accent-gold); }
    .feedback-card.rating-2 { border-left-color: #fd7e14; }
    .feedback-card.rating-1 { border-left-color: #dc3545; }
    .stats-card { 
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }
    .stats-card:hover {
        transform: translateY(-4px);
        border-color: var(--accent-gold);
    }
    .stats-card.primary { 
        background: rgba(102, 126, 234, 0.2);
        border-color: rgba(102, 126, 234, 0.3);
    }
    .stats-card.success { 
        background: rgba(17, 153, 142, 0.2);
        border-color: rgba(17, 153, 142, 0.3);
    }
    .stats-card.warning { 
        background: rgba(240, 147, 251, 0.2);
        border-color: rgba(240, 147, 251, 0.3);
    }
    .stats-card.info { 
        background: rgba(79, 172, 254, 0.2);
        border-color: rgba(79, 172, 254, 0.3);
    }
    .image-preview { 
        display: inline-block; 
        margin: 5px; 
    }
    .image-preview img { 
        max-width: 100px; 
        max-height: 100px; 
        border-radius: 2px; 
        cursor: pointer;
        border: 2px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s;
    }
    .image-preview img:hover {
        border-color: var(--accent-gold);
        transform: scale(1.1);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-primary {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.3);
    }

    .badge-info {
        background: rgba(13, 202, 240, 0.2);
        color: #0dcaf0;
        border: 1px solid rgba(13, 202, 240, 0.3);
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

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
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

    .form-row-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
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
            <h1>💬 Phản hồi đánh giá dịch vụ</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Gửi và quản lý phản hồi đánh giá</p>
        </div>
        <div>
            <?php if ($tour): ?>
            <button class="btn btn-primary" onclick="document.getElementById('feedbackModal').classList.add('show')">
                + Gửi phản hồi mới
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Alerts -->
<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ⚠ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!$tour): ?>
    <div class="card">
        <div style="text-align: center; padding: 40px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📅</div>
            <h5 style="margin-bottom: 20px; color: var(--accent-gold);">Chọn tour để xem và gửi phản hồi</h5>
            <div class="form-group" style="max-width: 400px; margin: 0 auto;">
                <select onchange="if(this.value) window.location.href='index.php?act=hdv/phan_hoi&tour_id='+this.value" class="form-group select">
                    <option value="">-- Chọn tour --</option>
                    <?php foreach($tours_list as $t): ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo htmlspecialchars($t['ten_tour']); ?> 
                        (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'])); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
<?php else: ?>

<!-- Tour hiện tại -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h5 style="margin-bottom: 10px; color: var(--accent-gold);">📦 <?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
            <div style="color: var(--text-muted); font-size: 13px;">
                📅 <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?> - 
                <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'])); ?>
            </div>
        </div>
        <div class="form-group" style="width: auto; min-width: 200px;">
            <select onchange="if(this.value) window.location.href='index.php?act=hdv/phan_hoi&tour_id='+this.value" class="form-group select">
                <option value="">Đổi tour...</option>
                <?php foreach($tours_list as $t): ?>
                <option value="<?php echo $t['id']; ?>" <?php echo $t['id'] == $tour_id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($t['ten_tour']); ?> 
                    (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'])); ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<!-- Thống kê -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px;">
    <div class="stats-card primary">
        <h2 style="margin: 0; font-size: 32px; color: var(--text-light);"><?php echo $stats['tong'] ?? 0; ?></h2>
        <small style="color: var(--text-muted); font-size: 12px;">Tổng phản hồi</small>
    </div>
    <div class="stats-card info">
        <h2 style="margin: 0; font-size: 32px; color: var(--text-light);"><?php echo $stats['moi'] ?? 0; ?></h2>
        <small style="color: var(--text-muted); font-size: 12px;">Mới gửi</small>
    </div>
    <div class="stats-card warning">
        <h2 style="margin: 0; font-size: 32px; color: var(--text-light);"><?php echo $stats['da_xem'] ?? 0; ?></h2>
        <small style="color: var(--text-muted); font-size: 12px;">Đã xem</small>
    </div>
    <div class="stats-card success">
        <h2 style="margin: 0; font-size: 32px; color: var(--text-light);">
            <?php echo number_format($stats['diem_tb'] ?? 0, 1); ?> ⭐
        </h2>
        <small style="color: var(--text-muted); font-size: 12px;">Điểm trung bình</small>
    </div>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom: 30px;">
    <div class="filter-buttons">
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>" 
           class="filter-btn <?php echo !$loai_filter ? 'active' : ''; ?>">Tất cả</a>
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>&loai=tour" 
           class="filter-btn <?php echo $loai_filter == 'tour' ? 'active' : ''; ?>">🗺️ Tour</a>
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>&loai=khach_san" 
           class="filter-btn <?php echo $loai_filter == 'khach_san' ? 'active' : ''; ?>">🏨 Khách sạn</a>
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>&loai=nha_hang" 
           class="filter-btn <?php echo $loai_filter == 'nha_hang' ? 'active' : ''; ?>">🍽️ Nhà hàng</a>
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>&loai=van_chuyen" 
           class="filter-btn <?php echo $loai_filter == 'van_chuyen' ? 'active' : ''; ?>">🚌 Vận chuyển</a>
        <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour_id; ?>&loai=nha_cung_cap" 
           class="filter-btn <?php echo $loai_filter == 'nha_cung_cap' ? 'active' : ''; ?>">👥 Nhà cung cấp</a>
    </div>
</div>

<!-- Danh sách phản hồi -->
<?php if (empty($phan_hoi_list)): ?>
    <div class="card">
        <div style="text-align: center; padding: 40px;">
            <div style="font-size: 48px; color: var(--text-muted); margin-bottom: 20px;">📭</div>
            <p style="color: var(--text-muted);">Chưa có phản hồi nào. Hãy gửi phản hồi đầu tiên!</p>
        </div>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px;">
        <?php 
        $loai_icons = [
            'tour' => '🗺️', 'khach_san' => '🏨', 'nha_hang' => '🍽️',
            'van_chuyen' => '🚌', 'nha_cung_cap' => '👥', 'khac' => '📌'
        ];
        foreach($phan_hoi_list as $ph): 
            $hinh_anh = json_decode($ph['hinh_anh'] ?? '[]', true);
        ?>
        <div class="feedback-card rating-<?php echo $ph['diem_danh_gia']; ?>">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
                <div>
                    <span class="badge badge-primary">
                        <?php echo $loai_icons[$ph['loai_danh_gia']] ?? '📌'; ?>
                        <?php echo ucfirst(str_replace('_', ' ', $ph['loai_danh_gia'])); ?>
                    </span>
                    <span class="badge <?php echo $ph['trang_thai'] == 'moi' ? 'badge-info' : ($ph['trang_thai'] == 'da_xem' ? 'badge-warning' : 'badge-success'); ?>" style="margin-left: 10px;">
                        <?php echo str_replace('_', ' ', ucfirst($ph['trang_thai'])); ?>
                    </span>
                </div>
                <div class="rating-display">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="bi bi-star<?php echo $i <= $ph['diem_danh_gia'] ? '-fill' : ''; ?>"></i>
                    <?php endfor; ?>
                </div>
            </div>
            
            <h6 style="margin: 0 0 10px 0; font-size: 16px; color: var(--text-light);"><?php echo htmlspecialchars($ph['tieu_de']); ?></h6>
            <p style="color: var(--text-muted); font-size: 12px; margin-bottom: 15px;">
                🏢 <?php echo htmlspecialchars($ph['ten_doi_tuong']); ?>
            </p>
            
            <p style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($ph['noi_dung'])); ?></p>
            
            <?php if ($ph['diem_manh']): ?>
            <div style="margin-bottom: 15px; padding: 12px; background: rgba(25, 135, 84, 0.1); border-left: 3px solid #198754; border-radius: 2px;">
                <strong style="color: #198754; font-size: 12px;">✓ Điểm mạnh:</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: var(--text-light);"><?php echo nl2br(htmlspecialchars($ph['diem_manh'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ($ph['diem_yeu']): ?>
            <div style="margin-bottom: 15px; padding: 12px; background: rgba(255, 193, 7, 0.1); border-left: 3px solid #ffc107; border-radius: 2px;">
                <strong style="color: #ffc107; font-size: 12px;">⚠ Điểm yếu:</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: var(--text-light);"><?php echo nl2br(htmlspecialchars($ph['diem_yeu'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if ($ph['de_xuat']): ?>
            <div style="margin-bottom: 15px; padding: 12px; background: rgba(13, 110, 253, 0.1); border-left: 3px solid #0d6efd; border-radius: 2px;">
                <strong style="color: #0d6efd; font-size: 12px;">💡 Đề xuất:</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: var(--text-light);"><?php echo nl2br(htmlspecialchars($ph['de_xuat'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($hinh_anh) && is_array($hinh_anh)): ?>
            <div style="margin-bottom: 15px;">
                <strong style="font-size: 12px; color: var(--accent-gold);">Hình ảnh:</strong><br>
                <?php foreach($hinh_anh as $img): ?>
                <div class="image-preview">
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="Ảnh" onclick="window.open(this.src)">
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <small style="color: var(--text-muted); font-size: 11px;">
                    🕐 <?php echo date('d/m/Y H:i', strtotime($ph['ngay_tao'])); ?>
                </small>
                <div style="display: flex; gap: 5px;">
                    <button class="btn btn-secondary btn-sm" 
                            onclick='editFeedback(<?php echo json_encode($ph, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'
                            style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                        ✏️
                    </button>
                    <a href="index.php?act=hdv/delete_phan_hoi&id=<?php echo $ph['id']; ?>&tour_id=<?php echo $tour_id; ?>" 
                       class="btn btn-secondary btn-sm"
                       style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: rgba(220, 53, 69, 0.3);"
                       onclick="return confirm('Xác nhận xóa phản hồi này?')">
                        🗑️
                    </a>
                </div>
            </div>
            
            <?php if ($ph['ghi_chu_xu_ly']): ?>
            <div class="alert alert-info" style="margin-top: 15px; padding: 12px;">
                <strong style="font-size: 12px;">ℹ️ Phản hồi từ quản lý:</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px;"><?php echo nl2br(htmlspecialchars($ph['ghi_chu_xu_ly'])); ?></p>
                <?php if ($ph['ten_nguoi_xu_ly']): ?>
                <small style="color: var(--text-muted); font-size: 11px;">- <?php echo htmlspecialchars($ph['ten_nguoi_xu_ly']); ?></small>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php endif; ?>

<!-- Modal -->
<div class="modal-overlay" id="feedbackModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">💬 Gửi phản hồi đánh giá</h5>
            <button onclick="document.getElementById('feedbackModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_phan_hoi" enctype="multipart/form-data">
            <input type="hidden" name="id" id="feedback_id">
            <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Loại đánh giá <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="loai_danh_gia" id="loai_danh_gia" required>
                        <option value="">-- Chọn loại --</option>
                        <option value="tour">🗺️ Tour</option>
                        <option value="khach_san">🏨 Khách sạn</option>
                        <option value="nha_hang">🍽️ Nhà hàng</option>
                        <option value="van_chuyen">🚌 Vận chuyển</option>
                        <option value="nha_cung_cap">👥 Nhà cung cấp</option>
                        <option value="khac">📌 Khác</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tên đối tượng <span style="color: #dc3545;">*</span></label>
                    <input type="text" class="form-group input" name="ten_doi_tuong" id="ten_doi_tuong" 
                           placeholder="VD: Khách sạn ABC, Nhà hàng XYZ..." required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Đánh giá <span style="color: #dc3545;">*</span></label>
                <div class="rating-stars" id="ratingStars">
                    <i class="bi bi-star" data-rating="1"></i>
                    <i class="bi bi-star" data-rating="2"></i>
                    <i class="bi bi-star" data-rating="3"></i>
                    <i class="bi bi-star" data-rating="4"></i>
                    <i class="bi bi-star" data-rating="5"></i>
                </div>
                <input type="hidden" name="diem_danh_gia" id="diem_danh_gia" value="5" required>
            </div>
            
            <div class="form-group">
                <label>Tiêu đề <span style="color: #dc3545;">*</span></label>
                <input type="text" class="form-group input" name="tieu_de" id="tieu_de" required>
            </div>
            
            <div class="form-group">
                <label>Nội dung <span style="color: #dc3545;">*</span></label>
                <textarea class="form-group textarea" name="noi_dung" id="noi_dung" rows="3" required></textarea>
            </div>
            
            <div class="form-row-3">
                <div class="form-group">
                    <label>Điểm mạnh</label>
                    <textarea class="form-group textarea" name="diem_manh" id="diem_manh" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Điểm yếu</label>
                    <textarea class="form-group textarea" name="diem_yeu" id="diem_yeu" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Đề xuất</label>
                    <textarea class="form-group textarea" name="de_xuat" id="de_xuat" rows="2"></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <label>Hình ảnh</label>
                <input type="file" class="form-group input" name="hinh_anh[]" accept="image/*" multiple>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('feedbackModal').classList.remove('show')">Đóng</button>
                <button type="submit" class="btn btn-primary">📤 Gửi</button>
            </div>
        </form>
    </div>
</div>

<script>
    const stars = document.querySelectorAll('#ratingStars i');
    const ratingInput = document.getElementById('diem_danh_gia');
    
    if (stars.length > 0 && ratingInput) {
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                updateStars(rating);
            });
            star.addEventListener('mouseenter', function() {
                updateStars(this.getAttribute('data-rating'));
            });
        });
        
        document.getElementById('ratingStars').addEventListener('mouseleave', function() {
            updateStars(ratingInput.value);
        });
        
        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('bi-star');
                    star.classList.add('bi-star-fill', 'active');
                } else {
                    star.classList.remove('bi-star-fill', 'active');
                    star.classList.add('bi-star');
                }
            });
        }
        
        updateStars(5);
    }
    
    function editFeedback(data) {
        document.getElementById('feedback_id').value = data.id;
        document.getElementById('loai_danh_gia').value = data.loai_danh_gia;
        document.getElementById('ten_doi_tuong').value = data.ten_doi_tuong;
        document.getElementById('tieu_de').value = data.tieu_de;
        document.getElementById('noi_dung').value = data.noi_dung;
        document.getElementById('diem_manh').value = data.diem_manh || '';
        document.getElementById('diem_yeu').value = data.diem_yeu || '';
        document.getElementById('de_xuat').value = data.de_xuat || '';
        ratingInput.value = data.diem_danh_gia;
        updateStars(data.diem_danh_gia);
        document.getElementById('feedbackModal').classList.add('show');
    }
    
    var modal = document.getElementById('feedbackModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.querySelector('form').reset();
                document.getElementById('feedback_id').value = '';
                updateStars(5);
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
