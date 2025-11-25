<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhân Sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .stats-card {
            border: none;
            border-left: 4px solid;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: all 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .stats-icon {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        .filter-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
        }
        .role-tab {
            border: 2px solid transparent;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .role-tab:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.15);
        }
        .role-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }
        .employee-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            font-weight: bold;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .role-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .action-btn-group {
            display: flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table-custom tbody tr {
            transition: all 0.2s;
        }
        .table-custom tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.005);
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.3;
        }
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                        <a class="nav-link active" href="#">
                            <i class="bi bi-people"></i> Nhân sự
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
                        <h1 class="display-5 fw-bold mb-2">
                            <i class="bi bi-people-fill"></i> Quản Lý Nhân Sự
                        </h1>
                        <p class="lead mb-0 opacity-75">Quản lý thông tin nhân viên và hồ sơ cá nhân</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?act=admin/hdv_advanced" class="btn btn-light btn-lg">
                            <i class="bi bi-person-badge"></i> Quản lý HDV
                        </a>
                        <button id="btnAdd" class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#nhanSuModal">
                            <i class="bi bi-plus-circle"></i> Thêm nhân sự
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; ?>
            <div class="alert alert-<?php echo htmlspecialchars($f['type']); ?> alert-dismissible fade show" role="alert">
                <i class="bi bi-<?php echo $f['type'] === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($f['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash']); endif; ?>

        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <?php 
            $total = count($nhan_su_list);
            $hdvCount = isset($data_by_role['HDV']) ? count($data_by_role['HDV']) : 0;
            $dieuHanhCount = isset($data_by_role['DieuHanh']) ? count($data_by_role['DieuHanh']) : 0;
            $nhaCungCapCount = isset($data_by_role['NhaCungCap']) ? count($data_by_role['NhaCungCap']) : 0;
            ?>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #0d6efd !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Tổng nhân sự</p>
                                <h2 class="mb-0 fw-bold"><?php echo $total; ?></h2>
                            </div>
                            <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #198754 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Hướng dẫn viên</p>
                                <h2 class="mb-0 fw-bold text-success"><?php echo $hdvCount; ?></h2>
                            </div>
                            <div class="stats-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-person-badge"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #0dcaf0 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Điều hành</p>
                                <h2 class="mb-0 fw-bold text-info"><?php echo $dieuHanhCount; ?></h2>
                            </div>
                            <div class="stats-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-briefcase"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card h-100" style="border-left-color: #ffc107 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Nhà cung cấp</p>
                                <h2 class="mb-0 fw-bold text-warning"><?php echo $nhaCungCapCount; ?></h2>
                            </div>
                            <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-shop"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <form class="d-flex" method="get" action="index.php">
                        <input type="hidden" name="act" value="admin/nhanSu">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input class="form-control" type="search" placeholder="Tìm kiếm tên, email, số điện thoại..." 
                                   name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? '') ?>">
                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="bi bi-funnel"></i> Lọc theo vai trò bên dưới
                    </small>
                </div>
            </div>
        </div>

        <!-- Role Tabs -->
        <?php if (!empty($roles)): ?>
        <div class="d-flex flex-wrap mb-4">
            <button class="role-tab <?php echo ($active_role===null)?'active':''; ?>" 
                    onclick="window.location.href='index.php?act=admin/nhanSu'">
                <i class="bi bi-grid"></i> Tất cả 
                <span class="badge <?php echo ($active_role===null)?'bg-light text-dark':'bg-secondary'; ?> ms-2">
                    <?php echo count($nhan_su_list); ?>
                </span>
            </button>
            <?php 
            $roleIcons = [
                'HDV' => 'person-badge',
                'DieuHanh' => 'briefcase',
                'NhaCungCap' => 'shop',
                'Khac' => 'three-dots'
            ];
            foreach($roles as $r): 
                $count = isset($data_by_role[$r]) ? count($data_by_role[$r]) : 0; 
                $icon = $roleIcons[$r] ?? 'person';
            ?>
                <button class="role-tab <?php echo ($active_role===$r)?'active':''; ?>" 
                        onclick="window.location.href='index.php?act=admin/nhanSu&role=<?php echo urlencode($r); ?>'">
                    <i class="bi bi-<?php echo $icon; ?>"></i> <?php echo htmlspecialchars($r); ?> 
                    <span class="badge <?php echo ($active_role===$r)?'bg-light text-dark':'bg-secondary'; ?> ms-2">
                        <?php echo $count; ?>
                    </span>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Employee Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <?php if (!empty($nhan_su_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Avatar</th>
                                    <th>Họ tên</th>
                                    <th>Vai trò</th>
                                    <th>Liên hệ</th>
                                    <th>Chứng chỉ</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Kinh nghiệm</th>
                                    <th style="width: 280px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($nhan_su_list as $nhan_su): ?>
                                <tr>
                                    <td>
                                        <div class="employee-avatar">
                                            <?php 
                                            $name = $nhan_su['ho_ten'] ?? 'N';
                                            echo strtoupper(mb_substr($name, 0, 1));
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($nhan_su['ho_ten'] ?? ''); ?></div>
                                        <small class="text-muted">ID: #<?php echo htmlspecialchars($nhan_su['nhan_su_id']); ?></small>
                                    </td>
                                    <td>
                                        <span class="role-badge <?php 
                                            echo match($nhan_su['vai_tro'] ?? '') {
                                                'HDV' => 'bg-success',
                                                'DieuHanh' => 'bg-info',
                                                'NhaCungCap' => 'bg-warning text-dark',
                                                default => 'bg-secondary'
                                            };
                                        ?>">
                                            <?php echo htmlspecialchars($nhan_su['vai_tro'] ?? ''); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small>
                                            <div><i class="bi bi-phone text-primary"></i> <?php echo htmlspecialchars($nhan_su['so_dien_thoai'] ?? 'N/A'); ?></div>
                                            <div><i class="bi bi-envelope text-info"></i> <?php echo htmlspecialchars($nhan_su['email'] ?? 'N/A'); ?></div>
                                        </small>
                                    </td>
                                    <td><small><?php echo htmlspecialchars($nhan_su['chung_chi'] ?? 'N/A'); ?></small></td>
                                    <td><small><?php echo htmlspecialchars($nhan_su['ngon_ngu'] ?? 'N/A'); ?></small></td>
                                    <td><small><?php echo htmlspecialchars($nhan_su['kinh_nghiem'] ?? 'N/A'); ?></small></td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="index.php?act=admin/nhanSu_chi_tiet&id=<?php echo $nhan_su['nhan_su_id']; ?>" 
                                               class="btn btn-sm btn-info" title="Xem sơ yếu lý lịch">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($nhan_su['vai_tro'] === 'HDV'): ?>
                                            <a href="index.php?act=admin/hdv_detail&id=<?php echo $nhan_su['nhan_su_id']; ?>" 
                                               class="btn btn-sm btn-success" title="Quản lý HDV">
                                                <i class="bi bi-calendar-check"></i>
                                            </a>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-primary btn-edit" 
                                                data-id="<?php echo $nhan_su['nhan_su_id']; ?>"
                                                data-vai_tro="<?php echo htmlspecialchars($nhan_su['vai_tro'] ?? ''); ?>"
                                                data-chung_chi="<?php echo htmlspecialchars($nhan_su['chung_chi'] ?? ''); ?>"
                                                data-ngon_ngu="<?php echo htmlspecialchars($nhan_su['ngon_ngu'] ?? ''); ?>"
                                                data-kinh_nghiem="<?php echo htmlspecialchars($nhan_su['kinh_nghiem'] ?? ''); ?>"
                                                data-suc_khoe="<?php echo htmlspecialchars($nhan_su['suc_khoe'] ?? ''); ?>"
                                                data-user_info="<?php echo htmlspecialchars(($nhan_su['ho_ten'] ?? '') . ' (' . ($nhan_su['ten_dang_nhap'] ?? '') . ')'); ?>"
                                                title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="index.php?act=admin/nhanSu_delete&id=<?php echo $nhan_su['nhan_su_id']; ?>" 
                                               class="btn btn-sm btn-warning" 
                                               onclick="return confirm('Xóa nhân sự này? (Tài khoản sẽ được giữ)');"
                                               title="Xóa NV">
                                                <i class="bi bi-person-x"></i>
                                            </a>
                                            <a href="index.php?act=admin/nhanSu_delete&id=<?php echo $nhan_su['nhan_su_id']; ?>&delete_user=1" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('XÓA VĨ VIỄN: Nhân sự này và tài khoản liên kết sẽ bị xóa. Bạn chắc chắn?');"
                                               title="Xóa All">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <h4 class="mb-3">Chưa có nhân sự nào</h4>
                        <p class="text-muted mb-4">Hãy thêm nhân sự đầu tiên để bắt đầu quản lý</p>
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#nhanSuModal">
                            <i class="bi bi-plus-circle"></i> Thêm nhân sự ngay
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="nhanSuModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">
                <i class="bi bi-person-plus"></i> Thêm / Sửa nhân sự
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="nhanSuForm" method="post" action="index.php?act=admin/nhanSu_create">
          <div class="modal-body">
                <input type="hidden" name="nhan_su_id" id="nhan_su_id" value="">
                
                <div class="row g-3">
                    <div class="col-12" id="userSelectWrapper">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-circle text-primary"></i> Chọn người dùng 
                            <span class="text-danger">*</span>
                        </label>
                        <select name="nguoi_dung_id" id="nguoi_dung_id" class="form-select" required>
                            <option value="">-- Chọn người dùng --</option>
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i> Chỉ hiển thị tài khoản chưa có hồ sơ nhân sự
                        </small>
                    </div>
                    
                    <div class="col-12" id="userInfoDisplay" style="display:none;">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-circle text-primary"></i> Người dùng
                        </label>
                        <input type="text" id="userInfoText" class="form-control" readonly>
                        <small class="form-text text-muted">
                            <i class="bi bi-lock"></i> Không thể thay đổi người dùng khi sửa nhân sự
                        </small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-briefcase text-success"></i> Vai trò
                        </label>
                        <select name="vai_tro" id="vai_tro" class="form-select">
                            <option value="HDV">HDV</option>
                            <option value="DieuHanh">Điều hành</option>
                            <option value="NhaCungCap">Nhà cung cấp</option>
                            <option value="Khac">Khác</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-heart-pulse text-danger"></i> Sức khỏe
                        </label>
                        <input name="suc_khoe" id="suc_khoe" class="form-control" placeholder="VD: Tốt, khỏe mạnh">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-award text-warning"></i> Chứng chỉ
                        </label>
                        <textarea name="chung_chi" id="chung_chi" class="form-control" rows="2" 
                                  placeholder="VD: Chứng chỉ HDV, Bằng lái xe B2..."></textarea>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-translate text-info"></i> Ngôn ngữ
                        </label>
                        <input name="ngon_ngu" id="ngon_ngu" class="form-control" placeholder="VD: Tiếng Anh, Tiếng Nhật">
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-clock-history text-primary"></i> Kinh nghiệm
                        </label>
                        <textarea name="kinh_nghiem" id="kinh_nghiem" class="form-control" rows="3" 
                                  placeholder="VD: 5 năm kinh nghiệm làm HDV quốc tế..."></textarea>
                    </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle"></i> Đóng
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="bi bi-check-circle"></i> Lưu thông tin
            </button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function(){
            const modal = document.getElementById('nhanSuModal');
            const form = document.getElementById('nhanSuForm');
            const submitBtn = document.getElementById('submitBtn');

            // Load danh sách người dùng có sẵn
            function loadAvailableUsers() {
                const select = document.getElementById('nguoi_dung_id');
                fetch('index.php?act=admin/nhanSu_get_users')
                    .then(r => r.json())
                    .then(data => {
                        if (data.users && data.users.length > 0) {
                            const html = '<option value="">-- Chọn người dùng --</option>' + 
                                data.users.map(u => `<option value="${u.id}">${u.ho_ten} (${u.ten_dang_nhap})</option>`).join('');
                            select.innerHTML = html;
                        }
                    })
                    .catch(e => console.error('Lỗi tải người dùng:', e));
            }

            document.getElementById('btnAdd').addEventListener('click', function(){
                form.action = 'index.php?act=admin/nhanSu_create';
                form.nhan_su_id.value = '';
                form.reset();
                
                // Show user select, hide user info
                document.getElementById('userSelectWrapper').style.display = 'block';
                document.getElementById('userInfoDisplay').style.display = 'none';
                document.getElementById('nguoi_dung_id').required = true;
                
                loadAvailableUsers();
                modal.querySelector('.modal-title').innerHTML = '<i class="bi bi-person-plus"></i> Thêm nhân sự';
            });

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    form.action = 'index.php?act=admin/nhanSu_update';
                    form.nhan_su_id.value = id;
                    
                    // Hide user select, show user info
                    document.getElementById('userSelectWrapper').style.display = 'none';
                    document.getElementById('userInfoDisplay').style.display = 'block';
                    document.getElementById('nguoi_dung_id').required = false;
                    document.getElementById('userInfoText').value = this.dataset.user_info || '';
                    
                    document.getElementById('vai_tro').value = this.dataset.vai_tro || '';
                    document.getElementById('chung_chi').value = this.dataset.chung_chi || '';
                    document.getElementById('ngon_ngu').value = this.dataset.ngon_ngu || '';
                    document.getElementById('kinh_nghiem').value = this.dataset.kinh_nghiem || '';
                    document.getElementById('suc_khoe').value = this.dataset.suc_khoe || '';
                    modal.querySelector('.modal-title').innerHTML = '<i class="bi bi-pencil"></i> Sửa nhân sự';
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                });
            });
        })();
    </script>
</body>
</html>