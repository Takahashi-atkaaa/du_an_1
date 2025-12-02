<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thu Chi Từng Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 1400px; margin: 0 auto; }
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #f0f0f0; }
        th { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        tr:hover { background: #f8f9fa; }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transition: all 0.3s;
        }
        .btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-money-bill-wave"></i> Thu Chi Từng Tour</h1>
            <a href="index.php?act=admin/baoCaoTaiChinh" class="btn" style="margin-top: 15px;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Tên Tour</th>
                        <th>Tổng Thu</th>
                        <th>Chi phí thực tế</th>
                        <th>Dự toán</th>
                        <th>Lợi Nhuận</th>
                        <th>Trạng thái</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($tours)): ?>
                        <tr><td colspan="5" style="text-align: center; color: #999;">Chưa có tour nào</td></tr>
                    <?php else: ?>
                        <?php foreach($tours as $tour): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($tour['ten_tour']) ?></strong></td>
                                <td><?= number_format($tour['tong_thu']) ?>đ</td>
                                <td><?= number_format($tour['tong_chi_thuc_te']) ?>đ</td>
                                <td><?= number_format($tour['tong_du_toan']) ?>đ</td>
                                <td style="color: <?= $tour['loi_nhuan'] >= 0 ? '#10b981' : '#ef4444' ?>; font-weight: 700;">
                                    <?= number_format($tour['loi_nhuan']) ?>đ
                                </td>
                                <td>
                                    <?php if ($tour['status'] === 'VuotDuToan'): ?>
                                        <span title="Chi phí thực tế đã vượt dự toán!" style="color:#d9534f;font-weight:bold">
                                            <i class="fas fa-exclamation-triangle"></i> Vượt dự toán
                                        </span>
                                    <?php elseif ($tour['status'] === 'GanVuot'): ?>
                                        <span title="Chi phí thực tế đã đạt 90% dự toán!" style="color:#f0ad4e;font-weight:bold">
                                            <i class="fas fa-exclamation-circle"></i> Gần vượt
                                        </span>
                                    <?php else: ?>
                                        <span title="Chi phí trong mức an toàn" style="color:#10b981;font-weight:bold">
                                            <i class="fas fa-check-circle"></i> An toàn
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="index.php?act=admin/thuChiTour&tour_id=<?= $tour['tour_id'] ?>" class="btn" style="padding: 6px 12px; font-size: 13px;">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
