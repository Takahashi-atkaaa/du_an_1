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
        <a href="index.php?act=hdv/phanHoi">Phản hồi</a> | 
        <a href="index.php?act=auth/logout">Đăng xuất</a>
    </nav>
    
    <hr>
    
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
</body>
</html>


