<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý Tour Du lịch</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Đăng nhập</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?act=auth/login">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
            <a href="index.php?act=auth/register">Chưa có tài khoản? Đăng ký</a>
        </form>
    </div>
</body>
</html>


