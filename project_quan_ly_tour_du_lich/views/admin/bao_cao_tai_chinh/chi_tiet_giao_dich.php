<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Giao dịch</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 250px;
            flex-shrink: 0;
        }
        .info-value {
            flex: 1;
            color: #212529;
        }
        .badge-thu {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .badge-chi {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .amount {
            font-size: 28px;
            font-weight: 700;
            margin: 20px 0;
        }
        .amount-thu { color: #10b981; }
        .amount-chi { color: #ef4444; }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-receipt"></i> Chi tiết Giao dịch</h1>
            <a href="index.php?act=admin/lichSuGiaoDich" class="btn" style="margin-top: 15px;">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <?php if ($giao_dich): ?>
            <div class="card">
                <div class="section-title">Thông tin Giao dịch</div>
                
                <div class="amount amount-<?= strtolower($giao_dich['loai']) ?>">
                    <?= number_format($giao_dich['so_tien'] ?? 0) ?>đ
                </div>
                
                <div class="info-row">
                    <div class="info-label">Loại giao dịch:</div>
                    <div class="info-value">
                        <span class="badge-<?= strtolower($giao_dich['loai']) ?>">
                            <?= htmlspecialchars($giao_dich['loai'] ?? 'N/A') ?>
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Loại giao dịch chi tiết:</div>
                    <div class="info-value"><?= htmlspecialchars($giao_dich['loai_giao_dich'] ?? 'N/A') ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ngày giao dịch:</div>
                    <div class="info-value">
                        <?= $giao_dich['ngay_giao_dich'] ? date('d/m/Y', strtotime($giao_dich['ngay_giao_dich'])) : 'N/A' ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Mô tả:</div>
                    <div class="info-value"><?= htmlspecialchars($giao_dich['mo_ta'] ?? 'Không có mô tả') ?></div>
                </div>

                <?php if (!empty($giao_dich['tour_id'])): ?>
                    <div class="info-row">
                        <div class="info-label">Tour:</div>
                        <div class="info-value">
                            <a href="index.php?act=admin/chiTietTour&id=<?= $giao_dich['tour_id'] ?>" style="color: #667eea;">
                                Tour ID: <?= $giao_dich['tour_id'] ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($giao_dich['booking_id'])): ?>
                    <div class="info-row">
                        <div class="info-label">Booking:</div>
                        <div class="info-value">
                            <a href="index.php?act=admin/chiTietBooking&id=<?= $giao_dich['booking_id'] ?>" style="color: #667eea;">
                                Booking #<?= $giao_dich['booking_id'] ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($giao_dich['khach_hang_id'])): ?>
                    <div class="info-row">
                        <div class="info-label">Khách hàng:</div>
                        <div class="info-value">Khách hàng ID: <?= $giao_dich['khach_hang_id'] ?></div>
                    </div>
                <?php endif; ?>

                <div class="info-row">
                    <div class="info-label">Loại đối tượng:</div>
                    <div class="info-value"><?= htmlspecialchars($giao_dich['loai_doi_tuong'] ?? 'N/A') ?></div>
                </div>

                <?php if (!empty($giao_dich['doi_tuong_id'])): ?>
                    <div class="info-row">
                        <div class="info-label">Đối tượng ID:</div>
                        <div class="info-value"><?= $giao_dich['doi_tuong_id'] ?></div>
                    </div>
                <?php endif; ?>

                <div class="info-row">
                    <div class="info-label">Người thực hiện:</div>
                    <div class="info-value">
                        <?= htmlspecialchars($giao_dich['nguoi_thuc_hien'] ?? 'N/A') ?>
                        <?php if (!empty($giao_dich['nguoi_thuc_hien_id'])): ?>
                            (ID: <?= $giao_dich['nguoi_thuc_hien_id'] ?>)
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ngày tạo:</div>
                    <div class="info-value">
                        <?= $giao_dich['created_at'] ? date('d/m/Y H:i:s', strtotime($giao_dich['created_at'])) : 'N/A' ?>
                    </div>
                </div>

                <?php if (!empty($giao_dich['updated_at']) && $giao_dich['updated_at'] != $giao_dich['created_at']): ?>
                    <div class="info-row">
                        <div class="info-label">Ngày cập nhật:</div>
                        <div class="info-value">
                            <?= date('d/m/Y H:i:s', strtotime($giao_dich['updated_at'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="card">
                <p style="text-align: center; color: #999; padding: 40px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                    Không tìm thấy giao dịch
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>








