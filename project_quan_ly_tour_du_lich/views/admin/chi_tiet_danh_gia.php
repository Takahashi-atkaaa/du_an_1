<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đánh giá</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .review-detail {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
        }
        .rating-large {
            font-size: 3em;
            color: #ffc107;
        }
        .response-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">Admin Panel</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php?act=admin/danhGia"><i class="bi bi-arrow-left"></i> Quản lý đánh giá</a>
                <a class="nav-link" href="index.php?act=admin/dashboard"><i class="bi bi-house"></i> Dashboard</a>
                <a class="nav-link" href="index.php?act=auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <a href="index.php?act=admin/danhGia" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Thông tin đánh giá -->
                <div class="card review-detail mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h3><i class="bi bi-chat-square-quote"></i> Chi tiết Đánh giá</h3>
                            <hr class="bg-white">
                            
                            <div class="mb-3">
                                <h5><i class="bi bi-person-circle"></i> Thông tin khách hàng</h5>
                                <p class="mb-1"><strong>Họ tên:</strong> <?= htmlspecialchars($danhGia['ten_khach_hang']) ?></p>
                                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($danhGia['email_khach_hang']) ?></p>
                                <?php if (!empty($danhGia['dien_thoai_khach_hang'])): ?>
                                    <p class="mb-1"><strong>Điện thoại:</strong> <?= htmlspecialchars($danhGia['dien_thoai_khach_hang']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <h5><i class="bi bi-info-circle"></i> Đối tượng đánh giá</h5>
                                <?php
                                $loaiText = [
                                    'Tour' => 'Tour',
                                    'NhaCungCap' => 'Nhà cung cấp',
                                    'NhanSu' => 'Nhân sự'
                                ];
                                ?>
                                <p class="mb-1"><strong>Loại:</strong> 
                                    <span class="badge bg-light text-dark"><?= $loaiText[$danhGia['loai_danh_gia']] ?? $danhGia['loai_danh_gia'] ?></span>
                                </p>
                                
                                <?php if ($danhGia['loai_danh_gia'] === 'Tour' && !empty($danhGia['ten_tour'])): ?>
                                    <p class="mb-1"><strong>Tour:</strong> <?= htmlspecialchars($danhGia['ten_tour']) ?></p>
                                <?php elseif ($danhGia['loai_danh_gia'] === 'NhaCungCap' && !empty($danhGia['ten_nha_cung_cap'])): ?>
                                    <p class="mb-1"><strong>Nhà cung cấp:</strong> <?= htmlspecialchars($danhGia['ten_nha_cung_cap']) ?></p>
                                    <?php if (!empty($danhGia['loai_dich_vu'])): ?>
                                        <p class="mb-1"><strong>Loại dịch vụ:</strong> <?= htmlspecialchars($danhGia['loai_dich_vu']) ?></p>
                                    <?php endif; ?>
                                <?php elseif ($danhGia['loai_danh_gia'] === 'NhanSu' && !empty($danhGia['ten_nhan_su'])): ?>
                                    <p class="mb-1"><strong>Nhân sự:</strong> <?= htmlspecialchars($danhGia['ten_nhan_su']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <h5><i class="bi bi-calendar"></i> Thời gian</h5>
                                <p class="mb-0">
                                    <strong>Ngày đánh giá:</strong> 
                                    <?= date('d/m/Y H:i', strtotime($danhGia['ngay_danh_gia'])) ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <h5>Điểm đánh giá</h5>
                            <div class="rating-large">
                                <?= $danhGia['diem'] ?> <i class="bi bi-star-fill"></i>
                            </div>
                            <div class="mt-3">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?= $i <= $danhGia['diem'] ? '-fill' : '' ?>" style="font-size: 1.5em; color: #ffc107;"></i>
                                <?php endfor; ?>
                            </div>
                            
                            <?php if (!empty($danhGia['tieu_chi'])): ?>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark">
                                        <?= htmlspecialchars($danhGia['tieu_chi']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Nội dung đánh giá -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-chat-left-text"></i> Nội dung đánh giá</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="font-size: 1.1em; line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($danhGia['noi_dung'])) ?>
                        </p>
                    </div>
                </div>
                
                <!-- Phản hồi -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-reply"></i> Phản hồi của Admin</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($danhGia['phan_hoi_admin'])): ?>
                            <div class="response-section mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>Đã phản hồi</strong>
                                    <?php if (!empty($danhGia['ngay_phan_hoi'])): ?>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($danhGia['ngay_phan_hoi'])) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($danhGia['phan_hoi_admin'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="index.php?act=admin/danhGia/traLoi">
                            <input type="hidden" name="id" value="<?= $danhGia['danh_gia_id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">
                                    <?= !empty($danhGia['phan_hoi_admin']) ? 'Cập nhật phản hồi' : 'Thêm phản hồi' ?>
                                </label>
                                <textarea name="phan_hoi_admin" class="form-control" rows="5" required><?= htmlspecialchars($danhGia['phan_hoi_admin'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send"></i> Gửi phản hồi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
