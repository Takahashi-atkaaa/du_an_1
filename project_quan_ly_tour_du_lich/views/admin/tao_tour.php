<?php $isCapNhat = isset($tour) && isset($tour['tour_id']); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isCapNhat ? 'Sửa tour' : 'Thêm tour'; ?> - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1><?php echo $isCapNhat ? 'Sửa tour' : 'Thêm tour'; ?></h1>
        <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour">← Quay lại danh sách</a>
        <div class="content">
            <?php if (isset($_SESSION['error'])) : ?>
                <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['image_upload_error'])) : ?>
                <div style="color: red; margin-bottom: 10px;"><?php echo htmlspecialchars($_SESSION['image_upload_error']); unset($_SESSION['image_upload_error']); ?></div>
            <?php endif; ?>
            
            <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL; ?>index.php?act=<?php echo $isCapNhat ? 'tour/update' : 'tour/create'; ?>">
                <?php if ($isCapNhat) : ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($tour['tour_id']); ?>">
                <?php endif; ?>
                <h2>Thông tin tour</h2>
                
                <div style="margin-bottom: 10px;">
                    <label>Tên tour *</label><br>
                    <input type="text" name="ten_tour" value="<?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>" required style="width: 100%;">
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Loại tour</label><br>
                    <select name="loai_tour" style="width: 100%;">
                        <?php $loai = $tour['loai_tour'] ?? 'TrongNuoc'; ?>
                        <option value="TrongNuoc" <?php echo $loai === 'TrongNuoc' ? 'selected' : ''; ?>>Trong nước</option>
                        <option value="QuocTe" <?php echo $loai === 'QuocTe' ? 'selected' : ''; ?>>Quốc tế</option>
                        <option value="TheoYeuCau" <?php echo $loai === 'TheoYeuCau' ? 'selected' : ''; ?>>Theo yêu cầu</option>
                    </select>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Mô tả</label><br>
                    <textarea name="mo_ta" rows="4" style="width: 100%;"><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></textarea>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label>Giá cơ bản (VND) *</label><br>
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
                    <?php $status = $tour['trang_thai'] ?? 'HoatDong'; ?>
                    <select name="trang_thai" style="width: 100%;">
                        <option value="HoatDong" <?php echo $status === 'HoatDong' ? 'selected' : ''; ?>>Hoạt động</option>
                        <option value="TamDung" <?php echo $status === 'TamDung' ? 'selected' : ''; ?>>Tạm dừng</option>
                        <option value="HetHan" <?php echo $status === 'HetHan' ? 'selected' : ''; ?>>Hết hạn</option>
                    </select>
                </div>
                
                <hr style="margin: 20px 0;">
                <h2>Lịch khởi hành</h2>
                <?php
                    $lichKhoiHanh = $lichKhoiHanhList[0] ?? ['ngay_khoi_hanh' => '', 'ngay_ket_thuc' => '', 'diem_tap_trung' => '', 'hdv_id' => '', 'trang_thai' => 'SapKhoiHanh'];
                ?>
                <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                    <div style="margin-bottom: 5px;">
                        <label>Ngày khởi hành</label>
                        <input type="date" name="lich_khoi_hanh[ngay_khoi_hanh]" value="<?php echo htmlspecialchars($lichKhoiHanh['ngay_khoi_hanh'] ?? ''); ?>" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 5px;">
                        <label>Ngày kết thúc</label>
                        <input type="date" name="lich_khoi_hanh[ngay_ket_thuc]" value="<?php echo htmlspecialchars($lichKhoiHanh['ngay_ket_thuc'] ?? ''); ?>" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 5px;">
                        <label>Điểm tập trung</label>
                        <input type="text" name="lich_khoi_hanh[diem_tap_trung]" value="<?php echo htmlspecialchars($lichKhoiHanh['diem_tap_trung'] ?? ''); ?>" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 5px;">
                        <label>ID Hướng dẫn viên</label>
                        <input type="number" name="lich_khoi_hanh[hdv_id]" value="<?php echo htmlspecialchars($lichKhoiHanh['hdv_id'] ?? ''); ?>" min="0" style="width: 100%;">
                    </div>
                    <div style="margin-bottom: 5px;">
                        <label>Trạng thái</label>
                        <select name="lich_khoi_hanh[trang_thai]" style="width: 100%;">
                            <?php $lichStatus = $lichKhoiHanh['trang_thai'] ?? 'SapKhoiHanh'; ?>
                            <option value="SapKhoiHanh" <?php echo $lichStatus === 'SapKhoiHanh' ? 'selected' : ''; ?>>Sắp khởi hành</option>
                            <option value="DangChay" <?php echo $lichStatus === 'DangChay' ? 'selected' : ''; ?>>Đang chạy</option>
                            <option value="HoanThanh" <?php echo $lichStatus === 'HoanThanh' ? 'selected' : ''; ?>>Hoàn thành</option>
                        </select>
                    </div>
                </div>
                
                <hr style="margin: 20px 0;">
                <button type="submit" name="hanh_dong" value="preview">Cập nhật lịch trình</button>
                <h2>Lịch trình tour</h2>
                <?php
                    $lichTrinhList = $lichTrinhList ?? [];
                    $expectedDayCount = 1;
                    $startDateForSchedule = null;
                    $dateRangeValid = false;

                    if (!empty($lichKhoiHanh['ngay_khoi_hanh']) && !empty($lichKhoiHanh['ngay_ket_thuc'])) {
                        try {
                            $startDateForSchedule = new DateTime($lichKhoiHanh['ngay_khoi_hanh']);
                            $endDateForSchedule = new DateTime($lichKhoiHanh['ngay_ket_thuc']);
                            if ($endDateForSchedule >= $startDateForSchedule) {
                                $expectedDayCount = $startDateForSchedule->diff($endDateForSchedule)->days + 1;
                                $dateRangeValid = true;
                            }
                        } catch (Exception $e) {
                            // Giữ expectedDayCount nếu ngày không hợp lệ
                        }
                    }

                    if ($dateRangeValid) {
                        $lichTrinhList = array_slice($lichTrinhList, 0, $expectedDayCount);
                    }

                    for ($i = count($lichTrinhList); $i < $expectedDayCount; $i++) {
                        $lichTrinhList[] = ['ngay_thu' => '', 'dia_diem' => '', 'hoat_dong' => ''];
                    }
                ?>
                <p><small>Lịch trình sẽ tự động tạo dựa trên ngày khởi hành và ngày kết thúc. Vui lòng nhập ngày khởi hành trước.</small></p>
                <?php foreach ($lichTrinhList as $chiSo => $lichTrinh) : ?>
                    <?php
                        $soNgayThu = ($lichTrinh['ngay_thu'] ?? '') !== '' ? (int)$lichTrinh['ngay_thu'] : $chiSo + 1;
                        $nhanNgay = '';
                        if ($dateRangeValid && $startDateForSchedule instanceof DateTime) {
                            $ngayHienTai = clone $startDateForSchedule;
                            $ngayHienTai->modify('+' . $chiSo . ' day');
                            $nhanNgay = ' (' . $ngayHienTai->format('d/m/Y') . ')';
                            $soNgayThu = $chiSo + 1;
                        }
                    ?>
                    
                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                        <div style="margin-bottom: 5px;">
                            <label>Ngày thứ <?php echo $soNgayThu; ?><?php echo $nhanNgay; ?></label>
                            <input type="hidden" name="lich_trinh[<?php echo $chiSo; ?>][ngay_thu]" value="<?php echo $soNgayThu; ?>">
                            <span style="color: #666;">Ngày <?php echo $soNgayThu; ?></span>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Địa điểm</label>
                            <input type="text" name="lich_trinh[<?php echo $chiSo; ?>][dia_diem]" value="<?php echo htmlspecialchars($lichTrinh['dia_diem'] ?? ''); ?>" style="width: 100%;">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Hoạt động</label>
                            <textarea name="lich_trinh[<?php echo $chiSo; ?>][hoat_dong]" rows="2" style="width: 100%;"><?php echo htmlspecialchars($lichTrinh['hoat_dong'] ?? ''); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <hr style="margin: 20px 0;">
                <h2>Hình ảnh tour</h2>
                <?php
                    $hinhAnhList = $hinhAnhList ?? [];
                    $maxHinhAnh = max(count($hinhAnhList), 5);
                    for ($i = count($hinhAnhList); $i < $maxHinhAnh; $i++) {
                        $hinhAnhList[] = ['url_anh' => '', 'mo_ta' => ''];
                    }
                ?>
                <?php foreach ($hinhAnhList as $index => $anh) : ?>
                    <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                        <div style="margin-bottom: 5px;">
                            <label>Ảnh</label><br>
                            <?php if (!empty($anh['url_anh'])) : ?>
                                <div style="margin-bottom: 5px;">
                                    <img src="<?php echo htmlspecialchars(BASE_URL . $anh['url_anh']); ?>" alt="Ảnh tour" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="hinh_anh_file[<?php echo $index; ?>]" accept="image/*">
                            <input type="hidden" name="hinh_anh[<?php echo $index; ?>][url_anh]" value="<?php echo htmlspecialchars($anh['url_anh'] ?? ''); ?>">
                        </div>
                        <div style="margin-bottom: 5px;">
                            <label>Mô tả hình ảnh</label>
                            <input type="text" name="hinh_anh[<?php echo $index; ?>][mo_ta]" value="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>" style="width: 100%;">
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div style="margin-top: 20px;">
                    <?php if ($isCapNhat) : ?>
                        <button type="submit" name="hanh_dong" value="update">Cập nhật</button>
                    <?php else : ?>
                        <button type="submit" name="hanh_dong" value="create">Tạo mới</button>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" style="margin-left: 10px;">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

