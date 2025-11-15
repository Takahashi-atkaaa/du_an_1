<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân sự</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-actions button { margin-right: 4px; }
        .nav-tabs .badge { font-size: 0.75rem; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">Quản trị</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Nhân sự</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="m-0">Danh sách nhân sự</h3>
            <div>
                <form class="d-inline-flex align-items-center" method="get" action="index.php">
                    <input type="hidden" name="act" value="admin/nhanSu">
                    <input class="form-control form-control-sm me-2" type="search" placeholder="Tìm kiếm tên, email..." aria-label="Search" name="q" value="<?php echo htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button class="btn btn-sm btn-outline-secondary me-2" type="submit">Tìm</button>
                </form>
                <button id="btnAdd" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#nhanSuModal">Thêm nhân sự</button>
            </div>
        </div>

        <?php if (!empty($_SESSION['flash'])): $f = $_SESSION['flash']; ?>
            <div class="alert alert-<?php echo htmlspecialchars($f['type']); ?>"><?php echo htmlspecialchars($f['message']); ?></div>
            <?php unset($_SESSION['flash']); endif; ?>

        <!-- Tabs for roles -->
        <?php if (!empty($roles)): ?>
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($active_role===null)?'active':''; ?>" id="tab-all" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#content-all">
                        Tất cả <span class="badge bg-secondary ms-1"><?php echo count($nhan_su_list); ?></span>
                    </button>
                </li>
                <?php foreach($roles as $r): $count = isset($data_by_role[$r]) ? count($data_by_role[$r]) : 0; ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo ($active_role===$r)?'active':''; ?>" id="tab-<?php echo htmlspecialchars($r); ?>" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#content-<?php echo htmlspecialchars($r); ?>">
                            <?php echo htmlspecialchars($r); ?> <span class="badge bg-info ms-1"><?php echo $count; ?></span>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Họ tên</th>
                                <th>Chức vụ</th>
                                <th>Điện thoại</th>
                                <th>Email</th>
                                <th>Chứng chỉ</th>
                                <th>Ngôn ngữ</th>
                                <th>Kinh nghiệm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($nhan_su_list)): ?>
                            <?php foreach ($nhan_su_list as $nhan_su): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($nhan_su['nhan_su_id']); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['ho_ten'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['chuc_vu'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['so_dien_thoai'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['email'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['chung_chi'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['ngon_ngu'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($nhan_su['kinh_nghiem'] ?? ''); ?></td>
                                    <td class="table-actions">
                                        <button class="btn btn-sm btn-primary btn-edit" 
                                            data-id="<?php echo $nhan_su['nhan_su_id']; ?>"
                                            data-ho_ten="<?php echo htmlspecialchars($nhan_su['ho_ten'] ?? ''); ?>"
                                            data-chuc_vu="<?php echo htmlspecialchars($nhan_su['chuc_vu'] ?? ''); ?>"
                                            data-so_dien_thoai="<?php echo htmlspecialchars($nhan_su['so_dien_thoai'] ?? ''); ?>"
                                            data-email="<?php echo htmlspecialchars($nhan_su['email'] ?? ''); ?>"
                                            data-chung_chi="<?php echo htmlspecialchars($nhan_su['chung_chi'] ?? ''); ?>"
                                            data-ngon_ngu="<?php echo htmlspecialchars($nhan_su['ngon_ngu'] ?? ''); ?>"
                                            data-kinh_nghiem="<?php echo htmlspecialchars($nhan_su['kinh_nghiem'] ?? ''); ?>"
                                            >Sửa</button>
                                        <a href="index.php?act=admin/nhanSu_delete&id=<?php echo $nhan_su['nhan_su_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa nhân sự này?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">Không có nhân sự nào.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="nhanSuModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Thêm / Sửa nhân sự</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="nhanSuForm" method="post" action="index.php?act=admin/nhanSu_create">
          <div class="modal-body">
                <input type="hidden" name="nhan_su_id" id="nhan_su_id" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Họ tên</label>
                        <input name="ho_ten" id="ho_ten" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chức vụ</label>
                        <input name="chuc_vu" id="chuc_vu" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input name="so_dien_thoai" id="so_dien_thoai" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" id="email" type="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chứng chỉ</label>
                        <input name="chung_chi" id="chung_chi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngôn ngữ</label>
                        <input name="ngon_ngu" id="ngon_ngu" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Kinh nghiệm</label>
                        <textarea name="kinh_nghiem" id="kinh_nghiem" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ</label>
                        <input name="dia_chi" id="dia_chi" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input name="ngay_bat_dau" id="ngay_bat_dau" type="date" class="form-control">
                    </div>
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">Lưu</button>
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

            document.getElementById('btnAdd').addEventListener('click', function(){
                form.action = 'index.php?act=admin/nhanSu_create';
                form.nhan_su_id.value = '';
                form.reset();
                modal.querySelector('.modal-title').textContent = 'Thêm nhân sự';
            });

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function(){
                    const id = this.dataset.id;
                    form.action = 'index.php?act=admin/nhanSu_update';
                    form.nhan_su_id.value = id;
                    document.getElementById('ho_ten').value = this.dataset.ho_ten || '';
                    document.getElementById('chuc_vu').value = this.dataset.chuc_vu || '';
                    document.getElementById('so_dien_thoai').value = this.dataset.so_dien_thoai || '';
                    document.getElementById('email').value = this.dataset.email || '';
                    document.getElementById('chung_chi').value = this.dataset.chung_chi || '';
                    document.getElementById('ngon_ngu').value = this.dataset.ngon_ngu || '';
                    document.getElementById('kinh_nghiem').value = this.dataset.kinh_nghiem || '';
                    modal.querySelector('.modal-title').textContent = 'Sửa nhân sự';
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                });
            });
        })();
    </script>
</body>
</html>
