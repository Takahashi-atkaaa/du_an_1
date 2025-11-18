<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
        }
        .vai-tro-HDV { background: #0d6efd; color: white; }
        .vai-tro-TaiXe { background: #6c757d; color: white; }
        .vai-tro-HauCan { background: #198754; color: white; }
        .vai-tro-DieuHanh { background: #ffc107; color: #000; }
        .vai-tro-Khac { background: #6c757d; color: white; }
        
        .trang-thai-ChoXacNhan { background: #fff3cd; color: #856404; }
        .trang-thai-DaXacNhan { background: #d4edda; color: #155724; }
        .trang-thai-TuChoi { background: #f8d7da; color: #721c24; }
        .trang-thai-Huy { background: #e2e3e5; color: #383d41; }
        
        .service-card {
            border-left: 4px solid #6c757d;
            margin-bottom: 1rem;
        }
        .service-card.Xe { border-left-color: #0d6efd; }
        .service-card.KhachSan { border-left-color: #198754; }
        .service-card.VeMayBay { border-left-color: #dc3545; }
        .service-card.NhaHang { border-left-color: #fd7e14; }
        .service-card.DiemThamQuan { border-left-color: #20c997; }
        .service-card.Visa { border-left-color: #6f42c1; }
        .service-card.BaoHiem { border-left-color: #d63384; }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="ms-auto">
                <a href="index.php?act=lichKhoiHanh/index" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <?php if (!empty($_SESSION['flash'])): $flash = $_SESSION['flash']; ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($flash['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); endif; ?>

        <!-- Thông tin lịch khởi hành -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Thông tin Lịch Khởi Hành</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tour:</strong> <?php echo htmlspecialchars($lich['ten_tour'] ?? 'N/A'); ?></p>
                        <p><strong>Mã chuyến:</strong> <?php echo htmlspecialchars($lich['ma_chuyen'] ?? 'N/A'); ?></p>
                        <p><strong>Ngày khởi hành:</strong> <?php echo date('d/m/Y', strtotime($lich['ngay_khoi_hanh'] ?? 'now')); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Trạng thái:</strong> 
                            <span class="badge bg-<?php echo $lich['trang_thai'] == 'HoanTat' ? 'success' : 'warning'; ?>">
                                <?php echo htmlspecialchars($lich['trang_thai'] ?? 'N/A'); ?>
                            </span>
                        </p>
                        <p><strong>Số chỗ đã đặt:</strong> <?php echo $lich['so_cho_da_dat'] ?? 0; ?>/<?php echo $lich['so_cho_toi_da'] ?? 0; ?></p>
                        <p><strong>Giá tour:</strong> <?php echo number_format($lich['gia_tour'] ?? 0, 0, ',', '.'); ?> VNĐ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-nhan-su">
                    <i class="bi bi-people"></i> Phân bổ nhân sự (<?php echo count($phan_bo_nhan_su ?? []); ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-dich-vu">
                    <i class="bi bi-box"></i> Phân bổ dịch vụ (<?php echo count($phan_bo_dich_vu ?? []); ?>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-lich-su">
                    <i class="bi bi-clock-history"></i> Lịch sử thay đổi
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Tab Phân bổ nhân sự -->
            <div class="tab-pane fade show active" id="tab-nhan-su">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Danh sách nhân sự</h6>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddNhanSu">
                            <i class="bi bi-plus-circle"></i> Thêm nhân sự
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Họ tên</th>
                                        <th>Vai trò</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                        <th>Thời gian xác nhận</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($phan_bo_nhan_su)): foreach($phan_bo_nhan_su as $pb): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pb['ho_ten'] ?? 'N/A'); ?></td>
                                        <td><span class="badge vai-tro-<?php echo $pb['vai_tro']; ?>"><?php echo $pb['vai_tro']; ?></span></td>
                                        <td><span class="badge trang-thai-<?php echo $pb['trang_thai']; ?>"><?php echo $pb['trang_thai']; ?></span></td>
                                        <td><?php echo htmlspecialchars($pb['ghi_chu'] ?? ''); ?></td>
                                        <td><?php echo $pb['thoi_gian_xac_nhan'] ? date('d/m/Y H:i', strtotime($pb['thoi_gian_xac_nhan'])) : '-'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" onclick="editNhanSu(<?php echo $pb['id']; ?>)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="deleteNhanSu(<?php echo $pb['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Chưa có nhân sự nào được phân bổ</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Phân bổ dịch vụ -->
            <div class="tab-pane fade" id="tab-dich-vu">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Danh sách dịch vụ</h6>
                        <div>
                            <span class="me-3"><strong>Tổng chi phí:</strong> <?php echo number_format($tong_chi_phi ?? 0, 0, ',', '.'); ?> VNĐ</span>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddDichVu">
                                <i class="bi bi-plus-circle"></i> Thêm dịch vụ
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($phan_bo_dich_vu)): 
                            $grouped = [];
                            foreach($phan_bo_dich_vu as $dv) {
                                $grouped[$dv['loai_dich_vu']][] = $dv;
                            }
                            foreach($grouped as $loai => $dich_vus): ?>
                            <h6 class="mt-3 mb-2">
                                <i class="bi bi-tag"></i> <?php echo $loai; ?> 
                                <span class="badge bg-secondary"><?php echo count($dich_vus); ?></span>
                            </h6>
                            <?php foreach($dich_vus as $dv): ?>
                            <div class="card service-card <?php echo $loai; ?>">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></h6>
                                            <?php if ($dv['nha_cung_cap_id']): ?>
                                            <p class="mb-1"><i class="bi bi-building"></i> <small><?php echo htmlspecialchars($dv['ten_nha_cung_cap'] ?? ''); ?></small></p>
                                            <?php endif; ?>
                                            <p class="mb-1">
                                                <i class="bi bi-calendar"></i>
                                                <?php echo date('d/m/Y', strtotime($dv['ngay_bat_dau'])); ?>
                                                <?php if ($dv['ngay_ket_thuc']): ?>
                                                - <?php echo date('d/m/Y', strtotime($dv['ngay_ket_thuc'])); ?>
                                                <?php endif; ?>
                                                <?php if ($dv['gio_bat_dau']): ?>
                                                <i class="bi bi-clock ms-2"></i> <?php echo substr($dv['gio_bat_dau'], 0, 5); ?>
                                                <?php endif; ?>
                                            </p>
                                            <?php if ($dv['dia_diem']): ?>
                                            <p class="mb-1"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($dv['dia_diem']); ?></p>
                                            <?php endif; ?>
                                            <?php if ($dv['ghi_chu']): ?>
                                            <p class="mb-0"><small class="text-muted"><?php echo htmlspecialchars($dv['ghi_chu']); ?></small></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <h6>
                                                <?php echo number_format($dv['gia_tien'] ?? 0, 0, ',', '.'); ?> VNĐ
                                                <?php if ($dv['so_luong'] > 1): ?>
                                                x <?php echo $dv['so_luong']; ?>
                                                <?php endif; ?>
                                            </h6>
                                            <?php if ($dv['so_luong'] > 1): ?>
                                            <p class="mb-2"><strong><?php echo number_format($dv['gia_tien'] * $dv['so_luong'], 0, ',', '.'); ?> VNĐ</strong></p>
                                            <?php endif; ?>
                                            <span class="badge trang-thai-<?php echo $dv['trang_thai']; ?> mb-2"><?php echo $dv['trang_thai']; ?></span>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-warning" onclick="editDichVu(<?php echo $dv['id']; ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger" onclick="deleteDichVu(<?php echo $dv['id']; ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="alert alert-info">Chưa có dịch vụ nào được phân bổ</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tab Lịch sử -->
            <div class="tab-pane fade" id="tab-lich-su">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>Loại</th>
                                        <th>Thay đổi</th>
                                        <th>Người thực hiện</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($history)): foreach($history as $h): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y H:i:s', strtotime($h['thoi_gian'])); ?></td>
                                        <td><span class="badge bg-info"><?php echo $h['loai_phan_bo']; ?></span></td>
                                        <td><?php echo htmlspecialchars($h['thay_doi']); ?></td>
                                        <td><?php echo htmlspecialchars($h['nguoi_thay_doi'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Chưa có lịch sử thay đổi</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm nhân sự -->
    <div class="modal fade" id="modalAddNhanSu" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="index.php?act=admin/phan_bo_nhan_su_add">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm nhân sự</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich['id'] ?? 0; ?>">
                        <div class="mb-3">
                            <label class="form-label">Nhân sự *</label>
                            <select name="nhan_su_id" class="form-select" required>
                                <option value="">-- Chọn nhân sự --</option>
                                <?php if (!empty($available_nhan_su)): foreach($available_nhan_su as $ns): ?>
                                <option value="<?php echo $ns['id']; ?>"><?php echo htmlspecialchars($ns['ho_ten']); ?> - <?php echo $ns['chuc_vu']; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Vai trò *</label>
                            <select name="vai_tro" class="form-select" required>
                                <option value="HDV">Hướng dẫn viên</option>
                                <option value="TaiXe">Tài xế</option>
                                <option value="HauCan">Hậu cần</option>
                                <option value="DieuHanh">Điều hành</option>
                                <option value="Khac">Khác</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Thêm dịch vụ -->
    <div class="modal fade" id="modalAddDichVu" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="index.php?act=admin/phan_bo_dich_vu_add">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm dịch vụ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lich['id'] ?? 0; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại dịch vụ *</label>
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhà cung cấp</label>
                                <select name="nha_cung_cap_id" class="form-select">
                                    <option value="">-- Chọn nhà cung cấp --</option>
                                    <?php if (!empty($nha_cung_cap_list)): foreach($nha_cung_cap_list as $ncc): ?>
                                    <option value="<?php echo $ncc['id_nha_cung_cap']; ?>"><?php echo htmlspecialchars($ncc['ten_don_vi']); ?></option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên dịch vụ *</label>
                            <input type="text" name="ten_dich_vu" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="so_luong" class="form-control" value="1" min="1">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Đơn vị</label>
                                <input type="text" name="don_vi" class="form-control" placeholder="VD: chiếc, phòng">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Giá tiền *</label>
                                <input type="number" name="gia_tien" class="form-control" required step="1000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" name="ngay_bat_dau" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" name="ngay_ket_thuc" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giờ bắt đầu</label>
                                <input type="time" name="gio_bat_dau" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giờ kết thúc</label>
                                <input type="time" name="gio_ket_thuc" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa điểm</label>
                            <input type="text" name="dia_diem" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteNhanSu(id) {
            if (confirm('Bạn có chắc muốn xóa nhân sự này?')) {
                window.location.href = 'index.php?act=admin/phan_bo_nhan_su_delete&id=' + id;
            }
        }
        
        function deleteDichVu(id) {
            if (confirm('Bạn có chắc muốn xóa dịch vụ này?')) {
                window.location.href = 'index.php?act=admin/phan_bo_dich_vu_delete&id=' + id;
            }
        }
        
        function editNhanSu(id) {
            // TODO: Implement edit modal
            alert('Chức năng chỉnh sửa đang phát triển');
        }
        
        function editDichVu(id) {
            // TODO: Implement edit modal
            alert('Chức năng chỉnh sửa đang phát triển');
        }
    </script>
</body>
</html>
