<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($entry) ? 'Sửa' : 'Thêm'; ?> Nhật ký Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/quanLyTour">
                            <i class="bi bi-compass"></i> Tour
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?act=admin/quanLyNhatKyTour">
                            <i class="bi bi-journal-text"></i> Nhật ký Tour
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-journal-plus"></i> 
                            <?php echo isset($entry) ? 'Sửa' : 'Thêm'; ?> Nhật ký Tour
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Alerts -->
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="index.php?act=admin/saveNhatKyTour" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo $entry['id'] ?? ''; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tour <span class="text-danger">*</span></label>
                                    <select class="form-select" name="tour_id" required>
                                        <option value="">-- Chọn tour --</option>
                                        <?php foreach($tours as $tour): ?>
                                        <option value="<?php echo $tour['tour_id']; ?>" 
                                                <?php echo (isset($entry) && $entry['tour_id'] == $tour['tour_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tour['ten_tour']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">HDV <span class="text-danger">*</span></label>
                                    <select class="form-select" name="nhan_su_id" required>
                                        <option value="">-- Chọn HDV --</option>
                                        <?php foreach($hdvList as $hdv): ?>
                                        <option value="<?php echo $hdv['nhan_su_id']; ?>" 
                                                <?php echo (isset($entry) && $entry['nhan_su_id'] == $hdv['nhan_su_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($hdv['ho_ten'] ?? 'N/A'); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loại nhật ký <span class="text-danger">*</span></label>
                                    <select class="form-select" name="loai_nhat_ky" id="loai_nhat_ky" required>
                                        <option value="hanh_trinh" <?php echo (isset($entry) && $entry['loai_nhat_ky']=='hanh_trinh')?'selected':''; ?>>📍 Hành trình</option>
                                        <option value="su_co" <?php echo (isset($entry) && $entry['loai_nhat_ky']=='su_co')?'selected':''; ?>>⚠️ Sự cố</option>
                                        <option value="phan_hoi" <?php echo (isset($entry) && $entry['loai_nhat_ky']=='phan_hoi')?'selected':''; ?>>💬 Phản hồi khách</option>
                                        <option value="hoat_dong" <?php echo (isset($entry) && $entry['loai_nhat_ky']=='hoat_dong')?'selected':''; ?>>🎯 Hoạt động nổi bật</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày ghi <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="ngay_ghi" 
                                           value="<?php echo isset($entry) ? date('Y-m-d', strtotime($entry['ngay_ghi'])) : date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="tieu_de" 
                                       value="<?php echo isset($entry['tieu_de']) ? htmlspecialchars($entry['tieu_de']) : ''; ?>"
                                       placeholder="VD: Tham quan Vịnh Hạ Long" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="noi_dung" rows="5" 
                                          placeholder="Mô tả chi tiết sự kiện, hoạt động, phản hồi của khách..." required><?php echo isset($entry['noi_dung']) ? htmlspecialchars($entry['noi_dung']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-3" id="cach_xu_ly_group" style="display: <?php echo (isset($entry) && $entry['loai_nhat_ky']=='su_co')?'block':'none'; ?>;">
                                <label class="form-label">Cách xử lý (chỉ cho sự cố)</label>
                                <textarea class="form-control" name="cach_xu_ly" rows="3" 
                                          placeholder="Mô tả cách xử lý sự cố..."><?php echo isset($entry) ? htmlspecialchars($entry['cach_xu_ly'] ?? '') : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh (tối đa 5 ảnh)</label>
                                <input type="file" class="form-control" name="hinh_anh[]" 
                                       accept="image/*" multiple id="imageInput">
                                <small class="text-muted">Chọn nhiều ảnh bằng Ctrl + Click</small>
                                <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                                
                                <?php if (isset($entry) && !empty($entry['hinh_anh'])): ?>
                                    <?php 
                                    $images = json_decode($entry['hinh_anh'], true);
                                    if ($images && is_array($images) && !empty($images)):
                                    ?>
                                        <div class="mt-3">
                                            <label class="form-label">Hình ảnh hiện tại:</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                <?php foreach ($images as $img): ?>
                                                    <img src="<?php echo BASE_URL . $img; ?>" alt="Hình ảnh" 
                                                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem; cursor: pointer;"
                                                         onclick="window.open('<?php echo BASE_URL . $img; ?>', '_blank')">
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php?act=admin/quanLyNhatKyTour" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Lưu nhật ký
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide cách xử lý field
        document.getElementById('loai_nhat_ky').addEventListener('change', function() {
            var cachXuLyGroup = document.getElementById('cach_xu_ly_group');
            if (this.value === 'su_co') {
                cachXuLyGroup.style.display = 'block';
            } else {
                cachXuLyGroup.style.display = 'none';
            }
        });
        
        // Image preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            var preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            
            var files = Array.from(e.target.files).slice(0, 5);
            files.forEach(function(file) {
                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '100px';
                        img.style.height = '100px';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '0.5rem';
                        img.style.margin = '0.25rem';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
</body>
</html>
