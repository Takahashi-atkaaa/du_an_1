<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Dự Toán</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
        .btn-sm { padding: 6px 12px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-calculator"></i> Danh Sách Dự Toán Tour</h1>
            <a href="index.php?act=admin/formDuToan" class="btn">
                <i class="fas fa-plus"></i> Tạo Dự Toán Mới
            </a>
        </div>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Tour</th>
                        <th>Tổng Dự Toán</th>
                        <th>Người Tạo</th>
                        <th>Ngày Tạo</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($duToans)): ?>
                        <tr><td colspan="5" style="text-align: center; color: #999;">Chưa có dự toán nào</td></tr>
                    <?php else: ?>
                        <?php foreach($duToans as $dt): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($dt['ten_tour']) ?></strong></td>
                                <td style="font-weight: 700; color: #667eea;">
                                    <?= number_format($dt['tong_du_toan']) ?>đ
                                </td>
                                <td><?= htmlspecialchars($dt['nguoi_tao'] ?? 'N/A') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($dt['ngay_tao'])) ?></td>
                                <td>
                                    <a href="index.php?act=admin/formDuToan&id=<?= $dt['du_toan_id'] ?>" class="btn btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="index.php?act=admin/soSanhDuToan&du_toan_id=<?= $dt['du_toan_id'] ?>" class="btn btn-sm">
                                        <i class="fas fa-chart-bar"></i> So sánh
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
