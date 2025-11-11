<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tour) && $tour ? 'Sửa tour' : 'Thêm tour'; ?> - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1><?php echo isset($tour) && $tour ? 'Sửa tour' : 'Thêm tour'; ?></h1>
        <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour">← Quay lại danh sách</a>
        <div class="content">
            <form method="post" action="<?php echo BASE_URL; ?>index.php?act=<?php echo isset($tour) && $tour ? 'tour/update' : 'tour/create'; ?>">
                <?php if (isset($tour) && $tour) : ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($tour['tour_id']); ?>">
                <?php endif; ?>
                
                <div style="margin-bottom: 10px;">
                    <label>Tên tour</label><br>
                    <input type="text" name="ten_tour" value="<?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>" required style="width: 100%;">
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Loại tour</label><br>
                    <select name="loai_tour" style="width: 100%;">
                        <?php
                            $loai = $tour['loai_tour'] ?? 'TrongNuoc';
                        ?>
                        <option value="TrongNuoc" <?php echo $loai === 'TrongNuoc' ? 'selected' : ''; ?>>Trong nước</option>
                        <option value="QuocTe" <?php echo $loai === 'QuocTe' ? 'selected' : ''; ?>>Quốc tế</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Mô tả</label><br>
                    <textarea name="mo_ta" rows="4" style="width: 100%;"><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></textarea>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Giá cơ bản (VND)</label><br>
                    <input type="number" name="gia_co_ban" step="1000" min="0" value="<?php echo htmlspecialchars((string)($tour['gia_co_ban'] ?? '0')); ?>" required style="width: 100%;">
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Chính sách</label><br>
                    <textarea name="chinh_sach" rows="4" style="width: 100%;"><?php echo htmlspecialchars($tour['chinh_sach'] ?? ''); ?></textarea>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>ID Nhà cung cấp</label><br>
                    <input type="number" name="id_nha_cung_cap" min="0" value="<?php echo htmlspecialchars((string)($tour['id_nha_cung_cap'] ?? '')); ?>" style="width: 100%;">
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Trạng thái</label><br>
                    <?php
                        $status = $tour['trang_thai'] ?? 'HoatDong';
                    ?>
                    <select name="trang_thai" style="width: 100%;">
                        <option value="HoatDong" <?php echo $status === 'HoatDong' ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="TamDung" <?php echo $status === 'TamDung' ? 'selected' : ''; ?>>Tạm dừng</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit"><?php echo isset($tour) && $tour ? 'Cập nhật' : 'Tạo mới'; ?></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


