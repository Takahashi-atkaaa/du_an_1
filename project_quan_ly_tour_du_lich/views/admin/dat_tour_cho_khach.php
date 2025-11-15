<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour Cho Khách - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
    <style>
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .form-section h3 {
            margin-top: 0;
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
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
            box-sizing: border-box;
        }
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group.required label::after {
            content: " *";
            color: red;
        }
        .cho-trong-info {
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-weight: bold;
        }
        .cho-trong-info.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .cho-trong-info.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .loai-khach-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        .loai-khach-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: pointer;
        }
        .loai-khach-group input[type="radio"] {
            width: auto;
            margin-right: 5px;
        }
        .cong-ty-field {
            display: none;
        }
        .cong-ty-field.show {
            display: block;
        }
        .btn-submit {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        .btn-submit:hover {
            background: #0056b3;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Đặt Tour Cho Khách Hàng</h1>
        <nav>
            <a href="index.php?act=admin/dashboard">← Quay lại Dashboard</a>
            <a href="index.php?act=admin/quanLyBooking">Quản lý Booking</a>
        </nav>

        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?act=booking/datTourChoKhach" id="datTourForm">
                <!-- Thông tin Tour -->
                <div class="form-section">
                    <h3>1. Chọn Tour</h3>
                    <div class="form-group required">
                        <label for="tour_id">Tour</label>
                        <select name="tour_id" id="tour_id" required>
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($tours as $t): ?>
                                <option value="<?php echo $t['tour_id']; ?>" 
                                    data-gia="<?php echo $t['gia_co_ban']; ?>"
                                    <?php echo (isset($formData['tour_id']) && $formData['tour_id'] == $t['tour_id']) || (isset($tour) && $tour['tour_id'] == $t['tour_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['ten_tour']); ?> - <?php echo number_format($t['gia_co_ban']); ?> VNĐ
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="ngay_khoi_hanh">Ngày khởi hành</label>
                        <input type="date" name="ngay_khoi_hanh" id="ngay_khoi_hanh" 
                            value="<?php echo $formData['ngay_khoi_hanh'] ?? ''; ?>" 
                            min="<?php echo date('Y-m-d'); ?>" required>
                        <div id="cho-trong-info" class="cho-trong-info" style="display: none;"></div>
                    </div>

                    <div class="form-group required">
                        <label for="so_nguoi">Số lượng người</label>
                        <input type="number" name="so_nguoi" id="so_nguoi" 
                            value="<?php echo $formData['so_nguoi'] ?? '1'; ?>" 
                            min="1" required>
                    </div>
                </div>

                <!-- Thông tin Khách hàng -->
                <div class="form-section">
                    <h3>2. Thông tin Khách hàng</h3>
                    
                    <div class="form-group required">
                        <label for="loai_khach">Loại khách</label>
                        <div class="loai-khach-group">
                            <label>
                                <input type="radio" name="loai_khach" value="le" 
                                    <?php echo (!isset($formData['loai_khach']) || $formData['loai_khach'] == 'le') ? 'checked' : ''; ?>>
                                Khách lẻ (1-2 người)
                            </label>
                            <label>
                                <input type="radio" name="loai_khach" value="doan" 
                                    <?php echo (isset($formData['loai_khach']) && $formData['loai_khach'] == 'doan') ? 'checked' : ''; ?>>
                                Đoàn (Công ty/Tổ chức)
                            </label>
                        </div>
                    </div>

                    <div class="form-group cong-ty-field <?php echo (isset($formData['loai_khach']) && $formData['loai_khach'] == 'doan') ? 'show' : ''; ?>" id="cong-ty-field">
                        <label for="ten_cong_ty">Tên công ty/Tổ chức</label>
                        <input type="text" name="ten_cong_ty" id="ten_cong_ty" 
                            value="<?php echo htmlspecialchars($formData['ten_cong_ty'] ?? ''); ?>">
                    </div>

                    <div class="form-row">
                        <div class="form-group required">
                            <label for="ho_ten">Họ và tên</label>
                            <input type="text" name="ho_ten" id="ho_ten" 
                                value="<?php echo htmlspecialchars($formData['ho_ten'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="gioi_tinh">Giới tính</label>
                            <select name="gioi_tinh" id="gioi_tinh">
                                <option value="">-- Chọn --</option>
                                <option value="Nam" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="Nữ" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                <option value="Khác" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group required">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" 
                                value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                        </div>

                        <div class="form-group required">
                            <label for="so_dien_thoai">Số điện thoại</label>
                            <input type="tel" name="so_dien_thoai" id="so_dien_thoai" 
                                value="<?php echo htmlspecialchars($formData['so_dien_thoai'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dia_chi">Địa chỉ</label>
                            <input type="text" name="dia_chi" id="dia_chi" 
                                value="<?php echo htmlspecialchars($formData['dia_chi'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="ngay_sinh">Ngày sinh</label>
                            <input type="date" name="ngay_sinh" id="ngay_sinh" 
                                value="<?php echo $formData['ngay_sinh'] ?? ''; ?>">
                        </div>
                    </div>
                </div>

                <!-- Yêu cầu đặc biệt -->
                <div class="form-section">
                    <h3>3. Yêu cầu đặc biệt & Ghi chú</h3>
                    
                    <div class="form-group">
                        <label for="yeu_cau_dac_biet">Yêu cầu đặc biệt</label>
                        <textarea name="yeu_cau_dac_biet" id="yeu_cau_dac_biet" 
                            placeholder="Nhập các yêu cầu đặc biệt của khách hàng..."><?php echo htmlspecialchars($formData['yeu_cau_dac_biet'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ghi_chu">Ghi chú nội bộ</label>
                        <textarea name="ghi_chu" id="ghi_chu" 
                            placeholder="Ghi chú cho nhân viên..."><?php echo htmlspecialchars($formData['ghi_chu'] ?? ''); ?></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Xác nhận đặt tour</button>
            </form>
        </div>
    </div>

    <script>
        // Hiển thị/ẩn trường công ty
        document.querySelectorAll('input[name="loai_khach"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const congTyField = document.getElementById('cong-ty-field');
                if (this.value === 'doan') {
                    congTyField.classList.add('show');
                } else {
                    congTyField.classList.remove('show');
                }
            });
        });

        // Kiểm tra chỗ trống khi thay đổi tour, ngày khởi hành hoặc số người
        function kiemTraChoTrong() {
            const tourId = document.getElementById('tour_id').value;
            const ngayKhoiHanh = document.getElementById('ngay_khoi_hanh').value;
            const soNguoi = document.getElementById('so_nguoi').value;
            const infoDiv = document.getElementById('cho-trong-info');

            if (!tourId || !ngayKhoiHanh || !soNguoi) {
                infoDiv.style.display = 'none';
                return;
            }

            fetch(`index.php?act=booking/kiemTraChoTrong&tour_id=${tourId}&ngay_khoi_hanh=${ngayKhoiHanh}&so_nguoi=${soNguoi}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        infoDiv.style.display = 'none';
                        return;
                    }

                    infoDiv.style.display = 'block';
                    if (data.co_cho) {
                        infoDiv.className = 'cho-trong-info success';
                        infoDiv.textContent = `✓ Còn ${data.cho_trong} chỗ trống (Đã đặt: ${data.da_dat}/${data.toi_da})`;
                    } else {
                        infoDiv.className = 'cho-trong-info error';
                        infoDiv.textContent = `✗ Không đủ chỗ! Chỉ còn ${data.cho_trong} chỗ trống (Đã đặt: ${data.da_dat}/${data.toi_da})`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    infoDiv.style.display = 'none';
                });
        }

        document.getElementById('tour_id').addEventListener('change', kiemTraChoTrong);
        document.getElementById('ngay_khoi_hanh').addEventListener('change', kiemTraChoTrong);
        document.getElementById('so_nguoi').addEventListener('input', kiemTraChoTrong);

        // Validation form
        document.getElementById('datTourForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const soDienThoai = document.getElementById('so_dien_thoai').value;

            if (!email && !soDienThoai) {
                e.preventDefault();
                alert('Vui lòng nhập email hoặc số điện thoại.');
                return false;
            }

            const infoDiv = document.getElementById('cho-trong-info');
            if (infoDiv.style.display === 'block' && infoDiv.classList.contains('error')) {
                e.preventDefault();
                alert('Không đủ chỗ trống. Vui lòng chọn ngày khác hoặc giảm số lượng người.');
                return false;
            }
        });
    </script>
</body>
</html>

