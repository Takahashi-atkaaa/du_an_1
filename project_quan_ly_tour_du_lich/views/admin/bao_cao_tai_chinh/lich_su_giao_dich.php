<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch Sử Giao Dịch</title>
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
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
        .badge-thu { background: #10b981; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .badge-chi { background: #ef4444; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Lịch Sử Giao Dịch Nội Bộ</h1>
            <a href="index.php?act=admin/baoCaoTaiChinh" class="btn" style="margin-top: 15px;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3 style="color: #10b981; font-size: 14px; margin-bottom: 10px;">TỔNG THU</h3>
                <div style="font-size: 28px; font-weight: 700; color: #333;">
                    <?= number_format($tongThu) ?>đ
                </div>
            </div>
            <div class="stat-card">
                <h3 style="color: #ef4444; font-size: 14px; margin-bottom: 10px;">TỔNG CHI</h3>
                <div style="font-size: 28px; font-weight: 700; color: #333;">
                    <?= number_format($tongChi) ?>đ
                </div>
            </div>
        </div>
        
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Ngày GD</th>
                        <th>Loại</th>
                        <th>Loại GD</th>
                        <th>Số Tiền</th>
                        <th>Mô Tả</th>
                        <th>Người Thực Hiện</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($giaoDichs)): ?>
                        <tr><td colspan="6" style="text-align: center; color: #999;">Chưa có giao dịch</td></tr>
                    <?php else: ?>
                        <?php foreach($giaoDichs as $gd): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($gd['ngay_giao_dich'])) ?></td>
                                <td>
                                    <span class="badge-<?= $gd['loai'] == 'Thu' ? 'thu' : 'chi' ?>">
                                        <?= $gd['loai'] ?>
                                    </span>
                                </td>
                                <td><?= $gd['loai_giao_dich'] ?></td>
                                <td style="font-weight: 700; color: <?= $gd['loai'] == 'Thu' ? '#10b981' : '#ef4444' ?>">
                                    <?= number_format($gd['so_tien']) ?>đ
                                </td>
                                <td><?= htmlspecialchars($gd['mo_ta'] ?? '') ?></td>
                                <td><?= htmlspecialchars($gd['nguoi_thuc_hien'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
