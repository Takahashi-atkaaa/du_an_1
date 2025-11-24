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
        .lich-trinh-item {
            border: 1px solid #dee2e6;
            transition: all 0.3s;
        }
        .lich-trinh-item:hover {
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1);
            border-color: #0d6efd;
        }
        .lich-trinh-item .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .lich-trinh-item .card-header .btn-danger {
            background: rgba(220, 53, 69, 0.9);
            border: none;
        }
        .lich-trinh-item .card-header .btn-danger:hover {
            background: #dc3545;
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
                                    <option value="TamDung" <?php echo $tt === 'TamDung' ? 'selected' : ''; ?>>
                                        Tạm dừng
                                    </option>
                                    <option value="HetHan" <?php echo $tt === 'HetHan' ? 'selected' : ''; ?>>
                                        Hết hạn
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

                    <!-- Lịch trình chi tiết -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-calendar-week"></i> Lịch trình chi tiết
                        </div>
                        
                        <div id="lichTrinhContainer">
                            <?php if (!empty($lichTrinhList)): ?>
                                <?php foreach ($lichTrinhList as $idx => $lt): ?>
                                    <div class="lich-trinh-item card mb-3" data-index="<?php echo $idx; ?>">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">
                                                <i class="bi bi-calendar-day text-primary"></i> 
                                                Ngày <?php echo $lt['ngay_thu'] ?? ($idx + 1); ?>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="xoaLichTrinh(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="lich_trinh[<?php echo $idx; ?>][ngay_thu]" value="<?php echo $lt['ngay_thu'] ?? ($idx + 1); ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label small">Địa điểm</label>
                                                <input type="text" name="lich_trinh[<?php echo $idx; ?>][dia_diem]" 
                                                       class="form-control" placeholder="VD: Vịnh Hạ Long, Đảo Titop" 
                                                       value="<?php echo htmlspecialchars($lt['dia_diem'] ?? ''); ?>" required>
                                            </div>
                                            
                                            <div class="mb-0">
                                                <label class="form-label small">Hoạt động</label>
                                                <textarea name="lich_trinh[<?php echo $idx; ?>][hoat_dong]" 
                                                          class="form-control" rows="3" 
                                                          placeholder="Mô tả hoạt động trong ngày..."><?php echo htmlspecialchars($lt['hoat_dong'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <button type="button" class="btn btn-outline-primary w-100" onclick="themLichTrinh()">
                            <i class="bi bi-plus-circle"></i> Thêm ngày mới
                        </button>
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
                    
                    <div id="hinhAnhContainer">
                        <?php if (!empty($hinhAnhList)): ?>
                            <?php foreach ($hinhAnhList as $idx => $anh): ?>
                                <div class="image-item mb-3" data-index="<?php echo $idx; ?>">
                                    <input type="hidden" name="hinh_anh[<?php echo $idx; ?>][url_anh]" value="<?php echo htmlspecialchars($anh['url_anh'] ?? ''); ?>">
                                    <input type="hidden" name="hinh_anh[<?php echo $idx; ?>][mo_ta]" value="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>">
                                    <input type="hidden" name="hinh_anh[<?php echo $idx; ?>][la_anh_chinh]" value="<?php echo $anh['la_anh_chinh'] ?? 0; ?>">
                                    <?php if (!empty($anh['url_anh'])): ?>
                                        <img src="<?php echo htmlspecialchars($anh['url_anh']); ?>" class="img-thumbnail mb-2" style="max-height: 150px;">
                                    <?php endif; ?>
                                    <input type="file" name="hinh_anh_file[]" class="form-control form-control-sm mb-1" accept="image/*">
                                    <input type="text" name="hinh_anh[<?php echo $idx; ?>][mo_ta]" class="form-control form-control-sm mb-1" placeholder="Mô tả ảnh" value="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>">
                                    <div class="form-check">
                                        <input type="checkbox" name="hinh_anh[<?php echo $idx; ?>][la_anh_chinh]" value="1" class="form-check-input" <?php echo ($anh['la_anh_chinh'] ?? 0) ? 'checked' : ''; ?>>
                                        <label class="form-check-label small">Ảnh chính</label>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="xoaHinhAnh(this)">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                    <hr>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary w-100" onclick="themHinhAnh()">
                        <i class="bi bi-plus-circle"></i> Thêm ảnh
                    </button>
                </div>                    <!-- Thống kê (chỉ hiện khi sửa) -->
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
        let hinhAnhIndex = <?php echo !empty($hinhAnhList) ? count($hinhAnhList) : 0; ?>;
        
        function themHinhAnh() {
            const container = document.getElementById('hinhAnhContainer');
            const div = document.createElement('div');
            div.className = 'image-item mb-3';
            div.dataset.index = hinhAnhIndex;
            div.innerHTML = `
                <input type="hidden" name="hinh_anh[${hinhAnhIndex}][url_anh]" value="">
                <input type="file" name="hinh_anh_file[]" class="form-control form-control-sm mb-1" accept="image/*" required>
                <input type="text" name="hinh_anh[${hinhAnhIndex}][mo_ta]" class="form-control form-control-sm mb-1" placeholder="Mô tả ảnh">
                <div class="form-check">
                    <input type="checkbox" name="hinh_anh[${hinhAnhIndex}][la_anh_chinh]" value="1" class="form-check-input">
                    <label class="form-check-label small">Ảnh chính</label>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-1" onclick="xoaHinhAnh(this)">
                    <i class="bi bi-trash"></i> Xóa
                </button>
                <hr>
            `;
            container.appendChild(div);
            hinhAnhIndex++;
        }
        
        function xoaHinhAnh(btn) {
            btn.closest('.image-item').remove();
        }
        
        // Auto add first image input if empty
        if (hinhAnhIndex === 0) {
            themHinhAnh();
        }
        
        // ===== LỊCH TRÌNH CHI TIẾT =====
        let lichTrinhIndex = <?php echo !empty($lichTrinhList) ? count($lichTrinhList) : 0; ?>;
        
        function themLichTrinh() {
            lichTrinhIndex++;
            const container = document.getElementById('lichTrinhContainer');
            const div = document.createElement('div');
            div.className = 'lich-trinh-item card mb-3';
            div.dataset.index = lichTrinhIndex;
            div.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">
                        <i class="bi bi-calendar-day text-primary"></i> 
                        Ngày ${lichTrinhIndex + 1}
                    </span>
                    <button type="button" class="btn btn-sm btn-danger" onclick="xoaLichTrinh(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="lich_trinh[${lichTrinhIndex}][ngay_thu]" value="${lichTrinhIndex + 1}">
                    
                    <div class="mb-3">
                        <label class="form-label small">Địa điểm</label>
                        <input type="text" name="lich_trinh[${lichTrinhIndex}][dia_diem]" 
                               class="form-control" placeholder="VD: Vịnh Hạ Long, Đảo Titop" required>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label small">Hoạt động</label>
                        <textarea name="lich_trinh[${lichTrinhIndex}][hoat_dong]" 
                                  class="form-control" rows="3" 
                                  placeholder="Mô tả hoạt động trong ngày..."></textarea>
                    </div>
                </div>
            `;
            container.appendChild(div);
            capNhatSoNgay();
        }
        
        function xoaLichTrinh(btn) {
            if (confirm('Bạn có chắc muốn xóa ngày này?')) {
                btn.closest('.lich-trinh-item').remove();
                capNhatSoNgay();
            }
        }
        
        function capNhatSoNgay() {
            const items = document.querySelectorAll('.lich-trinh-item');
            items.forEach((item, idx) => {
                const headerText = item.querySelector('.card-header span');
                headerText.innerHTML = `<i class="bi bi-calendar-day text-primary"></i> Ngày ${idx + 1}`;
                const hiddenInput = item.querySelector('input[type="hidden"]');
                if (hiddenInput) {
                    hiddenInput.value = idx + 1;
                }
            });
        }
        
        // Auto add first day if empty
        if (lichTrinhIndex === 0) {
            themLichTrinh();
        }
    </script>
</body>
</html>
