<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}

// $chiTiet chứa 1 bản ghi lịch sử xóa
$thongTin = [];
if (!empty($chiTiet['thong_tin_nha_cung_cap'])) {
    $decoded = json_decode($chiTiet['thong_tin_nha_cung_cap'], true);
    if (is_array($decoded)) {
        $thongTin = $decoded;
    }
}

$loaiDichVuMap = [
    'KhachSan' => 'Khách sạn',
    'NhaHang'  => 'Nhà hàng',
    'Xe'       => 'Xe vận chuyển',
    'Ve'       => 'Vé máy bay / tàu',
    'Visa'     => 'Visa',
    'BaoHiem'  => 'Bảo hiểm',
    'Khac'     => 'Khác'
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết nhật ký xóa nhà cung cấp</title>
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
            <a class="nav-link" href="index.php?act=admin/nhaCungCap">
                <i class="bi bi-building"></i> Nhà cung cấp
            </a>
            <a class="nav-link" href="index.php?act=admin/lichSuXoaNhaCungCap">
                <i class="bi bi-clock-history"></i> Lịch sử xóa
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            <i class="bi bi-file-earmark-text text-danger"></i> Chi tiết nhật ký xóa nhà cung cấp
        </h3>
        <div>
            <a href="index.php?act=admin/lichSuXoaNhaCungCap" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <?php if (empty($chiTiet)): ?>
        <div class="alert alert-danger">
            Không tìm thấy bản ghi lịch sử xóa.
        </div>
    <?php else: ?>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Thông tin chung</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>ID nhật ký:</strong> <?php echo htmlspecialchars($chiTiet['id'] ?? ''); ?></p>
                        <p><strong>Nhà cung cấp ID:</strong> 
                            <span class="badge bg-secondary">
                                #<?php echo htmlspecialchars($chiTiet['nha_cung_cap_id'] ?? 'N/A'); ?>
                            </span>
                        </p>
                        <p><strong>Thời gian xóa:</strong> 
                            <?php 
                            echo !empty($chiTiet['thoi_gian_xoa']) 
                                ? date('d/m/Y H:i:s', strtotime($chiTiet['thoi_gian_xoa'])) 
                                : 'N/A'; 
                            ?>
                        </p>
                        <p><strong>Lý do xóa:</strong><br>
                            <?php if (!empty($chiTiet['ly_do_xoa'])): ?>
                                <span class="text-danger"><?php echo nl2br(htmlspecialchars($chiTiet['ly_do_xoa'])); ?></span>
                            <?php else: ?>
                                <span class="text-muted">Không có</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Người thực hiện xóa</strong>
                    </div>
                    <div class="card-body">
                        <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($chiTiet['nguoi_xoa'] ?? 'N/A'); ?></p>
                        <p><strong>Email:</strong> 
                            <?php echo !empty($chiTiet['email_nguoi_xoa']) 
                                ? htmlspecialchars($chiTiet['email_nguoi_xoa']) 
                                : '<span class="text-muted">N/A</span>'; ?>
                        </p>
                        <p><strong>ID người xóa:</strong> <?php echo htmlspecialchars($chiTiet['nguoi_xoa_id'] ?? ''); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>Thông tin nhà cung cấp tại thời điểm xóa</strong>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($thongTin)): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Tên đơn vị:</strong> <?php echo htmlspecialchars($thongTin['ten_don_vi'] ?? ''); ?></p>
                                    <p><strong>Loại dịch vụ:</strong> 
                                        <?php 
                                        $loai = $thongTin['loai_dich_vu'] ?? null;
                                        echo $loai ? ($loaiDichVuMap[$loai] ?? $loai) : 'N/A';
                                        ?>
                                    </p>
                                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($thongTin['dia_chi'] ?? ''); ?></p>
                                    <p><strong>Liên hệ:</strong> <?php echo htmlspecialchars($thongTin['lien_he'] ?? ''); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Mô tả chi tiết:</strong></p>
                                    <p><?php echo !empty($thongTin['mo_ta']) 
                                        ? nl2br(htmlspecialchars($thongTin['mo_ta'])) 
                                        : '<span class="text-muted">Không có</span>'; ?></p>

                                    <?php if (!empty($thongTin['ghi_chu'])): ?>
                                        <p><strong>Ghi chú nội bộ:</strong></p>
                                        <p><?php echo nl2br(htmlspecialchars($thongTin['ghi_chu'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Không lưu kèm thông tin chi tiết nhà cung cấp.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>


