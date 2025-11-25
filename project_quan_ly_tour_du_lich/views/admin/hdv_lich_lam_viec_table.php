<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch làm việc HDV - Dạng bảng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-calendar-check"></i> Lịch làm việc HDV
            </a>
            <div class="ms-auto">
                <a href="index.php?act=admin/hdv_advanced" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; ?>
            <div class="alert alert-<?php echo htmlspecialchars($f['type']); ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($f['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); endif; ?>

        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-calendar3"></i> Lịch làm việc tất cả HDV</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                    <i class="bi bi-plus-circle"></i> Thêm lịch
                </button>
            </div>
            <div class="card-body">
                <!-- Bộ lọc -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <select class="form-select" id="filterHDV" onchange="filterTable()">
                            <option value="">Tất cả HDV</option>
                            <?php if (!empty($hdv_list)): foreach($hdv_list as $hdv): ?>
                            <option value="<?php echo $hdv['nhan_su_id']; ?>">
                                <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                            </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filterTrangThai" onchange="filterTable()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="ChoXacNhan">Chờ xác nhận</option>
                            <option value="DaXacNhan">Đã xác nhận</option>
                            <option value="SapKhoiHanh">Sắp khởi hành</option>
                            <option value="DangChay">Đang chạy</option>
                            <option value="HoanThanh">Hoàn thành</option>
                            <option value="Huy">Hủy</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="month" class="form-control" id="filterThang" onchange="filterTable()" 
                               value="<?php echo date('Y-m'); ?>">
                    </div>
                </div>

                <!-- Bảng lịch làm việc -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">HDV</th>
                                <th width="25%">Tour</th>
                                <th width="12%">Ngày KH</th>
                                <th width="12%">Ngày KT</th>
                                <th width="10%">Trạng thái LKH</th>
                                <th width="10%">Trạng thái PB</th>
                                <th width="6%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <?php if (!empty($lich_lam_viec)): 
                                $stt = 1;
                                foreach($lich_lam_viec as $lich): 
                            ?>
                            <tr class="schedule-row" 
                                data-hdv="<?php echo $lich['nhan_su_id']; ?>"
                                data-trangthai="<?php echo $lich['trang_thai']; ?>"
                                data-thang="<?php echo date('Y-m', strtotime($lich['ngay_khoi_hanh'])); ?>">
                                <td><?php echo $stt++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($lich['ho_ten'] ?? 'N/A'); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-person-badge"></i> 
                                        <?php echo $lich['vai_tro'] ?? 'HDV'; ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($lich['ten_tour'] ?? 'Chưa có thông tin tour'); ?>
                                </td>
                                <td>
                                    <i class="bi bi-calendar-event"></i>
                                    <?php echo date('d/m/Y', strtotime($lich['ngay_khoi_hanh'])); ?>
                                </td>
                                <td>
                                    <i class="bi bi-calendar-check"></i>
                                    <?php echo date('d/m/Y', strtotime($lich['ngay_ket_thuc'])); ?>
                                </td>
                                <td>
                                    <?php
                                    $badge_class = 'secondary';
                                    switch($lich['trang_thai']) {
                                        case 'SapKhoiHanh': $badge_class = 'info'; break;
                                        case 'DangChay': $badge_class = 'success'; break;
                                        case 'HoanThanh': $badge_class = 'dark'; break;
                                        case 'DaXacNhan': $badge_class = 'primary'; break;
                                        case 'ChoXacNhan': $badge_class = 'warning'; break;
                                        case 'Huy': $badge_class = 'danger'; break;
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $badge_class; ?>">
                                        <?php echo $lich['trang_thai']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $pb_badge = 'secondary';
                                    switch($lich['pb_trang_thai'] ?? 'ChoXacNhan') {
                                        case 'DaXacNhan': $pb_badge = 'success'; break;
                                        case 'ChoXacNhan': $pb_badge = 'warning'; break;
                                        case 'TuChoi': $pb_badge = 'danger'; break;
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $pb_badge; ?>">
                                        <?php echo $lich['pb_trang_thai'] ?? 'ChoXacNhan'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?act=admin/chi_tiet_lich_khoi_hanh&id=<?php echo $lich['id']; ?>" 
                                       class="btn btn-sm btn-primary" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Chưa có lịch làm việc nào</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Thống kê -->
                <?php if (!empty($lich_lam_viec)): ?>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <strong>Tổng số lịch:</strong> <?php echo count($lich_lam_viec); ?> lịch làm việc
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Debug info -->
        <div class="card mt-3">
            <div class="card-header">
                <strong>Debug Info</strong>
            </div>
            <div class="card-body">
                <p><strong>API Endpoint:</strong> <code>index.php?act=admin/hdv_get_schedule</code></p>
                <button class="btn btn-sm btn-secondary" onclick="testAPI()">Test API</button>
                <pre id="apiResult" class="mt-2 bg-light p-2" style="max-height: 300px; overflow-y: auto;"></pre>
            </div>
        </div>
    </div>

    <!-- Modal: Thêm lịch -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm lịch làm việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="index.php?act=admin/hdv_add_schedule">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">HDV *</label>
                            <select name="hdv_id" class="form-select" required>
                                <option value="">-- Chọn HDV --</option>
                                <?php if (!empty($hdv_list)): foreach($hdv_list as $hdv): ?>
                                <option value="<?php echo $hdv['nhan_su_id']; ?>">
                                    <?php echo htmlspecialchars($hdv['ho_ten']); ?>
                                </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tour *</label>
                            <select name="tour_id" class="form-select" required>
                                <option value="">-- Chọn tour --</option>
                                <?php if (!empty($tours)): foreach($tours as $tour): ?>
                                <option value="<?php echo $tour['tour_id']; ?>">
                                    <?php echo htmlspecialchars($tour['ten_tour']); ?>
                                </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày khởi hành *</label>
                                <input type="date" name="ngay_khoi_hanh" class="form-control" required 
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kết thúc *</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control" required
                                       min="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Điểm tập trung</label>
                            <input type="text" name="diem_tap_trung" class="form-control" 
                                   placeholder="VD: Công viên 23/9, TP.HCM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="ChoXacNhan">Chờ xác nhận</option>
                                <option value="DaXacNhan" selected>Đã xác nhận</option>
                                <option value="SapKhoiHanh">Sắp khởi hành</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Thêm lịch
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterTable() {
            const hdvFilter = document.getElementById('filterHDV').value;
            const trangThaiFilter = document.getElementById('filterTrangThai').value;
            const thangFilter = document.getElementById('filterThang').value;
            
            const rows = document.querySelectorAll('.schedule-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const hdv = row.dataset.hdv;
                const trangThai = row.dataset.trangthai;
                const thang = row.dataset.thang;
                
                const matchHDV = !hdvFilter || hdv === hdvFilter;
                const matchTrangThai = !trangThaiFilter || trangThai === trangThaiFilter;
                const matchThang = !thangFilter || thang === thangFilter;
                
                if (matchHDV && matchTrangThai && matchThang) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            console.log('Filtered:', visibleCount, 'rows visible');
        }

        function testAPI() {
            const resultDiv = document.getElementById('apiResult');
            resultDiv.textContent = 'Loading...';
            
            fetch('index.php?act=admin/hdv_get_schedule')
                .then(response => response.json())
                .then(data => {
                    resultDiv.textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    resultDiv.textContent = 'Error: ' + error.message;
                });
        }
    </script>
</body>
</html>
