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
                    <a href="index.php?act=lichKhoiHanh/index" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Quay lại danh sách
                    </a>
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
                                        <select name="loai_dich_vu" class="form-select" required>
                                            <option value="Xe">Xe</option>
                                            <option value="KhachSan">Khách sạn</option>
                                            <option value="VeMayBay">Vé máy bay</option>
                                            <option value="NhaHang">Nhà hàng</option>
                                            <option value="DiemThamQuan">Điểm tham quan</option>
                                            <option value="Visa">Visa</option>
                                            <option value="BaoHiem">Bảo hiểm</option>
                                            <option value="Khac">Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Tên dịch vụ <span class="text-danger">*</span></label>
                                        <input type="text" name="ten_dich_vu" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Nhà cung cấp</label>
                                        <select name="nha_cung_cap_id" class="form-select">
                                            <option value="">-- Chọn nhà cung cấp --</option>
                                            <?php foreach ($nhaCungCapList as $ncc): ?>
                                                <option value="<?php echo $ncc['id_nha_cung_cap']; ?>">
                                                    <?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Số lượng <span class="text-danger">*</span></label>
                                        <input type="number" name="so_luong" class="form-control" value="1" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Đơn vị</label>
                                        <input type="text" name="don_vi" class="form-control" placeholder="VD: phòng, xe...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Ngày bắt đầu</label>
                                        <input type="date" name="ngay_bat_dau" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Ngày kết thúc</label>
                                        <input type="date" name="ngay_ket_thuc" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giờ bắt đầu</label>
                                        <input type="time" name="gio_bat_dau" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giờ kết thúc</label>
                                        <input type="time" name="gio_ket_thuc" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Địa điểm</label>
                                        <input type="text" name="dia_diem" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Giá tiền (VNĐ)</label>
                                        <input type="number" name="gia_tien" class="form-control" step="1000" min="0">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Ghi chú</label>
                                        <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
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
                                                            <form method="POST" action="index.php?act=lichKhoiHanh/updateTrangThaiDichVu" class="d-inline">
                                                                <input type="hidden" name="id" value="<?php echo $pb['id']; ?>">
                                                                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                                                                <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                    <option value="ChoXacNhan" <?php echo $pb['trang_thai'] == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                                                    <option value="DaXacNhan" <?php echo $pb['trang_thai'] == 'DaXacNhan' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                                                    <option value="TuChoi" <?php echo $pb['trang_thai'] == 'TuChoi' ? 'selected' : ''; ?>>Từ chối</option>
                                                                    <option value="Huy" <?php echo $pb['trang_thai'] == 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                                                                    <option value="HoanTat" <?php echo $pb['trang_thai'] == 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                                                </select>
                                                            </form>
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
</body>
</html>
