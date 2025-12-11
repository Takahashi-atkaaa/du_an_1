<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo - HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .notification-item {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .notification-item:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .notification-item.unread {
            border-left: 4px solid var(--primary-color);
        }
    </style>
</head>
<body class="bg-light">
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-bell"></i> Thông báo
                    </h3>
                    <p class="mb-0 opacity-75">Tất cả thông báo của bạn</p>
                </div>
                <a href="index.php?act=hdv/dashboard" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($notifications)): ?>
            <?php foreach($notifications as $notif): ?>
            <div class="notification-item <?php echo !$notif['da_xem'] ? 'unread' : ''; ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="mb-0">
                        <?php echo htmlspecialchars($notif['tieu_de']); ?>
                        <?php if (!$notif['da_xem']): ?>
                        <span class="badge bg-danger">Mới</span>
                        <?php endif; ?>
                    </h5>
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> 
                        <?php echo date('d/m/Y H:i', strtotime($notif['ngay_gui'])); ?>
                    </small>
                </div>
                <p class="mb-0"><?php echo nl2br(htmlspecialchars($notif['noi_dung'])); ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Bạn chưa có thông báo nào.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
