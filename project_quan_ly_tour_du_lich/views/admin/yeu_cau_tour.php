<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu tour của khách hàng</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .container { max-width: 900px; margin-top: 40px; }
        h1 { font-size: 2rem; font-weight: bold; margin-bottom: 32px; }
        .table-custom { background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(0,0,0,0.06); overflow: hidden; }
        .table-custom th, .table-custom td { vertical-align: middle; text-align: center; }
        .btn-xac-nhan { border-radius: 20px; font-weight: 500; padding: 0.25rem 1.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh sách yêu cầu tour của khách hàng</h1>
        <table class="table table-custom">
            <thead>
                <tr>
                    <th>Khách hàng</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                    <th>Địa điểm</th>
                    <th>Thời gian</th>
                    <th>Số người</th>
                    <th>Yêu cầu đặc biệt</th>
                    <th>Thời gian gửi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($yeuCauTourList as $i => $yc): ?>
                <tr>
                    <td><?php echo htmlspecialchars($yc['ho_ten'] ?? 'Ẩn danh'); ?></td>
                    <td><?php echo htmlspecialchars($yc['email'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($yc['so_dien_thoai'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($yc['dia_diem'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($yc['thoi_gian'] ?? ''); ?></td>
                    <td><span class="badge bg-info text-dark fs-6 px-3 py-2"><?php echo htmlspecialchars($yc['so_nguoi'] ?? ''); ?></span></td>
                    <td><?php echo htmlspecialchars($yc['yeu_cau_dac_biet'] ?? ''); ?></td>
                    <td><span class="text-secondary"><?php echo htmlspecialchars($yc['ngay_gui'] ?? ''); ?></span></td>
                    <td>
                        <?php if (empty($yc['trang_thai']) || $yc['trang_thai'] === 'DaGui'): ?>
                            <a href="index.php?act=admin/xacNhanYeuCauTour&id=<?php echo $yc['id']; ?>" class="btn btn-success btn-xac-nhan">Xác nhận</a>
                        <?php else: ?>
                            <span class="badge bg-success">Đã xác nhận</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
