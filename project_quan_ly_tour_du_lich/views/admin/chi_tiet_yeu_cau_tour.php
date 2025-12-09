<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Yêu cầu Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .info-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }
        .info-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 200px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Chi tiết Yêu cầu Tour</h1>
                <p class="text-muted mb-0">Xem và phản hồi yêu cầu từ khách hàng</p>
            </div>
            <a href="index.php?act=admin/quanLyYeuCauTour" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Thông tin yêu cầu -->
            <div class="col-md-8">
                <div class="card info-card">
                    <div class="card-header">
                        <i class="bi bi-info-circle me-2"></i>Thông tin Yêu cầu
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Khách hàng:</div>
                            <div class="info-value">
                                <strong><?php echo htmlspecialchars($yeuCau['nguoi_gui_ten'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($thongTin['Email'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Số điện thoại:</div>
                            <div class="info-value"><?php echo htmlspecialchars($thongTin['Số điện thoại'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Địa điểm:</div>
                            <div class="info-value">
                                <strong><?php echo htmlspecialchars($thongTin['Địa điểm'] ?? 'N/A'); ?></strong>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Thời gian:</div>
                            <div class="info-value"><?php echo htmlspecialchars($thongTin['Thời gian'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Số người:</div>
                            <div class="info-value"><?php echo htmlspecialchars($thongTin['Số người'] ?? 'N/A'); ?></div>
                        </div>
                        <?php if (!empty($thongTin['Ngân sách'])): ?>
                        <div class="info-row">
                            <div class="info-label">Ngân sách:</div>
                            <div class="info-value"><?php echo htmlspecialchars($thongTin['Ngân sách']); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($thongTin['Yêu cầu đặc biệt'])): ?>
                        <div class="info-row">
                            <div class="info-label">Yêu cầu đặc biệt:</div>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($thongTin['Yêu cầu đặc biệt'])); ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <div class="info-label">Thời gian gửi:</div>
                            <div class="info-value"><?php echo date('d/m/Y H:i:s', strtotime($yeuCau['created_at'])); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Trạng thái:</div>
                            <div class="info-value">
                                <?php if ($yeuCau['trang_thai'] === 'DaGui'): ?>
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                <?php elseif (strpos($yeuCau['noi_dung'] ?? '', 'Đã xử lý') !== false): ?>
                                    <span class="badge bg-success">Đã xử lý</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($yeuCau['trang_thai'] ?? 'N/A'); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form phản hồi -->
                <div class="card info-card">
                    <div class="card-header">
                        <i class="bi bi-chat-left-text me-2"></i>Phản hồi Yêu cầu
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php?act=admin/phanHoiYeuCauTour">
                            <input type="hidden" name="yeu_cau_id" value="<?php echo $yeuCau['id']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Trạng thái xử lý</label>
                                <select name="trang_thai" class="form-select">
                                    <option value="DaXuLy">Đã xử lý</option>
                                    <option value="DaGui" selected>Chờ xử lý</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                                <textarea name="phan_hoi" class="form-control" rows="6" required placeholder="Nhập nội dung phản hồi cho khách hàng..."></textarea>
                                <small class="text-muted">Thông báo này sẽ được gửi đến khách hàng qua hệ thống.</small>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Gửi phản hồi
                                </button>
                                <a href="index.php?act=admin/quanLyYeuCauTour" class="btn btn-outline-secondary">
                                    <i class="bi bi-x me-2"></i>Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Gợi ý Tour -->
            <div class="col-md-4">
                <div class="card info-card">
                    <div class="card-header">
                        <i class="bi bi-lightbulb me-2"></i>Gợi ý Tour tương tự
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tourList)): ?>
                            <div class="list-group list-group-flush">
                                <?php 
                                $count = 0;
                                foreach ($tourList as $tour): 
                                    if ($count >= 5) break;
                                    $count++;
                                ?>
                                    <div class="list-group-item px-0">
                                        <h6 class="mb-1"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'N/A'); ?></h6>
                                        <p class="mb-1 small text-muted">
                                            <?php echo htmlspecialchars(mb_substr($tour['mo_ta'] ?? '', 0, 80)); ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="text-primary fw-bold">
                                                <?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?>đ
                                            </span>
                                            <a href="index.php?act=admin/chiTietTour&id=<?php echo $tour['tour_id']; ?>" class="btn btn-sm btn-outline-primary">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-0">Chưa có tour nào trong hệ thống.</p>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="index.php?act=admin/taoTour" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle me-2"></i>Tạo tour mới
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

