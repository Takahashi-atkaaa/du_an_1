<?php $isCapNhat = isset($tour) && isset($tour['tour_id']); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isCapNhat ? 'Sửa tour' : 'Thêm tour mới'; ?> - Quản lý Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .form-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 0.375rem;
            border: 2px dashed #dee2e6;
            padding: 0.5rem;
        }
        .custom-file-upload {
            border: 2px dashed #0d6efd;
            display: inline-block;
            padding: 2rem;
            cursor: pointer;
            text-align: center;
            border-radius: 0.375rem;
            background: #f8f9fa;
            transition: all 0.3s;
            width: 100%;
        }
        .custom-file-upload:hover {
            background: #e7f1ff;
            border-color: #0a58ca;
        }
        .btn-action-group {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #dee2e6;
            margin-top: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?act=admin/quanLyTour">
                            <i class="bi bi-compass"></i> Tour
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 pb-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">
                    <i class="bi bi-<?php echo $isCapNhat ? 'pencil-square' : 'plus-circle'; ?> text-primary"></i>
                    <?php echo $isCapNhat ? 'Sửa thông tin tour' : 'Thêm tour mới'; ?>
                </h3>
                <p class="text-muted mb-0">
                    <?php echo $isCapNhat ? 'Cập nhật thông tin chi tiết của tour' : 'Điền đầy đủ thông tin để tạo tour mới'; ?>
                </p>
            </div>
            <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['image_upload_error'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['image_upload_error']); unset($_SESSION['image_upload_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL; ?>index.php?act=<?php echo $isCapNhat ? 'tour/update' : 'tour/create'; ?>">
            <?php if ($isCapNhat): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($tour['tour_id']); ?>">
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Thông tin cơ bản -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-info-circle"></i> Thông tin cơ bản
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label required-label">Tên tour</label>
                                <input type="text" name="ten_tour" class="form-control" 
                                       value="<?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>" 
                                       placeholder="VD: Hà Nội - Hạ Long 3N2D"
                                       required>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label">Loại tour</label>
                                <select name="loai_tour" class="form-select">
                                    <?php $loai = $tour['loai_tour'] ?? 'TrongNuoc'; ?>
                                    <option value="TrongNuoc" <?php echo $loai === 'TrongNuoc' ? 'selected' : ''; ?>>Trong nước</option>
                                    <option value="QuocTe" <?php echo $loai === 'QuocTe' ? 'selected' : ''; ?>>Quốc tế</option>
                                    <option value="TheoYeuCau" <?php echo $loai === 'TheoYeuCau' ? 'selected' : ''; ?>>Theo yêu cầu</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Mô tả tour</label>
                                <textarea name="mo_ta" class="form-control" rows="4" 
                                          placeholder="Giới thiệu ngắn gọn về tour..."><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-label">Giá cơ bản (VNĐ)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                    <input type="number" name="gia_co_ban" class="form-control" 
                                           step="1000" min="0" 
                                           value="<?php echo htmlspecialchars((string)($tour['gia_co_ban'] ?? '0')); ?>" 
                                           required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select name="trang_thai" class="form-select">
                                    <?php $tt = $tour['trang_thai'] ?? 'HoatDong'; ?>
                                    <option value="HoatDong" <?php echo $tt === 'HoatDong' ? 'selected' : ''; ?>>
                                        <i class="bi bi-check-circle"></i> Hoạt động
                                    </option>
                                    <option value="NgungHoatDong" <?php echo $tt === 'NgungHoatDong' ? 'selected' : ''; ?>>
                                        Ngừng hoạt động
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin lộ trình -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-geo-alt"></i> Thông tin lộ trình
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Điểm khởi hành</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-pin-map"></i></span>
                                    <input type="text" name="diem_khoi_hanh" class="form-control" 
                                           value="<?php echo htmlspecialchars($tour['diem_khoi_hanh'] ?? ''); ?>" 
                                           placeholder="VD: Hà Nội">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Điểm đến</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-flag"></i></span>
                                    <input type="text" name="diem_den" class="form-control" 
                                           value="<?php echo htmlspecialchars($tour['diem_den'] ?? ''); ?>" 
                                           placeholder="VD: Hạ Long, Quảng Ninh">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Thời gian</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                    <input type="text" name="thoi_gian" class="form-control" 
                                           value="<?php echo htmlspecialchars($tour['thoi_gian'] ?? ''); ?>" 
                                           placeholder="VD: 3 ngày 2 đêm">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Phương tiện</label>
                                <select name="phuong_tien" class="form-select">
                                    <?php $pt = $tour['phuong_tien'] ?? ''; ?>
                                    <option value="">-- Chọn phương tiện --</option>
                                    <option value="Xe" <?php echo $pt === 'Xe' ? 'selected' : ''; ?>>Xe ô tô</option>
                                    <option value="MayBay" <?php echo $pt === 'MayBay' ? 'selected' : ''; ?>>Máy bay</option>
                                    <option value="Tau" <?php echo $pt === 'Tau' ? 'selected' : ''; ?>>Tàu hỏa</option>
                                    <option value="Khac" <?php echo $pt === 'Khac' ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Số chỗ tối đa</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" name="so_cho_toi_da" class="form-control" 
                                           min="1" value="<?php echo htmlspecialchars($tour['so_cho_toi_da'] ?? ''); ?>" 
                                           placeholder="VD: 30">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chính sách & điều kiện -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-file-text"></i> Chính sách & Điều kiện
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Điều kiện hủy</label>
                                <textarea name="dieu_kien_huy" class="form-control" rows="3" 
                                          placeholder="VD: Hủy trước 7 ngày: Hoàn 100%, Hủy trước 3 ngày: Hoàn 50%..."><?php echo htmlspecialchars($tour['dieu_kien_huy'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Bao gồm</label>
                                <textarea name="bao_gom" class="form-control" rows="3" 
                                          placeholder="VD: Vé tham quan, Khách sạn 4*, Bữa ăn..."><?php echo htmlspecialchars($tour['bao_gom'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Không bao gồm</label>
                                <textarea name="khong_bao_gom" class="form-control" rows="3" 
                                          placeholder="VD: Chi phí cá nhân, Bảo hiểm..."><?php echo htmlspecialchars($tour['khong_bao_gom'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Lưu ý</label>
                                <textarea name="luu_y" class="form-control" rows="3" 
                                          placeholder="VD: Mang theo CMND/CCCD, Chuẩn bị đồ bơi..."><?php echo htmlspecialchars($tour['luu_y'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Hình ảnh -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-images"></i> Hình ảnh tour
                        </div>
                        
                        <div class="mb-3">
                            <label class="custom-file-upload">
                                <i class="bi bi-cloud-upload fs-1 text-primary d-block mb-2"></i>
                                <span class="d-block fw-semibold">Click để chọn ảnh</span>
                                <span class="d-block small text-muted">Hỗ trợ: JPG, PNG, GIF (Max: 5MB)</span>
                                <input type="file" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                            </label>
                        </div>
                        
                        <div id="imagePreview" class="text-center"></div>
                    </div>

                    <!-- Thống kê (chỉ hiện khi sửa) -->
                    <?php if ($isCapNhat): ?>
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-bar-chart"></i> Thống kê
                        </div>
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="bi bi-hash text-muted"></i> Mã tour</span>
                                <span class="badge bg-primary">#<?php echo $tour['tour_id']; ?></span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="bi bi-calendar text-muted"></i> Ngày tạo</span>
                                <small class="text-muted">
                                    <?php echo isset($tour['ngay_tao']) ? date('d/m/Y', strtotime($tour['ngay_tao'])) : 'N/A'; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Gợi ý -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-lightbulb"></i> Gợi ý</h6>
                        <ul class="mb-0 ps-3 small">
                            <li>Tên tour nên ngắn gọn, rõ ràng</li>
                            <li>Mô tả hấp dẫn để thu hút khách</li>
                            <li>Điền đầy đủ thông tin lộ trình</li>
                            <li>Giá cả minh bạch, rõ ràng</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="btn-action-group">
                <div class="container-fluid px-0">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> <?php echo $isCapNhat ? 'Cập nhật tour' : 'Tạo tour mới'; ?>
                        </button>
                        <button type="reset" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                        </button>
                        <a href="<?php echo BASE_URL; ?>index.php?act=admin/quanLyTour" class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-x-circle"></i> Hủy bỏ
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" class="image-preview img-fluid">';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
