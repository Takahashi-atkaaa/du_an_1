<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Yêu cầu Đặc biệt - HDV</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <h1>Quản lý Yêu cầu Đặc biệt của Khách</h1>
    <nav>
        <a href="index.php?act=hdv/lichLamViec">← Quay lại lịch làm việc</a> | 
        <a href="index.php?act=hdv/nhatKyTour">Nhật ký Tour</a> | 
        <a href="index.php?act=hdv/danhSachKhach">Danh sách Khách</a> | 
        <a href="index.php?act=hdv/checkInKhach">Điểm danh khách</a> | 
        <a href="index.php?act=hdv/phanHoi">Phản hồi</a> | 
        <a href="index.php?act=auth/logout">Đăng xuất</a>
    </nav>
    
    <hr>

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

    <?php if (!empty($lichKhoiHanhList) && !empty($yeuCauDacBietTheoLich)): ?>
        <?php foreach ($lichKhoiHanhList as $lich): ?>
            <?php
                $lichId = (int)($lich['id'] ?? 0);
                $danhSachKhach = $yeuCauDacBietTheoLich[$lichId] ?? [];
            ?>
            <?php if (!empty($danhSachKhach)): ?>
                <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background: #f9f9f9;">
                    <h2 style="margin-top: 0;"><?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                        (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                    </h2>
                    <p>
                        <strong>Thời gian:</strong>
                        <?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>
                        →
                        <?php echo !empty($lich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?>
                    </p>
                    <p><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($lich['diem_tap_trung'] ?? 'Chưa cập nhật'); ?></p>
                    
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 15px;">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                                <th>Số người</th>
                                <th>Yêu cầu đặc biệt hiện tại</th>
                                <th>Cập nhật yêu cầu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $stt = 1; foreach ($danhSachKhach as $khach): ?>
                                <?php $khachHangId = (int)($khach['khach_hang_id'] ?? 0); ?>
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
                                    <td><?php echo (int)($khach['so_nguoi'] ?? 1); ?> khách</td>
                                    <td>
                                        <div style="max-width: 300px; word-wrap: break-word; padding: 8px; background: #fff; border: 1px solid #ddd; border-radius: 4px; min-height: 60px;">
                                            <?php if (!empty($khach['yeu_cau_dac_biet'])): ?>
                                                <?php echo nl2br(htmlspecialchars($khach['yeu_cau_dac_biet'])); ?>
                                            <?php else: ?>
                                                <em style="color: #999;">Chưa có yêu cầu đặc biệt</em>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" action="index.php?act=hdv/updateYeuCauDacBiet" style="display: flex; flex-direction: column; gap: 5px;">
                                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichId; ?>">
                                            <input type="hidden" name="tour_id" value="<?php echo $lich['tour_id']; ?>">
                                            <input type="hidden" name="khach_hang_id" value="<?php echo $khachHangId; ?>">
                                            <input type="hidden" name="redirect_to" value="hdv/quanLyYeuCauDacBiet">
                                            <textarea name="noi_dung" rows="4" style="width: 100%; min-width: 250px; padding: 6px;" placeholder="Ví dụ: ăn chay, dị ứng, bệnh lý, yêu cầu riêng..."><?php echo htmlspecialchars($khach['yeu_cau_dac_biet'] ?? ''); ?></textarea>
                                            <button type="submit" style="background: #28a745; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold;">💾 Lưu yêu cầu</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="padding: 20px; background: #f8f9fa; border-radius: 4px;">
            <strong>Chưa có khách hàng nào được đặt cho các tour bạn phụ trách.</strong><br>
            Hoặc tất cả khách hàng đều chưa có yêu cầu đặc biệt.
        </p>
    <?php endif; ?>
</body>
</html>

