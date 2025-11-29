<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Tour Cho Khách</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
            flex: 1;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 3px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }
        .step.active .step-circle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }
        .step-label {
            font-size: 0.875rem;
            color: #6c757d;
            text-align: center;
        }
        .step.active .step-label {
            color: #667eea;
            font-weight: 600;
        }
        .form-section {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .form-section-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #667eea;
        }
        .form-section-header .icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }
        .form-section-header h3 {
            margin: 0;
            color: #212529;
            font-weight: 600;
        }
        .availability-status {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
            display: none;
        }
        .availability-status.success {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
        }
        .availability-status.error {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f1aeb5;
        }
        .availability-status.loading {
            background: #cfe2ff;
            color: #084298;
            border: 1px solid #9ec5fe;
        }
        .customer-type-card {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .customer-type-card:hover {
            border-color: #667eea;
            box-shadow: 0 0.25rem 0.5rem rgba(102, 126, 234, 0.2);
        }
        .customer-type-card.active {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        }
        .customer-type-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .customer-type-card .title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .customer-type-card .description {
            font-size: 0.875rem;
            color: #6c757d;
        }
        .sidebar-info {
            position: sticky;
            top: 20px;
        }
        .tour-summary-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
        }
        .tour-summary-card .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: -1.5rem -1.5rem 1rem -1.5rem;
            font-weight: 600;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-label {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .summary-value {
            font-weight: 600;
            color: #212529;
        }
        .total-price {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }
        .total-price .label {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .total-price .amount {
            font-size: 1.75rem;
            font-weight: bold;
            color: #198754;
        }
        .quick-tips {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 0.5rem;
            padding: 1.5rem;
        }
        .quick-tips .title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .quick-tips ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #856404;
        }
        .quick-tips li {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        .submit-section {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1.5rem;
            border-top: 2px solid #e9ecef;
            margin: 2rem -2rem -2rem -2rem;
            border-radius: 0 0 0.5rem 0.5rem;
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
                        <a class="nav-link" href="index.php?act=admin/quanLyBooking">
                            <i class="bi bi-calendar-check"></i> Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-plus-circle"></i> Đặt tour
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
                        <h1 class="display-5 fw-bold mb-2">
                            <i class="bi bi-plus-circle-fill"></i> Đặt Tour Cho Khách Hàng
                        </h1>
                        <p class="lead mb-0 opacity-75">Tạo booking mới và quản lý thông tin đặt tour</p>
                    </div>
                    <div>
                        <a href="index.php?act=admin/quanLyBooking" class="btn btn-light btn-lg">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i>
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Chọn tour</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Thông tin khách</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Yêu cầu & Ghi chú</div>
            </div>
        </div>

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <form method="POST" action="index.php?act=booking/datTourChoKhach" id="datTourForm">
                    <!-- Step 1: Chọn Tour -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <h3>Bước 1: Chọn Tour & Lịch Trình</h3>
                                <small class="text-muted">Chọn tour và ngày khởi hành phù hợp</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-map text-primary"></i> Chọn tour
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="tour_id" id="tour_id" class="form-select form-select-lg" required>
                                    <option value="">-- Vui lòng chọn tour --</option>
                                    <?php foreach ($tours as $t): ?>
                                        <option value="<?php echo $t['tour_id']; ?>" 
                                            data-gia="<?php echo $t['gia_co_ban']; ?>"
                                            <?php echo (isset($formData['tour_id']) && $formData['tour_id'] == $t['tour_id']) || (isset($tour) && $tour['tour_id'] == $t['tour_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($t['ten_tour']); ?> - <?php echo number_format($t['gia_co_ban']); ?> ₫
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-success"></i> Ngày khởi hành
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="ngay_khoi_hanh" id="ngay_khoi_hanh" class="form-control form-control-lg"
                                    value="<?php echo $formData['ngay_khoi_hanh'] ?? ''; ?>" 
                                    min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check text-primary"></i> Ngày kết thúc
                                </label>
                                <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control form-control-lg"
                                    value="<?php echo $formData['ngay_ket_thuc'] ?? ''; ?>">
                                <small class="text-muted">Nếu bỏ trống, hệ thống sẽ dùng ngày khởi hành</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-people text-warning"></i> Số lượng người
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="so_nguoi" id="so_nguoi" class="form-control form-control-lg"
                                    value="<?php echo $formData['so_nguoi'] ?? '1'; ?>" 
                                    min="1" required>
                            </div>

                            <div class="col-12">
                                <div id="cho-trong-info" class="availability-status">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-hourglass-split me-2 fs-5"></i>
                                        <span id="status-text">Đang kiểm tra chỗ trống...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Thông tin Khách hàng -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="icon">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <h3>Bước 2: Thông Tin Khách Hàng</h3>
                                <small class="text-muted">Nhập thông tin liên hệ của khách hàng</small>
                            </div>
                        </div>

                        <!-- Loại khách -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">
                                <i class="bi bi-diagram-3 text-info"></i> Loại khách hàng
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" name="loai_khach" value="le" id="loai_le" class="d-none"
                                        <?php echo (!isset($formData['loai_khach']) || $formData['loai_khach'] == 'le') ? 'checked' : ''; ?>>
                                    <label for="loai_le" class="customer-type-card <?php echo (!isset($formData['loai_khach']) || $formData['loai_khach'] == 'le') ? 'active' : ''; ?>">
                                        <div class="icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div class="title">Khách lẻ</div>
                                        <div class="description">Cá nhân hoặc gia đình (1-2 người)</div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" name="loai_khach" value="doan" id="loai_doan" class="d-none"
                                        <?php echo (isset($formData['loai_khach']) && $formData['loai_khach'] == 'doan') ? 'checked' : ''; ?>>
                                    <label for="loai_doan" class="customer-type-card <?php echo (isset($formData['loai_khach']) && $formData['loai_khach'] == 'doan') ? 'active' : ''; ?>">
                                        <div class="icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="title">Đoàn khách</div>
                                        <div class="description">Công ty hoặc tổ chức (3+ người)</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Tên công ty (ẩn mặc định) -->
                        <div id="cong-ty-field" class="mb-3 <?php echo (isset($formData['loai_khach']) && $formData['loai_khach'] == 'doan') ? '' : 'd-none'; ?>">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building text-primary"></i> Tên công ty/Tổ chức
                            </label>
                            <input type="text" name="ten_cong_ty" id="ten_cong_ty" class="form-control"
                                value="<?php echo htmlspecialchars($formData['ten_cong_ty'] ?? ''); ?>"
                                placeholder="Nhập tên công ty hoặc tổ chức...">
                        </div>

                        <!-- Thông tin cá nhân -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-person-circle text-primary"></i> Họ và tên
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="ho_ten" id="ho_ten" class="form-control"
                                    value="<?php echo htmlspecialchars($formData['ho_ten'] ?? ''); ?>"
                                    placeholder="Nguyễn Văn A" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-gender-ambiguous text-secondary"></i> Giới tính
                                </label>
                                <select name="gioi_tinh" id="gioi_tinh" class="form-select">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="Nam" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="Nữ" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="Khác" <?php echo (isset($formData['gioi_tinh']) && $formData['gioi_tinh'] == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-envelope text-info"></i> Email
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                                    placeholder="email@example.com">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-phone text-success"></i> Số điện thoại
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="so_dien_thoai" id="so_dien_thoai" class="form-control"
                                    value="<?php echo htmlspecialchars($formData['so_dien_thoai'] ?? ''); ?>"
                                    placeholder="0987654321">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt text-danger"></i> Địa chỉ
                                </label>
                                <input type="text" name="dia_chi" id="dia_chi" class="form-control"
                                    value="<?php echo htmlspecialchars($formData['dia_chi'] ?? ''); ?>"
                                    placeholder="Số nhà, Đường, Quận, Thành phố">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar text-warning"></i> Ngày sinh
                                </label>
                                <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control"
                                    value="<?php echo $formData['ngay_sinh'] ?? ''; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Yêu cầu đặc biệt -->
                    <div class="form-section">
                        <div class="form-section-header">
                            <div class="icon">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                            <div>
                                <h3>Bước 3: Yêu Cầu Đặc Biệt & Ghi Chú</h3>
                                <small class="text-muted">Thêm yêu cầu đặc biệt hoặc ghi chú nếu có</small>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-star text-warning"></i> Yêu cầu đặc biệt
                                </label>
                                <textarea name="yeu_cau_dac_biet" id="yeu_cau_dac_biet" class="form-control" rows="4"
                                    placeholder="VD: Ăn chay, dị ứng thực phẩm, cần xe lăn, phòng riêng..."><?php echo htmlspecialchars($formData['yeu_cau_dac_biet'] ?? ''); ?></textarea>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> Thông tin này sẽ được gửi cho đội ngũ điều phối
                                </small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-pencil-square text-secondary"></i> Ghi chú nội bộ
                                </label>
                                <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="3"
                                    placeholder="Ghi chú dành cho nhân viên nội bộ..."><?php echo htmlspecialchars($formData['ghi_chu'] ?? ''); ?></textarea>
                                <small class="text-muted">
                                    <i class="bi bi-lock"></i> Ghi chú này chỉ hiển thị cho nhân viên
                                </small>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="submit-section">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        <i class="bi bi-shield-check"></i> Thông tin được mã hóa và bảo mật
                                    </small>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-check-circle"></i> Xác nhận đặt tour
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-info">
                    <!-- Tour Summary -->
                    <div class="tour-summary-card">
                        <div class="header">
                            <i class="bi bi-receipt"></i> Tóm tắt đặt tour
                        </div>
                        <div id="tour-summary">
                            <div class="summary-item">
                                <span class="summary-label">Tour:</span>
                                <span class="summary-value" id="summary-tour">Chưa chọn</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Ngày khởi hành:</span>
                                <span class="summary-value" id="summary-date">--/--/----</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Số người:</span>
                                <span class="summary-value" id="summary-people">0 người</span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Giá/người:</span>
                                <span class="summary-value" id="summary-price">0 ₫</span>
                            </div>
                            <div class="total-price">
                                <div class="label">Tổng cộng:</div>
                                <div class="amount" id="summary-total">0 ₫</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="quick-tips">
                        <div class="title">
                            <i class="bi bi-lightbulb-fill"></i> Lưu ý quan trọng
                        </div>
                        <ul>
                            <li>Email hoặc số điện thoại là bắt buộc</li>
                            <li>Kiểm tra kỹ thông tin trước khi xác nhận</li>
                            <li>Chỗ trống được cập nhật theo thời gian thực</li>
                            <li>Đối với đoàn, vui lòng nhập tên công ty/tổ chức</li>
                            <li>Yêu cầu đặc biệt giúp chúng tôi phục vụ tốt hơn</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Customer type selection
        document.querySelectorAll('input[name="loai_khach"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.customer-type-card').forEach(card => {
                    card.classList.remove('active');
                });
                this.parentElement.classList.add('active');
                
                const congTyField = document.getElementById('cong-ty-field');
                if (this.value === 'doan') {
                    congTyField.classList.remove('d-none');
                } else {
                    congTyField.classList.add('d-none');
                }
            });
        });

        // Update summary
        function updateSummary() {
            const tourSelect = document.getElementById('tour_id');
            const selectedOption = tourSelect.options[tourSelect.selectedIndex];
            const soNguoi = parseInt(document.getElementById('so_nguoi').value) || 0;
            const ngayKhoiHanh = document.getElementById('ngay_khoi_hanh').value;
            const gia = parseInt(selectedOption.dataset.gia) || 0;

            document.getElementById('summary-tour').textContent = selectedOption.text.split(' - ')[0] || 'Chưa chọn';
            document.getElementById('summary-date').textContent = ngayKhoiHanh ? new Date(ngayKhoiHanh).toLocaleDateString('vi-VN') : '--/--/----';
            document.getElementById('summary-people').textContent = soNguoi + ' người';
            document.getElementById('summary-price').textContent = gia.toLocaleString('vi-VN') + ' ₫';
            document.getElementById('summary-total').textContent = (gia * soNguoi).toLocaleString('vi-VN') + ' ₫';
        }

        // Check availability
        function kiemTraChoTrong() {
            const tourId = document.getElementById('tour_id').value;
            const ngayKhoiHanh = document.getElementById('ngay_khoi_hanh').value;
            const soNguoi = document.getElementById('so_nguoi').value;
            const infoDiv = document.getElementById('cho-trong-info');
            const statusText = document.getElementById('status-text');

            if (!tourId || !ngayKhoiHanh || !soNguoi) {
                infoDiv.style.display = 'none';
                return;
            }

            infoDiv.style.display = 'block';
            infoDiv.className = 'availability-status loading';
            statusText.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Đang kiểm tra chỗ trống...';

            fetch(`index.php?act=booking/kiemTraChoTrong&tour_id=${tourId}&ngay_khoi_hanh=${ngayKhoiHanh}&so_nguoi=${soNguoi}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        infoDiv.style.display = 'none';
                        return;
                    }

                    if (data.co_cho) {
                        infoDiv.className = 'availability-status success';
                        statusText.innerHTML = `<i class="bi bi-check-circle-fill me-2 fs-5"></i><strong>Còn ${data.cho_trong} chỗ trống</strong> - Đã đặt: ${data.da_dat}/${data.toi_da} người`;
                    } else {
                        infoDiv.className = 'availability-status error';
                        statusText.innerHTML = `<i class="bi bi-x-circle-fill me-2 fs-5"></i><strong>Không đủ chỗ!</strong> Chỉ còn ${data.cho_trong} chỗ trống (Đã đặt: ${data.da_dat}/${data.toi_da})`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    infoDiv.style.display = 'none';
                });
        }

        // Event listeners
        function syncNgayKetThucMin() {
            const endInput = document.getElementById('ngay_ket_thuc');
            const startInput = document.getElementById('ngay_khoi_hanh');
            if (!endInput || !startInput) return;
            if (startInput.value) {
                endInput.min = startInput.value;
                if (!endInput.value || endInput.value < startInput.value) {
                    endInput.value = startInput.value;
                }
            }
        }

        document.getElementById('tour_id').addEventListener('change', function() {
            updateSummary();
            kiemTraChoTrong();
        });
        document.getElementById('ngay_khoi_hanh').addEventListener('change', function() {
            syncNgayKetThucMin();
            kiemTraChoTrong();
        });
        document.getElementById('so_nguoi').addEventListener('input', function() {
            updateSummary();
            kiemTraChoTrong();
        });

        // Form validation
        document.getElementById('datTourForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const soDienThoai = document.getElementById('so_dien_thoai').value;

            if (!email && !soDienThoai) {
                e.preventDefault();
                alert('Vui lòng nhập email hoặc số điện thoại.');
                return false;
            }

            const infoDiv = document.getElementById('cho-trong-info');
            if (infoDiv.style.display !== 'none' && infoDiv.classList.contains('error')) {
                e.preventDefault();
                alert('Không đủ chỗ trống. Vui lòng chọn ngày khác hoặc giảm số lượng người.');
                return false;
            }
        });

        // Initialize
        updateSummary();
        syncNgayKetThucMin();
    </script>
</body>
</html>

