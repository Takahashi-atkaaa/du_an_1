<?php
// Register page không dùng layout vì cần full screen
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Quản lý Tour Du lịch</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/aventura.css">
    <style>
        body {
            background: #1a1a1a;
            background-image: url('<?php echo BASE_URL; ?>public/images/logos/hinh-nen-viet-nam-4k10.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            padding: 2rem 0;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(26, 26, 26, 0.85);
            z-index: 1;
        }
        
        .register-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 500px;
            padding: 0 20px;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 2px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 3rem 2.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-circle {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }
        
        .logo-circle i {
            font-size: 2.5rem;
            color: white;
        }
        
        .register-header h2 {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .register-header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.9rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 2px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .form-control:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: var(--accent-gold);
        }
        
        .btn-register {
            width: 100%;
            padding: 1rem;
            background: var(--accent-gold);
            border: none;
            border-radius: 2px;
            color: var(--primary-dark);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-register:hover {
            background: #c9a030;
            transform: translateY(-2px);
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .login-link a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .login-link a:hover {
            color: var(--accent-gold);
        }
        
        .alert {
            background: rgba(220, 53, 69, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: white;
            padding: 1rem;
            border-radius: 2px;
            margin-bottom: 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="glass-card">
            <div class="register-header">
                <div class="logo-circle">
                    <i>👤</i>
                </div>
                <h2>Đăng ký</h2>
                <p>Tạo tài khoản mới</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert">
                    ⚠ <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?act=auth/register">
                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="ho_ten" class="form-control" placeholder="Nhập họ tên" required>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Nhập email" required>
                </div>
                
                <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="tel" name="so_dien_thoai" class="form-control" placeholder="Nhập số điện thoại" required>
                </div>
                
                <div class="form-group">
                    <label>Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" placeholder="Nhập tên đăng nhập" required>
                </div>
                
                <div class="form-group">
                    <label>Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu" required>
                </div>
                
                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirm" class="form-control" placeholder="Nhập lại mật khẩu" required>
                </div>
                
                <button type="submit" class="btn-register">
                    Đăng ký
                </button>
            </form>
            
            <div class="login-link">
                <a href="index.php?act=auth/login">
                    Đã có tài khoản? Đăng nhập ngay
                </a>
            </div>
        </div>
    </div>
</body>
</html>
