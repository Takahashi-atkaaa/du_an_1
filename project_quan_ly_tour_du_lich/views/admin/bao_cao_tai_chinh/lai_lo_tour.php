<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Lãi Lỗ Từng Tour</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        th {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
        }
        tr:hover { background: #f8f9fa; }
        .profit { color: #10b981; font-weight: 700; }
        .loss { color: #ef4444; font-weight: 700; }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Báo Cáo Lãi Lỗ Từng Tour</h1>
            <a href="index.php?act=admin/baoCaoTaiChinh" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Doanh Thu</th>
                        <th>Chi Phí</th>
                        <th>Lợi Nhuận</th>
                        <th>Tỷ Suất (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($baoCao)): ?>
                        <tr><td colspan="5" style="text-align: center; color: #999;">Chưa có dữ liệu</td></tr>
                    <?php else: ?>
                        <?php foreach($baoCao as $item): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($item['tour']['ten_tour']) ?></strong></td>
                                <td><?= number_format($item['doanh_thu']) ?>đ</td>
                                <td><?= number_format($item['chi_phi']) ?>đ</td>
                                <td class="<?= $item['loi_nhuan'] >= 0 ? 'profit' : 'loss' ?>">
                                    <?= number_format($item['loi_nhuan']) ?>đ
                                </td>
                                <td class="<?= $item['ty_suat'] >= 0 ? 'profit' : 'loss' ?>">
                                    <?= number_format($item['ty_suat'], 2) ?>%
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
