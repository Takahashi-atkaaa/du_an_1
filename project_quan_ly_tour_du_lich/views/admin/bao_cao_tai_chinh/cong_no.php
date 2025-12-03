<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Công Nợ</title>
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-file-invoice-dollar"></i> Quản Lý Công Nợ</h1>
            <a href="index.php?act=admin/baoCaoTaiChinh" class="btn" style="margin-top: 15px;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <div class="card">
            <h2 style="color: #333; margin-bottom: 15px;">Công Nợ Khách Hàng</h2>
            <table>
                <thead>
                    <tr>
                        <th>Khách Hàng</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Tổng Booking</th>
                        <th>Đã Thanh Toán</th>
                        <th>Còn Nợ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($congNoKhachHang)): ?>
                        <tr><td colspan="6" style="text-align: center; color: #999;">Không có công nợ</td></tr>
                    <?php else: ?>
                        <?php foreach($congNoKhachHang as $cn): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($cn['ho_ten'] ?? '') ?></strong></td>
                                <td><?= htmlspecialchars($cn['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($cn['so_dien_thoai'] ?? '') ?></td>
                                <td><?= number_format($cn['tong_gia_tri_booking']) ?>đ</td>
                                <td><?= number_format($cn['da_thanh_toan']) ?>đ</td>
                                <td style="color: #ef4444; font-weight: 700;">
                                    <?= number_format($cn['con_no']) ?>đ
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
