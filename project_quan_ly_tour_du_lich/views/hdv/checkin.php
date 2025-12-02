<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in & Điểm danh - HDV</title>
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
        
        .checkpoint-card {
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .checkpoint-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .checkpoint-card.active {
            border-left-color: var(--secondary-color);
            background: linear-gradient(to right, rgba(102, 126, 234, 0.1), transparent);
        }
        
        .customer-row {
            transition: all 0.2s;
        }
        
        .customer-row:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-chua_checkin { background: #e9ecef; color: #495057; }
        .status-da_checkin { background: #d1e7dd; color: #0f5132; }
        .status-vang_mat { background: #f8d7da; color: #842029; }
        .status-re_gio { background: #fff3cd; color: #856404; }
        
        .checkpoint-type {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
        }
        
        .type-tap_trung { background: #cfe2ff; color: #084298; }
        .type-tham_quan { background: #e7d6f5; color: #6f42c1; }
        .type-an_uong { background: #ffeeba; color: #856404; }
        .type-nghi_ngoi { background: #d1ecf1; color: #0c5460; }
        .type-khac { background: #f8d7da; color: #842029; }
        
        .stats-card {
            border-radius: 1rem;
            padding: 1.5rem;
            background: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
    </style>
</head>
<body class="bg-light">
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1">
                        <i class="bi bi-check2-square"></i> Check-in & Điểm danh
                    </h3>
                    <?php if (isset($tour) && $tour): ?>
                    <p class="mb-0 opacity-75">
                        <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?>
                        <span class="mx-2">•</span>
                        <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'] ?? 'now')); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <a href="index.php?act=hdv/dashboard" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Trang chủ
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <!-- Tour Selector -->
        <?php if (empty($tour)): ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Chọn tour để check-in</h5>
                <select class="form-select" onchange="if(this.value) window.location.href='index.php?act=hdv/checkin&tour_id=' + this.value">
                    <option value="">-- Chọn tour --</option>
                    <?php if(isset($tours_list)) foreach($tours_list as $t): ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo htmlspecialchars($t['ten_tour'] ?? ''); ?> 
                        (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'] ?? 'now')); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php else: ?>
        
        <div class="row">
            <!-- Left: Checkpoints List -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Điểm check-in</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCheckpointModal">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($diem_checkin_list)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($diem_checkin_list as $diem): ?>
                            <a href="index.php?act=hdv/checkin&tour_id=<?php echo $_GET['tour_id']; ?>&diem_id=<?php echo $diem['id']; ?>" 
                               class="list-group-item list-group-item-action checkpoint-card <?php echo (isset($diem_hien_tai) && $diem_hien_tai && $diem['id'] == $diem_hien_tai['id']) ? 'active' : ''; ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="badge bg-secondary me-2"><?php echo $diem['thu_tu'] ?? ''; ?></span>
                                            <strong><?php echo htmlspecialchars($diem['ten_diem'] ?? ''); ?></strong>
                                        </div>
                                        <div class="small text-muted">
                                            <span class="checkpoint-type type-<?php echo $diem['loai_diem']; ?>">
                                                <?php 
                                                $types = [
                                                    'tap_trung' => '📍 Tập trung',
                                                    'tham_quan' => '🏛️ Tham quan',
                                                    'an_uong' => '🍽️ Ăn uống',
                                                    'nghi_ngoi' => '🏨 Nghỉ ngơi',
                                                    'khac' => '📌 Khác'
                                                ];
                                                echo $types[$diem['loai_diem']] ?? $diem['loai_diem'];
                                                ?>
                                            </span>
                                        </div>
                                        <?php if ($diem['thoi_gian_du_kien']): ?>
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-clock"></i> <?php echo date('H:i d/m', strtotime($diem['thoi_gian_du_kien'])); ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-danger" 
                                                onclick="event.preventDefault(); if(confirm('Xóa điểm này?')) window.location.href='index.php?act=hdv/delete_diem_checkin&id=<?php echo $diem['id']; ?>&tour_id=<?php echo $_GET['tour_id']; ?>'">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-geo-alt" style="font-size: 2rem;"></i>
                            <p>Chưa có điểm check-in nào</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Right: Customer List -->
            <div class="col-md-8">
                <?php if (isset($diem_hien_tai) && $diem_hien_tai): ?>
                
                <!-- Stats Summary -->
                <div class="row mb-4">
                    <?php 
                    $total = count($khach_list);
                    $da_checkin = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 'da_checkin'; }));
                    $vang_mat = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 'vang_mat'; }));
                    $re_gio = count(array_filter($khach_list, function($k) { return ($k['checkin_status'] ?? '') === 're_gio'; }));
                    $chua_checkin = $total - $da_checkin - $vang_mat - $re_gio;
                    ?>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card">
                            <div class="text-muted small">Tổng số khách</div>
                            <h3 class="mb-0"><?php echo $total; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card border-start border-success border-3">
                            <div class="text-muted small">Đã check-in</div>
                            <h3 class="mb-0 text-success"><?php echo $da_checkin; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card border-start border-warning border-3">
                            <div class="text-muted small">Chưa check-in</div>
                            <h3 class="mb-0 text-warning"><?php echo $chua_checkin; ?></h3>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="stats-card border-start border-danger border-3">
                            <div class="text-muted small">Vắng mặt</div>
                            <h3 class="mb-0 text-danger"><?php echo $vang_mat; ?></h3>
                        </div>
                    </div>
                </div>
                
                <!-- Customer List -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-people"></i> Danh sách khách hàng
                            <span class="badge bg-primary"><?php echo $total; ?> người</span>
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($khach_list)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Liên hệ</th>
                                        <th>Số người</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($khach_list as $index => $khach): ?>
                                    <tr class="customer-row">
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($khach['ho_ten'] ?? 'N/A'); ?></strong>
                                            <?php if (!empty($khach['checkin_note'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($khach['checkin_note'] ?? ''); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small>
                                                <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($khach['so_dien_thoai'] ?? 'N/A'); ?><br>
                                                <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($khach['email'] ?? 'N/A'); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo ($khach['so_nguoi_lon'] ?? 0) + ($khach['so_tre_em'] ?? 0); ?> người</span>
                                            <small class="text-muted d-block"><?php echo $khach['so_nguoi_lon'] ?? 0; ?> NL, <?php echo $khach['so_tre_em'] ?? 0; ?> TE</small>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo $khach['checkin_status'] ?? 'chua_checkin'; ?>">
                                                <?php 
                                                $statuses = [
                                                    'chua_checkin' => '⏳ Chưa check-in',
                                                    'da_checkin' => '✅ Đã check-in',
                                                    'vang_mat' => '❌ Vắng mặt',
                                                    're_gio' => '⏰ Đến trễ'
                                                ];
                                                echo $statuses[$khach['checkin_status'] ?? 'chua_checkin'];
                                                ?>
                                            </span>
                                            <?php if (!empty($khach['thoi_gian_checkin'])): ?>
                                            <br><small class="text-muted"><?php echo date('H:i', strtotime($khach['thoi_gian_checkin'])); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-success" 
                                                        onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 'da_checkin')">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                                <button class="btn btn-warning" 
                                                        onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 're_gio')">
                                                    <i class="bi bi-clock"></i>
                                                </button>
                                                <button class="btn btn-danger" 
                                                        onclick="updateCheckin(<?php echo $diem_hien_tai['id']; ?>, <?php echo $khach['booking_id']; ?>, 'vang_mat')">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people" style="font-size: 3rem;"></i>
                            <p>Chưa có khách hàng nào đăng ký tour này</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php else: ?>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Vui lòng chọn điểm check-in bên trái để xem danh sách khách hàng
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php endif; ?>
    </div>

    <!-- Add Checkpoint Modal -->
    <div class="modal fade" id="addCheckpointModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle"></i> Thêm điểm check-in
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?act=hdv/save_diem_checkin">
                    <input type="hidden" name="tour_id" value="<?php echo $tour['tour_id'] ?? ''; ?>">
                    <input type="hidden" name="diem_id" value="">
                    
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Tên điểm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="ten_diem" 
                                   placeholder="VD: Điểm tập trung Hà Nội" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại điểm <span class="text-danger">*</span></label>
                                <select class="form-select" name="loai_diem" required>
                                    <option value="tap_trung">📍 Tập trung</option>
                                    <option value="tham_quan">🏛️ Tham quan</option>
                                    <option value="an_uong">🍽️ Ăn uống</option>
                                    <option value="nghi_ngoi">🏨 Nghỉ ngơi</option>
                                    <option value="khac">📌 Khác</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thứ tự</label>
                                <input type="number" class="form-control" name="thu_tu" value="<?php echo (count($diem_checkin_list ?? []) + 1); ?>" min="1">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Thời gian dự kiến</label>
                            <input type="datetime-local" class="form-control" name="thoi_gian_du_kien">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea class="form-control" name="ghi_chu" rows="3" 
                                      placeholder="Thông tin bổ sung về điểm check-in..."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateCheckin(diemId, bookingId, trangThai) {
            var ghi_chu = null;
            
            if (trangThai === 'vang_mat') {
                ghi_chu = prompt('Lý do vắng mặt (không bắt buộc):');
            } else if (trangThai === 're_gio') {
                ghi_chu = prompt('Ghi chú về việc đến trễ (không bắt buộc):');
            }
            
            var formData = new FormData();
            formData.append('diem_checkin_id', diemId);
            formData.append('booking_id', bookingId);
            formData.append('trang_thai', trangThai);
            if (ghi_chu) formData.append('ghi_chu', ghi_chu);
            
            fetch('index.php?act=hdv/save_checkin_khach', {
                method: 'POST',
                body: formData
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(function(error) {
                alert('Lỗi kết nối: ' + error);
            });
        }
    </script>
</body>
</html>
