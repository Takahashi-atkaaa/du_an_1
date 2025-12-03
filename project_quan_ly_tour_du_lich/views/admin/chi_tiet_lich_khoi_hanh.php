<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Lịch Khởi Hành - Admin</title>
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
        .info-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
        }
        .info-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
        }
        .info-row {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
        }
        .info-value {
            color: #212529;
            font-size: 1rem;
        }
        .section-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 2rem;
        }
        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #495057;
        }
        .add-form-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom thead {
            background: #f8f9fa;
        }
        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }
        .badge-role {
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .stats-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.25rem;
        }
        .service-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
</head>
<body class="bg-light">
<?php
$serviceTypeOptions = [
    'Xe' => 'Xe vận chuyển',
    'KhachSan' => 'Khách sạn',
    'VeMayBay' => 'Vé máy bay',
    'Ve' => 'Vé tàu / xe khách',
    'NhaHang' => 'Nhà hàng',
    'DiemThamQuan' => 'Điểm tham quan',
    'Visa' => 'Visa',
    'BaoHiem' => 'Bảo hiểm',
    'Khac' => 'Khác'
];
$catalogServicesMap = $catalogServicesMap ?? [];
?>
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
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-dark">#<?php echo $lichKhoiHanh['id']; ?></span>
                            <span class="badge <?php 
                                echo match($lichKhoiHanh['trang_thai']) {
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
                        echo $statusLabels[$lichKhoiHanh['trang_thai']] ?? $lichKhoiHanh['trang_thai'];
                        ?>
                            </span>
                        </div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="bi bi-calendar-event"></i> Chi tiết Lịch Khởi Hành
                        </h1>
                        <p class="lead mb-0 opacity-75">
                            <?php echo htmlspecialchars($lichKhoiHanh['ten_tour'] ?? 'N/A'); ?>
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?act=lichKhoiHanh/edit&id=<?php echo $lichKhoiHanh['id']; ?>" 
                           class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Sửa lịch
                        </a>
                    <a href="index.php?act=lichKhoiHanh/index" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
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
            
        <div class="row">
            <!-- Left Column: Info -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <div class="info-card card">
                    <div class="card-header">
                        <i class="bi bi-info-circle"></i> Thông tin cơ bản
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-calendar-event text-primary"></i> Ngày khởi hành</div>
                            <div class="info-value fw-bold">
                                <?php echo $lichKhoiHanh['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lichKhoiHanh['ngay_khoi_hanh'])) : 'N/A'; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-clock text-info"></i> Giờ xuất phát</div>
                            <div class="info-value"><?php echo $lichKhoiHanh['gio_xuat_phat'] ?? 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-calendar-check text-success"></i> Ngày kết thúc</div>
                            <div class="info-value">
                                <?php echo $lichKhoiHanh['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lichKhoiHanh['ngay_ket_thuc'])) : 'N/A'; ?>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-clock text-warning"></i> Giờ kết thúc</div>
                            <div class="info-value"><?php echo $lichKhoiHanh['gio_ket_thuc'] ?? 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-geo-alt text-danger"></i> Điểm tập trung</div>
                            <div class="info-value"><?php echo htmlspecialchars($lichKhoiHanh['diem_tap_trung'] ?? 'Chưa xác định'); ?></div>
                        </div>
                    </div>
                </div>

                <!-- Booking Stats -->
                <div class="info-card card">
                    <div class="card-header">
                        <i class="bi bi-graph-up"></i> Thống kê
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-people"></i> Số chỗ</div>
                            <div class="info-value">
                                <span class="stats-badge bg-primary text-white">
                                    <?php echo $lichKhoiHanh['so_cho'] ?? 50; ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-file-text"></i> Số booking</div>
                            <div class="info-value">
                                <span class="stats-badge bg-info text-white">
                                    <?php echo $lichKhoiHanh['so_booking'] ?? 0; ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-person-check"></i> Tổng người đã đặt</div>
                            <div class="info-value">
                                <span class="stats-badge bg-success text-white">
                                    <?php echo $lichKhoiHanh['tong_nguoi_dat'] ?? 0; ?>
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label"><i class="bi bi-percent"></i> Tỷ lệ lấp đầy</div>
                            <div class="info-value">
                                <?php 
                                $soCho = $lichKhoiHanh['so_cho'] ?? 50;
                                $tongNguoi = $lichKhoiHanh['tong_nguoi_dat'] ?? 0;
                                $tyLe = $soCho > 0 ? round(($tongNguoi / $soCho) * 100, 1) : 0;
                                ?>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar <?php echo $tyLe >= 80 ? 'bg-success' : ($tyLe >= 50 ? 'bg-warning' : 'bg-danger'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $tyLe; ?>%"
                                         aria-valuenow="<?php echo $tyLe; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?php echo $tyLe; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Tabs -->
            <div class="col-lg-8">
                <!-- Nav Tabs -->
                <ul class="nav nav-pills mb-4" id="detailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="staff-tab" data-bs-toggle="pill" data-bs-target="#staff" type="button">
                            <i class="bi bi-people"></i> Nhân sự
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="customer-tab" data-bs-toggle="pill" data-bs-target="#customer" type="button">
                            <i class="bi bi-person-lines-fill"></i> Danh sách khách
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="service-tab" data-bs-toggle="pill" data-bs-target="#service" type="button">
                            <i class="bi bi-gear"></i> Dịch vụ
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="detailTabsContent">
                    <!-- Tab: Nhân sự -->
                    <div class="tab-pane fade show active" id="staff" role="tabpanel">
                        <!-- Add Staff Form -->
                        <div class="add-form-card">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-person-plus"></i> Thêm nhân sự mới
                            </h6>
                            <form method="POST" action="index.php?act=lichKhoiHanh/phanBoNhanSu">
                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nhân sự <span class="text-danger">*</span></label>
                                        <select name="nhan_su_id" class="form-select" required>
                                <option value="">-- Chọn nhân sự --</option>
                                <?php foreach ($nhanSuList as $ns): ?>
                                    <option value="<?php echo $ns['nhan_su_id']; ?>">
                                        <?php echo htmlspecialchars($ns['ho_ten'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($ns['vai_tro'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                        <select name="vai_tro" class="form-select" required>
                                            <option value="HDV">Hướng dẫn viên</option>
                                <option value="TaiXe">Tài xế</option>
                                <option value="HauCan">Hậu cần</option>
                                <option value="DieuHanh">Điều hành</option>
                                <option value="Khac">Khác</option>
                            </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Ghi chú</label>
                                        <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Thêm nhân sự
                                        </button>
                                    </div>
                                </div>
            </form>
                        </div>

                        <!-- Staff List -->
                        <div class="section-card card">
                            <div class="section-header">
                                <i class="bi bi-people"></i> Danh sách nhân sự đã phân bổ
                            </div>
                            <div class="card-body p-0">
            <?php if (!empty($phanBoNhanSu)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Nhân sự</th>
                            <th>Vai trò</th>
                                                    <th>Liên hệ</th>
                            <th>Trạng thái</th>
                                                    <th>Xác nhận lúc</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phanBoNhanSu as $pb): ?>
                            <tr>
                                                        <td>
                                                            <div class="fw-semibold"><?php echo htmlspecialchars($pb['ho_ten'] ?? 'N/A'); ?></div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-role bg-primary">
                                                                <?php 
                                                                $roles = [
                                                                    'HDV' => 'Hướng dẫn viên',
                                                                    'TaiXe' => 'Tài xế',
                                                                    'HauCan' => 'Hậu cần',
                                                                    'DieuHanh' => 'Điều hành',
                                                                    'Khac' => 'Khác'
                                                                ];
                                                                echo $roles[$pb['vai_tro']] ?? $pb['vai_tro'];
                                                                ?>
                                                            </span>
                                                        </td>
                                <td>
                                                            <small>
                                                                <div><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($pb['email'] ?? 'N/A'); ?></div>
                                                                <div><i class="bi bi-phone"></i> <?php echo htmlspecialchars($pb['so_dien_thoai'] ?? 'N/A'); ?></div>
                                                            </small>
                                </td>
                                <td>
                                                            <span class="badge <?php 
                                                                echo match($pb['trang_thai']) {
                                                                    'ChoXacNhan' => 'bg-warning text-dark',
                                                                    'DaXacNhan' => 'bg-success',
                                                                    'TuChoi' => 'bg-danger',
                                                                    'Huy' => 'bg-secondary',
                                                                    default => 'bg-secondary'
                                                                };
                                                            ?>">
                                    <?php
                                    $statusLabels = [
                                        'ChoXacNhan' => 'Chờ xác nhận',
                                        'DaXacNhan' => 'Đã xác nhận',
                                        'TuChoi' => 'Từ chối',
                                        'Huy' => 'Hủy'
                                    ];
                                    echo $statusLabels[$pb['trang_thai']] ?? $pb['trang_thai'];
                                    ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small><?php echo $pb['thoi_gian_xac_nhan'] ? date('d/m/Y H:i', strtotime($pb['thoi_gian_xac_nhan'])) : 'N/A'; ?></small>
                                </td>
                                <td>
                                    <a href="index.php?act=lichKhoiHanh/deleteNhanSu&id=<?php echo $pb['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Xóa phân bổ này?');">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                                    </div>
            <?php else: ?>
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-1 opacity-25"></i>
                                        <p class="mt-3">Chưa có nhân sự nào được phân bổ</p>
                                    </div>
            <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Danh sách khách -->
                    <div class="tab-pane fade" id="customer" role="tabpanel">
                        
                        <!-- Add Customer Form -->
                        <div class="add-form-card">
                            <h6 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person-plus"></i> Thêm khách mới
                                </span>
                                <button type="button" id="btnAddGuestRow" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm người
                                </button>
                            </h6>
                            <form method="POST" action="index.php?act=lichKhoiHanh/themKhachChiTiet">
                                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Booking <span class="text-danger">*</span></label>
                                        <select name="booking_id" class="form-select" required>
                                            <option value="">-- Chọn booking --</option>
                                            <?php foreach ($bookingList as $b): ?>
                                                <option value="<?php echo $b['booking_id']; ?>">
                                                    Booking #<?php echo $b['booking_id']; ?> - <?php echo htmlspecialchars($b['ho_ten'] ?? 'N/A'); ?> (<?php echo $b['so_nguoi']; ?> người)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end justify-content-end">
                                        <small class="text-muted">
                                            Có thể nhập nhiều khách rồi lưu một lần.
                                        </small>
                                    </div>
                                </div>

                                <div id="guestFormsWrapper">
                                    <div class="guest-form border rounded-3 p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold">
                                                <i class="bi bi-person-badge"></i> Khách <span class="guest-index">1</span>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-guest" style="display:none;">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-semibold">Họ tên <span class="text-danger">*</span></label>
                                                <input type="text" name="ho_ten[]" class="form-control guest-ho-ten" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Số CMND/CCCD</label>
                                                <input type="text" name="so_cmnd[]" class="form-control">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Số Passport</label>
                                                <input type="text" name="so_passport[]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Ngày sinh</label>
                                                <input type="date" name="ngay_sinh[]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Giới tính</label>
                                                <select name="gioi_tinh[]" class="form-select">
                                                    <option value="Nam">Nam</option>
                                                    <option value="Nu">Nữ</option>
                                                    <option value="Khac">Khác</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Quốc tịch</label>
                                                <input type="text" name="quoc_tich[]" class="form-control" value="Việt Nam">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Số điện thoại</label>
                                                <input type="text" name="so_dien_thoai[]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Email</label>
                                                <input type="email" name="email[]" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-semibold">Địa chỉ</label>
                                                <input type="text" name="dia_chi[]" class="form-control">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small fw-semibold">Ghi chú</label>
                                                <textarea name="ghi_chu[]" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Lưu danh sách khách
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Customer List -->
                        <div class="section-card card">
                            <div class="section-header">
                                <i class="bi bi-person-lines-fill"></i> Danh sách khách chi tiết
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($bookingList)): ?>
                                    <?php foreach ($bookingList as $booking): ?>
                                        <div class="border-bottom p-3">
                                            <h6 class="fw-bold mb-2">
                                                Booking #<?php echo $booking['booking_id']; ?> - 
                                                <?php echo htmlspecialchars($booking['ho_ten'] ?? 'N/A'); ?>
                                                <span class="badge bg-primary ms-2"><?php echo $booking['so_nguoi']; ?> người</span>
                                            </h6>
                                            <?php 
                                            $khachList = $danhSachKhachChiTiet[$booking['booking_id']] ?? [];
                                            if (!empty($khachList)): ?>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>STT</th>
                                                                <th>Họ tên</th>
                                                                <th>CMND/Passport</th>
                                                                <th>Ngày sinh</th>
                                                                <th>Giới tính</th>
                                                                <th>SĐT</th>
                                                                <th>Trạng thái</th>
                                                                <th>Thao tác</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($khachList as $idx => $khach): ?>
                                                                <tr>
                                                                    <td><?php echo $idx + 1; ?></td>
                                                                    <td><?php echo htmlspecialchars($khach['ho_ten'] ?? 'N/A'); ?></td>
                                                                    <td>
                                                                        <?php if ($khach['so_cmnd']): ?>
                                                                            CMND: <?php echo htmlspecialchars($khach['so_cmnd']); ?><br>
                                                                        <?php endif; ?>
                                                                        <?php if ($khach['so_passport']): ?>
                                                                            Passport: <?php echo htmlspecialchars($khach['so_passport']); ?>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?php echo $khach['ngay_sinh'] ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : 'N/A'; ?></td>
                                                                    <td><?php echo htmlspecialchars($khach['gioi_tinh'] ?? 'N/A'); ?></td>
                                                                    <td><?php echo htmlspecialchars($khach['so_dien_thoai'] ?? 'N/A'); ?></td>
                                                                    <td>
                                                                        <span class="badge <?php 
                                                                            echo $khach['trang_thai'] === 'DaCheckIn' ? 'bg-success' : 
                                                                                ($khach['trang_thai'] === 'DaCheckOut' ? 'bg-secondary' : 'bg-warning');
                                                                        ?>">
                                                                            <?php 
                                                                            echo $khach['trang_thai'] === 'DaCheckIn' ? 'Đã check-in' : 
                                                                                ($khach['trang_thai'] === 'DaCheckOut' ? 'Đã check-out' : 'Chưa check-in');
                                                                            ?>
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="index.php?act=lichKhoiHanh/suaKhachChiTiet&id=<?php echo $khach['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                                                               class="btn btn-info" title="Sửa">
                                                                                <i class="bi bi-pencil"></i>
                                                                            </a>
                                                                            <a href="index.php?act=lichKhoiHanh/xoaKhachChiTiet&id=<?php echo $khach['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                                                               class="btn btn-danger" 
                                                                               onclick="return confirm('Xóa khách này?');"
                                                                               title="Xóa">
                                                                                <i class="bi bi-trash"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted mb-0">Chưa có khách nào trong booking này.</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x fs-1 opacity-25"></i>
                                        <p class="mt-3">Chưa có booking nào cho lịch khởi hành này</p>
                                    </div>
            <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Dịch vụ -->
                    <div class="tab-pane fade" id="service" role="tabpanel">
                        <!-- Add Service Form -->
                        <div class="add-form-card">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-plus-circle"></i> Thêm dịch vụ mới
                            </h6>
                            <form method="POST" action="index.php?act=lichKhoiHanh/phanBoDichVu">
                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Loại dịch vụ <span class="text-danger">*</span></label>
                                        <select name="loai_dich_vu" id="loaiDichVuSelect" class="form-select" required>
                                <?php foreach ($serviceTypeOptions as $value => $label): ?>
                                    <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tên dịch vụ <span class="text-danger">*</span></label>
                                        <input type="text" name="ten_dich_vu" id="tenDichVuInput" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nhà cung cấp</label>
                                        <select name="nha_cung_cap_id" id="supplierSelect" class="form-select">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                <?php foreach ($nhaCungCapList as $ncc): ?>
                                    <option value="<?php echo $ncc['id_nha_cung_cap']; ?>">
                                        <?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Dịch vụ có sẵn</label>
                                        <select id="catalogServiceSelect" class="form-select" disabled>
                                            <option value="">-- Chọn dịch vụ có sẵn --</option>
                                        </select>
                                        <div class="form-text">Tự động điền thông tin khi chọn.</div>
                                        <input type="hidden" name="catalog_service_id" id="catalogServiceIdInput">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" name="so_luong" id="soLuongInput" class="form-control" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Đơn vị</label>
                                        <input type="text" name="don_vi" id="donViInput" class="form-control" placeholder="VD: phòng, xe...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Ngày bắt đầu</label>
                                        <input type="date" name="ngay_bat_dau" id="ngayBatDauInput" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Ngày kết thúc</label>
                                        <input type="date" name="ngay_ket_thuc" id="ngayKetThucInput" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giờ bắt đầu</label>
                                        <input type="time" name="gio_bat_dau" id="gioBatDauInput" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giờ kết thúc</label>
                                        <input type="time" name="gio_ket_thuc" id="gioKetThucInput" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Địa điểm</label>
                                        <input type="text" name="dia_diem" id="diaDiemInput" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giá tiền (VNĐ)</label>
                                        <input type="number" name="gia_tien" id="giaTienInput" class="form-control" step="1000" min="0">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Ghi chú</label>
                                        <textarea name="ghi_chu" id="ghiChuInput" class="form-control" rows="2"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-plus-circle"></i> Thêm dịch vụ
                                        </button>
                                    </div>
                                </div>
            </form>
                        </div>

                        <!-- Service List -->
                        <div class="section-card card">
                            <div class="section-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-gear"></i> Danh sách dịch vụ đã phân bổ</span>
                                <?php if (!empty($phanBoDichVu)): ?>
                                    <span class="badge bg-primary">Tổng: <?php echo number_format($tongChiPhi ?? 0); ?> VNĐ</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-0">
            <?php if (!empty($phanBoDichVu)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-custom">
                    <thead>
                        <tr>
                                                    <th>Dịch vụ</th>
                            <th>Nhà cung cấp</th>
                            <th>Số lượng</th>
                            <th>Thời gian</th>
                            <th>Địa điểm</th>
                            <th>Giá tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phanBoDichVu as $pb): ?>
                            <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <div class="service-icon bg-primary bg-opacity-10 text-primary">
                                                                    <i class="bi bi-<?php 
                                                                        echo match($pb['loai_dich_vu']) {
                                                                            'Xe' => 'bus-front',
                                                                            'KhachSan' => 'building',
                                                                            'VeMayBay' => 'airplane',
                                                                            'NhaHang' => 'shop',
                                                                            'DiemThamQuan' => 'pin-map',
                                                                            'Visa' => 'credit-card',
                                                                            'BaoHiem' => 'shield-check',
                                                                            default => 'gear'
                                                                        };
                                                                    ?>"></i>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-semibold"><?php echo htmlspecialchars($pb['ten_dich_vu']); ?></div>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($pb['loai_dich_vu']); ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                <td><?php echo htmlspecialchars($pb['ten_don_vi'] ?? 'N/A'); ?></td>
                                <td><?php echo $pb['so_luong']; ?> <?php echo htmlspecialchars($pb['don_vi'] ?? ''); ?></td>
                                <td>
                                                            <small>
                                    <?php if ($pb['ngay_bat_dau']): ?>
                                        <?php echo date('d/m/Y', strtotime($pb['ngay_bat_dau'])); ?>
                                        <?php if ($pb['gio_bat_dau']): ?>
                                                                        <?php echo substr($pb['gio_bat_dau'], 0, 5); ?>
                                        <?php endif; ?>
                                        <?php if ($pb['ngay_ket_thuc'] && $pb['ngay_ket_thuc'] != $pb['ngay_bat_dau']): ?>
                                                                        <br>- <?php echo date('d/m/Y', strtotime($pb['ngay_ket_thuc'])); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                                            </small>
                                </td>
                                                        <td><small><?php echo htmlspecialchars($pb['dia_diem'] ?? 'N/A'); ?></small></td>
                                                        <td class="fw-bold text-primary"><?php echo $pb['gia_tien'] ? number_format($pb['gia_tien']) : '0'; ?> VNĐ</td>
                                <td>
                                    <?php
                                                            $badgeClass = match($pb['trang_thai'] ?? 'ChoXacNhan') {
                                                                'DaXacNhan' => 'bg-success',
                                                                'TuChoi' => 'bg-danger',
                                                                'Huy' => 'bg-secondary',
                                                                'HoanTat' => 'bg-primary',
                                                                default => 'bg-warning text-dark'
                                                            };
                                                            $trangThaiText = match($pb['trang_thai'] ?? 'ChoXacNhan') {
                                        'DaXacNhan' => 'Đã xác nhận',
                                        'TuChoi' => 'Từ chối',
                                        'Huy' => 'Hủy',
                                                                'HoanTat' => 'Hoàn tất',
                                                                default => 'Chờ xác nhận'
                                                            };
                                                            ?>
                                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $trangThaiText; ?></span>
                                </td>
                                <td>
                                    <a href="index.php?act=lichKhoiHanh/deleteDichVu&id=<?php echo $pb['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Xóa phân bổ này?');">
                                                                <i class="bi bi-trash"></i>
                                                            </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                                    </div>
            <?php else: ?>
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-gear fs-1 opacity-25"></i>
                                        <p class="mt-3">Chưa có dịch vụ nào được phân bổ</p>
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
    (function() {
        // --- Thêm/xóa form khách nhanh ---
        const guestWrapper = document.getElementById('guestFormsWrapper');
        const addGuestBtn = document.getElementById('btnAddGuestRow');

        function updateGuestIndexes() {
            if (!guestWrapper) return;
            const forms = guestWrapper.querySelectorAll('.guest-form');
            forms.forEach((form, idx) => {
                const indexSpan = form.querySelector('.guest-index');
                if (indexSpan) {
                    indexSpan.textContent = idx + 1;
                }
                const removeBtn = form.querySelector('.btn-remove-guest');
                if (removeBtn) {
                    removeBtn.style.display = forms.length > 1 ? 'inline-block' : 'none';
                }
            });
        }

        function clearGuestForm(form) {
            form.querySelectorAll('input, textarea').forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else if (input.name && !input.name.startsWith('quoc_tich')) {
                    input.value = '';
                }
            });
        }

        if (guestWrapper && addGuestBtn) {
            addGuestBtn.addEventListener('click', function() {
                const firstForm = guestWrapper.querySelector('.guest-form');
                if (!firstForm) return;

                const clone = firstForm.cloneNode(true);
                clearGuestForm(clone);

                // gắn lại event cho nút xóa của clone
                const removeBtn = clone.querySelector('.btn-remove-guest');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        if (guestWrapper.querySelectorAll('.guest-form').length > 1) {
                            clone.remove();
                            updateGuestIndexes();
                        }
                    });
                }

                guestWrapper.appendChild(clone);
                updateGuestIndexes();
            });

            // event xóa cho form đầu tiên
            const firstRemove = guestWrapper.querySelector('.guest-form .btn-remove-guest');
            if (firstRemove) {
                firstRemove.addEventListener('click', function(e) {
                    const form = e.currentTarget.closest('.guest-form');
                    if (form && guestWrapper.querySelectorAll('.guest-form').length > 1) {
                        form.remove();
                        updateGuestIndexes();
                    }
                });
            }

            updateGuestIndexes();
        }

        // --- JS cho dịch vụ & nhà cung cấp ---
        const supplierCatalog = <?php echo json_encode($catalogServicesMap, JSON_UNESCAPED_UNICODE); ?>;
        const serviceTypeLabels = <?php echo json_encode($serviceTypeOptions, JSON_UNESCAPED_UNICODE); ?>;

        const supplierSelect = document.getElementById('supplierSelect');
        const catalogSelect = document.getElementById('catalogServiceSelect');
        if (!supplierSelect || !catalogSelect) {
            return;
        }

        const loaiSelect = document.getElementById('loaiDichVuSelect');
        const tenInput = document.getElementById('tenDichVuInput');
        const donViInput = document.getElementById('donViInput');
        const giaTienInput = document.getElementById('giaTienInput');
        const ghiChuInput = document.getElementById('ghiChuInput');
        const soLuongInput = document.getElementById('soLuongInput');
        const catalogServiceIdInput = document.getElementById('catalogServiceIdInput');

        function resetCatalogSelect() {
            catalogSelect.innerHTML = '<option value="">-- Chọn dịch vụ có sẵn --</option>';
            catalogSelect.disabled = true;
            if (catalogServiceIdInput) {
                catalogServiceIdInput.value = '';
            }
        }

        function populateCatalogOptions(supplierId) {
            resetCatalogSelect();
            if (!supplierId || !supplierCatalog[supplierId] || supplierCatalog[supplierId].length === 0) {
                return;
            }
            supplierCatalog[supplierId].forEach(service => {
                const option = document.createElement('option');
                option.value = service.id;
                const typeLabel = serviceTypeLabels[service.loai_dich_vu] ?? service.loai_dich_vu ?? '';
                option.textContent = service.ten_dich_vu + (typeLabel ? ` (${typeLabel})` : '');
                option.dataset.service = JSON.stringify({
                    loai: service.loai_dich_vu || '',
                    ten: service.ten_dich_vu || '',
                    donVi: service.don_vi_tinh || '',
                    gia: service.gia_tham_khao || '',
                    ghiChu: service.mo_ta || '',
                    soLuong: service.cong_suat_toi_da || ''
                });
                catalogSelect.appendChild(option);
            });
            catalogSelect.disabled = false;
        }

        function applyCatalogData(raw) {
            if (!raw) return;
            if (loaiSelect && raw.loai) {
                const hasOption = Array.from(loaiSelect.options).some(opt => opt.value === raw.loai);
                if (hasOption) {
                    loaiSelect.value = raw.loai;
                }
            }
            if (tenInput && raw.ten) {
                tenInput.value = raw.ten;
            }
            if (donViInput) {
                donViInput.value = raw.donVi || '';
            }
            if (giaTienInput) {
                giaTienInput.value = raw.gia || '';
            }
            if (ghiChuInput) {
                ghiChuInput.value = raw.ghiChu || '';
            }
            if (soLuongInput && raw.soLuong) {
                soLuongInput.value = raw.soLuong;
            }
        }

        supplierSelect.addEventListener('change', function() {
            populateCatalogOptions(this.value);
        });

        catalogSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (!selected || !selected.dataset.service) {
                if (catalogServiceIdInput) {
                    catalogServiceIdInput.value = '';
                }
                return;
            }
            try {
                const data = JSON.parse(selected.dataset.service);
                applyCatalogData(data);
                if (catalogServiceIdInput) {
                    catalogServiceIdInput.value = selected.value || '';
                }
            } catch (error) {
                console.error('Không thể đọc dữ liệu dịch vụ có sẵn', error);
            }
        });

        if (supplierSelect.value) {
            populateCatalogOptions(supplierSelect.value);
        }
    })();
    </script>
</body>
</html>
