<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Điểm danh khách - HDV</title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-badge.checkin { background: #d4edda; color: #155724; }
        .status-badge.pending { background: #fff3cd; color: #856404; }
        .status-badge.checkout { background: #d1ecf1; color: #0c5460; }
        table.checkin-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.checkin-table th,
        table.checkin-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }
        table.checkin-table th {
            background: #f1f1f1;
            text-align: left;
        }
        .checkin-form-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .checkin-form-inline textarea {
            min-width: 180px;
        }
        .checkin-form-inline button {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Điểm danh khách theo tour</h1>
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
            <form method="GET" action="index.php" style="margin: 20px 0;">
                <input type="hidden" name="act" value="hdv/checkInKhach">
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
                    <table class="checkin-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khách/Booking</th>
                                <th>Liên hệ</th>
                                <th>Nhóm</th>
                                <th>Trạng thái điểm danh</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                                <?php
                                    $khachHangId = (int)($khach['khach_hang_id'] ?? 0);
                                    $soNguoi = (int)($khach['so_nguoi'] ?? 1);
                                    $nhomLabel = $soNguoi >= 10 ? 'Đoàn lớn' : ($soNguoi >= 5 ? 'Nhóm' : ($soNguoi >= 3 ? 'Nhóm nhỏ' : ($soNguoi == 2 ? 'Cặp' : 'Khách lẻ')));
                                    $checkin = $checkinMap[$khachHangId] ?? null;
                                    $trangThai = $checkin['trang_thai'] ?? 'ChuaCheckIn';
                                    $badgeClass = match ($trangThai) {
                                        'DaCheckIn' => 'status-badge checkin',
                                        'DaCheckOut' => 'status-badge checkout',
                                        default => 'status-badge pending'
                                    };
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
                                        <span class="<?php echo $badgeClass; ?>">
                                            <?php
                                                $labels = [
                                                    'ChuaCheckIn' => 'Chưa check-in',
                                                    'DaCheckIn' => 'Đã check-in',
                                                    'DaCheckOut' => 'Đã check-out'
                                                ];
                                                echo $labels[$trangThai] ?? $trangThai;
                                            ?>
                                        </span>
                                        <?php if (!empty($checkin['checkin_time'])): ?>
                                            <br><small>Check-in: <?php echo date('d/m H:i', strtotime($checkin['checkin_time'])); ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($checkin['checkout_time'])): ?>
                                            <br><small>Check-out: <?php echo date('d/m H:i', strtotime($checkin['checkout_time'])); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="index.php?act=hdv/updateCheckInKhach" class="checkin-form-inline">
                                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $selectedLich['id']; ?>">
                                            <input type="hidden" name="booking_id" value="<?php echo $khach['booking_id']; ?>">
                                            <input type="hidden" name="khach_hang_id" value="<?php echo $khachHangId; ?>">
                                            <select name="trang_thai">
                                                <option value="ChuaCheckIn" <?php echo $trangThai === 'ChuaCheckIn' ? 'selected' : ''; ?>>Chưa check-in</option>
                                                <option value="DaCheckIn" <?php echo $trangThai === 'DaCheckIn' ? 'selected' : ''; ?>>Đã check-in</option>
                                                <option value="DaCheckOut" <?php echo $trangThai === 'DaCheckOut' ? 'selected' : ''; ?>>Đã check-out</option>
                                            </select>
                                            <textarea name="ghi_chu" rows="2" placeholder="Ghi chú ngắn..."><?php echo htmlspecialchars($checkin['ghi_chu'] ?? ''); ?></textarea>
                                            <button type="submit">Cập nhật</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có khách nào được đặt cho lịch khởi hành này.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Không tìm thấy lịch khởi hành bạn chọn.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Bạn chưa được phân công lịch khởi hành nào.</p>
        <?php endif; ?>
    </div>
</body>
</html>

