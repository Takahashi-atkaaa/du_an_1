<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo giá - Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <style>
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .action-buttons .btn {
            margin: 2px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-text"></i> Quản lý Báo giá
            </h1>
            <div>
                <a href="index.php?act=nhaCungCap/dashboard" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Dashboard
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

        <!-- Navigation -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="index.php?act=nhaCungCap/baoGia">
                    <i class="bi bi-file-earmark-text"></i> Báo giá
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/dichVu">
                    <i class="bi bi-briefcase"></i> Dịch vụ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/congNo">
                    <i class="bi bi-cash-stack"></i> Công nợ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php?act=nhaCungCap/hopDong">
                    <i class="bi bi-file-earmark-check"></i> Lịch sử hợp tác
                </a>
            </li>
        </ul>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">Lọc theo trạng thái:</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="index.php?act=nhaCungCap/baoGia" class="btn btn-sm <?php echo !$trangThai ? 'btn-primary' : 'btn-outline-primary'; ?>">
                            Tất cả
                        </a>
                        <a href="index.php?act=nhaCungCap/baoGia&trang_thai=ChoXacNhan" class="btn btn-sm <?php echo $trangThai === 'ChoXacNhan' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                            Chờ xác nhận
                        </a>
                        <a href="index.php?act=nhaCungCap/baoGia&trang_thai=DaXacNhan" class="btn btn-sm <?php echo $trangThai === 'DaXacNhan' ? 'btn-success' : 'btn-outline-success'; ?>">
                            Đã xác nhận
                        </a>
                        <a href="index.php?act=nhaCungCap/baoGia&trang_thai=TuChoi" class="btn btn-sm <?php echo $trangThai === 'TuChoi' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                            Từ chối
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services List -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Danh sách dịch vụ</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dichVu)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 4rem;"></i>
                        <p class="mt-3">Không có dịch vụ nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tour</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Tên dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Giá tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $statusMap = [
                                    'ChoXacNhan' => ['text' => 'Chờ xác nhận', 'class' => 'warning'],
                                    'DaXacNhan' => ['text' => 'Đã xác nhận', 'class' => 'success'],
                                    'TuChoi' => ['text' => 'Từ chối', 'class' => 'danger'],
                                    'Huy' => ['text' => 'Hủy', 'class' => 'secondary'],
                                    'HoanTat' => ['text' => 'Hoàn tất', 'class' => 'info']
                                ];
                                
                                $loaiDichVuMap = [
                                    'Xe' => 'Xe',
                                    'KhachSan' => 'Khách sạn',
                                    'VeMayBay' => 'Vé máy bay',
                                    'NhaHang' => 'Nhà hàng',
                                    'DiemThamQuan' => 'Điểm tham quan',
                                    'Visa' => 'Visa',
                                    'BaoHiem' => 'Bảo hiểm',
                                    'Khac' => 'Khác'
                                ];
                                
                                foreach ($dichVu as $dv): 
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></strong>
                                        <?php if ($dv['ngay_khoi_hanh']): ?>
                                            <br><small class="text-muted">
                                                <i class="bi bi-calendar"></i> 
                                                <?php echo date('d/m/Y', strtotime($dv['ngay_khoi_hanh'])); ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo $loaiDichVuMap[$dv['loai_dich_vu']] ?? $dv['loai_dich_vu']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($dv['ten_dich_vu']); ?></td>
                                    <td>
                                        <?php echo $dv['so_luong']; ?>
                                        <?php if ($dv['don_vi']): ?>
                                            <small class="text-muted"><?php echo $dv['don_vi']; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['ngay_bat_dau']): ?>
                                            <?php echo date('d/m/Y', strtotime($dv['ngay_bat_dau'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['ngay_ket_thuc']): ?>
                                            <?php echo date('d/m/Y', strtotime($dv['ngay_ket_thuc'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($dv['gia_tien']): ?>
                                            <strong class="text-success">
                                                <?php echo number_format($dv['gia_tien'], 0, ',', '.'); ?>đ
                                            </strong>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa có giá</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $statusMap[$dv['trang_thai']] ?? ['text' => $dv['trang_thai'], 'class' => 'secondary'];
                                        ?>
                                        <span class="badge bg-<?php echo $status['class']; ?> status-badge">
                                            <?php echo $status['text']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($dv['trang_thai'] === 'ChoXacNhan'): ?>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#xacNhanModal<?php echo $dv['id']; ?>">
                                                    <i class="bi bi-check-circle"></i> Xác nhận
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tuChoiModal<?php echo $dv['id']; ?>">
                                                    <i class="bi bi-x-circle"></i> Từ chối
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if ($dv['trang_thai'] === 'ChoXacNhan' || $dv['trang_thai'] === 'DaXacNhan'): ?>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#capNhatGiaModal<?php echo $dv['id']; ?>">
                                                    <i class="bi bi-pencil"></i> Cập nhật giá
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if ($dv['ghi_chu']): ?>
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ghiChuModal<?php echo $dv['id']; ?>">
                                                    <i class="bi bi-info-circle"></i> Ghi chú
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Xác nhận -->
                                <div class="modal fade" id="xacNhanModal<?php echo $dv['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Xác nhận dịch vụ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="index.php?act=nhaCungCap/xacNhanBooking">
                                                <div class="modal-body">
                                                    <input type="hidden" name="dich_vu_id" value="<?php echo $dv['id']; ?>">
                                                    <input type="hidden" name="action" value="xac_nhan">
                                                    
                                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></p>
                                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($dv['ten_dich_vu']); ?></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Giá tiền (VND)</label>
                                                        <input type="number" class="form-control" name="gia_tien" 
                                                               value="<?php echo $dv['gia_tien'] ?? ''; ?>" 
                                                               min="0" step="1000" required>
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

                                <!-- Modal Từ chối -->
                                <div class="modal fade" id="tuChoiModal<?php echo $dv['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Từ chối dịch vụ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="index.php?act=nhaCungCap/xacNhanBooking">
                                                <div class="modal-body">
                                                    <input type="hidden" name="dich_vu_id" value="<?php echo $dv['id']; ?>">
                                                    <input type="hidden" name="action" value="tu_choi">
                                                    
                                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></p>
                                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($dv['ten_dich_vu']); ?></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Lý do từ chối</label>
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

                                <!-- Modal Cập nhật giá -->
                                <div class="modal fade" id="capNhatGiaModal<?php echo $dv['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Cập nhật giá dịch vụ</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="index.php?act=nhaCungCap/capNhatGia">
                                                <div class="modal-body">
                                                    <input type="hidden" name="dich_vu_id" value="<?php echo $dv['id']; ?>">
                                                    
                                                    <p><strong>Tour:</strong> <?php echo htmlspecialchars($dv['ten_tour'] ?? 'N/A'); ?></p>
                                                    <p><strong>Dịch vụ:</strong> <?php echo htmlspecialchars($dv['ten_dich_vu']); ?></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Giá tiền (VND)</label>
                                                        <input type="number" class="form-control" name="gia_tien" 
                                                               value="<?php echo $dv['gia_tien'] ?? ''; ?>" 
                                                               min="0" step="1000" required>
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

                                <!-- Modal Ghi chú -->
                                <div class="modal fade" id="ghiChuModal<?php echo $dv['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Ghi chú</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><?php echo nl2br(htmlspecialchars($dv['ghi_chu'])); ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
