<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch làm việc - HDV</title>
</head>
<body>
    <h1>Lịch làm việc của tôi</h1>
    <nav>
        <a href="index.php?act=hdv/nhatKyTour">Nhật ký Tour</a> | 
        <a href="index.php?act=hdv/danhSachKhach">Danh sách Khách</a> | 
        <a href="index.php?act=hdv/checkInKhach">Điểm danh khách</a> | 
        <a href="index.php?act=hdv/quanLyYeuCauDacBiet">Yêu cầu đặc biệt</a> | 

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
        <h2>Quản lý yêu cầu đặc biệt của khách</h2>
        <?php foreach ($lichKhoiHanhList as $lich): ?>
            <?php
                $lichId = (int)($lich['id'] ?? 0);
                $danhSachKhach = $yeuCauDacBietTheoLich[$lichId] ?? [];
            ?>
            <?php if (!empty($danhSachKhach)): ?>
                <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background: #f9f9f9;">
                    <h3 style="margin-top: 0;"><?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?> 
                        (<?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>)
                    </h3>
                    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Liên hệ</th>
                                <th>Yêu cầu đặc biệt</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSachKhach as $khach): ?>
                                <?php $khachHangId = (int)($khach['khach_hang_id'] ?? 0); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong><br>
                                        Booking #<?php echo $khach['booking_id']; ?><br>
                                        <small><?php echo (int)($khach['so_nguoi'] ?? 1); ?> khách</small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($khach['email'] ?? ''); ?><br>
                                        <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px; word-wrap: break-word;">
                                            <?php echo nl2br(htmlspecialchars($khach['yeu_cau_dac_biet'] ?? 'Chưa có yêu cầu đặc biệt')); ?>
                                        </div>
                                    </td>
                         
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <hr>
    <?php endif; ?>
    
    <h2>Lịch khởi hành được phân công (HDV chính)</h2>
    <?php if (isset($lichKhoiHanhList) && !empty($lichKhoiHanhList)): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Giờ xuất phát</th>
                    <th>Ngày kết thúc</th>
                    <th>Giờ kết thúc</th>
                    <th>Điểm tập trung</th>
                    <th>Số chỗ</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; foreach($lichKhoiHanhList as $lich): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($lich['ten_tour'] ?? 'N/A'); ?></td>
                    <td><?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                    <td><?php echo $lich['gio_xuat_phat'] ?? 'N/A'; ?></td>
                    <td><?php echo !empty($lich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?></td>
                    <td><?php echo $lich['gio_ket_thuc'] ?? 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($lich['diem_tap_trung'] ?? ''); ?></td>
                    <td><?php echo $lich['so_cho'] ?? 50; ?></td>
                    <td>
                        <?php
                        $statusLabels = [
                            'SapKhoiHanh' => 'Sắp khởi hành',
                            'DangChay' => 'Đang chạy',
                            'HoanThanh' => 'Hoàn thành'
                        ];
                        $trangThai = $lich['trang_thai'] ?? null;
                        echo $trangThai ? ($statusLabels[$trangThai] ?? $trangThai) : 'N/A';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có lịch khởi hành nào được phân công.</p>
    <?php endif; ?>
    
    <hr>
    
    <h2>Phân bổ nhân sự (HDV phụ, tài xế, ...)</h2>
    <?php if (isset($phanBoNhanSuList) && !empty($phanBoNhanSuList)): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tour</th>
                    <th>Ngày khởi hành</th>
                    <th>Ngày kết thúc</th>
                    <th>Vai trò</th>
                    <th>Trạng thái phân bổ</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $stt = 1; foreach($phanBoNhanSuList as $pb): ?>
                <tr>
                    <td><?php echo $stt++; ?></td>
                    <td><?php echo htmlspecialchars($pb['ten_tour'] ?? 'N/A'); ?></td>
                    <td><?php echo !empty($pb['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($pb['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                    <td><?php echo !empty($pb['ngay_ket_thuc']) ? date('d/m/Y', strtotime($pb['ngay_ket_thuc'])) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($pb['vai_tro'] ?? ''); ?></td>
                    <td>
                        <?php
                        $statusLabels = [
                            'ChoXacNhan' => 'Chờ xác nhận',
                            'DaXacNhan' => 'Đã xác nhận',
                            'TuChoi' => 'Từ chối',
                            'Huy' => 'Hủy'
                        ];
                        $trangThaiPb = $pb['trang_thai'] ?? null;
                        echo $trangThaiPb ? ($statusLabels[$trangThaiPb] ?? $trangThaiPb) : 'N/A';
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($pb['ghi_chu'] ?? ''); ?></td>
                    <td>
                        <form method="POST" action="index.php?act=lichKhoiHanh/updateTrangThaiNhanSu" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $pb['id']; ?>">
                            <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $pb['lich_khoi_hanh_id']; ?>">
                            <select name="trang_thai" onchange="this.form.submit()">
                                <option value="ChoXacNhan" <?php echo $pb['trang_thai'] == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                <option value="DaXacNhan" <?php echo $pb['trang_thai'] == 'DaXacNhan' ? 'selected' : ''; ?>>Đã xác nhận</option>

                            </select>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có phân bổ nhân sự nào.</p>
    <?php endif; ?>

    <?php if (!empty($lichKhoiHanhList)): ?>
        <hr>
        <h2>Chi tiết tour & nhiệm vụ của tôi</h2>
        <?php foreach ($lichKhoiHanhList as $lich): ?>
            <?php
                $tourId = $lich['tour_id'] ?? null;
                $lichTrinh = ($tourId && isset($lichTrinhTheoTour[$tourId])) ? $lichTrinhTheoTour[$tourId] : [];
                $nhiemVu = isset($nhiemVuTheoLich[$lich['id']]) ? $nhiemVuTheoLich[$lich['id']] : null;
            ?>
            <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
                <h3 style="margin-top: 0;"><?php echo htmlspecialchars($lich['ten_tour'] ?? 'Tour'); ?></h3>
                <p>
                    <strong>Thời gian:</strong> 
                    <?php echo !empty($lich['ngay_khoi_hanh']) ? date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])) : 'N/A'; ?>
                    →
                    <?php echo !empty($lich['ngay_ket_thuc']) ? date('d/m/Y', strtotime($lich['ngay_ket_thuc'])) : 'N/A'; ?>
                </p>
                <p><strong>Điểm tập trung:</strong> <?php echo htmlspecialchars($lich['diem_tap_trung'] ?? 'Chưa cập nhật'); ?></p>
                <p>
                    <strong>Nhiệm vụ của tôi:</strong> 
                    <?php 
                        if ($nhiemVu) {
                            echo htmlspecialchars($nhiemVu['vai_tro'] ?? 'HDV');
                            if (!empty($nhiemVu['ghi_chu'])) {
                                echo ' - ' . htmlspecialchars($nhiemVu['ghi_chu']);
                            }
                        } else {
                            echo 'HDV chính phụ trách xuyên suốt tour';
                        }
                    ?>
                </p>
                <div>
                    <strong>Lịch trình từng ngày:</strong>
                    <?php if (!empty($lichTrinh)): ?>
                        <ol>
                            <?php foreach ($lichTrinh as $ngay): ?>
                                <li style="margin-bottom: 10px;">
                                    <strong>Ngày <?php echo (int)($ngay['ngay_thu'] ?? 0); ?>:</strong>
                                    <?php if (!empty($ngay['dia_diem'])): ?>
                                        <em><?php echo htmlspecialchars($ngay['dia_diem']); ?></em><br>
                                    <?php endif; ?>
                                    <?php echo nl2br(htmlspecialchars($ngay['hoat_dong'] ?? '')); ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p>Chưa có lịch trình chi tiết cho tour này.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>


