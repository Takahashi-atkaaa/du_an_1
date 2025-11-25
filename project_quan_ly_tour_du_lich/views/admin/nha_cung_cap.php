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
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-building"></i> Quản lý Nhà cung cấp
            </h1>
            <div>
                <a href="index.php?act=admin/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại Dashboard
                </a>
            </div>
        </div>

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

        <!-- Suppliers List -->
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách Nhà cung cấp</h5>
                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="bi bi-plus-circle"></i> Thêm nhà cung cấp
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($nhaCungCapList)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                        <p class="mt-3">Chưa có nhà cung cấp nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đơn vị</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Địa chỉ</th>
                                    <th>Liên hệ</th>
                                    <th>Đánh giá TB</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $loaiDichVuMap = [
                                    'KhachSan' => 'Khách sạn',
                                    'NhaHang' => 'Nhà hàng',
                                    'Xe' => 'Xe',
                                    'Ve' => 'Vé',
                                    'Visa' => 'Visa',
                                    'BaoHiem' => 'Bảo hiểm',
                                    'Khac' => 'Khác'
                                ];
                                
                                foreach ($nhaCungCapList as $ncc): 
                                ?>
                                <tr>
                                    <td><?php echo $ncc['id_nha_cung_cap']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $loaiDichVuMap[$ncc['loai_dich_vu']] ?? $ncc['loai_dich_vu']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($ncc['dia_chi'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($ncc['lien_he'] ?? '-'); ?></td>
                                    <td>
                                        <?php if ($ncc['danh_gia_tb']): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill"></i> <?php echo number_format($ncc['danh_gia_tb'], 1); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>">
                                            <i class="bi bi-eye"></i> Xem
                                        </button>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>">
                                            <i class="bi bi-pencil"></i> Sửa
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Xem chi tiết -->
                                <div class="modal fade" id="viewSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Chi tiết Nhà cung cấp</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Tên đơn vị:</strong> <?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?></p>
                                                        <p><strong>Loại dịch vụ:</strong> 
                                                            <span class="badge bg-info">
                                                                <?php echo $loaiDichVuMap[$ncc['loai_dich_vu']] ?? $ncc['loai_dich_vu']; ?>
                                                            </span>
                                                        </p>
                                                        <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($ncc['dia_chi'] ?? '-'); ?></p>
                                                        <p><strong>Liên hệ:</strong> <?php echo htmlspecialchars($ncc['lien_he'] ?? '-'); ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Đánh giá TB:</strong> 
                                                            <?php if ($ncc['danh_gia_tb']): ?>
                                                                <span class="badge bg-warning text-dark">
                                                                    <i class="bi bi-star-fill"></i> <?php echo number_format($ncc['danh_gia_tb'], 1); ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="text-muted">Chưa có</span>
                                                            <?php endif; ?>
                                                        </p>
                                                        <?php if ($ncc['mo_ta']): ?>
                                                        <p><strong>Mô tả:</strong></p>
                                                        <p><?php echo nl2br(htmlspecialchars($ncc['mo_ta'])); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Sửa -->
                                <div class="modal fade" id="editSupplierModal<?php echo $ncc['id_nha_cung_cap']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Sửa Nhà cung cấp</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="index.php?act=admin/updateNhaCungCap">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_nha_cung_cap" value="<?php echo $ncc['id_nha_cung_cap']; ?>">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên đơn vị *</label>
                                                        <input type="text" class="form-control" name="ten_don_vi" 
                                                               value="<?php echo htmlspecialchars($ncc['ten_don_vi'] ?? ''); ?>" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Loại dịch vụ</label>
                                                        <select class="form-select" name="loai_dich_vu">
                                                            <option value="KhachSan" <?php echo ($ncc['loai_dich_vu'] === 'KhachSan') ? 'selected' : ''; ?>>Khách sạn</option>
                                                            <option value="NhaHang" <?php echo ($ncc['loai_dich_vu'] === 'NhaHang') ? 'selected' : ''; ?>>Nhà hàng</option>
                                                            <option value="Xe" <?php echo ($ncc['loai_dich_vu'] === 'Xe') ? 'selected' : ''; ?>>Xe</option>
                                                            <option value="Ve" <?php echo ($ncc['loai_dich_vu'] === 'Ve') ? 'selected' : ''; ?>>Vé</option>
                                                            <option value="Visa" <?php echo ($ncc['loai_dich_vu'] === 'Visa') ? 'selected' : ''; ?>>Visa</option>
                                                            <option value="BaoHiem" <?php echo ($ncc['loai_dich_vu'] === 'BaoHiem') ? 'selected' : ''; ?>>Bảo hiểm</option>
                                                            <option value="Khac" <?php echo ($ncc['loai_dich_vu'] === 'Khac') ? 'selected' : ''; ?>>Khác</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Địa chỉ</label>
                                                        <input type="text" class="form-control" name="dia_chi" 
                                                               value="<?php echo htmlspecialchars($ncc['dia_chi'] ?? ''); ?>">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Liên hệ</label>
                                                        <input type="text" class="form-control" name="lien_he" 
                                                               value="<?php echo htmlspecialchars($ncc['lien_he'] ?? ''); ?>">
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Mô tả</label>
                                                        <textarea class="form-control" name="mo_ta" rows="3"><?php echo htmlspecialchars($ncc['mo_ta'] ?? ''); ?></textarea>
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
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
                        <div class="mb-3">
                            <label class="form-label">Tên đơn vị *</label>
                            <input type="text" class="form-control" name="ten_don_vi" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Loại dịch vụ</label>
                            <select class="form-select" name="loai_dich_vu">
                                <option value="KhachSan">Khách sạn</option>
                                <option value="NhaHang">Nhà hàng</option>
                                <option value="Xe">Xe</option>
                                <option value="Ve">Vé</option>
                                <option value="Visa">Visa</option>
                                <option value="BaoHiem">Bảo hiểm</option>
                                <option value="Khac">Khác</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="dia_chi">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Liên hệ</label>
                            <input type="text" class="form-control" name="lien_he">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="mo_ta" rows="3"></textarea>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
