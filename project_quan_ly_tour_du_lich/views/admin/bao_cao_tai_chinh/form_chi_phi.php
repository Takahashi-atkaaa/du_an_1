<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ghi nhận chi phí thực tế</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 700px; margin: 40px auto; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 8px 32px rgba(76,130,251,0.12); padding: 32px; }
        .form-label { font-weight: 600; color: #4f8cff; }
        .btn-primary { background: linear-gradient(90deg,#00c9a7 0%,#4f8cff 100%); border: none; }
        .form-section { margin-bottom: 24px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h2 class="mb-4"><i class="fa fa-money-bill-wave"></i> Ghi nhận chi phí thực tế</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-section">
                <label class="form-label">Tour</label>
                <select name="tour_id" class="form-select" required>
                    <?php foreach ($tours as $tour): ?>
                        <option value="<?= $tour['tour_id'] ?>" <?= isset($duToan) && $duToan['tour_id'] == $tour['tour_id'] ? 'selected' : '' ?>><?= htmlspecialchars($tour['ten_tour']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-section">
                <label class="form-label">Dự toán liên quan</label>
                <select name="du_toan_id" class="form-select" required>
                    <?php foreach ($duToans as $dt): ?>
                        <option value="<?= $dt['du_toan_id'] ?>" <?= isset($duToan) && $duToan['du_toan_id'] == $dt['du_toan_id'] ? 'selected' : '' ?>><?= htmlspecialchars($dt['ten_tour']) ?> (<?= number_format($dt['tong_du_toan']) ?>đ)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-section">
                <label class="form-label">Loại chi phí</label>
                <select name="loai_chi_phi" class="form-select" required>
                    <option value="PhuongTien">Phương tiện</option>
                    <option value="LuuTru">Lưu trú</option>
                    <option value="VeThamQuan">Vé tham quan</option>
                    <option value="AnUong">Ăn uống</option>
                    <option value="HuongDanVien">Hướng dẫn viên</option>
                    <option value="DichVuBoSung">Dịch vụ bổ sung</option>
                    <option value="PhatSinh">Phát sinh dự kiến</option>
                </select>
            </div>
            <div class="form-section">
                <label class="form-label">Tên khoản chi</label>
                <input type="text" name="ten_khoan_chi" class="form-control" required>
            </div>
            <div class="form-section">
                <label class="form-label">Số tiền (VNĐ)</label>
                <input type="number" name="so_tien" class="form-control" required min="0">
            </div>
            <div class="form-section">
                <label class="form-label">Ngày phát sinh</label>
                <input type="date" name="ngay_phat_sinh" class="form-control" required>
            </div>
            <div class="form-section">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-section">
                <label class="form-label">Chứng từ (hóa đơn, ảnh...)</label>
                <input type="file" name="chung_tu" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary px-4 py-2"><i class="fa fa-save"></i> Lưu chi phí</button>
        </form>
    </div>
</div>
</body>
</html>
