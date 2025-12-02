<!-- Giao diện admin quản lý công nợ HDV -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Quản lý hóa đơn công nợ HDV</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>HDV</th>
                <th>Tour</th>
                <th>Số tiền</th>
                <th>Loại</th>
                <th>Ảnh hóa đơn</th>
                <th>Ghi chú</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($hoaDons as $hd): ?>
                <tr>
                    <td><?= htmlspecialchars($hd['ten_hdv']) ?></td>
                    <td><?= htmlspecialchars($hd['ten_tour']) ?></td>
                    <td><?= number_format($hd['so_tien']) ?>đ</td>
                    <td><?= $hd['loai_cong_no'] ?></td>
                    <td><?php if($hd['anh_hoa_don']): ?><a href="<?= $hd['anh_hoa_don'] ?>" target="_blank">Xem ảnh</a><?php endif; ?></td>
                    <td><?= htmlspecialchars($hd['ghi_chu']) ?></td>
                    <td><?= $hd['trang_thai'] ?></td>
                    <td><?= $hd['ngay_gui'] ?></td>
                    <td>
                        <a href="index.php?act=admin/duyetHoaDon&id=<?= $hd['id'] ?>" class="btn btn-success btn-sm">Duyệt</a>
                        <form method="POST" action="index.php?act=admin/tuChoiHoaDon" style="display:inline">
                            <input type="hidden" name="id" value="<?= $hd['id'] ?>">
                            <input type="text" name="ly_do" placeholder="Lý do từ chối" class="form-control mb-1" required>
                            <button type="submit" class="btn btn-danger btn-sm">Từ chối</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
