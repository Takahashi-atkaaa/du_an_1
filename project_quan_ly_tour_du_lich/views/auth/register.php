<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Quản lý Tour Du lịch</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Đăng ký</h2>
        <form method="POST" action="index.php?act=auth/register">
            <div class="form-group">
                <label>Họ và tên:</label>
                <input type="text" name="ho_ten" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="number" name="so_dien_thoai" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Đăng ký</button>
            <a href="index.php?act=auth/login">Đã có tài khoản? Đăng nhập</a>
        </form>
    </div>
</body>
</html>


