<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ HDV - <?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'HDV'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 0 auto 1rem;
        }
        .info-card {
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="profile-header">
        <div class="container">
            <a href="index.php?act=hdv/dashboard" class="btn btn-light btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <div class="profile-avatar">
                <?php echo strtoupper(substr($hdv_info['ho_ten'] ?? 'N', 0, 1)); ?>
            </div>
            <h3 class="text-center mb-0"><?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'N/A'); ?></h3>
            <p class="text-center mb-0 opacity-75">Hướng dẫn viên du lịch</p>
        </div>
    </div>

    <div class="container pb-5">
        <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Thông tin cá nhân -->
            <div class="col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Thông tin cá nhân</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="text-muted">Mã nhân sự:</td>
                                <td><strong><?php echo htmlspecialchars($hdv_info['nhan_su_id'] ?? 'N/A'); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Họ tên:</td>
                                <td><strong><?php echo htmlspecialchars($hdv_info['ho_ten'] ?? 'N/A'); ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email:</td>
                                <td><?php echo htmlspecialchars($hdv_info['email'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Số điện thoại:</td>
                                <td><?php echo htmlspecialchars($hdv_info['so_dien_thoai'] ?? 'N/A'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Thông tin công việc -->
            <div class="col-md-6 mb-4">
                <div class="card info-card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-briefcase"></i> Thông tin công việc</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%" class="text-muted">Loại HDV:</td>
                                <td><?php echo htmlspecialchars($hdv_info['loai_hdv'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Chuyên tuyến:</td>
                                <td><?php echo htmlspecialchars($hdv_info['chuyen_tuyen'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Chứng chỉ:</td>
                                <td><?php echo htmlspecialchars($hdv_info['chung_chi'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Ngôn ngữ:</td>
                                <td><?php echo htmlspecialchars($hdv_info['ngon_ngu'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Kinh nghiệm:</td>
                                <td><?php echo nl2br(htmlspecialchars($hdv_info['kinh_nghiem'] ?? 'N/A')); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Sức khỏe:</td>
                                <td><?php echo htmlspecialchars($hdv_info['suc_khoe'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Đánh giá TB:</td>
                                <td>
                                    <strong><?php echo number_format($hdv_info['danh_gia_tb'] ?? 0, 1); ?></strong> 
                                    <i class="bi bi-star-fill text-warning"></i>
                                    (<?php echo $hdv_info['so_tour_da_dan'] ?? 0; ?> tour)
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Trạng thái:</td>
                                <td>
                                    <span class="badge bg-<?php 
                                        $status = $hdv_info['trang_thai_lam_viec'] ?? '';
                                        echo $status == 'SanSang' ? 'success' : ($status == 'DangBan' ? 'warning' : 'secondary'); 
                                    ?>">
                                        <?php 
                                        $statusText = [
                                            'SanSang' => 'Sẵn sàng',
                                            'DangBan' => 'Đang bận',
                                            'NghiPhep' => 'Nghỉ phép',
                                            'TamNghi' => 'Tạm nghỉ'
                                        ];
                                        echo $statusText[$status] ?? 'N/A';
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        
                        <div class="mt-3">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="bi bi-pencil"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cập nhật -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" action="index.php?act=hdv/update_profile">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-pencil"></i> Cập nhật thông tin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($hdv_info['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="so_dien_thoai" 
                                       value="<?php echo htmlspecialchars($hdv_info['so_dien_thoai'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Chứng chỉ</label>
                                <input type="text" class="form-control" name="chung_chi" 
                                       value="<?php echo htmlspecialchars($hdv_info['chung_chi'] ?? ''); ?>"
                                       placeholder="VD: Chứng chỉ HDV Quốc gia, TOEIC 850...">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngôn ngữ</label>
                                <input type="text" class="form-control" name="ngon_ngu" 
                                       value="<?php echo htmlspecialchars($hdv_info['ngon_ngu'] ?? ''); ?>"
                                       placeholder="VD: Tiếng Anh, Tiếng Nhật, Tiếng Trung...">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sức khỏe</label>
                                <input type="text" class="form-control" name="suc_khoe" 
                                       value="<?php echo htmlspecialchars($hdv_info['suc_khoe'] ?? ''); ?>"
                                       placeholder="VD: Tốt, Bình thường...">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label class="form-label">Kinh nghiệm</label>
                                <textarea class="form-control" name="kinh_nghiem" rows="4"
                                          placeholder="Mô tả kinh nghiệm làm việc, các tour đã dẫn..."><?php echo htmlspecialchars($hdv_info['kinh_nghiem'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
