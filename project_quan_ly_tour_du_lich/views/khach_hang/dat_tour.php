<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour - Khách hàng</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Đặt Tour</h1>
        <?php if (isset($tour)): ?>
            <form method="POST" action="index.php?act=booking/create" data-gia-co-ban="<?php echo $tour['gia_co_ban'] ?? 0; ?>">
                <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id']; ?>">
                <div class="form-group">
                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($tour['ten_tour']); ?></p>
                    <p><strong>Giá cơ bản:</strong> <?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?> VNĐ</p>
                </div>
                <div class="form-group">
                    <label>Số lượng người:</label>
                    <input type="number" name="so_nguoi" min="1" required>
                </div>
                <div class="form-group">
                    <label>Ngày khởi hành:</label>
                    <input type="date" name="ngay_khoi_hanh" required>
                </div>
                <div class="form-group">
                    <label>Ngày kết thúc (nếu có):</label>
                    <input type="date" name="ngay_ket_thuc">
                    <small>Nếu bỏ trống, hệ thống sẽ dùng ngày khởi hành.</small>
                </div>
                <div class="form-group">
                    <label>Ghi chú:</label>
                    <textarea name="ghi_chu" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Tổng tiền:</label>
                    <input type="text" name="tong_tien" value="<?php echo (float)($tour['gia_co_ban'] ?? 0); ?>" readonly>
                </div>
                <button type="submit">Xác nhận đặt tour</button>
            </form>
            <script>
                (function() {
                    const startInput = document.querySelector('input[name="ngay_khoi_hanh"]');
                    const endInput = document.querySelector('input[name="ngay_ket_thuc"]');
                    if (!startInput || !endInput) return;
                    
                    function syncEndDate() {
                        if (startInput.value) {
                            endInput.min = startInput.value;
                            if (!endInput.value || endInput.value < startInput.value) {
                                endInput.value = startInput.value;
                            }
                        }
                    }
                    
                    startInput.addEventListener('change', syncEndDate);
                    syncEndDate();
                })();
            </script>
        <?php endif; ?>
    </div>
</body>
</html>


