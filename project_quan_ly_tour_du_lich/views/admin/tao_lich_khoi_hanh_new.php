<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Lịch Khởi Hành - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
        }
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
            color: #667eea;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        .required-label::after {
            content: " *";
            color: #dc3545;
        }
        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px dashed #dee2e6;
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
                        <a class="nav-link active" href="index.php?act=admin/quanLyLichKhoiHanh">
                            <i class="bi bi-calendar-check"></i> Lịch khởi hành
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="bi bi-plus-circle"></i> Tạo Lịch Khởi Hành Mới
                        </h1>
                        <p class="lead mb-0 opacity-75">Thêm lịch khởi hành mới cho tour</p>
                    </div>
                    <a href="index.php?act=lichKhoiHanh/index" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-lg-8">
                <form method="POST" action="index.php?act=lichKhoiHanh/create">
                    <!-- Tour Selection -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-compass"></i> Chọn Tour
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required-label fw-semibold">Tour</label>
                            <select name="tour_id" class="form-select form-select-lg" required>
                                <option value="">-- Chọn tour --</option>
                                <?php foreach ($tours as $tour): ?>
                                    <option value="<?php echo $tour['tour_id']; ?>">
                                        <?php echo htmlspecialchars($tour['ten_tour']); ?>
                                        <?php if (isset($tour['gia_co_ban'])): ?>
                                            - <?php echo number_format($tour['gia_co_ban'], 0, ',', '.'); ?> VNĐ
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Schedule Details -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-calendar-range"></i> Thông tin lịch trình
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required-label fw-semibold">
                                    <i class="bi bi-calendar-event text-primary"></i> Ngày khởi hành
                                </label>
                                <input type="date" name="ngay_khoi_hanh" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock text-info"></i> Giờ xuất phát
                                </label>
                                <input type="time" name="gio_xuat_phat" class="form-control" value="07:00">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check text-success"></i> Ngày kết thúc
                                </label>
                                <input type="date" name="ngay_ket_thuc" class="form-control">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-clock text-warning"></i> Giờ kết thúc
                                </label>
                                <input type="time" name="gio_ket_thuc" class="form-control" value="17:00">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt text-danger"></i> Điểm tập trung
                                </label>
                                <input type="text" name="diem_tap_trung" class="form-control" 
                                       placeholder="VD: Số 54 Trần Đại Nghĩa, Hai Bà Trưng, Hà Nội">
                            </div>
                        </div>
                    </div>

                    <!-- Capacity & Staff -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-people"></i> Sức chứa & Nhân sự
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label required-label fw-semibold">
                                    <i class="bi bi-people-fill text-primary"></i> Số chỗ
                                </label>
                                <input type="number" name="so_cho" class="form-control" value="50" min="1" required>
                                <small class="text-muted">Số lượng khách tối đa cho lịch khởi hành này</small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-badge text-success"></i> Hướng dẫn viên chính
                                </label>
                                <select name="hdv_id" class="form-select">
                                    <option value="">-- Chọn HDV --</option>
                                    <?php
                                    require_once 'models/NhanSu.php';
                                    $nhanSuModel = new NhanSu();
                                    $hdvList = $nhanSuModel->getByRole('HDV');
                                    foreach ($hdvList as $hdv):
                                    ?>
                                        <option value="<?php echo $hdv['nhan_su_id']; ?>">
                                            <?php echo htmlspecialchars($hdv['ho_ten'] ?? 'N/A'); ?>
                                            <?php if (isset($hdv['so_dien_thoai'])): ?>
                                                - <?php echo htmlspecialchars($hdv['so_dien_thoai']); ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Notes -->
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="bi bi-info-circle"></i> Trạng thái & Ghi chú
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on text-primary"></i> Trạng thái
                                </label>
                                <select name="trang_thai" class="form-select">
                                    <option value="SapKhoiHanh" selected>Sắp khởi hành</option>
                                    <option value="DangChay">Đang chạy</option>
                                    <option value="HoanThanh">Hoàn thành</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-chat-left-text text-info"></i> Ghi chú
                                </label>
                                <textarea name="ghi_chu" class="form-control" rows="3" 
                                          placeholder="Thêm ghi chú nếu cần..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="btn-action-group">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Tạo lịch khởi hành
                            </button>
                            <button type="reset" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-counterclockwise"></i> Đặt lại
                            </button>
                            <a href="index.php?act=lichKhoiHanh/index" class="btn btn-outline-danger btn-lg">
                                <i class="bi bi-x-circle"></i> Hủy bỏ
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Guide -->
            <div class="col-lg-4">
                <!-- Guide Card -->
                <div class="info-card">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-lightbulb text-warning"></i> Hướng dẫn
                    </h6>
                    <ul class="mb-0 ps-3 small">
                        <li class="mb-2">Chọn tour từ danh sách có sẵn</li>
                        <li class="mb-2">Nhập ngày giờ khởi hành và kết thúc chính xác</li>
                        <li class="mb-2">Điểm tập trung nên ghi rõ địa chỉ cụ thể</li>
                        <li class="mb-2">Số chỗ mặc định là 50, có thể điều chỉnh</li>
                        <li class="mb-2">HDV có thể chọn ngay hoặc phân bổ sau</li>
                        <li class="mb-2">Trạng thái mặc định là "Sắp khởi hành"</li>
                    </ul>
                </div>

                <!-- Status Info -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-info-circle text-primary"></i> Trạng thái
                        </h6>
                        <div class="mb-2">
                            <span class="badge bg-info me-2">Sắp khởi hành</span>
                            <small class="text-muted">Chưa bắt đầu tour</small>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success me-2">Đang chạy</span>
                            <small class="text-muted">Tour đang diễn ra</small>
                        </div>
                        <div>
                            <span class="badge bg-secondary me-2">Hoàn thành</span>
                            <small class="text-muted">Tour đã kết thúc</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-stars text-warning"></i> Mẹo nhanh
                        </h6>
                        <div class="alert alert-info mb-2 small">
                            <i class="bi bi-info-circle"></i>
                            Ngày kết thúc nên sau ngày khởi hành
                        </div>
                        <div class="alert alert-warning mb-0 small">
                            <i class="bi bi-exclamation-triangle"></i>
                            Kiểm tra lại thông tin trước khi tạo
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-fill end date based on tour duration (optional enhancement)
        document.querySelector('select[name="tour_id"]').addEventListener('change', function() {
            // Could fetch tour duration and auto-calculate end date
        });
        
        // Validate dates
        const startDate = document.querySelector('input[name="ngay_khoi_hanh"]');
        const endDate = document.querySelector('input[name="ngay_ket_thuc"]');
        
        startDate.addEventListener('change', function() {
            endDate.min = this.value;
        });
    </script>
</body>
</html>
