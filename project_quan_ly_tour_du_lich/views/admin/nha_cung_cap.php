<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà cung cấp - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
<?php
$selectedSupplier = $selectedSupplier ?? null;
$serviceTypeSummary = $serviceTypeSummary ?? [];
$supplierStats = $supplierStats ?? [];
$supplierServices = $supplierServices ?? [];
$serviceTypes = $serviceTypes ?? [];
$selectedLoai = $selectedLoai ?? null;
$loaiDichVuMap = [
    'KhachSan' => 'Khách sạn',
    'NhaHang' => 'Nhà hàng',
    'Xe' => 'Xe vận chuyển',
    'Ve' => 'Vé máy bay / tàu',
    'Visa' => 'Visa',
    'BaoHiem' => 'Bảo hiểm',
    'Khac' => 'Khác'
];
$statusMap = [
    'ChoXacNhan' => ['Chờ xác nhận', 'warning'],
    'DaXacNhan' => ['Đã xác nhận', 'success'],
    'TuChoi' => ['Từ chối', 'danger'],
    'Huy' => ['Hủy', 'secondary'],
    'HoanTat' => ['Hoàn tất', 'info']
];
?>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-building"></i> Quản lý Nhà cung cấp</h1>
                <p class="text-muted mb-0">Theo dõi đối tác khách sạn, nhà hàng, vận chuyển, vé, visa, bảo hiểm</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp
                </button>
                <a href="index.php?act=admin/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>
        </div>

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

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách đối tác</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($nhaCungCapList)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-inbox mb-2" style="font-size: 3rem;"></i>
                                <p>Chưa có nhà cung cấp nào</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($nhaCungCapList as $ncc): ?>
                                <a href="index.php?act=admin/nhaCungCap&id=<?php echo $ncc['id_nha_cung_cap']; ?>" 
                                   class="list-group-item list-group-item-action <?php echo ($selectedSupplier && $selectedSupplier['id_nha_cung_cap'] == $ncc['id_nha_cung_cap']) ? 'active' : ''; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?></strong>
                                            <div class="small opacity-75">
                                                <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($ncc['dia_chi'] ?? 'Chưa cập nhật'); ?>
                                            </div>
                                        </div>
                                        <span class="badge bg-light text-dark">
                                            <?php echo $loaiDichVuMap[$ncc['loai_dich_vu']] ?? $ncc['loai_dich_vu']; ?>
                                        </span>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Thông tin chi tiết</h5>
                        <?php if ($selectedSupplier): ?>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewSupplierModal<?php echo $selectedSupplier['id_nha_cung_cap']; ?>">
                                <i class="bi bi-eye"></i> Xem
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal<?php echo $selectedSupplier['id_nha_cung_cap']; ?>">
                                <i class="bi bi-pencil"></i> Sửa
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!$selectedSupplier): ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-arrow-left-right mb-2" style="font-size: 3rem;"></i>
                                <p>Chọn một nhà cung cấp ở danh sách bên trái để xem chi tiết.</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h4 class="mb-1"><?php echo htmlspecialchars($selectedSupplier['ten_don_vi']); ?></h4>
                                    <span class="badge bg-info mb-2">
                                        <?php echo $loaiDichVuMap[$selectedSupplier['loai_dich_vu']] ?? $selectedSupplier['loai_dich_vu']; ?>
                                    </span>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="bi bi-geo-alt text-primary"></i> <?php echo htmlspecialchars($selectedSupplier['dia_chi'] ?? 'Chưa cập nhật'); ?></li>
                                        <li><i class="bi bi-telephone text-primary"></i> <?php echo htmlspecialchars($selectedSupplier['lien_he'] ?? 'Chưa cập nhật'); ?></li>
                                        <?php if ($selectedSupplier['danh_gia_tb']): ?>
                                        <li><i class="bi bi-star-fill text-warning"></i> Đánh giá TB: <?php echo number_format($selectedSupplier['danh_gia_tb'], 1); ?>/5</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Mô tả năng lực</h6>
                                    <p class="text-muted mb-2">
                                        <?php echo $selectedSupplier['mo_ta'] ? nl2br(htmlspecialchars($selectedSupplier['mo_ta'])) : 'Chưa có mô tả chi tiết.'; ?>
                                    </p>
                                    <?php if (!empty($serviceTypes)): ?>
                                        <div class="small text-uppercase text-muted fw-bold mb-1">Danh mục dịch vụ đã cung ứng</div>
                                        <?php foreach ($serviceTypes as $type): ?>
                                            <span class="badge bg-secondary me-1 mb-1"><?php echo $loaiDichVuMap[$type] ?? $type; ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($selectedSupplier): ?>
        <div class="row mt-4 g-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Tổng dịch vụ</div>
                        <div class="display-6 fw-bold"><?php echo $supplierStats['tong_dich_vu'] ?? 0; ?></div>
                        <small class="text-muted">Tính từ <?php echo $supplierStats['hop_tac_tu'] ? date('d/m/Y', strtotime($supplierStats['hop_tac_tu'])) : 'N/A'; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Đã xác nhận</div>
                        <div class="display-6 fw-bold text-success"><?php echo $supplierStats['da_xac_nhan'] ?? 0; ?></div>
                        <small class="text-muted">Đang chờ: <?php echo $supplierStats['cho_xac_nhan'] ?? 0; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Giá trị hợp đồng</div>
                        <div class="display-6 fw-bold text-primary">
                            <?php echo number_format($supplierStats['tong_gia_tri'] ?? 0, 0, ',', '.'); ?>đ
                        </div>
                        <small class="text-muted">Cập nhật: <?php echo $supplierStats['moi_nhat'] ? date('d/m/Y', strtotime($supplierStats['moi_nhat'])) : 'N/A'; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted text-uppercase small">Từ chối/Hủy</div>
                        <div class="display-6 fw-bold text-danger"><?php echo $supplierStats['tu_choi'] ?? 0; ?></div>
                        <small class="text-muted">Theo dõi để cải thiện chất lượng</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Thống kê theo loại dịch vụ</h5>
                    <small class="text-muted">Danh mục dịch vụ và năng lực cung ứng</small>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($serviceTypeSummary)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p>Nhà cung cấp chưa tham gia tour nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Loại dịch vụ</th>
                                    <th>Lần cung cấp</th>
                                    <th>Đã xác nhận</th>
                                    <th>Giá trị</th>
                                    <th>Từ ngày</th>
                                    <th>Gần nhất</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($serviceTypeSummary as $summary): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?php echo $loaiDichVuMap[$summary['loai_dich_vu']] ?? $summary['loai_dich_vu']; ?></span></td>
                                    <td><?php echo $summary['so_lan_cung_cap']; ?></td>
                                    <td><?php echo $summary['so_da_xac_nhan']; ?></td>
                                    <td><?php echo number_format($summary['tong_doanh_thu'] ?? 0, 0, ',', '.'); ?>đ</td>
                                    <td><?php echo $summary['lan_dau'] ? date('d/m/Y', strtotime($summary['lan_dau'])) : '-'; ?></td>
                                    <td><?php echo $summary['lan_gan_nhat'] ? date('d/m/Y', strtotime($summary['lan_gan_nhat'])) : '-'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                    </tbody>
                </table>
                    </div>
            <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử cung ứng dịch vụ</h5>
                    <small class="text-muted">Theo dõi các tour đã hợp tác với đối tác</small>
                </div>
                <form class="d-flex gap-2" method="GET" action="index.php">
                    <input type="hidden" name="act" value="admin/nhaCungCap">
                    <input type="hidden" name="id" value="<?php echo $selectedSupplier['id_nha_cung_cap']; ?>">
                    <select name="loai" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Tất cả loại dịch vụ</option>
                        <?php foreach ($serviceTypes as $type): ?>
                            <option value="<?php echo $type; ?>" <?php echo ($selectedLoai === $type) ? 'selected' : ''; ?>>
                                <?php echo $loaiDichVuMap[$type] ?? $type; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Lọc</button>
                </form>
            </div>
            <div class="card-body">
                <?php if (empty($supplierServices)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-journal-minus" style="font-size: 3rem;"></i>
                        <p>Chưa có dữ liệu cho bộ lọc này</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tour</th>
                                    <th>Dịch vụ</th>
                                    <th>Thời gian</th>
                                    <th>Giá trị</th>
                            <th>Trạng thái</th>
                                    <th>Ghi chú</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                                <?php foreach ($supplierServices as $service): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($service['ten_tour'] ?? 'Tour chưa đặt tên'); ?></strong><br>
                                        <small class="text-muted">LKH #<?php echo $service['lich_khoi_hanh_id'] ?? '-'; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo $loaiDichVuMap[$service['loai_dich_vu']] ?? $service['loai_dich_vu']; ?></span><br>
                                        <small><?php echo htmlspecialchars($service['ten_dich_vu']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo $service['ngay_bat_dau'] ? date('d/m/Y', strtotime($service['ngay_bat_dau'])) : '-'; ?>
                                        <?php if ($service['ngay_ket_thuc']): ?>
                                            <br><small class="text-muted">đến <?php echo date('d/m/Y', strtotime($service['ngay_ket_thuc'])); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($service['gia_tien']): ?>
                                            <strong><?php echo number_format($service['gia_tien'], 0, ',', '.'); ?>đ</strong>
                                        <?php else: ?>
                                            <span class="text-muted">Đang cập nhật</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $status = $statusMap[$service['trang_thai']] ?? [$service['trang_thai'], 'secondary'];
                                        ?>
                                        <span class="badge bg-<?php echo $status[1]; ?>"><?php echo $status[0]; ?></span>
                                    </td>
                                    <td><?php echo $service['ghi_chu'] ? nl2br(htmlspecialchars($service['ghi_chu'])) : '<span class="text-muted">-</span>'; ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="index.php?act=admin/chiTietDichVu&id=<?php echo $service['id']; ?>&ncc_id=<?php echo $selectedSupplier['id_nha_cung_cap'] ?? ''; ?>" 
                                               class="btn btn-info text-white" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($service['trang_thai'] === 'ChoXacNhan'): ?>
                                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveServiceModal<?php echo $service['id']; ?>">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectServiceModal<?php echo $service['id']; ?>">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (in_array($service['trang_thai'], ['ChoXacNhan', 'DaXacNhan'])): ?>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePriceModal<?php echo $service['id']; ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if (!empty($service['ghi_chu'])): ?>
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#noteServiceModal<?php echo $service['id']; ?>">
                                                    <i class="bi bi-file-text"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                    </tbody>
                </table>
                    </div>
        <?php endif; ?>
            </div>

            </div>

    <?php if (!empty($supplierServices)): ?>
        <?php foreach ($supplierServices as $service): ?>
            <?php $serviceId = $service['id']; ?>
            <?php if ($service['trang_thai'] === 'ChoXacNhan'): ?>
                <!-- Approve modal -->
                <div class="modal fade" id="approveServiceModal<?php echo $serviceId; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Xác nhận dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="index.php?act=admin/supplierServiceAction">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="dich_vu_id" value="<?php echo $serviceId; ?>">
                                    <input type="hidden" name="ncc_id" value="<?php echo $selectedSupplier['id_nha_cung_cap'] ?? ''; ?>">
                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($service['ten_tour'] ?? 'N/A'); ?></p>
                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($service['ten_dich_vu']); ?></p>
                                    <div class="mb-3">
                                        <label class="form-label">Giá phê duyệt (VND)</label>
                                        <input type="number" class="form-control" name="gia_tien" min="0" step="1000" value="<?php echo $service['gia_tien'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-success">Xác nhận</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Reject modal -->
                <div class="modal fade" id="rejectServiceModal<?php echo $serviceId; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Từ chối dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="index.php?act=admin/supplierServiceAction">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="dich_vu_id" value="<?php echo $serviceId; ?>">
                                    <input type="hidden" name="ncc_id" value="<?php echo $selectedSupplier['id_nha_cung_cap'] ?? ''; ?>">
                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($service['ten_tour'] ?? 'N/A'); ?></p>
                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($service['ten_dich_vu']); ?></p>
                                    <div class="mb-3">
                                        <label class="form-label">Lý do / ghi chú</label>
                                        <textarea class="form-control" name="ghi_chu" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-danger">Từ chối</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (in_array($service['trang_thai'], ['ChoXacNhan', 'DaXacNhan'])): ?>
                <!-- Update price modal -->
                <div class="modal fade" id="updatePriceModal<?php echo $serviceId; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Cập nhật giá dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="index.php?act=admin/supplierServiceAction">
                                <div class="modal-body">
                                    <input type="hidden" name="action" value="update_price">
                                    <input type="hidden" name="dich_vu_id" value="<?php echo $serviceId; ?>">
                                    <input type="hidden" name="ncc_id" value="<?php echo $selectedSupplier['id_nha_cung_cap'] ?? ''; ?>">
                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($service['ten_tour'] ?? 'N/A'); ?></p>
                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($service['ten_dich_vu']); ?></p>
                                    <div class="mb-3">
                                        <label class="form-label">Giá mới (VND)</label>
                                        <input type="number" class="form-control" name="gia_tien" min="0" step="1000" value="<?php echo $service['gia_tien'] ?? ''; ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($service['ghi_chu'])): ?>
                <!-- Note modal -->
                <div class="modal fade" id="noteServiceModal<?php echo $serviceId; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Ghi chú dịch vụ</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php echo nl2br(htmlspecialchars($service['ghi_chu'])); ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
        </div>
            <?php endif; ?>
    </div>

    <!-- Modal Thêm nhà cung cấp -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Nhà cung cấp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?act=admin/addNhacungcap">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên đơn vị *</label>
                                <input type="text" class="form-control" name="ten_don_vi" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Loại dịch vụ</label>
                                <select class="form-select" name="loai_dich_vu">
                                    <?php foreach ($loaiDichVuMap as $key => $label): ?>
                                        <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Địa chỉ</label>
                                <input type="text" class="form-control" name="dia_chi">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Liên hệ</label>
                                <input type="text" class="form-control" name="lien_he">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả dịch vụ / năng lực</label>
                                <textarea class="form-control" name="mo_ta" rows="3" placeholder="VD: Cung cấp khách sạn 3-4 sao tại Hà Nội, Đà Nẵng, có thể cung ứng tối đa 50 phòng/ngày..."></textarea>
                            </div>
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

    <?php foreach ($nhaCungCapList as $ncc): ?>
        <!-- Modal xem -->
        <div class="modal fade" id="viewSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thông tin <?php echo htmlspecialchars($ncc['ten_don_vi']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Loại dịch vụ:</strong> <?php echo $loaiDichVuMap[$ncc['loai_dich_vu']] ?? $ncc['loai_dich_vu']; ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($ncc['dia_chi'] ?? 'Chưa cập nhật'); ?></p>
                        <p><strong>Liên hệ:</strong> <?php echo htmlspecialchars($ncc['lien_he'] ?? 'Chưa cập nhật'); ?></p>
                        <p><strong>Mô tả:</strong><br><?php echo $ncc['mo_ta'] ? nl2br(htmlspecialchars($ncc['mo_ta'])) : 'Chưa có mô tả'; ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal sửa -->
        <div class="modal fade" id="editSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập nhật <?php echo htmlspecialchars($ncc['ten_don_vi']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="index.php?act=admin/updateNhaCungCap">
                        <div class="modal-body">
                            <input type="hidden" name="id_nha_cung_cap" value="<?php echo $ncc['id_nha_cung_cap']; ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tên đơn vị *</label>
                                    <input type="text" class="form-control" name="ten_don_vi" value="<?php echo htmlspecialchars($ncc['ten_don_vi']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Loại dịch vụ</label>
                                    <select class="form-select" name="loai_dich_vu">
                                        <?php foreach ($loaiDichVuMap as $key => $label): ?>
                                            <option value="<?php echo $key; ?>" <?php echo ($ncc['loai_dich_vu'] === $key) ? 'selected' : ''; ?>>
                                                <?php echo $label; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="dia_chi" value="<?php echo htmlspecialchars($ncc['dia_chi'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Liên hệ</label>
                                    <input type="text" class="form-control" name="lien_he" value="<?php echo htmlspecialchars($ncc['lien_he'] ?? ''); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Mô tả</label>
                                    <textarea class="form-control" name="mo_ta" rows="3"><?php echo htmlspecialchars($ncc['mo_ta'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
