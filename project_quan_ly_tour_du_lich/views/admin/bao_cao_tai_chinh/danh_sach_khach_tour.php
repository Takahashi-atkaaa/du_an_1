<!-- danh_sach_khach_tour.php: Hiển thị danh sách khách của một tour -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Danh sách khách của tour</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Ngày đặt</th>
                <th>Số người</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): foreach ($bookings as $i => $bk): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($bk['ho_ten'] ?? '') ?></td>
                    <td><?= htmlspecialchars($bk['ngay_dat'] ?? '') ?></td>
                    <td><?= htmlspecialchars($bk['so_nguoi'] ?? '') ?></td>
                    <td><?= number_format($bk['tong_tien'] ?? 0) ?> đ</td>
                    <td><?= htmlspecialchars($bk['trang_thai'] ?? '') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center">Không có khách nào!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php?act=admin/thuChiTour&tour_id=<?= $_GET['tour_id'] ?>" class="btn btn-secondary mt-3">Quay lại chi tiết tour</a>
</div>