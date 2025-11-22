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
    <title>Phân Phòng Khách Sạn - Quản Lý Tour Du Lịch</title>
    <link rel="stylesheet" href="public/assets/css/admin.css">
    <style>
        .room-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
        }
        .room-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
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
        .room-list {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .room-list h3 {
            margin: 0 0 15px 0;
        }
        .room-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .room-info {
            flex: 1;
        }
        .room-info p {
            margin: 3px 0;
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
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-submit:hover {
            background: #218838;
        }
        .btn-cancel:hover {
            background: #5a6268;
        }
        .btn-delete:hover {
            background: #c82333;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        .status-booked {
            background: #fff3cd;
            color: #856404;
        }
        .status-checkedin {
            background: #d4edda;
            color: #155724;
        }
        .status-checkedout {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="room-container">
        <div class="no-print" style="margin-bottom: 20px;">
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/dashboard" style="color: #007bff; text-decoration: none;">← Dashboard</a> | 
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" style="color: #007bff; text-decoration: none;">Quản lý Tour</a>
        </div>
        <h1>Phân Phòng Khách Sạn</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($booking): ?>
            <div class="booking-info">
                <h3>Thông tin booking</h3>
                <p><strong>Mã booking:</strong> <?php echo htmlspecialchars($booking['booking_id'] ?? 'N/A'); ?></p>
                <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?></p>
                <p><strong>Số người:</strong> <?php echo ($booking['so_nguoi'] ?? 0); ?> người</p>
                <?php if ($checkin): ?>
                    <p><strong>Trạng thái check-in:</strong> 
                        <span class="status-badge status-checkedin">✅ Đã check-in</span>
                    </p>
                <?php endif; ?>
            </div>
            
            <!-- Form thêm/sửa phòng -->
            <div class="room-form">
                <h3>Thêm phân phòng mới</h3>
                <form method="POST" action="index.php?act=admin/phanPhongKhachSan">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanhId; ?>">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                    <?php if ($checkin): ?>
                        <input type="hidden" name="checkin_id" value="<?php echo $checkin['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="ten_khach_san">Tên khách sạn <span style="color: red;">*</span></label>
                        <input type="text" id="ten_khach_san" name="ten_khach_san" required 
                               list="hotel-list" placeholder="VD: Khách sạn Hoàng Gia">
                        <datalist id="hotel-list">
                            <?php foreach ($hotelList as $hotel): ?>
                                <option value="<?php echo htmlspecialchars($hotel); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    
                    <div class="form-row-3">
                        <div class="form-group">
                            <label for="so_phong">Số phòng <span style="color: red;">*</span></label>
                            <input type="text" id="so_phong" name="so_phong" required placeholder="VD: 301">
                        </div>
                        
                        <div class="form-group">
                            <label for="loai_phong">Loại phòng</label>
                            <select id="loai_phong" name="loai_phong">
                                <option value="Standard">Standard</option>
                                <option value="Superior">Superior</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="so_giuong">Số giường</label>
                            <input type="number" id="so_giuong" name="so_giuong" value="1" min="1" max="4">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ngay_nhan_phong">Ngày nhận phòng</label>
                            <input type="date" id="ngay_nhan_phong" name="ngay_nhan_phong">
                        </div>
                        
                        <div class="form-group">
                            <label for="ngay_tra_phong">Ngày trả phòng</label>
                            <input type="date" id="ngay_tra_phong" name="ngay_tra_phong">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="gia_phong">Giá phòng (VNĐ)</label>
                            <input type="number" id="gia_phong" name="gia_phong" value="0" min="0">
                        </div>
                        
                        <div class="form-group">
                            <label for="trang_thai">Trạng thái</label>
                            <select id="trang_thai" name="trang_thai">
                                <option value="DaDatPhong">Đã đặt phòng</option>
                                <option value="DaNhanPhong">Đã nhận phòng</option>
                                <option value="DaTraPhong">Đã trả phòng</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ghi_chu">Ghi chú</label>
                        <textarea id="ghi_chu" name="ghi_chu" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">➕ Thêm phòng</button>
                </form>
            </div>
            
            <!-- Danh sách phòng đã phân -->
            <?php if (!empty($roomList)): ?>
                <div class="room-list">
                    <h3>Danh sách phòng đã phân (<?php echo count($roomList); ?>)</h3>
                    
                    <?php foreach ($roomList as $room): ?>
                        <div class="room-item">
                            <div class="room-info">
                                <p><strong>🏨 <?php echo htmlspecialchars($room['ten_khach_san']); ?></strong> - Phòng <?php echo htmlspecialchars($room['so_phong']); ?>
                                    <?php 
                                        $statusClass = $room['trang_thai'] === 'DaDatPhong' ? 'status-booked' : 
                                                      ($room['trang_thai'] === 'DaNhanPhong' ? 'status-checkedin' : 'status-checkedout');
                                        $statusText = $room['trang_thai'] === 'DaDatPhong' ? 'Đã đặt' : 
                                                     ($room['trang_thai'] === 'DaNhanPhong' ? 'Đã nhận' : 'Đã trả');
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </p>
                                <p>Loại: <?php echo htmlspecialchars($room['loai_phong']); ?> | 
                                   Giường: <?php echo $room['so_giuong']; ?> | 
                                   Giá: <?php echo number_format($room['gia_phong'], 0, ',', '.'); ?> VNĐ</p>
                                <?php if ($room['ngay_nhan_phong']): ?>
                                    <p>📅 <?php echo date('d/m/Y', strtotime($room['ngay_nhan_phong'])); ?> 
                                       → <?php echo date('d/m/Y', strtotime($room['ngay_tra_phong'])); ?></p>
                                <?php endif; ?>
                                <?php if ($room['ghi_chu']): ?>
                                    <p>💬 <?php echo htmlspecialchars($room['ghi_chu']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <form method="POST" action="index.php?act=admin/phanPhongKhachSan" style="display: inline;" 
                                      onsubmit="return confirm('Bạn có chắc muốn xóa phân phòng này?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
                                    <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanhId; ?>">
                                    <button type="submit" class="btn-delete">🗑️ Xóa</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="room-list">
                    <p style="text-align: center; color: #666; padding: 20px;">
                        Chưa có phân phòng nào. Sử dụng form bên trên để thêm phòng.
                    </p>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <a href="index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id=<?php echo $lichKhoiHanhId; ?>" 
                   class="btn-cancel">
                    ← Quay lại danh sách
                </a>
            </div>
            
        <?php else: ?>
            <div class="room-form">
                <p style="color: #dc3545;">Không tìm thấy thông tin booking.</p>
                <a href="index.php?act=admin/quanLyBooking" class="btn-cancel">← Quay lại</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
