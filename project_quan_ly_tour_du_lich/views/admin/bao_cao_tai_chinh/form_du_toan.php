<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($duToan) ? 'Sửa' : 'Tạo' ?> Dự Toán Tour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
        }
        
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .form-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        
        .total-display {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-top: 20px;
        }
        
        .total-display h3 {
            font-size: 16px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .total-display .amount {
            font-size: 36px;
            font-weight: 700;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #666;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .money-input {
            position: relative;
        }
        
        .money-input::after {
            content: 'đ';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-weight: 600;
        }
        
        input[type="number"].money {
            padding-right: 35px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-calculator"></i>
                <?= isset($duToan) ? 'Sửa Dự Toán' : 'Tạo Dự Toán Mới' ?>
                <?= isset($tour) ? '- ' . htmlspecialchars($tour['ten_tour']) : '' ?>
            </h1>
        </div>
        
        <form method="POST" action="index.php?act=admin/duToanTour" class="form-card">
            <?php if(isset($duToan)): ?>
                <input type="hidden" name="du_toan_id" value="<?= $duToan['du_toan_id'] ?>">
            <?php endif; ?>
            
            <!-- Thông tin cơ bản -->
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Thông tin cơ bản</h3>
                
                <div class="form-group">
                    <label>Tour <span style="color: red;">*</span></label>
                    <select name="tour_id" required <?= isset($tour) ? 'readonly' : '' ?>>
                        <option value="">-- Chọn tour --</option>
                        <?php foreach($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>" 
                                <?= (isset($tour) && $tour['tour_id'] == $t['tour_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['ten_tour']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Lịch khởi hành (Tùy chọn)</label>
                    <input type="number" name="lich_khoi_hanh_id" 
                        value="<?= $duToan['lich_khoi_hanh_id'] ?? '' ?>"
                        placeholder="ID lịch khởi hành cụ thể (để trống nếu dự toán chung)">
                </div>
            </div>
            
            <!-- Chi phí phương tiện -->
            <div class="form-section">
                <h3><i class="fas fa-bus"></i> Chi phí phương tiện</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_phuong_tien" class="money chi-phi" 
                            value="<?= $duToan['cp_phuong_tien'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_phuong_tien" placeholder="VD: Xe bus 45 chỗ, xăng, phí cao tốc..."><?= $duToan['mo_ta_phuong_tien'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Chi phí lưu trú -->
            <div class="form-section">
                <h3><i class="fas fa-hotel"></i> Chi phí lưu trú</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_luu_tru" class="money chi-phi" 
                            value="<?= $duToan['cp_luu_tru'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_luu_tru" placeholder="VD: Khách sạn 4 sao, 2 đêm, 20 phòng..."><?= $duToan['mo_ta_luu_tru'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Chi phí vé tham quan -->
            <div class="form-section">
                <h3><i class="fas fa-ticket-alt"></i> Chi phí vé tham quan</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_ve_tham_quan" class="money chi-phi" 
                            value="<?= $duToan['cp_ve_tham_quan'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_ve_tham_quan" placeholder="VD: Vé vào cổng các điểm tham quan, vui chơi..."><?= $duToan['mo_ta_ve_tham_quan'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Chi phí ăn uống -->
            <div class="form-section">
                <h3><i class="fas fa-utensils"></i> Chi phí ăn uống</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_an_uong" class="money chi-phi" 
                            value="<?= $duToan['cp_an_uong'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_an_uong" placeholder="VD: 3 ngày x 3 bữa x 40 khách x 120k/bữa..."><?= $duToan['mo_ta_an_uong'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Chi phí HDV -->
            <div class="form-section">
                <h3><i class="fas fa-user-tie"></i> Chi phí hướng dẫn viên</h3>
                <div class="form-group money-input">
                    <label>Số tiền</label>
                    <input type="number" name="cp_huong_dan_vien" class="money chi-phi" 
                        value="<?= $duToan['cp_huong_dan_vien'] ?? 0 ?>" step="1000" min="0">
                </div>
            </div>
            
            <!-- Dịch vụ bổ sung -->
            <div class="form-section">
                <h3><i class="fas fa-plus-circle"></i> Dịch vụ bổ sung</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_dich_vu_bo_sung" class="money chi-phi" 
                            value="<?= $duToan['cp_dich_vu_bo_sung'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_dich_vu" placeholder="VD: Bảo hiểm, guide địa phương, phí tham quan riêng..."><?= $duToan['mo_ta_dich_vu'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Chi phí phát sinh dự kiến -->
            <div class="form-section">
                <h3><i class="fas fa-exclamation-triangle"></i> Chi phí phát sinh dự kiến (5-10%)</h3>
                <div class="form-row">
                    <div class="form-group money-input">
                        <label>Số tiền</label>
                        <input type="number" name="cp_phat_sinh_du_kien" class="money chi-phi" 
                            value="<?= $duToan['cp_phat_sinh_du_kien'] ?? 0 ?>" step="1000" min="0">
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta_phat_sinh" placeholder="VD: Dự phòng 10% cho các chi phí phát sinh..."><?= $duToan['mo_ta_phat_sinh'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Tổng dự toán -->
            <div class="total-display">
                <h3>TỔNG DỰ TOÁN</h3>
                <div class="amount" id="tongDuToan">0đ</div>
            </div>
            
            <div class="button-group">
                <a href="index.php?act=admin/duToanTour<?= isset($tour) ? '&tour_id=' . $tour['tour_id'] : '' ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu dự toán
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Tự động tính tổng
        const chiPhiInputs = document.querySelectorAll('.chi-phi');
        const tongDuToanEl = document.getElementById('tongDuToan');
        
        function formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        }
        
        function calculateTotal() {
            let total = 0;
            chiPhiInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            tongDuToanEl.textContent = formatMoney(total);
        }
        
        chiPhiInputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
        });
        
        // Tính toán lần đầu
        calculateTotal();
    </script>
</body>
</html>
