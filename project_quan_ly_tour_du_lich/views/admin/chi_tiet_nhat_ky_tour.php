<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}

$typeLabels = [
    'hanh_trinh' => '📍 Hành trình',
    'su_co' => '⚠️ Sự cố',
    'phan_hoi' => '💬 Phản hồi',
    'hoat_dong' => '🎯 Hoạt động'
];

$images = [];
if (!empty($entry['hinh_anh'])) {
    $decoded = json_decode($entry['hinh_anh'], true);
    if (is_array($decoded)) {
        $images = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết nhật ký tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?act=admin/quanLyNhatKyTour">
                    <i class="bi bi-journal-text"></i> Quản lý nhật ký
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-file-earmark-text"></i> Chi tiết nhật ký tour
                </h2>
                <small class="text-muted">Ghi nhận đầy đủ thông tin do hướng dẫn viên báo cáo</small>
            </div>
            <div class="d-flex gap-2">
                <a href="index.php?act=admin/formNhatKyTour&id=<?php echo $entry['id']; ?>" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Chỉnh sửa
                </a>
                <a href="index.php?act=admin/quanLyNhatKyTour" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?php echo htmlspecialchars($entry['tieu_de'] ?? ''); ?></strong>
                            <div class="text-muted small">
                                <i class="bi bi-calendar-event"></i>
                                <?php echo date('d/m/Y H:i', strtotime($entry['ngay_ghi'])); ?>
                            </div>
                        </div>
                        <span class="badge bg-primary fs-6">
                            <?php echo $typeLabels[$entry['loai_nhat_ky']] ?? $entry['loai_nhat_ky']; ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">Nội dung</h6>
                        <p class="mb-4">
                            <?php echo nl2br(htmlspecialchars($entry['noi_dung'] ?? '')); ?>
                        </p>

                        <?php if (!empty($entry['cach_xu_ly'])): ?>
                            <div class="alert alert-warning">
                                <strong><i class="bi bi-tools"></i> Cách xử lý / ghi chú:</strong><br>
                                <?php echo nl2br(htmlspecialchars($entry['cach_xu_ly'])); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($images)): ?>
                            <h6 class="text-uppercase text-muted">Hình ảnh đính kèm</h6>
                            <div class="row g-3">
                                <?php foreach ($images as $img): ?>
                                    <div class="col-md-4">
                                        <div class="ratio ratio-1x1 border rounded overflow-hidden">
                                            <img src="<?php echo BASE_URL . $img; ?>" 
                                                 alt="Ảnh nhật ký"
                                                 style="object-fit: cover; cursor: pointer;"
                                                 onclick="window.open('<?php echo BASE_URL . $img; ?>', '_blank')">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không có hình ảnh đính kèm.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <strong>Thông tin tour</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Tour:</strong> <?php echo htmlspecialchars($entry['ten_tour'] ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>ID tour:</strong> #<?php echo htmlspecialchars($entry['tour_id'] ?? 'N/A'); ?></p>
                        <p class="mb-0"><strong>Ngày ghi nhận:</strong> <?php echo date('d/m/Y', strtotime($entry['ngay_ghi'])); ?></p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Thông tin hướng dẫn viên</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($entry['hdv_ten'] ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($entry['hdv_email'] ?? 'N/A'); ?></p>
                        <p class="mb-0"><strong>Điện thoại:</strong> <?php echo htmlspecialchars($entry['hdv_sdt'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

