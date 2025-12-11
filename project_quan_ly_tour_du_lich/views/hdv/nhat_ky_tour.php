<?php
$pageTitle = 'Nhật ký Tour - HDV';
$currentPage = 'nhatKyTour';
ob_start();

if (!isset($entryEditing) || !$entryEditing) {
    $entryEditing = [];
}
$isEditing = !empty($entryEditing);
$formTourId = $isEditing
    ? $entryEditing['tour_id']
    : ($selectedTourId ?? ($lichKhoiHanhList[0]['tour_id'] ?? null));
$getField = function($key) use ($entryEditing) {
    return htmlspecialchars($entryEditing[$key] ?? '');
};
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

    .journal-entry {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--accent-gold);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .journal-entry:hover {
        border-color: var(--accent-gold);
        transform: translateX(4px);
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
        .split-layout {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>📝 Nhật ký tour của tôi</h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Ghi lại diễn biến và sự kiện trong tour</p>
        </div>
        <div>
            <a href="index.php?act=hdv/lichLamViec" class="btn btn-secondary">
                ← Lịch làm việc
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

<?php if (!empty($lichKhoiHanhList)): ?>
    <div class="card" style="margin-bottom: 30px;">
        <div class="form-group">
            <label><strong>Chọn tour:</strong></label>
            <form method="GET" action="index.php" style="display: flex; gap: 10px; align-items: end;">
                <input type="hidden" name="act" value="hdv/nhatKyTour">
                <select name="tour_id" id="tour_id" onchange="this.form.submit()" class="form-group select" style="flex: 1;">
                    <option value="">Tất cả tour tôi phụ trách</option>
                    <?php foreach ($lichKhoiHanhList as $lich): ?>
                        <option value="<?php echo $lich['tour_id']; ?>"
                            <?php echo (isset($selectedTourId) && (int)$selectedTourId === (int)$lich['tour_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                            (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="split-layout" style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px;">
        <!-- Form Section -->
        <div class="form-section">
            <h3 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">
                <?php echo $isEditing ? '✏️ Cập nhật nhật ký' : '➕ Ghi nhật ký mới'; ?>
            </h3>
            <form method="POST" action="index.php?act=hdv/nhatKyTour">
                <input type="hidden" name="journal_action" value="<?php echo $isEditing ? 'update' : 'create'; ?>">
                <?php if ($isEditing): ?>
                    <input type="hidden" name="entry_id" value="<?php echo (int)$entryEditing['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="form_tour_id">Tour <span style="color: #dc3545;">*</span></label>
                    <select name="tour_id" id="form_tour_id" class="form-group select" required>
                        <?php foreach ($lichKhoiHanhList as $lich): ?>
                            <option value="<?php echo $lich['tour_id']; ?>"
                                <?php echo ((int)$formTourId === (int)$lich['tour_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ngay_ghi">Ngày ghi <span style="color: #dc3545;">*</span></label>
                    <input type="date" name="ngay_ghi" id="ngay_ghi" class="form-group input" 
                           value="<?php echo htmlspecialchars($entryEditing['ngay_ghi'] ?? date('Y-m-d')); ?>" required>
                </div>

                <div class="form-group">
                    <label for="tieu_de">Tiêu đề / Tóm tắt</label>
                    <input type="text" name="tieu_de" id="tieu_de" class="form-group input"
                           value="<?php echo $getField('tieu_de'); ?>" placeholder="VD: Tham quan Vịnh Hạ Long">
                </div>

                <div class="form-group">
                    <label for="hoat_dong">Hoạt động nổi bật</label>
                    <textarea name="hoat_dong" id="hoat_dong" rows="3" class="form-group textarea" 
                              placeholder="Mô tả các hoạt động nổi bật trong ngày..."><?php echo $getField('hoat_dong'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="su_kien">Sự kiện / Sự cố</label>
                    <textarea name="su_kien" id="su_kien" rows="3" class="form-group textarea" 
                              placeholder="Ghi lại các sự kiện, sự cố xảy ra..."><?php echo $getField('su_kien'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="cach_xu_ly">Cách xử lý</label>
                    <textarea name="cach_xu_ly" id="cach_xu_ly" rows="3" class="form-group textarea" 
                              placeholder="Mô tả cách xử lý sự cố (nếu có)..."><?php echo $getField('cach_xu_ly'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="phan_hoi">Phản hồi của khách hàng</label>
                    <textarea name="phan_hoi" id="phan_hoi" rows="3" class="form-group textarea" 
                              placeholder="Ghi lại phản hồi, ý kiến của khách hàng..."><?php echo $getField('phan_hoi'); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="anh_minh_hoa">Link ảnh/video minh họa</label>
                    <input type="text" name="anh_minh_hoa" id="anh_minh_hoa" class="form-group input" 
                           value="<?php echo $getField('anh_minh_hoa'); ?>" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="ghi_chu_them">Ghi chú thêm</label>
                    <textarea name="ghi_chu_them" id="ghi_chu_them" rows="3" class="form-group textarea" 
                              placeholder="Các ghi chú khác..."><?php echo $getField('ghi_chu_them'); ?></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo $isEditing ? '💾 Cập nhật nhật ký' : '💾 Lưu nhật ký'; ?>
                    </button>
                    <?php if ($isEditing): ?>
                        <a href="index.php?act=hdv/nhatKyTour&tour_id=<?php echo (int)$entryEditing['tour_id']; ?>" class="btn btn-secondary">
                            Hủy
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Journal Entries List -->
        <div>
            <h3 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">📋 Diễn biến tour</h3>
            <?php if (!empty($nhatKyList)): ?>
                <?php foreach ($nhatKyList as $item): ?>
                    <div class="journal-entry">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px; flex-wrap: wrap; gap: 15px;">
                            <div>
                                <strong style="color: var(--accent-gold); font-size: 16px;"><?php echo htmlspecialchars($item['ten_tour'] ?? 'Tour'); ?></strong><br>
                                <small style="color: var(--text-muted); font-size: 11px;">
                                    📅 <?php echo !empty($item['ngay_ghi']) ? date('d/m/Y', strtotime($item['ngay_ghi'])) : 'N/A'; ?>
                                </small>
                            </div>
                            <a href="index.php?act=hdv/nhatKyTour&tour_id=<?php echo (int)$item['tour_id']; ?>&entry_id=<?php echo (int)$item['id']; ?>" 
                               class="btn btn-secondary btn-sm"
                               style="background: rgba(13, 110, 253, 0.2); color: #0d6efd; border-color: rgba(13, 110, 253, 0.3);">
                                ✏️ Chỉnh sửa
                            </a>
                        </div>
                        <div style="color: var(--text-light); line-height: 1.8; white-space: pre-line;">
                            <?php 
                            $noiDung = htmlspecialchars($item['noi_dung'] ?? '');
                            if (empty($noiDung)) {
                                $parts = [];
                                if (!empty($item['tieu_de'])) $parts[] = "📌 " . htmlspecialchars($item['tieu_de']);
                                if (!empty($item['hoat_dong'])) $parts[] = "🎯 Hoạt động: " . htmlspecialchars($item['hoat_dong']);
                                if (!empty($item['su_kien'])) $parts[] = "⚠️ Sự kiện: " . htmlspecialchars($item['su_kien']);
                                if (!empty($item['cach_xu_ly'])) $parts[] = "✅ Cách xử lý: " . htmlspecialchars($item['cach_xu_ly']);
                                if (!empty($item['phan_hoi'])) $parts[] = "💬 Phản hồi: " . htmlspecialchars($item['phan_hoi']);
                                if (!empty($item['ghi_chu_them'])) $parts[] = "📝 Ghi chú: " . htmlspecialchars($item['ghi_chu_them']);
                                echo implode("\n\n", $parts);
                            } else {
                                echo $noiDung;
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info" style="margin: 0;">
                    ℹ️ Chưa có nhật ký nào cho tour này.
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        ℹ️ Bạn chưa được phân công tour nào nên chưa thể ghi nhật ký.
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
