<?php
// File: views/admin/cong_no_khach_hang.php
// Hiển thị trạng thái thanh toán, lịch sử thanh toán theo từng hợp đồng/tour
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Công nợ khách hàng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; }
        .card { box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075); }
        .badge-paid { background: #198754; }
        .badge-unpaid { background: #dc3545; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-person-badge"></i> Công nợ khách hàng</h4>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($congNoKhachHang)) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Khách hàng</th>
                                        <th>Tour/Hợp đồng</th>
                                        <th>Trạng thái</th>
                                        <th>Số tiền còn nợ</th>
                                        <th>Lịch sử thanh toán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($congNoKhachHang as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['ten_khach_hang']) ?></td>
                                        <td><?= htmlspecialchars($item['ten_tour']) ?></td>
                                        <td>
                                            <?php if ($item['cong_no'] <= 0): ?>
                                                <span class="badge badge-paid">Đã thanh toán</span>
                                            <?php else: ?>
                                                <span class="badge badge-unpaid">Còn nợ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-danger"><?= number_format($item['cong_no']) ?>đ</td>
                                        <td>
                                            <?php if (!empty($item['lich_su_thanh_toan'])): ?>
                                                <ul class="mb-0">
                                                <?php foreach ($item['lich_su_thanh_toan'] as $ls): ?>
                                                    <li><?= date('d/m/Y', strtotime($ls['ngay'])) ?> - <?= number_format($ls['so_tien']) ?>đ</li>
                                                <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có thanh toán</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inbox display-4"></i>
                                <p class="mt-3">Không có dữ liệu công nợ khách hàng.</p>
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
