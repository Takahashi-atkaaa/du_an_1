<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu đặc biệt - HDV</title>
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
        
        .request-card {
            border-left: 4px solid;
            transition: all 0.3s;
            margin-bottom: 1rem;
        }
        
        .request-card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        
        .priority-khan_cap { border-left-color: #dc3545; background: #fff5f5; }
        .priority-cao { border-left-color: #fd7e14; background: #fff8f0; }
        .priority-trung_binh { border-left-color: #ffc107; background: #fffbf0; }
        .priority-thap { border-left-color: #6c757d; background: #f8f9fa; }
        
        .badge-type {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
        }
        
        .type-an_uong { background: #ffeeba; color: #856404; }
        .type-suc_khoe { background: #f8d7da; color: #721c24; }
        .type-di_chuyen { background: #d1ecf1; color: #0c5460; }
        .type-phong_o { background: #e7d6f5; color: #6f42c1; }
        .type-hoat_dong { background: #d1e7dd; color: #0f5132; }
        .type-khac { background: #e9ecef; color: #495057; }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.8rem;
        }
        
        .status-moi { background: #cfe2ff; color: #084298; }
        .status-dang_xu_ly { background: #fff3cd; color: #856404; }
        .status-da_giai_quyet { background: #d1e7dd; color: #0f5132; }
        .status-khong_the_thuc_hien { background: #f8d7da; color: #842029; }
        
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
                        <i class="bi bi-star"></i> Yêu cầu đặc biệt
                    </h3>
                    <?php if (isset($tour) && $tour): ?>
                    <p class="mb-0 opacity-75">
                        <?php echo htmlspecialchars($tour['ten_tour']); ?>
                        <span class="mx-2">•</span>
                        <?php echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh'])); ?>
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
                <h5 class="card-title">Chọn tour để xem yêu cầu đặc biệt</h5>
                <select class="form-select" onchange="if(this.value) window.location.href='index.php?act=hdv/yeu_cau_dac_biet&tour_id=' + this.value">
                    <option value="">-- Chọn tour --</option>
                    <?php if(isset($tours_list)) foreach($tours_list as $t): ?>
                    <option value="<?php echo $t['id']; ?>">
                        <?php echo htmlspecialchars($t['ten_tour']); ?> 
                        (<?php echo date('d/m/Y', strtotime($t['ngay_khoi_hanh'])); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Stats Summary -->
        <div class="row mb-4">
            <?php 
            $total = count($yeu_cau_list);
            $khan_cap = count(array_filter($yeu_cau_list, function($y) { return $y['muc_do_uu_tien'] === 'khan_cap'; }));
            $dang_xu_ly = count(array_filter($yeu_cau_list, function($y) { return $y['trang_thai'] === 'dang_xu_ly'; }));
            $da_giai_quyet = count(array_filter($yeu_cau_list, function($y) { return $y['trang_thai'] === 'da_giai_quyet'; }));
            ?>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="text-muted small">Tổng yêu cầu</div>
                    <h3 class="mb-0"><?php echo $total; ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card border-start border-danger border-3">
                    <div class="text-muted small">Khẩn cấp</div>
                    <h3 class="mb-0 text-danger"><?php echo $khan_cap; ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card border-start border-warning border-3">
                    <div class="text-muted small">Đang xử lý</div>
                    <h3 class="mb-0 text-warning"><?php echo $dang_xu_ly; ?></h3>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card border-start border-success border-3">
                    <div class="text-muted small">Đã giải quyết</div>
                    <h3 class="mb-0 text-success"><?php echo $da_giai_quyet; ?></h3>
                </div>
            </div>
        </div>
        
        <!-- Add Request Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">Danh sách yêu cầu đặc biệt</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRequestModal">
                <i class="bi bi-plus-circle"></i> Thêm yêu cầu
            </button>
        </div>
        
        <!-- Requests List -->
        <?php if (!empty($yeu_cau_list)): ?>
        <div class="row">
            <?php foreach($yeu_cau_list as $yc): ?>
            <div class="col-md-6 mb-3">
                <div class="card request-card priority-<?php echo $yc['muc_do_uu_tien']; ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge-type type-<?php echo $yc['loai_yeu_cau']; ?> me-2">
                                        <?php 
                                        $types = [
                                            'an_uong' => '🍽️ Ăn uống',
                                            'suc_khoe' => '💊 Sức khỏe',
                                            'di_chuyen' => '🚗 Di chuyển',
                                            'phong_o' => '🏨 Phòng ở',
                                            'hoat_dong' => '🎯 Hoạt động',
                                            'khac' => '📌 Khác'
                                        ];
                                        echo $types[$yc['loai_yeu_cau']] ?? 'Khác';
                                        ?>
                                    </span>
                                    <span class="status-badge status-<?php echo $yc['trang_thai']; ?>">
                                        <?php 
                                        $statuses = [
                                            'moi' => '🆕 Mới',
                                            'dang_xu_ly' => '⏳ Đang xử lý',
                                            'da_giai_quyet' => '✅ Đã giải quyết',
                                            'khong_the_thuc_hien' => '❌ Không thể thực hiện'
                                        ];
                                        echo $statuses[$yc['trang_thai']] ?? 'Mới';
                                        ?>
                                    </span>
                                </div>
                                <h6 class="mb-2"><?php echo htmlspecialchars($yc['tieu_de']); ?></h6>
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($yc['ho_ten'] ?? 'N/A'); ?>
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-tag"></i> Booking #<?php echo htmlspecialchars($yc['booking_id'] ?? 'N/A'); ?>
                                </div>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" 
                                        onclick="editRequest(<?php echo htmlspecialchars(json_encode($yc), ENT_QUOTES, 'UTF-8'); ?>)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="index.php?act=hdv/delete_yeu_cau&id=<?php echo $yc['id']; ?>&tour_id=<?php echo $_GET['tour_id']; ?>" 
                                   class="btn btn-outline-danger"
                                   onclick="return confirm('Xóa yêu cầu này?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        
                        <?php if (!empty($yc['mo_ta'])): ?>
                        <div class="mb-2">
                            <strong>Mô tả:</strong>
                            <p class="mb-0 small"><?php echo nl2br(htmlspecialchars($yc['mo_ta'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($yc['ghi_chu_hdv'])): ?>
                        <div class="alert alert-info mb-2 small">
                            <strong><i class="bi bi-chat-dots"></i> Ghi chú HDV:</strong><br>
                            <?php echo nl2br(htmlspecialchars($yc['ghi_chu_hdv'])); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-center small text-muted">
                            <span>
                                <i class="bi bi-flag"></i> 
                                <?php 
                                $priorities = [
                                    'thap' => 'Thấp',
                                    'trung_binh' => 'Trung bình',
                                    'cao' => 'Cao',
                                    'khan_cap' => 'Khẩn cấp'
                                ];
                                echo $priorities[$yc['muc_do_uu_tien']] ?? 'Trung bình';
                                ?>
                            </span>
                            <span>
                                <i class="bi bi-clock"></i> <?php echo date('d/m/Y H:i', strtotime($yc['ngay_tao'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Chưa có yêu cầu đặc biệt nào cho tour này
        </div>
        <?php endif; ?>
        
        <?php endif; ?>
    </div>

    <!-- Add/Edit Request Modal -->
    <div class="modal fade" id="addRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="bi bi-plus-circle"></i> Thêm yêu cầu đặc biệt
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?act=hdv/save_yeu_cau">
                    <input type="hidden" name="tour_id" value="<?php echo $_GET['tour_id'] ?? ''; ?>">
                    <input type="hidden" name="yeu_cau_id" id="yeu_cau_id" value="">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                                <select class="form-select" name="booking_id" id="booking_id" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    <?php if(isset($bookings_list)) foreach($bookings_list as $b): ?>
                                    <option value="<?php echo $b['booking_id']; ?>">
                                        <?php echo htmlspecialchars($b['ho_ten']); ?> 
                                        (Booking #<?php echo $b['booking_id']; ?> - <?php echo $b['so_nguoi']; ?> người)
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại yêu cầu <span class="text-danger">*</span></label>
                                <select class="form-select" name="loai_yeu_cau" id="loai_yeu_cau" required>
                                    <option value="an_uong">🍽️ Ăn uống (ăn chay, dị ứng...)</option>
                                    <option value="suc_khoe">💊 Sức khỏe (bệnh lý, thuốc...)</option>
                                    <option value="di_chuyen">🚗 Di chuyển (xe lăn, chậm chân...)</option>
                                    <option value="phong_o">🏨 Phòng ở (tầng thấp, gần thang máy...)</option>
                                    <option value="hoat_dong">🎯 Hoạt động (không leo núi, không bơi...)</option>
                                    <option value="khac">📌 Khác</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tieu_de" id="tieu_de"
                                   placeholder="VD: Ăn chay trường, Dị ứng hải sản, Đái tháo đường..." required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control" name="mo_ta" id="mo_ta" rows="4" 
                                      placeholder="Mô tả chi tiết yêu cầu, lưu ý cần thiết..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mức độ ưu tiên <span class="text-danger">*</span></label>
                                <select class="form-select" name="muc_do_uu_tien" id="muc_do_uu_tien" required>
                                    <option value="thap">⬇️ Thấp</option>
                                    <option value="trung_binh" selected>➡️ Trung bình</option>
                                    <option value="cao">⬆️ Cao</option>
                                    <option value="khan_cap">🚨 Khẩn cấp</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select" name="trang_thai" id="trang_thai" required>
                                    <option value="moi" selected>🆕 Mới</option>
                                    <option value="dang_xu_ly">⏳ Đang xử lý</option>
                                    <option value="da_giai_quyet">✅ Đã giải quyết</option>
                                    <option value="khong_the_thuc_hien">❌ Không thể thực hiện</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ghi chú của HDV</label>
                            <textarea class="form-control" name="ghi_chu_hdv" id="ghi_chu_hdv" rows="3" 
                                      placeholder="Ghi chú về cách xử lý, kết quả..."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu yêu cầu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editRequest(data) {
            document.getElementById('yeu_cau_id').value = data.id;
            document.getElementById('booking_id').value = data.booking_id;
            document.getElementById('loai_yeu_cau').value = data.loai_yeu_cau;
            document.getElementById('tieu_de').value = data.tieu_de || '';
            document.getElementById('mo_ta').value = data.mo_ta || '';
            document.getElementById('muc_do_uu_tien').value = data.muc_do_uu_tien;
            document.getElementById('trang_thai').value = data.trang_thai;
            document.getElementById('ghi_chu_hdv').value = data.ghi_chu_hdv || '';
            
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil"></i> Cập nhật yêu cầu đặc biệt';
            
            new bootstrap.Modal(document.getElementById('addRequestModal')).show();
        }
        
        // Reset form khi đóng modal
        document.getElementById('addRequestModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('yeu_cau_id').value = '';
            document.querySelector('form').reset();
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-plus-circle"></i> Thêm yêu cầu đặc biệt';
        });
    </script>
</body>
</html>
