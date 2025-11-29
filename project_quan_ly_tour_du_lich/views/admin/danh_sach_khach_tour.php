<!-- danh_sach_khach_tour.php: Quản lý danh sách khách của tour -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Danh sách khách của tour</h2>
    <?php if (isset($tour)): ?>
        <div class="mb-3"><strong>Tour:</strong> <?= htmlspecialchars($tour['ten_tour'] ?? '') ?></div>
    <?php endif; ?>
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="ho_ten" class="form-control" placeholder="Họ tên khách" required>
        </div>
        <div class="col-md-3">
            <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="col-md-3">
            <input type="text" name="so_dien_thoai" class="form-control" placeholder="Số điện thoại">
        </div>
        <div class="col-md-3">
            <input type="text" name="ghi_chu" class="form-control" placeholder="Ghi chú">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Thêm khách</button>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Ghi chú</th>
                <th>Ngày thêm</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dsKhach)): foreach ($dsKhach as $i => $kh): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($kh['ho_ten'] ?? '') ?></td>
                    <td><?= htmlspecialchars($kh['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($kh['so_dien_thoai'] ?? '') ?></td>
                    <td><?= htmlspecialchars($kh['ghi_chu'] ?? '') ?></td>
                    <td><?= htmlspecialchars($kh['ngay_them'] ?? '') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center">Chưa có khách nào!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Quay lại</a>
</div>