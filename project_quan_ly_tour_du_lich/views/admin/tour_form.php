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
                
                <hr style="margin: 20px 0;">
                <h3>Lịch trình tour</h3>
                <?php
                    $lichTrinhList = [];
                    if (isset($tour) && $tour) {
                        $lichTrinhList = $this->model->getLichTrinhByTourId($tour['tour_id']);
                    }
                    if (empty($lichTrinhList)) {
                        $lichTrinhList = [['ngay_thu' => '', 'dia_diem' => '', 'hoat_dong' => '']];
                    }
                ?>
                <?php foreach ($lichTrinhList as $index => $lichTrinh) : ?>
                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                        <div style="margin-bottom: 5px;">
                            <label>Ngày thứ</label>
                            <input type="number" name="lich_trinh[<?php echo $index; ?>][ngay_thu]" value="<?php echo htmlspecialchars($lichTrinh['ngay_thu'] ?? ''); ?>" min="1" style="width: 100px;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Địa điểm</label>
                            <input type="text" name="lich_trinh[<?php echo $index; ?>][dia_diem]" value="<?php echo htmlspecialchars($lichTrinh['dia_diem'] ?? ''); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Hoạt động</label>
                            <textarea name="lich_trinh[<?php echo $index; ?>][hoat_dong]" rows="2" style="width: 100%;"><?php echo htmlspecialchars($lichTrinh['hoat_dong'] ?? ''); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="margin-bottom: 10px;">
                    <button type="button" onclick="this.parentElement.insertAdjacentHTML('beforebegin', '<div style=\'border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;\'><div style=\'margin-bottom: 5px;\'><label>Ngày thứ</label><input type=\'number\' name=\'lich_trinh[' + document.querySelectorAll(\'[name^=\\\'lich_trinh\\\']\').length + '][ngay_thu]\' min=\'1\' style=\'width: 100px;\'></div><div style=\'margin-bottom: 5px;\'><label>Địa điểm</label><input type=\'text\' name=\'lich_trinh[' + document.querySelectorAll(\'[name^=\\\'lich_trinh\\\']\').length + '][dia_diem]\' style=\'width: 100%;\'></div><div style=\'margin-bottom: 5px;\'><label>Hoạt động</label><textarea name=\'lich_trinh[' + document.querySelectorAll(\'[name^=\\\'lich_trinh\\\']\').length + '][hoat_dong]\' rows=\'2\' style=\'width: 100%;\'></textarea></div></div>')">+ Thêm ngày</button>
                </div>
                
                <hr style="margin: 20px 0;">
                <h3>Lịch khởi hành</h3>
                <?php
                    $lichKhoiHanhList = [];
                    if (isset($tour) && $tour) {
                        $lichKhoiHanhList = $this->model->getLichKhoiHanhByTourId($tour['tour_id']);
                    }
                    if (empty($lichKhoiHanhList)) {
                        $lichKhoiHanhList = [['ngay_khoi_hanh' => '', 'ngay_ket_thuc' => '', 'diem_tap_trung' => '', 'hdv_id' => '', 'trang_thai' => 'SapKhoiHanh']];
                    }
                ?>
                <?php foreach ($lichKhoiHanhList as $index => $lichKhoiHanh) : ?>
                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                        <div style="margin-bottom: 5px;">
                            <label>Ngày khởi hành</label>
                            <input type="date" name="lich_khoi_hanh[<?php echo $index; ?>][ngay_khoi_hanh]" value="<?php echo htmlspecialchars($lichKhoiHanh['ngay_khoi_hanh'] ?? ''); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Ngày kết thúc</label>
                            <input type="date" name="lich_khoi_hanh[<?php echo $index; ?>][ngay_ket_thuc]" value="<?php echo htmlspecialchars($lichKhoiHanh['ngay_ket_thuc'] ?? ''); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Điểm tập trung</label>
                            <input type="text" name="lich_khoi_hanh[<?php echo $index; ?>][diem_tap_trung]" value="<?php echo htmlspecialchars($lichKhoiHanh['diem_tap_trung'] ?? ''); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>ID Hướng dẫn viên</label>
                            <input type="number" name="lich_khoi_hanh[<?php echo $index; ?>][hdv_id]" value="<?php echo htmlspecialchars($lichKhoiHanh['hdv_id'] ?? ''); ?>" min="0" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Trạng thái</label>
                            <select name="lich_khoi_hanh[<?php echo $index; ?>][trang_thai]" style="width: 100%;">
                                <?php $lichStatus = $lichKhoiHanh['trang_thai'] ?? 'SapKhoiHanh'; ?>
                                <option value="SapKhoiHanh" <?php echo $lichStatus === 'SapKhoiHanh' ? 'selected' : ''; ?>>Sắp khởi hành</option>
                                <option value="DangChay" <?php echo $lichStatus === 'DangChay' ? 'selected' : ''; ?>>Đang chạy</option>
                                <option value="HoanThanh" <?php echo $lichStatus === 'HoanThanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="margin-bottom: 10px;">
                    <button type="button" onclick="this.parentElement.insertAdjacentHTML('beforebegin', '<div style=\'border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;\'><div style=\'margin-bottom: 5px;\'><label>Ngày khởi hành</label><input type=\'date\' name=\'lich_khoi_hanh[' + document.querySelectorAll(\'[name^=\\\'lich_khoi_hanh\\\']\').length + '][ngay_khoi_hanh]\' style=\'width: 100%;\'></div><div style=\'margin-bottom: 5px;\'><label>Ngày kết thúc</label><input type=\'date\' name=\'lich_khoi_hanh[' + document.querySelectorAll(\'[name^=\\\'lich_khoi_hanh\\\']\').length + '][ngay_ket_thuc]\' style=\'width: 100%;\'></div><div style=\'margin-bottom: 5px;\'><label>Điểm tập trung</label><input type=\'text\' name=\'lich_khoi_hanh[' + document.querySelectorAll(\'[name^=\\\'lich_khoi_hanh\\\']\').length + '][diem_tap_trung]\' style=\'width: 100%;\'></div><div style=\'margin-bottom: 5px;\'><label>ID Hướng dẫn viên</label><input type=\'number\' name=\'lich_khoi_hanh[' + document.querySelectorAll(\'[name^=\\\'lich_khoi_hanh\\\']\').length + '][hdv_id]\' min=\'0\' style=\'width: 100%;\'></div><div style=\'margin-bottom: 5px;\'><label>Trạng thái</label><select name=\'lich_khoi_hanh[' + document.querySelectorAll(\'[name^=\\\'lich_khoi_hanh\\\']\').length + '][trang_thai]\' style=\'width: 100%;\'><option value=\'SapKhoiHanh\'>Sắp khởi hành</option><option value=\'DangChay\'>Đang chạy</option><option value=\'HoanThanh\'>Hoàn thành</option></select></div></div>')">+ Thêm lịch khởi hành</button>
                </div>
                
                <hr style="margin: 20px 0;">
                <h3>Hình ảnh tour</h3>
                <?php
                    $hinhAnhList = [];
                    if (isset($tour) && $tour) {
                        $hinhAnhList = $this->model->getHinhAnhByTourId($tour['tour_id']);
                    }
                    if (empty($hinhAnhList)) {
                        $hinhAnhList = [['url_anh' => '', 'mo_ta' => '']];
                    }
                ?>
                <?php foreach ($hinhAnhList as $index => $anh) : ?>
                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                        <div style="margin-bottom: 5px;">
                            <label>URL hình ảnh</label>
                            <input type="text" name="hinh_anh[<?php echo $index; ?>][url_anh]" value="<?php echo htmlspecialchars($anh['url_anh'] ?? ''); ?>" style="width: 100%;" placeholder="public/images/tour1.jpg">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Mô tả hình ảnh</label>
                            <input type="text" name="hinh_anh[<?php echo $index; ?>][mo_ta]" value="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>" style="width: 100%;">
                        </div>
                    </div>
                <?php endforeach; ?>
                <div style="margin-bottom: 10px;">
                    <button type="button" onclick="this.parentElement.insertAdjacentHTML('beforebegin', '<div style=\'border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;\'><div style=\'margin-bottom: 5px;\'><label>URL hình ảnh</label><input type=\'text\' name=\'hinh_anh[' + document.querySelectorAll(\'[name^=\\\'hinh_anh\\\']\').length + '][url_anh]\' style=\'width: 100%;\' placeholder=\'public/images/tour1.jpg\'></div><div style=\'margin-bottom: 5px;\'><label>Mô tả hình ảnh</label><input type=\'text\' name=\'hinh_anh[' + document.querySelectorAll(\'[name^=\\\'hinh_anh\\\']\').length + '][mo_ta]\' style=\'width: 100%;\'></div></div>')">+ Thêm hình ảnh</button>
                </div>
                
                <div>
                    <button type="submit"><?php echo isset($tour) && $tour ? 'Cập nhật' : 'Tạo mới'; ?></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


