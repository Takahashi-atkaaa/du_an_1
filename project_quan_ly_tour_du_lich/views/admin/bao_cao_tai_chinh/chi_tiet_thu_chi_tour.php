<!-- chi_tiet_thu_chi_tour.php: Hiển thị chi tiết thu chi của một tour -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Chi tiết thu – chi tour: <span style="color:#007bff;"><?= htmlspecialchars($tour['ten_tour'] ?? '') ?></span></h2>
    <div class="row mb-3">
        <div class="col-md-3">
            <strong>Tổng thu:</strong> <span class="text-success fw-bold"><?= number_format($tongThu ?? 0) ?> đ</span>
        </div>
        <div class="col-md-3">
            <strong>Chi phí giao dịch:</strong> <span class="text-danger fw-bold"><?= number_format($tongChiGD ?? 0) ?> đ</span>
        </div>
        <div class="col-md-3">
            <strong>Chi phí thực tế:</strong> <span class="text-danger fw-bold"><?= number_format($tongChiThucTe ?? 0) ?> đ</span>
        </div>
        <div class="col-md-3">
            <strong>Lợi nhuận thực tế:</strong> <span class="fw-bold" style="color:<?= ($loiNhuan ?? 0) >= 0 ? '#28a745' : '#dc3545' ?>;">
                <?= number_format($loiNhuan ?? 0) ?> đ
            </span>
        </div>
    </div>
    <h4>Danh sách giao dịch</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Ngày</th>
                <th>Loại</th>
                <th>Số tiền</th>
                <th>Mô tả</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($giaoDichs)): foreach ($giaoDichs as $i => $gd): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($gd['ngay_giao_dich'] ?? '') ?></td>
                    <td><?= htmlspecialchars($gd['loai'] ?? '') ?></td>
                    <td><?= number_format($gd['so_tien'] ?? 0) ?> đ</td>
                    <td><?= htmlspecialchars($gd['mo_ta'] ?? '') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">Không có giao dịch nào!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <h4>Danh sách booking</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
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
                    <td><?= number_format($bk['tong_tien'] ?? 0) ?> đ</td>
                    <td><?= htmlspecialchars($bk['trang_thai'] ?? '') ?></td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="5" class="text-center">Không có booking nào!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="index.php?act=admin/thuChiTour" class="btn btn-secondary mt-3">Quay lại danh sách tour</a>
</div>
