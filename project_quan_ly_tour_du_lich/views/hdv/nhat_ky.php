<?php
$pageTitle = 'Nhật ký Tour - HDV';
$currentPage = 'nhatKy';
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

    .diary-entry {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid;
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .diary-entry:hover {
        border-color: var(--accent-gold);
        transform: translateX(4px);
    }

    .entry-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        flex-wrap: wrap;
        gap: 15px;
    }

    .entry-type-badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .type-hanh_trinh { background: rgba(21, 101, 192, 0.2); color: #1565c0; border: 1px solid rgba(21, 101, 192, 0.3); }
    .type-su_co { background: rgba(198, 40, 40, 0.2); color: #c62828; border: 1px solid rgba(198, 40, 40, 0.3); }
    .type-phan_hoi { background: rgba(106, 27, 154, 0.2); color: #6a1b9a; border: 1px solid rgba(106, 27, 154, 0.3); }
    .type-hoat_dong { background: rgba(46, 125, 50, 0.2); color: #2e7d32; border: 1px solid rgba(46, 125, 50, 0.3); }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .image-gallery img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 2px;
        cursor: pointer;
        transition: transform 0.3s;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .image-gallery img:hover {
        transform: scale(1.05);
        border-color: var(--accent-gold);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(255, 255, 255, 0.1);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 10px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent-gold);
        border: 3px solid var(--primary-dark);
        box-shadow: 0 0 0 2px var(--accent-gold);
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
        .timeline {
            padding-left: 20px;
        }
        .timeline-item::before {
            left: -25px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>📝 Nhật ký Tour</h1>
            <?php if ($tour): ?>
            <p style="color: var(--text-muted); margin-top: 10px;"><?php echo htmlspecialchars($tour['ten_tour']); ?></p>
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

<?php 
if (isset($_SESSION['error'])): 
    $error_msg = $_SESSION['error'];
    unset($_SESSION['error']);
    if (stripos($error_msg, 'quyền') === false && stripos($error_msg, 'permission') === false):
?>
    <div class="alert alert-danger">
        ⚠ <?php echo htmlspecialchars($error_msg); ?>
    </div>
<?php 
    endif;
endif; 
?>

<!-- Tour Selector -->
<?php if (empty($tour)): ?>
<div class="card">
    <h5 style="margin-bottom: 20px; color: var(--accent-gold);">Chọn tour để xem nhật ký</h5>
    <div class="form-group">
        <select onchange="if(this.value) window.location.href='index.php?act=hdv/nhat_ky&tour_id=' + this.value" class="form-group select">
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
<?php else: ?>

<!-- Tour Info Card -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div>
            <h5 style="margin-bottom: 10px; font-size: 18px;"><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
            <div style="color: var(--text-muted); font-size: 13px;">
                📅 <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?>
                - <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'])); ?>
            </div>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('addEntryModal').classList.add('show')">
            + Thêm nhật ký
        </button>
    </div>
</div>

<!-- Diary Entries Timeline -->
<div class="timeline">
    <?php if (!empty($nhat_ky_list)): ?>
        <?php foreach($nhat_ky_list as $entry): ?>
        <div class="timeline-item">
            <div class="diary-entry">
                <div class="entry-header">
                    <div>
                        <span class="entry-type-badge type-<?php echo $entry['loai_nhat_ky']; ?>">
                            <?php 
                            $types = [
                                'hanh_trinh' => '📍 Hành trình',
                                'su_co' => '⚠️ Sự cố',
                                'phan_hoi' => '💬 Phản hồi khách',
                                'hoat_dong' => '🎯 Hoạt động'
                            ];
                            echo $types[$entry['loai_nhat_ky']] ?? $entry['loai_nhat_ky'];
                            ?>
                        </span>
                        <small style="color: var(--text-muted); margin-left: 15px; font-size: 12px;">
                            🕐 <?php echo date('d/m/Y H:i', strtotime($entry['ngay_ghi'])); ?>
                        </small>
                    </div>
                    <div style="display: flex; gap: 5px;">
                        <a href="index.php?act=hdv/nhat_ky&tour_id=<?php echo $tour['id']; ?>&edit_id=<?php echo $entry['id']; ?>" 
                           class="btn btn-secondary btn-sm"
                           style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                            ✏️
                        </a>
                        <a href="index.php?act=hdv/delete_nhat_ky&id=<?php echo $entry['id']; ?>&tour_id=<?php echo $tour['id']; ?>" 
                           class="btn btn-secondary btn-sm"
                           style="background: rgba(220, 53, 69, 0.2); color: #dc3545; border-color: rgba(220, 53, 69, 0.3);"
                           onclick="return confirm('Bạn có chắc muốn xóa nhật ký này?')">
                            🗑️
                        </a>
                    </div>
                </div>
                
                <?php if (!empty($entry['tieu_de'])): ?>
                <h5 style="margin-bottom: 15px; font-size: 18px; color: var(--text-light);"><?php echo htmlspecialchars($entry['tieu_de']); ?></h5>
                <?php endif; ?>
                
                <div style="margin-bottom: 15px; color: var(--text-light); line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($entry['noi_dung'] ?? '')); ?>
                </div>
                
                <?php if (!empty($entry['cach_xu_ly']) && isset($entry['loai_nhat_ky']) && $entry['loai_nhat_ky'] === 'su_co'): ?>
                <div class="alert alert-success" style="margin-bottom: 15px;">
                    <strong>✓ Cách xử lý:</strong><br>
                    <?php echo nl2br(htmlspecialchars($entry['cach_xu_ly'])); ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($entry['hinh_anh'])): ?>
                <div class="image-gallery">
                    <?php 
                    $images = json_decode($entry['hinh_anh'], true);
                    if ($images && is_array($images)):
                        foreach($images as $img):
                    ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" 
                         alt="Hình ảnh" 
                         onclick="viewImage('<?php echo htmlspecialchars($img); ?>')">
                    <?php 
                        endforeach;
                    endif;
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">
            ℹ️ Chưa có nhật ký nào cho tour này. Hãy thêm nhật ký đầu tiên!
        </div>
    <?php endif; ?>
</div>

<?php endif; ?>

<!-- Add/Edit Entry Modal -->
<?php if ($tour): ?>
<div class="modal-overlay" id="addEntryModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h5 style="margin: 0; color: var(--accent-gold);">
                <?php if(isset($edit_entry)): ?>
                ✏️ Sửa nhật ký tour
                <?php else: ?>
                + Thêm nhật ký tour
                <?php endif; ?>
            </h5>
            <button onclick="document.getElementById('addEntryModal').classList.remove('show')" 
                    style="background: none; border: none; color: var(--text-light); font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <form method="POST" action="index.php?act=hdv/save_nhat_ky" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="tour_id" value="<?php echo isset($tour['id']) ? $tour['id'] : ''; ?>" id="form_tour_id">
            <input type="hidden" name="entry_id" value="<?php echo isset($edit_entry['id']) ? $edit_entry['id'] : ''; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Loại nhật ký <span style="color: #dc3545;">*</span></label>
                    <select class="form-group select" name="loai_nhat_ky" id="loai_nhat_ky" required>
                        <option value="hanh_trinh" <?php echo (isset($edit_entry) && $edit_entry['loai_nhat_ky']=='hanh_trinh')?'selected':''; ?>>📍 Hành trình</option>
                        <option value="su_co" <?php echo (isset($edit_entry) && $edit_entry['loai_nhat_ky']=='su_co')?'selected':''; ?>>⚠️ Sự cố</option>
                        <option value="phan_hoi" <?php echo (isset($edit_entry) && $edit_entry['loai_nhat_ky']=='phan_hoi')?'selected':''; ?>>💬 Phản hồi khách</option>
                        <option value="hoat_dong" <?php echo (isset($edit_entry) && $edit_entry['loai_nhat_ky']=='hoat_dong')?'selected':''; ?>>🎯 Hoạt động nổi bật</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Ngày ghi <span style="color: #dc3545;">*</span></label>
                    <input type="date" class="form-group input" name="ngay_ghi" 
                           value="<?php echo isset($edit_entry) ? date('Y-m-d', strtotime($edit_entry['ngay_ghi'])) : date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Tiêu đề <span style="color: #dc3545;">*</span></label>
                <input type="text" class="form-group input" name="tieu_de" 
                       value="<?php echo isset($edit_entry['tieu_de']) ? htmlspecialchars($edit_entry['tieu_de']) : ''; ?>"
                       placeholder="VD: Tham quan Vịnh Hạ Long" required>
            </div>
            
            <div class="form-group">
                <label>Nội dung chi tiết <span style="color: #dc3545;">*</span></label>
                <textarea class="form-group textarea" name="noi_dung" rows="5" 
                          placeholder="Mô tả chi tiết sự kiện, hoạt động, phản hồi của khách..." required><?php echo isset($edit_entry['noi_dung']) ? htmlspecialchars($edit_entry['noi_dung']) : ''; ?></textarea>
            </div>
            
            <div class="form-group" id="cach_xu_ly_group" style="display: <?php echo (isset($edit_entry) && $edit_entry['loai_nhat_ky']=='su_co')?'block':'none'; ?>;">
                <label>Cách xử lý (chỉ cho sự cố)</label>
                <textarea class="form-group textarea" name="cach_xu_ly" rows="3" 
                          placeholder="Mô tả cách xử lý sự cố..."><?php echo isset($edit_entry) ? htmlspecialchars($edit_entry['cach_xu_ly'] ?? '') : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Hình ảnh (tối đa 5 ảnh)</label>
                <input type="file" class="form-group input" name="hinh_anh[]" 
                       accept="image/*" multiple id="imageInput">
                <small style="color: var(--text-muted); font-size: 11px; margin-top: 5px; display: block;">Chọn nhiều ảnh bằng Ctrl + Click</small>
                <div id="imagePreview" class="image-gallery" style="margin-top: 15px;"></div>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addEntryModal').classList.remove('show')">Hủy</button>
                <button type="submit" class="btn btn-primary">💾 Lưu nhật ký</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Image Viewer Modal -->
<div class="modal-overlay" id="imageViewerModal" onclick="if(event.target === this) this.classList.remove('show')">
    <div class="modal-content" onclick="event.stopPropagation()" style="max-width: 90%; padding: 20px;">
        <div style="text-align: center;">
            <img src="" id="viewerImage" style="max-width: 100%; height: auto; border-radius: 2px;">
        </div>
        <div style="text-align: right; margin-top: 15px;">
            <button onclick="document.getElementById('imageViewerModal').classList.remove('show')" class="btn btn-secondary btn-sm">Đóng</button>
        </div>
    </div>
</div>

<script>
    // Show/hide cách xử lý field
    var loaiNhatKySelect = document.getElementById('loai_nhat_ky');
    if (loaiNhatKySelect) {
        loaiNhatKySelect.addEventListener('change', function() {
            var cachXuLyGroup = document.getElementById('cach_xu_ly_group');
            if (this.value === 'su_co') {
                cachXuLyGroup.style.display = 'block';
            } else {
                cachXuLyGroup.style.display = 'none';
            }
        });
    }
    
    // Image preview
    var imageInput = document.getElementById('imageInput');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            var preview = document.getElementById('imagePreview');
            if (preview) {
                preview.innerHTML = '';
                
                var files = Array.from(e.target.files).slice(0, 5);
                files.forEach(function(file) {
                    if (file.type.startsWith('image/')) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            var img = document.createElement('img');
                            img.src = e.target.result;
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    }
    
    // View image
    function viewImage(src) {
        var viewerImage = document.getElementById('viewerImage');
        var modal = document.getElementById('imageViewerModal');
        if (viewerImage && modal) {
            viewerImage.src = src;
            modal.classList.add('show');
        }
    }
    
    // Validate form before submit
    function validateForm() {
        var tourId = document.getElementById('form_tour_id');
        if (tourId && (!tourId.value || tourId.value === '')) {
            alert('Lỗi: Không tìm thấy thông tin tour. Vui lòng thử lại.');
            return false;
        }
        return true;
    }
    
    // Auto show modal when edit_id is present
    <?php if(isset($edit_entry)): ?>
    window.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('addEntryModal');
        if (modal) {
            modal.classList.add('show');
        }
    });
    <?php endif; ?>
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
