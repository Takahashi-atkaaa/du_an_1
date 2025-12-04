
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giao dịch của tour</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .table th, .table td { vertical-align: middle; }
        .badge-thu { background: #198754; }
        .badge-chi { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-receipt"></i> Giao dịch - <?= htmlspecialchars($tour['ten_tour'] ?? '') ?></h4>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($giaoDichs)) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày giao dịch</th>
                                        <th>Loại</th>
                                        <th class="text-end">Số tiền</th>
                                        <th>Mô tả</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($giaoDichs as $gd): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($gd['ngay_giao_dich'])) ?></td>
                                        <td>
                                            <?php if ($gd['loai'] === 'Thu'): ?>
                                                <span class="badge badge-thu">Thu</span>
                                            <?php else: ?>
                                                <span class="badge badge-chi">Chi</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end fw-bold <?= $gd['loai'] === 'Thu' ? 'text-success' : 'text-danger' ?>">
                                            <?= number_format($gd['so_tien']) ?>đ
                                        </td>
                                        <td><?= htmlspecialchars($gd['mo_ta'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-3">Không có giao dịch nào cho tour này.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="text-center">
                    <a href="index.php?act=admin/baoCaoTaiChinh" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
