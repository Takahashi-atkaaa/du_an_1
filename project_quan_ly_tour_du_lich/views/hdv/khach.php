<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Khách - HDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        
        .customer-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        
        .customer-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .customer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-light">
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-people"></i> Danh sách Khách
                    </h3>
                    <?php if ($tour): ?>
                    <p class="mb-0 opacity-75"><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></p>
                    <?php endif; ?>
                </div>
                <a href="index.php?act=hdv/dashboard" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Tour Selector -->
        <?php if (empty($tour)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Chọn tour để xem danh sách khách</h5>
                <select class="form-select" onchange="if(this.value) window.location.href='index.php?act=hdv/khach&tour_id=' + this.value">
                    <option value="">-- Chọn tour --</option>
                    <?php foreach($tours_list as $t): ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo htmlspecialchars($t['ten_tour'] ?? ''); ?> 
                        (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'] ?? 'now')); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Tour Info -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h5>
                        <div class="text-muted">
                            <i class="bi bi-calendar3"></i> 
                            <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'] ?? 'now')); ?>
                            -
                            <?php echo date('d/m/Y', strtotime($tour['ngay_ket_thuc'] ?? 'now')); ?>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary fs-5">
                            <i class="bi bi-people"></i> <?php echo count($khach_list); ?> khách
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer List -->
        <?php if (!empty($khach_list)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>CMND/Passport</th>
                            <th>Ngày sinh</th>
                            <th>Giới tính</th>
                            <th>Quốc tịch</th>
                            <th>Liên hệ</th>
                            <th>Địa chỉ</th>
                            <th>Trạng thái</th>
                            <th>Booking ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($khach_list as $index => $khach): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'Khách'); ?></strong>
                            </td>
                            <td>
                                <?php if (!empty($khach['so_cmnd'])): ?>
                                    CMND: <?php echo htmlspecialchars($khach['so_cmnd'] ?? ''); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($khach['so_passport'])): ?>
                                    Passport: <?php echo htmlspecialchars($khach['so_passport'] ?? ''); ?>
                                <?php endif; ?>
                                <?php if (empty($khach['so_cmnd']) && empty($khach['so_passport'])): ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo !empty($khach['ngay_sinh']) ? date('d/m/Y', strtotime($khach['ngay_sinh'])) : 'N/A'; ?>
                            </td>
                            <td>
                                <?php 
                                $gioiTinhLabels = ['Nam' => 'Nam', 'Nu' => 'Nữ', 'Khac' => 'Khác'];
                                echo $gioiTinhLabels[$khach['gioi_tinh']] ?? $khach['gioi_tinh'] ?? 'N/A';
                                ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($khach['quoc_tich'] ?? 'Việt Nam'); ?>
                            </td>
                            <td>
                                <?php if (!empty($khach['email'])): ?>
                                    <small><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($khach['email'] ?? ''); ?></small><br>
                                <?php endif; ?>
                                <?php if (!empty($khach['so_dien_thoai'])): ?>
                                    <small><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? ''); ?></small>
                                <?php endif; ?>
                                <?php if (empty($khach['email']) && empty($khach['so_dien_thoai'])): ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo !empty($khach['dia_chi']) ? htmlspecialchars($khach['dia_chi'] ?? '') : 'N/A'; ?>
                            </td>
                            <td>
                                <?php
                                $trangThaiLabels = [
                                    'ChuaCheckIn' => 'Chưa check-in',
                                    'DaCheckIn' => 'Đã check-in',
                                    'DaCheckOut' => 'Đã check-out'
                                ];
                                $trangThaiClass = [
                                    'ChuaCheckIn' => 'warning',
                                    'DaCheckIn' => 'success',
                                    'DaCheckOut' => 'secondary'
                                ];
                                $trangThai = $khach['trang_thai'] ?? 'ChuaCheckIn';
                                ?>
                                <span class="badge bg-<?php echo $trangThaiClass[$trangThai] ?? 'warning'; ?>">
                                    <?php echo $trangThaiLabels[$trangThai] ?? $trangThai; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($khach['booking_id'])): ?>
                                    #<?php echo $khach['booking_id']; ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Chưa có khách nào trong danh sách. Vui lòng thêm khách vào lịch khởi hành này.
            </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
