<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - Quản lý Tour Du lịch</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Quên mật khẩu</h2>
        <form method="POST" action="index.php?act=auth/forgotPassword">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit">Gửi email đặt lại mật khẩu</button>
            <a href="index.php?act=auth/login">Quay lại đăng nhập</a>
        </form>
    </div>
</body>
</html>


