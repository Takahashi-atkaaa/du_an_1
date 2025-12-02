<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: #f6f8fb;
        }
        .gradient-header {
            background: linear-gradient(90deg, #4f8cff 0%, #6a82fb 100%);
            color: #fff;
            border-bottom-left-radius: 2rem;
            border-bottom-right-radius: 2rem;
            box-shadow: 0 8px 32px rgba(76,130,251,0.12);
            position: relative;
        }
        .avatar-box {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg,#fff 60%,#e3eafe 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 5rem;
            box-shadow: 0 4px 24px rgba(76,130,251,0.18);
            border: 6px solid #fff;
            margin-bottom: 1rem;
            transition: box-shadow 0.3s;
        }
        .avatar-box:hover {
            box-shadow: 0 8px 40px rgba(76,130,251,0.28);
        }
        .badge-role {
            background: linear-gradient(90deg,#00c9a7 0%,#4f8cff 100%);
            color: #fff;
            font-size: 1rem;
            padding: 0.5rem 1.2rem;
            border-radius: 2rem;
            box-shadow: 0 2px 8px rgba(0,201,167,0.12);
            font-weight: 600;
            letter-spacing: 1px;
        }
        .card-info {
            background: #fff;
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(76,130,251,0.08);
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            border: none;
            transition: box-shadow 0.3s;
        }
        .card-info:hover {
            box-shadow: 0 8px 32px rgba(76,130,251,0.18);
        }
        .section-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #4f8cff;
            margin-bottom: 16px;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-label {
            color: #6a82fb;
            font-weight: 500;
            min-width: 120px;
            display: inline-block;
        }
        .fw-semibold {
            font-weight: 600;
        }
        .back-btn {
            font-weight: 600;
            border-radius: 1.5rem;
            padding: 0.6rem 1.5rem;
            box-shadow: 0 2px 8px rgba(76,130,251,0.10);
            background: #fff;
            color: #4f8cff;
            border: none;
            transition: background 0.2s, color 0.2s;
        }
        .back-btn:hover {
            background: #4f8cff;
            color: #fff;
        }
        @media (max-width: 768px) {
            .gradient-header { padding: 2rem 1rem; }
            .card-info { padding: 1.2rem 0.7rem; }
            .avatar-box { width: 100px; height: 100px; font-size: 3rem; }
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="gradient-header p-5 mb-4 rounded-bottom shadow">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4">
            <div class="avatar-box">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="flex-grow-1">
                <h1 class="mb-2 fw-bold" style="font-size:2.2rem;letter-spacing:1px;">
                    <?= htmlspecialchars($nguoiDung['ho_ten'] ?? '') ?>
                </h1>
                <div class="mb-2">
                    <span class="fw-bold">Vai trò:</span> <span class="badge-role"><?= htmlspecialchars($nguoiDung['vai_tro'] ?? '') ?></span>
                </div>
                <?php if (strtolower($nguoiDung['trang_thai'] ?? '') === 'active' || strtolower($nguoiDung['trang_thai'] ?? '') === 'hoatdong'): ?>
                    <span class="badge bg-success px-3 py-2">Đang hoạt động</span>
                <?php else: ?>
                    <span class="badge bg-secondary px-3 py-2">Không hoạt động</span>
                <?php endif; ?>
            </div>
        </div>
        <a href="index.php?act=admin/quanLyNguoiDung" class="back-btn mt-4"><i class="bi bi-arrow-left"></i> Quay lại danh sách</a>
    </div>
    <div class="row g-4 px-4 mt-2">
        <div class="col-md-6">
            <div class="card-info">
                <div class="section-title"><i class="bi bi-person-lines-fill"></i> Thông tin cá nhân</div>
                <div class="mb-2"><span class="info-label"># ID:</span> <span class="fw-bold text-primary"><?= $nguoiDung['id'] ?? '' ?></span></div>
                <div class="mb-2"><span class="info-label">Họ và tên:</span> <span class="fw-semibold"><?= htmlspecialchars($nguoiDung['ho_ten'] ?? '') ?></span></div>
                <div class="mb-2"><span class="info-label">Email:</span> <a href="mailto:<?= htmlspecialchars($nguoiDung['email'] ?? '') ?>" class="text-decoration-none text-info fw-semibold"><?= htmlspecialchars($nguoiDung['email'] ?? '') ?></a></div>
                <div class="mb-2"><span class="info-label">Điện thoại:</span> <a href="tel:<?= htmlspecialchars($nguoiDung['so_dien_thoai'] ?? '') ?>" class="text-decoration-none text-success fw-semibold"><?= htmlspecialchars($nguoiDung['so_dien_thoai'] ?? '') ?></a></div>
                <div class="mb-2"><span class="info-label">Tên đăng nhập:</span> <span class="fw-semibold"><?= htmlspecialchars($nguoiDung['ten_dang_nhap'] ?? '') ?></span></div>
                <div class="mb-2"><span class="info-label">Ngày tạo:</span> <span class="fw-semibold"><?= htmlspecialchars($nguoiDung['ngay_tao'] ?? '') ?></span></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-info">
                <div class="section-title"><i class="bi bi-shield-lock"></i> Trạng thái & Quyền</div>
                <div class="mb-2"><span class="info-label">Trạng thái:</span> <span class="fw-semibold text-info"><?= htmlspecialchars($nguoiDung['trang_thai'] ?? '') ?></span></div>
                <div class="mb-2"><span class="info-label">Quyền cấp cao:</span> <span class="fw-semibold text-danger"><?= !empty($nguoiDung['quyen_cap_cao']) ? 'Có' : 'Không' ?></span></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
