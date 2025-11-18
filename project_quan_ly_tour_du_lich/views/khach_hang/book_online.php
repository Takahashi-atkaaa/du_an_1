<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour Online - <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .book-online-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .tour-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .tour-header h1 {
            color: #007bff;
            margin-bottom: 10px;
        }
        .tour-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .tour-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .tour-info p {
            margin: 10px 0;
        }
        .tour-info strong {
            color: #333;
            min-width: 150px;
            display: inline-block;
        }
        .price-highlight {
            font-size: 24px;
            color: #dc3545;
            font-weight: bold;
        }
        .booking-form {
            background: #fff;
            padding: 25px;
            border: 2px solid #007bff;
            border-radius: 8px;
        }
        .booking-form h3 {
            color: #007bff;
            margin-bottom: 20px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .schedule-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .schedule-item {
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="book-online-container">
        <div class="tour-header">
            <h1><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h1>
            <p style="color: #666;">Đặt tour nhanh chóng - Không cần đăng nhập</p>
        </div>

        <?php if (isset($anhChinh) && $anhChinh): ?>
            <img src="<?php echo BASE_URL . htmlspecialchars($anhChinh['url_anh']); ?>" 
                 alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>" 
                 class="tour-image">
        <?php endif; ?>

        <div class="tour-info">
            <p><strong>Loại tour:</strong> <?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></p>
            <p><strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($tour['mo_ta'] ?? '')); ?></p>
            <p><strong>Giá:</strong> <span class="price-highlight"><?php echo number_format((float)($tour['gia_co_ban'] ?? 0), 0, ',', '.'); ?> VNĐ/người</span></p>
            <?php if (!empty($tour['chinh_sach'])): ?>
                <p><strong>Chính sách:</strong> <?php echo nl2br(htmlspecialchars($tour['chinh_sach'])); ?></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($lichTrinhList)): ?>
        <div class="schedule-list">
            <h3>Lịch trình tour</h3>
            <?php foreach ($lichTrinhList as $lt): ?>
                <div class="schedule-item">
                    <strong>Ngày <?php echo $lt['ngay_thu'] ?? ''; ?>:</strong>
                    <?php echo htmlspecialchars($lt['dia_diem'] ?? ''); ?>
                    <?php if (!empty($lt['mo_ta'])): ?>
                        <br><small><?php echo htmlspecialchars($lt['mo_ta']); ?></small>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="booking-form">
            <h3>Đặt tour ngay</h3>
            
            <div class="alert alert-info">
                <strong>Lưu ý:</strong> Vui lòng điền đầy đủ thông tin. Chúng tôi sẽ liên hệ lại để xác nhận đặt tour.
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert" style="background: #f8d7da; color: #721c24;">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert" style="background: #d4edda; color: #155724;">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>index.php?act=booking/createOnline">
                <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id']; ?>">
                
                <div class="form-group">
                    <label>Họ và tên: *</label>
                    <input type="text" name="ho_ten" required placeholder="Nhập họ tên đầy đủ">
                </div>

                <div class="form-group">
                    <label>Email: *</label>
                    <input type="email" name="email" required placeholder="email@example.com">
                </div>

                <div class="form-group">
                    <label>Số điện thoại: *</label>
                    <input type="tel" name="so_dien_thoai" required placeholder="0123456789" pattern="[0-9]{10,11}">
                </div>

                <div class="form-group">
                    <label>Số lượng người:</label>
                    <input type="number" name="so_nguoi" min="1" value="1" required>
                </div>

                <?php if (!empty($lichKhoiHanhList)): ?>
                <div class="form-group">
                    <label>Chọn ngày khởi hành:</label>
                    <select name="lich_khoi_hanh_id">
                        <option value="">-- Chọn ngày khởi hành --</option>
                        <?php foreach ($lichKhoiHanhList as $lkh): ?>
                            <option value="<?php echo $lkh['id']; ?>">
                                <?php echo date('d/m/Y', strtotime($lkh['ngay_khoi_hanh'])); ?>
                                <?php if (!empty($lkh['gio_xuat_phat'])): ?>
                                    - <?php echo $lkh['gio_xuat_phat']; ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <div class="form-group">
                    <label>Ngày khởi hành mong muốn:</label>
                    <input type="date" name="ngay_khoi_hanh" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Ghi chú (nếu có):</label>
                    <textarea name="ghi_chu" rows="4" placeholder="Yêu cầu đặc biệt, thắc mắc..."></textarea>
                </div>

                <button type="submit" style="width: 100%; padding: 15px; font-size: 18px;">
                    Đặt tour ngay
                </button>
            </form>
        </div>

        <p style="text-align: center; margin-top: 30px; color: #666;">
            <a href="<?php echo BASE_URL; ?>index.php?act=tour/index">« Xem thêm tour khác</a>
        </p>
    </div>
</body>
</html>
