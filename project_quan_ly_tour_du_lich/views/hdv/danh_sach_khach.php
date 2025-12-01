<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Khách - HDV</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Danh sách khách trong đoàn</h1>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="index.php?act=hdv/lichLamViec">← Quay lại lịch làm việc</a>
            <a href="index.php?act=hdv/checkInKhach">Đi tới điểm danh khách</a>
        </div>

        <?php if (!empty($lichKhoiHanhList)): ?>
            <form method="GET" action="index.php" style="margin: 20px 0;">
                <input type="hidden" name="act" value="hdv/danhSachKhach">
                <label for="lich_id"><strong>Chọn lịch khởi hành:</strong></label>
                <select name="lich_id" id="lich_id" onchange="this.form.submit()">
                    <?php foreach ($lichKhoiHanhList as $lich): ?>
                        <option value="<?php echo $lich['id']; ?>" <?php echo (isset($selectedLich) && $selectedLich && $selectedLich['id'] == $lich['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                            (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if ($selectedLich): ?>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <h2 style="margin-top: 0;"><?php echo htmlspecialchars($selectedLich['ten_tour'] ?? 'Tour'); ?></h2>
                    <p>
                        <strong>Thời gian:</strong>
                        <?php echo !empty($selectedLich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($selectedLich['ngay_khoi_hanh'])) : 'N/A'; ?>
                        →
                        <?php echo !empty($selectedLich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($selectedLich['ngay_ket_thuc'])) : 'N/A'; ?>
                    </p>
                    <p><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($selectedLich['diem_tap_trung'] ?? 'Chưa cập nhật'); ?></p>
                </div>

                <?php if (!empty($danhSachKhach)): ?>
                    <div style="margin-bottom: 10px;">
                        <strong>Tổng số khách: <?php echo count($danhSachKhach); ?> người</strong>
                    </div>
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f0f0f0;">
                                <th>STT</th>
                                <th>Họ tên</th>
                                <th>CMND/Passport</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Quốc tịch</th>
                                <th>Liên hệ</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <th>Booking ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong>
                                    </td>
                                    <td>
                                        <?php if (!empty($khach['so_cmnd'])): ?>
                                            CMND: <?php echo htmlspecialchars($khach['so_cmnd']); ?><br>
                                        <?php endif; ?>
                                        <?php if (!empty($khach['so_passport'])): ?>
                                            Passport: <?php echo htmlspecialchars($khach['so_passport']); ?>
                                        <?php endif; ?>
                                        <?php if (empty($khach['so_cmnd']) && empty($khach['so_passport'])): ?>
                                            <span style="color: #999;">Chưa cập nhật</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($khach['ngay_sinh']) ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : 'N/A'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $gioiTinhLabels = ['Nam' => 'Nam', 'Nu' => 'Nữ', 'Khac' => 'Khác'];
                                        echo $gioiTinhLabels[$khach['gioi_tinh']] ?? $khach['gioi_tinh'] ?? 'N/A';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($khach['quoc_tich'] ?? 'Việt Nam'); ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($khach['email'])): ?>
                                            <small><?php echo htmlspecialchars($khach['email']); ?></small><br>
                                        <?php endif; ?>
                                        <?php if (!empty($khach['so_dien_thoai'])): ?>
                                            <small><?php echo htmlspecialchars($khach['so_dien_thoai']); ?></small>
                                        <?php endif; ?>
                                        <?php if (empty($khach['email']) && empty($khach['so_dien_thoai'])): ?>
                                            <span style="color: #999;">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($khach['dia_chi']) ? htmlspecialchars($khach['dia_chi']) : 'N/A'; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $trangThaiLabels = [
                                            'ChuaCheckIn' => 'Chưa check-in',
                                            'DaCheckIn' => 'Đã check-in',
                                            'DaCheckOut' => 'Đã check-out'
                                        ];
                                        $trangThaiClass = [
                                            'ChuaCheckIn' => 'background-color: #ffc107; color: #000; padding: 4px 8px; border-radius: 4px;',
                                            'DaCheckIn' => 'background-color: #28a745; color: #fff; padding: 4px 8px; border-radius: 4px;',
                                            'DaCheckOut' => 'background-color: #6c757d; color: #fff; padding: 4px 8px; border-radius: 4px;'
                                        ];
                                        $trangThai = $khach['trang_thai'] ?? 'ChuaCheckIn';
                                        ?>
                                        <span style="<?php echo $trangThaiClass[$trangThai] ?? $trangThaiClass['ChuaCheckIn']; ?>">
                                            <?php echo $trangThaiLabels[$trangThai] ?? $trangThai; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($khach['booking_id'])): ?>
                                            #<?php echo $khach['booking_id']; ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có khách nào trong danh sách. Vui lòng thêm khách vào lịch khởi hành này.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Không tìm thấy lịch khởi hành phù hợp.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Bạn chưa được phân công tour nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>


