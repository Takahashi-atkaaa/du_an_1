<!DOCTYPE html>

<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour - Khách hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #e0e7ff 0%, #f0fdfa 100%); }
        .booking-card {
            background: rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            max-width: 520px;
            margin: 48px auto;
            padding: 40px 32px 32px 32px;
            position: relative;
        }
        .booking-card h1 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1d4ed8;
            margin-bottom: 18px;
        }
        .booking-card .tour-info {
            background: linear-gradient(90deg,#f0fdfa 60%,#e0e7ff 100%);
            border-radius: 16px;
            padding: 18px 20px 10px 20px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(59,130,246,0.06);
        }
        .booking-card label {
            font-weight: 600;
            color: #374151;
        }
        .booking-card input, .booking-card textarea {
            border-radius: 12px;
            border: 1px solid #e0e7ef;
            background: #f8fafc;
            font-size: 1.08rem;
        }
        .booking-card input:focus, .booking-card textarea:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 2px #bae6fd44;
        }
        .booking-card .form-group {
            margin-bottom: 18px;
        }
        .booking-card .total-box {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #0ea5e9;
            margin-bottom: 18px;
        }
        .booking-card button[type="submit"] {
            background: linear-gradient(90deg,#2563eb 60%,#0ea5e9 100%);
            border: none;
            border-radius: 999px;
            font-weight: 700;
            font-size: 1.15rem;
            padding: 14px 0;
            box-shadow: 0 4px 16px rgba(59,130,246,0.10);
            transition: background 0.18s;
        }
        .booking-card button[type="submit"]:hover {
            background: linear-gradient(90deg,#0ea5e9 60%,#2563eb 100%);
        }
        .booking-card .icon {
            color: #0ea5e9;
            font-size: 1.5rem;
            margin-right: 8px;
        }
        @media (max-width: 600px) {
            .booking-card { padding: 18px 6px; }
        }
    </style>
</head>
<body>
    <div class="booking-card">
        <h1><i class="bi bi-calendar2-check icon"></i>Đặt Tour</h1>
        <?php if (isset($tour)): ?>
            <form method="POST" action="index.php?act=booking/create" data-gia-co-ban="<?php echo $tour['gia_co_ban'] ?? 0; ?>">
                <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id']; ?>">
                <div class="tour-info mb-3">
                    <div class="mb-1"><span class="fw-bold text-primary"><i class="bi bi-geo-alt-fill"></i> Tour:</span> <?php echo htmlspecialchars($tour['ten_tour']); ?></div>
                    <div><span class="fw-bold text-success"><i class="bi bi-cash-coin"></i> Giá cơ bản:</span> <span class="badge bg-gradient bg-info text-dark ms-1"><?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?> VNĐ</span></div>
                </div>
                <div class="form-group">
                    <label for="so_nguoi"><i class="bi bi-people-fill"></i> Số lượng người:</label>
                    <input type="number" id="so_nguoi" name="so_nguoi" min="1" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="ngay_khoi_hanh"><i class="bi bi-calendar-event"></i> Ngày khởi hành:</label>
                    <input type="date" id="ngay_khoi_hanh" name="ngay_khoi_hanh" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="ghi_chu"><i class="bi bi-chat-left-text"></i> Ghi chú:</label>
                    <textarea id="ghi_chu" name="ghi_chu" rows="3" class="form-control" placeholder="Yêu cầu đặc biệt, dị ứng, v.v..."></textarea>
                </div>
                <div class="form-group">
                    <label for="tong_tien"><i class="bi bi-currency-exchange"></i> Giá tiền/người:</label>
                    <div class="total-box">
                        <input type="text" id="tong_tien" name="tong_tien" class="form-control-plaintext fw-bold text-primary" value="<?php echo (float)($tour['gia_co_ban'] ?? 0); ?>" readonly style="font-size:1.2rem; background:transparent;">
                    </div>
                </div>
                <button type="submit"><i class="bi bi-check2-circle me-1"></i> Xác nhận đặt tour</button>
            </form>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


