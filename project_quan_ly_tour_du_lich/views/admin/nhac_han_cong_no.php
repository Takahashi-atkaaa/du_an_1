<?php
// File: views/admin/nhac_han_cong_no.php
// Hiển thị thông báo/cảnh báo nhắc hạn thu nợ hoặc công nợ phải trả
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Nhắc hạn công nợ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .alert-warning { font-size: 1.1rem; }
        .alert-danger { font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0"><i class="bi bi-bell"></i> Nhắc hạn công nợ</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($nhacHanCongNo)) { ?>
                            <?php foreach ($nhacHanCongNo as $item): ?>
                                <div class="alert <?= $item['is_qua_han'] ? 'alert-danger' : 'alert-warning' ?> mb-3">
                                    <strong><?= htmlspecialchars($item['doi_tuong']) ?>:</strong> <?= htmlspecialchars($item['noi_dung']) ?>
                                    <br>
                                    <span class="fw-bold">Hạn: <?= date('d/m/Y', strtotime($item['han'])) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php } else { ?>
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-3">Không có thông báo công nợ đến hạn.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="text-center">
                    <a href="index.php?act=admin/baoCaoTaiChinh" class="btn btn-outline-warning">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
