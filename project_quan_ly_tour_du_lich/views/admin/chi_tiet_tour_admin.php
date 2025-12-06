<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Tour - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .tour-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
        .info-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: transform 0.2s;
        }
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            font-size: 1.1rem;
            color: #212529;
            margin-top: 0.25rem;
        }
        .nav-pills .nav-link {
            color: #495057;
            font-weight: 500;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .timeline-item {
            position: relative;
            padding-left: 2.5rem;
            padding-bottom: 1.5rem;
            border-left: 2px solid #e9ecef;
        }
        .timeline-item:last-child {
            border-left: none;
        }
        .timeline-badge {
            position: absolute;
            left: -0.75rem;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .schedule-card {
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }
        .schedule-card:hover {
            border-left-color: #764ba2;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.2);
        }
        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        .image-gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            aspect-ratio: 4/3;
        }
        .image-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .image-gallery-item:hover img {
            transform: scale(1.1);
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .price-tag {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
        }
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 pb-5">
        <!-- Header -->
        <div class="tour-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark">#{<?php echo $tour['tour_id']; ?>}</span>
                            <span class="badge <?php echo $tour['trang_thai'] === 'HoatDong' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $tour['trang_thai'] === 'HoatDong' ? 'Hoạt động' : 'Ngừng hoạt động'; ?>
                            </span>
                            <span class="badge bg-info">
                                <?php 
                                $loaiTour = [
                                    'TrongNuoc' => 'Trong nước',
                                    'QuocTe' => 'Quốc tế',
                                    'TheoYeuCau' => 'Theo yêu cầu'
                                ];
                                echo $loaiTour[$tour['loai_tour']] ?? $tour['loai_tour'];
                                ?>
                            </span>
                        </div>
                        <h1 class="display-5 fw-bold mb-2"><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h1>
                        <p class="lead mb-0 opacity-75">
                            <i class="bi bi-clock"></i> <?php echo htmlspecialchars($tour['thoi_gian'] ?? 'N/A'); ?>
                            <span class="mx-2">•</span>
                            <i class="bi bi-pin-map"></i> <?php echo htmlspecialchars($tour['diem_khoi_hanh'] ?? 'N/A'); ?>
                            <i class="bi bi-arrow-right mx-2"></i>
                            <i class="bi bi-flag"></i> <?php echo htmlspecialchars($tour['diem_den'] ?? 'N/A'); ?>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?act=admin/quanLyTour" class="btn btn-light">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                        <a href="index.php?act=tour/update&id=<?php echo $tour['tour_id']; ?>" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Sửa tour
                        </a>
                    </div>
                </div>
            </div>
        </div>

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

        <?php 
            $tourBookingLink = rtrim(BASE_URL, '/') . '/index.php?act=tour/show&id=' . $tour['tour_id'];
            $tourQrPath = !empty($tour['qr_code_path']) ? BASE_URL . $tour['qr_code_path'] : null;
        ?>

        <div class="row">
            <!-- Left Column: Info Cards -->
            <div class="col-lg-4 mb-4">
                <!-- Price Card -->
                <div class="card info-card mb-3">
                    <div class="card-body text-center">
                        <div class="info-label">Giá cơ bản</div>
                        <div class="price-tag"><?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?> VNĐ</div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card info-card mb-3">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">Thông tin nhanh</h6>
                        <div class="mb-3">
                            <div class="info-label"><i class="bi bi-calendar-event"></i> Ngày tạo</div>
                            <div class="info-value">
                                <?php echo isset($tour['ngay_tao']) ? date('d/m/Y', strtotime($tour['ngay_tao'])) : 'N/A'; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="info-label"><i class="bi bi-truck"></i> Phương tiện</div>
                            <div class="info-value">
                                <?php 
                                $phuongTien = [
                                    'Xe' => 'Xe ô tô',
                                    'MayBay' => 'Máy bay',
                                    'Tau' => 'Tàu hỏa',
                                    'Khac' => 'Khác'
                                ];
                                $ptValue = $tour['phuong_tien'] ?? '';
                                echo !empty($ptValue) ? ($phuongTien[$ptValue] ?? $ptValue) : 'Chưa xác định';
                                ?>
                            </div>
                        </div>
                        <div>
                            <div class="info-label"><i class="bi bi-people"></i> Số chỗ tối đa</div>
                            <div class="info-value"><?php echo htmlspecialchars($tour['so_cho_toi_da'] ?? 'Không giới hạn'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- QR & booking link -->
                <div class="card info-card mb-3">
                    <div class="card-body text-center">
                        <h6 class="card-title fw-bold mb-3">
                            <i class="bi bi-qr-code"></i> Đặt tour online
                        </h6>
                        <?php if ($tourQrPath): ?>
                            <img src="<?php echo $tourQrPath; ?>" alt="QR <?php echo htmlspecialchars($tour['ten_tour']); ?>" class="img-fluid mb-3" style="max-width: 260px;">
                            <div class="d-grid gap-2">
                                <a href="<?php echo $tourQrPath; ?>" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-download"></i> Tải mã QR
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-muted mb-3">Chưa có mã QR cho tour này.</p>
                        <?php endif; ?>
                        <a href="index.php?act=tour/generateQr&id=<?php echo $tour['tour_id']; ?>" class="btn btn-primary btn-sm mt-2">
                            <i class="bi bi-arrow-repeat"></i> Tạo/Cập nhật mã QR
                        </a>
                        <div class="mt-3 text-start">
                            <label class="info-label mb-1">Link đặt tour</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="shareLinkInput" readonly value="<?php echo htmlspecialchars($tourBookingLink); ?>">
                                <button class="btn btn-outline-primary" type="button" onclick="copyShareLink('<?php echo htmlspecialchars($tourBookingLink); ?>')">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Image -->
                <?php if (!empty($tour['hinh_anh'])): ?>
                <div class="card info-card">
                    <img src="<?php echo htmlspecialchars($tour['hinh_anh']); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>"
                         style="height: 250px; object-fit: cover;">
                </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Tabs Content -->
            <div class="col-lg-8">
                <!-- Nav Tabs -->
                <ul class="nav nav-pills mb-4" id="tourTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="pill" data-bs-target="#info" type="button">
                            <i class="bi bi-info-circle"></i> Thông tin
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="itinerary-tab" data-bs-toggle="pill" data-bs-target="#itinerary" type="button">
                            <i class="bi bi-map"></i> Lịch trình
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="schedule-tab" data-bs-toggle="pill" data-bs-target="#schedule" type="button">
                            <i class="bi bi-calendar-check"></i> Lịch khởi hành
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="images-tab" data-bs-toggle="pill" data-bs-target="#images" type="button">
                            <i class="bi bi-images"></i> Hình ảnh
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="tourTabsContent">
                    <!-- Tab: Thông tin -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <!-- Mô tả -->
                                <?php if (!empty($tour['mo_ta'])): ?>
                                <div class="mb-4">
                                    <h6 class="section-title">
                                        <i class="bi bi-file-text text-primary"></i> Mô tả tour
                                    </h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($tour['mo_ta'])); ?></p>
                                </div>
                                <?php endif; ?>

                                <!-- Bao gồm -->
                                <?php if (!empty($tour['bao_gom'])): ?>
                                <div class="mb-4">
                                    <h6 class="section-title">
                                        <i class="bi bi-check-circle text-success"></i> Bao gồm
                                    </h6>
                                    <div class="text-muted"><?php echo nl2br(htmlspecialchars($tour['bao_gom'])); ?></div>
                                </div>
                                <?php endif; ?>

                                <!-- Không bao gồm -->
                                <?php if (!empty($tour['khong_bao_gom'])): ?>
                                <div class="mb-4">
                                    <h6 class="section-title">
                                        <i class="bi bi-x-circle text-danger"></i> Không bao gồm
                                    </h6>
                                    <div class="text-muted"><?php echo nl2br(htmlspecialchars($tour['khong_bao_gom'])); ?></div>
                                </div>
                                <?php endif; ?>

                                <!-- Điều kiện hủy -->
                                <?php if (!empty($tour['dieu_kien_huy'])): ?>
                                <div class="mb-4">
                                    <h6 class="section-title">
                                        <i class="bi bi-exclamation-triangle text-warning"></i> Điều kiện hủy
                                    </h6>
                                    <div class="text-muted"><?php echo nl2br(htmlspecialchars($tour['dieu_kien_huy'])); ?></div>
                                </div>
                                <?php endif; ?>

                                <!-- Lưu ý -->
                                <?php if (!empty($tour['luu_y'])): ?>
                                <div class="mb-4">
                                    <h6 class="section-title">
                                        <i class="bi bi-lightbulb text-info"></i> Lưu ý
                                    </h6>
                                    <div class="text-muted"><?php echo nl2br(htmlspecialchars($tour['luu_y'])); ?></div>
                                </div>
                                <?php endif; ?>

                                <!-- Chính sách -->
                                <?php if (!empty($tour['chinh_sach'])): ?>
                                <div>
                                    <h6 class="section-title">
                                        <i class="bi bi-shield-check text-primary"></i> Chính sách
                                    </h6>
                                    <div class="text-muted"><?php echo nl2br(htmlspecialchars($tour['chinh_sach'])); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Lịch trình -->
                    <div class="tab-pane fade" id="itinerary" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="section-title">
                                    <i class="bi bi-map text-primary"></i> Lịch trình chi tiết
                                </h6>
                                
                                <?php if (!empty($lichTrinhList)): ?>
                                    <div class="timeline">
                                        <?php foreach ($lichTrinhList as $index => $lt): ?>
                                            <div class="timeline-item">
                                                <div class="timeline-badge">
                                                    <?php if ($lt['ngay_thu'] == 0): ?>
                                                        📍
                                                    <?php else: ?>
                                                        <?php echo $lt['ngay_thu']; ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <h6 class="fw-bold text-primary mb-2">
                                                            <?php if ($lt['ngay_thu'] == 0): ?>
                                                                ✈️ Điểm tập trung: <?php echo htmlspecialchars($lt['dia_diem']); ?>
                                                            <?php else: ?>
                                                                🗓️ NGÀY <?php echo $lt['ngay_thu']; ?>: <?php echo htmlspecialchars($lt['dia_diem']); ?>
                                                            <?php endif; ?>
                                                        </h6>
                                                        <div class="text-muted" style="white-space: pre-line; line-height: 1.8;">
                                                            <?php echo htmlspecialchars($lt['hoat_dong']); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-calendar-x"></i>
                                        <p>Chưa có lịch trình nào được thêm</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Lịch khởi hành -->
                    <div class="tab-pane fade" id="schedule" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="section-title mb-0">
                                        <i class="bi bi-calendar-check text-primary"></i> Lịch khởi hành
                                    </h6>
                                    <a href="index.php?act=tour/taoLichKhoiHanh&tour_id=<?php echo $tour['tour_id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle"></i> Tạo mới
                                    </a>
                                </div>
                                
                                <?php if (!empty($lichKhoiHanhList)): ?>
                                    <div class="row g-3">
                                        <?php foreach ($lichKhoiHanhList as $lk): ?>
                                            <div class="col-md-6">
                                                <div class="card schedule-card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <span class="badge status-badge <?php 
                                                                echo match($lk['trang_thai']) {
                                                                    'SapKhoiHanh' => 'bg-info',
                                                                    'DangChay' => 'bg-success',
                                                                    'HoanThanh' => 'bg-secondary',
                                                                    default => 'bg-secondary'
                                                                };
                                                            ?>">
                                                                <?php
                                                                $statusLabels = [
                                                                    'SapKhoiHanh' => 'Sắp khởi hành',
                                                                    'DangChay' => 'Đang chạy',
                                                                    'HoanThanh' => 'Hoàn thành'
                                                                ];
                                                                echo $statusLabels[$lk['trang_thai']] ?? $lk['trang_thai'];
                                                                ?>
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="mb-2">
                                                            <i class="bi bi-calendar-event text-primary"></i>
                                                            <strong>Khởi hành:</strong>
                                                            <?php echo $lk['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lk['ngay_khoi_hanh'])) : 'N/A'; ?>
                                                            <span class="text-muted">• <?php echo $lk['gio_xuat_phat'] ?? 'N/A'; ?></span>
                                                        </div>
                                                        
                                                        <div class="mb-2">
                                                            <i class="bi bi-calendar-check text-success"></i>
                                                            <strong>Kết thúc:</strong>
                                                            <?php echo $lk['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lk['ngay_ket_thuc'])) : 'N/A'; ?>
                                                            <span class="text-muted">• <?php echo $lk['gio_ket_thuc'] ?? 'N/A'; ?></span>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <i class="bi bi-geo-alt text-danger"></i>
                                                            <strong>Điểm tập trung:</strong>
                                                            <?php echo htmlspecialchars($lk['diem_tap_trung'] ?? 'N/A'); ?>
                                                        </div>
                                                        
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-muted">
                                                                <i class="bi bi-people"></i> <?php echo $lk['so_cho'] ?? 50; ?> chỗ
                                                            </span>
                                                            <a href="index.php?act=tour/chiTietLichKhoiHanh&id=<?php echo $lk['id']; ?>&tour_id=<?php echo $tour['tour_id']; ?>" 
                                                               class="btn btn-outline-primary btn-sm">
                                                                <i class="bi bi-eye"></i> Chi tiết
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-calendar-x"></i>
                                        <p>Chưa có lịch khởi hành nào</p>
                                        <a href="index.php?act=tour/taoLichKhoiHanh&tour_id=<?php echo $tour['tour_id']; ?>" 
                                           class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Tạo lịch khởi hành đầu tiên
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Hình ảnh -->
                    <div class="tab-pane fade" id="images" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="section-title">
                                    <i class="bi bi-images text-primary"></i> Thư viện hình ảnh
                                </h6>
                                
                                <?php if (!empty($hinhAnhList)): ?>
                                    <div class="image-gallery">
                                        <?php foreach ($hinhAnhList as $anh): ?>
                                            <div class="image-gallery-item">
                                                <img src="<?php echo htmlspecialchars($anh['url_anh']); ?>" 
                                                     alt="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>"
                                                     class="img-fluid">
                                                <?php if (!empty($anh['mo_ta'])): ?>
                                                    <div class="p-2 bg-white">
                                                        <small class="text-muted"><?php echo htmlspecialchars($anh['mo_ta']); ?></small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="bi bi-image"></i>
                                        <p>Chưa có hình ảnh nào</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyShareLink(link) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(link)
                    .then(() => alert('Đã sao chép link đặt tour.'))
                    .catch(() => alert('Không thể sao chép link.'));
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = link;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Đã sao chép link đặt tour.');
            }
        }
    </script>
</body>
</html>
