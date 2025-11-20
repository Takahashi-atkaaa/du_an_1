<?php 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.php?act=auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Khách - Quản Lý Tour Du Lịch</title>
    <link rel="stylesheet" href="public/assets/css/admin.css">
    <style>
        .checkin-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .checkin-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .booking-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .booking-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        .booking-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .btn-submit {
            background: #28a745;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-submit:hover {
            background: #218838;
        }
        .btn-cancel:hover {
            background: #5a6268;
        }
        .checkin-status {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="checkin-container">
        <div style="margin-bottom: 20px;">
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/dashboard" style="color: #007bff; text-decoration: none;">← Dashboard</a> | 
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" style="color: #007bff; text-decoration: none;">Quản lý Tour</a>
        </div>
        <h1><?php echo $checkin ? 'Cập Nhật Check-in' : 'Check-in Khách'; ?></h1>
        
        <?php if ($booking): ?>
            <div class="booking-info">
                <h3>Thông tin booking</h3>
                <p><strong>Mã booking:</strong> <?php echo htmlspecialchars($booking['booking_id'] ?? 'N/A'); ?></p>
                <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?></p>
                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($booking['so_dien_thoai'] ?? 'N/A'); ?></p>
                <p><strong>Số người:</strong> <?php echo ($booking['so_nguoi_lon'] ?? 0); ?> người lớn, <?php echo ($booking['so_tre_em'] ?? 0); ?> trẻ em</p>
            </div>
            
            <?php if ($checkin): ?>
                <div class="checkin-status">
                    ✅ Khách đã check-in vào lúc: <?php echo date('d/m/Y H:i', strtotime($checkin['checkin_time'])); ?>
                </div>
            <?php endif; ?>
            
            <div class="checkin-form">
                <form method="POST" action="index.php?act=<?php echo $checkin ? 'admin/updateCheckIn' : 'admin/checkInKhach'; ?>">
                    <?php if ($checkin): ?>
                        <input type="hidden" name="id" value="<?php echo $checkin['id']; ?>">
                    <?php endif; ?>
                    
                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanhId; ?>">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    
                    <div class="form-group">
                        <label for="ho_ten">Họ và tên <span style="color: red;">*</span></label>
                        <input type="text" id="ho_ten" name="ho_ten" 
                               value="<?php echo htmlspecialchars($checkin['ho_ten'] ?? $booking['ho_ten'] ?? ''); ?>" 
                               required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="so_cmnd">Số CMND/CCCD</label>
                            <input type="text" id="so_cmnd" name="so_cmnd" 
                                   value="<?php echo htmlspecialchars($checkin['so_cmnd'] ?? ''); ?>" 
                                   placeholder="VD: 001234567890">
                        </div>
                        
                        <div class="form-group">
                            <label for="so_passport">Số Passport</label>
                            <input type="text" id="so_passport" name="so_passport" 
                                   value="<?php echo htmlspecialchars($checkin['so_passport'] ?? ''); ?>" 
                                   placeholder="VD: A12345678">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="so_dien_thoai">Số điện thoại</label>
                            <input type="tel" id="so_dien_thoai" name="so_dien_thoai" 
                                   value="<?php echo htmlspecialchars($checkin['so_dien_thoai'] ?? $booking['so_dien_thoai'] ?? ''); ?>" 
                                   placeholder="VD: 0901234567">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($checkin['email'] ?? $booking['email'] ?? ''); ?>" 
                                   placeholder="VD: email@example.com">
                        </div>
                    </div>
                    
                    <?php if ($checkin): ?>
                        <div class="form-group">
                            <label for="trang_thai">Trạng thái</label>
                            <select id="trang_thai" name="trang_thai">
                                <option value="DaCheckIn" <?php echo ($checkin['trang_thai'] ?? '') === 'DaCheckIn' ? 'selected' : ''; ?>>
                                    Đã check-in
                                </option>
                                <option value="DaCheckOut" <?php echo ($checkin['trang_thai'] ?? '') === 'DaCheckOut' ? 'selected' : ''; ?>>
                                    Đã check-out
                                </option>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="ghi_chu">Ghi chú</label>
                        <textarea id="ghi_chu" name="ghi_chu" placeholder="Nhập ghi chú nếu có..."><?php echo htmlspecialchars($checkin['ghi_chu'] ?? ''); ?></textarea>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <button type="submit" class="btn-submit">
                            <?php echo $checkin ? '✅ Cập nhật' : '✅ Check-in'; ?>
                        </button>
                        <a href="index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                           class="btn-cancel">
                            ← Quay lại
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="checkin-form">
                <p style="color: #dc3545;">Không tìm thấy thông tin booking.</p>
                <a href="index.php?act=admin/quanLyBooking" class="btn-cancel">← Quay lại</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
