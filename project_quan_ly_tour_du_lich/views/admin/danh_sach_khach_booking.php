<!-- Dashboard Booking Khách Hàng - UI hiện đại, full màn hình -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    body { background: #f6f8fb; }
    .gradient-header {
        background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 2rem 2rem 1rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 24px rgba(78,84,200,0.12);
        width: 100%;
    }
    .card-stat {
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(78,84,200,0.08);
        text-align: center;
        padding: 1.2rem 0.5rem;
        margin-bottom: 1rem;
        background: #fff;
        width: 100%;
    }
    .card-stat .icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    .btn-main {
        background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
        color: #fff;
        border: none;
        font-weight: 600;
    }
    .btn-main:hover { background: #4e54c8; color: #fff; }
    .table thead { background: #e9ecef; }
    .card { width: 100%; }
</style>
<div class="container-fluid px-4">
  <div class="gradient-header mb-4">
    <h2 class="mb-2"><i class="bi bi-person-lines-fill"></i> Quản Lý Khách Booking</h2>
    <p class="mb-0">Quản lý danh sách khách hàng từng booking, thêm/xóa/sửa trực tiếp, và xử lý thông tin khách hàng.</p>
  </div>
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card-stat">
        <div class="icon text-primary"><i class="bi bi-people"></i></div>
        <div class="fs-4 fw-bold"><?= count($khachList) ?></div>
        <div class="text-muted">Tổng khách</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-stat">
        <div class="icon text-success"><i class="bi bi-person-check"></i></div>
        <div class="fs-4 fw-bold">
          <?= count(array_filter($khachList, fn($k) => ($k['diem_danh'] ?? '') === 'co_mat')) ?>
        </div>
        <div class="text-muted">Có mặt</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-stat">
        <div class="icon text-danger"><i class="bi bi-person-x"></i></div>
        <div class="fs-4 fw-bold">
          <?= count(array_filter($khachList, fn($k) => ($k['diem_danh'] ?? '') === 'vang_mat')) ?>
        </div>
        <div class="text-muted">Vắng mặt</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-stat">
        <div class="icon text-info"><i class="bi bi-search"></i></div>
        <div class="fs-4 fw-bold">Tìm kiếm</div>
        <div class="text-muted">Lọc khách</div>
      </div>
    </div>
  </div>
  <form method="get" class="row g-2 mb-3">
    <input type="hidden" name="act" value="admin/danhSachKhachBooking">
    <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['booking_id'] ?? '') ?>">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên, email, SĐT..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-main"><i class="bi bi-search"></i> Tìm kiếm</button>
    </div>
  </form>
  <div class="card p-3 mb-4">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>STT</th>
          <th>Họ tên</th>
          <th>Email</th>
          <th>SĐT</th>
          <th>Địa chỉ</th>
          <th>Ngày sinh</th>
          <th>Giới tính</th>
          <th>Điểm danh</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $search = trim($_GET['search'] ?? '');
        $filteredKhachList = $khachList;
        if ($search) {
          $filteredKhachList = array_filter($khachList, function($kh) use ($search) {
            return stripos($kh['ten_khach_hang'] ?? '', $search) !== false
              || stripos($kh['gmail'] ?? '', $search) !== false
              || stripos($kh['so_dien_thoai'] ?? '', $search) !== false;
          });
        }
        if (!empty($filteredKhachList)): foreach ($filteredKhachList as $i => $kh): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($kh['ten_khach_hang'] ?? '') ?></td>
          <td><?= htmlspecialchars($kh['gmail'] ?? '') ?></td>
          <td><?= htmlspecialchars($kh['so_dien_thoai'] ?? '') ?></td>
          <td><?= htmlspecialchars($kh['dia_chi'] ?? '') ?></td>
          <td><?= !empty($kh['ngay_sinh']) ? date('d/m/Y', strtotime($kh['ngay_sinh'])) : '' ?></td>
          <td><?= htmlspecialchars($kh['gioi_tinh'] ?? '') ?></td>
          <td><?= htmlspecialchars($kh['diem_danh'] ?? '') ?></td>
          <td>
            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalSuaKhach<?= $kh['khach_hang_id'] ?>" title="Sửa"><i class="bi bi-pencil"></i></button>
            <button type="button" class="btn btn-sm btn-danger" onclick="xoaKhachBooking(<?= $kh['khach_hang_id'] ?>, <?= $booking['booking_id'] ?>)" title="Xóa"><i class="bi bi-trash"></i></button>
          </td>
        </tr>
        <!-- Modal sửa khách -->
        <div class="modal fade" id="modalSuaKhach<?= $kh['khach_hang_id'] ?>" tabindex="-1" aria-labelledby="modalSuaKhachLabel<?= $kh['khach_hang_id'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <form method="post" action="index.php?act=admin/suaKhachBooking&khach_id=<?= $kh['khach_hang_id'] ?>&booking_id=<?= $booking['booking_id'] ?>">
                <div class="modal-header bg-warning text-dark">
                  <h5 class="modal-title" id="modalSuaKhachLabel<?= $kh['khach_hang_id'] ?>">Sửa thông tin khách hàng</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-3">
                    <label class="form-label">Họ tên *</label>
                    <input type="text" name="ho_ten" class="form-control" value="<?= htmlspecialchars($kh['ten_khach_hang'] ?? '') ?>" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($kh['gmail'] ?? '') ?>" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Số điện thoại *</label>
                    <input type="text" name="so_dien_thoai" class="form-control" value="<?= htmlspecialchars($kh['so_dien_thoai'] ?? '') ?>" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="dia_chi" class="form-control" value="<?= htmlspecialchars($kh['dia_chi'] ?? '') ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" class="form-control" value="<?= !empty($kh['ngay_sinh']) ? $kh['ngay_sinh'] : '' ?>">
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gioi_tinh" class="form-select">
                      <option value="">Chọn giới tính</option>
                      <option value="Nam" <?= ($kh['gioi_tinh'] ?? '') === 'Nam' ? 'selected' : '' ?>>Nam</option>
                      <option value="Nữ" <?= ($kh['gioi_tinh'] ?? '') === 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                      <option value="Khác" <?= ($kh['gioi_tinh'] ?? '') === 'Khác' ? 'selected' : '' ?>>Khác</option>
                    </select>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                  <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php endforeach; else: ?>
        <tr><td colspan="9" class="text-center">Chưa có khách nào!</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <button type="button" class="btn btn-main mt-3" data-bs-toggle="modal" data-bs-target="#modalThemKhach">
      <i class="bi bi-person-plus"></i> Thêm khách hàng
    </button>
  </div>
  <!-- Modal thêm khách -->
  <div class="modal fade" id="modalThemKhach" tabindex="-1" aria-labelledby="modalThemKhachLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="modalThemKhachLabel">Thêm khách hàng vào booking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <form method="post" action="index.php?act=admin/themKhachBooking&booking_id=<?= $booking['booking_id'] ?? 0 ?>">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Họ tên *</label>
              <input type="text" name="ho_ten" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Số điện thoại *</label>
              <input type="text" name="so_dien_thoai" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Địa chỉ</label>
              <input type="text" name="dia_chi" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Ngày sinh</label>
              <input type="date" name="ngay_sinh" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Giới tính</label>
              <select name="gioi_tinh" class="form-select">
                <option value="">Chọn giới tính</option>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Điểm danh</label>
              <select name="diem_danh" class="form-select">
                <option value="co_mat">Có mặt</option>
                <option value="vang_mat">Vắng mặt</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            <button type="submit" class="btn btn-main"><i class="bi bi-person-plus"></i> Thêm khách hàng</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function xoaKhachBooking(khachId, bookingId) {
    if (confirm('Bạn có chắc muốn xóa khách này khỏi booking?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'index.php?act=admin/xoaKhachBooking&khach_id=' + khachId + '&booking_id=' + bookingId, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.reload();
            }
        };
        xhr.send();
    }
}
</script>
