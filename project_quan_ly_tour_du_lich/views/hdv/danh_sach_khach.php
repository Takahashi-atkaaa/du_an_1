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
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khách/Booking</th>
                                <th>Liên hệ</th>
                                <th>Nhóm</th>
                                <th>Ngày đặt</th>
                                <th>Ghi chú đặc biệt</th>
                                <th>Ghi chú nội bộ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                                <?php
                                    $soNguoi = (int)($khach['so_nguoi'] ?? 1);
                                    if ($soNguoi >= 10) {
                                        $nhomLabel = 'Đoàn lớn';
                                    } elseif ($soNguoi >= 5) {
                                        $nhomLabel = 'Nhóm';
                                    } elseif ($soNguoi >= 3) {
                                        $nhomLabel = 'Nhóm nhỏ';
                                    } elseif ($soNguoi == 2) {
                                        $nhomLabel = 'Cặp';
                                    } else {
                                        $nhomLabel = 'Khách lẻ';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $stt++; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong><br>
                                        Booking #<?php echo $khach['booking_id']; ?><br>
                                        <?php if (!empty($khach['dia_chi'])): ?>
                                            <small><?php echo htmlspecialchars($khach['dia_chi']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($khach['email'] ?? ''); ?><br>
                                        <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?>
                                    </td>
                                    <td>
                                        <?php echo $soNguoi; ?> khách<br>
                                        <small><?php echo $nhomLabel; ?></small>
                                    </td>
                                    <td>
                                        <?php echo !empty($khach['ngay_dat']) ? date('d/m/Y', strtotime($khach['ngay_dat'])) : 'N/A'; ?>
                                    </td>
                                    <td>
                                        <?php echo nl2br(htmlspecialchars($khach['yeu_cau_dac_biet'] ?? '')); ?>
                                    </td>
                                    <td>
                                        <?php echo nl2br(htmlspecialchars($khach['ghi_chu_booking'] ?? '')); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có khách nào đặt tour này hoặc tất cả booking đã bị hủy.</p>
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


