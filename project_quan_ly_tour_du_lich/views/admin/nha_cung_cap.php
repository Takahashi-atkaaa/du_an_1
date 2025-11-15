<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Booking - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Nhà cung cấp</h1>
        <a href="index.php?act=admin/dashboard">← Quay lại Dashboard</a>
        <div class="content">
            <h2>Danh sách Booking</h2>
            <?php if (isset($bookings)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tour</th>
                            <th>Khách hàng</th>
                            <th>Số lượng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Nội dung booking sẽ được hiển thị ở đây -->
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


