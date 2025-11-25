<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhật ký Tour - HDV</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Nhật ký tour của tôi</h1>
        <a href="index.php?act=hdv/lichLamViec">← Quay lại lịch làm việc</a>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="background: #d4edda; padding: 12px; margin: 15px 0; border-radius: 4px; color: #155724;">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; padding: 12px; margin: 15px 0; border-radius: 4px; color: #721c24;">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($lichKhoiHanhList)): ?>
            <?php
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
            <form method="GET" action="index.php" style="margin: 20px 0;">
                <input type="hidden" name="act" value="hdv/nhatKyTour">
                <label for="tour_id"><strong>Chọn tour:</strong></label>
                <select name="tour_id" id="tour_id" onchange="this.form.submit()">
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

            <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                <div style="flex: 1 1 350px; min-width: 320px;">
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h2 style="margin-top: 0;"><?php echo $isEditing ? 'Cập nhật nhật ký' : 'Ghi nhật ký mới'; ?></h2>
                        <form method="POST" action="index.php?act=hdv/nhatKyTour">
                            <input type="hidden" name="journal_action" value="<?php echo $isEditing ? 'update' : 'create'; ?>">
                            <?php if ($isEditing): ?>
                                <input type="hidden" name="entry_id" value="<?php echo (int)$entryEditing['id']; ?>">
                            <?php endif; ?>

                            <label for="form_tour_id">Tour</label>
                            <select name="tour_id" id="form_tour_id" required>
                                <?php foreach ($lichKhoiHanhList as $lich): ?>
                                    <option value="<?php echo $lich['tour_id']; ?>"
                                        <?php echo ((int)$formTourId === (int)$lich['tour_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label for="ngay_ghi">Ngày ghi</label>
                            <input type="date" name="ngay_ghi" id="ngay_ghi" value="<?php echo htmlspecialchars($entryEditing['ngay_ghi'] ?? date('Y-m-d')); ?>" required>

                            <label for="tieu_de">Tiêu đề / Tóm tắt</label>
                            <input type="text" name="tieu_de" id="tieu_de" 
                                   value="<?php echo $getField('tieu_de'); ?>">

                            <label for="hoat_dong">Hoạt động nổi bật</label>
                            <textarea name="hoat_dong" id="hoat_dong" rows="3"><?php echo $getField('hoat_dong'); ?></textarea>

                            <label for="su_kien">Sự kiện / Sự cố</label>
                            <textarea name="su_kien" id="su_kien" rows="3"><?php echo $getField('su_kien'); ?></textarea>

                            <label for="cach_xu_ly">Cách xử lý</label>
                            <textarea name="cach_xu_ly" id="cach_xu_ly" rows="3"><?php echo $getField('cach_xu_ly'); ?></textarea>

                            <label for="phan_hoi">Phản hồi của khách hàng</label>
                            <textarea name="phan_hoi" id="phan_hoi" rows="3"><?php echo $getField('phan_hoi'); ?></textarea>

                            <label for="anh_minh_hoa">Link ảnh/video minh họa</label>
                            <input type="text" name="anh_minh_hoa" id="anh_minh_hoa" value="<?php echo $getField('anh_minh_hoa'); ?>" placeholder="https://...">

                            <label for="ghi_chu_them">Ghi chú thêm</label>
                            <textarea name="ghi_chu_them" id="ghi_chu_them" rows="3"><?php echo $getField('ghi_chu_them'); ?></textarea>

                            <button type="submit" style="margin-top: 15px;">
                                <?php echo $isEditing ? 'Cập nhật nhật ký' : 'Lưu nhật ký'; ?>
                            </button>
                            <?php if ($isEditing): ?>
                                <a href="index.php?act=hdv/nhatKyTour&tour_id=<?php echo (int)$entryEditing['tour_id']; ?>" style="margin-left: 10px;">Hủy</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <div style="flex: 2 1 400px; min-width: 340px;">
                    <h2>Diễn biến tour</h2>
                    <?php if (!empty($nhatKyList)): ?>
                        <?php foreach ($nhatKyList as $item): ?>
                            <div style="border: 1px solid #ddd; border-radius: 6px; padding: 15px; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['ten_tour'] ?? 'Tour'); ?></strong><br>
                                        <small><?php echo !empty($item['ngay_ghi']) ? date('d/m/Y', strtotime($item['ngay_ghi'])) : 'N/A'; ?></small>
                                    </div>
                                    <a href="index.php?act=hdv/nhatKyTour&tour_id=<?php echo (int)$item['tour_id']; ?>&entry_id=<?php echo (int)$item['id']; ?>">
                                        Chỉnh sửa
                                    </a>
                                </div>
                                <hr>
                                <p style="white-space: pre-line; margin-bottom: 5px;"><?php echo htmlspecialchars($item['noi_dung'] ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Chưa có nhật ký nào cho tour này.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Bạn chưa được phân công tour nào nên chưa thể ghi nhật ký.</p>
        <?php endif; ?>
    </div>
</body>
</html>


