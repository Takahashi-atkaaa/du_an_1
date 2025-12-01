<!-- Giao diện HDV thanh toán công nợ tour -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<div class="container mt-4">
    <h2>Thanh toán công nợ tour</h2>
    <form method="POST" action="index.php?act=hdv/guiHoaDon" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="tour_id" class="form-label">Chọn tour</label>
            <select name="tour_id" id="tour_id" class="form-select" required>
                <?php foreach($tours as $tour): ?>
                    <option value="<?= $tour['tour_id'] ?>"><?= htmlspecialchars($tour['ten_tour']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="so_tien" class="form-label">Số tiền cần thanh toán</label>
            <input type="number" name="so_tien" id="so_tien" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="loai_cong_no" class="form-label">Loại hóa đơn</label>
            <select name="loai_cong_no" id="loai_cong_no" class="form-select" required>
                <option value="Thu">Gửi hóa đơn thu</option>
                <option value="Chi">Gửi hóa đơn chi</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="anh_hoa_don" class="form-label">Ảnh hóa đơn (bill/chuyển khoản)</label>
            <input type="file" name="anh_hoa_don" id="anh_hoa_don" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="ghi_chu" class="form-label">Ghi chú</label>
            <textarea name="ghi_chu" id="ghi_chu" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi hóa đơn</button>
    </form>
    <hr>
    <h4>Lịch sử hóa đơn đã gửi</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tour</th>
                <th>Số tiền</th>
                <th>Loại</th>
                <th>Ảnh hóa đơn</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($congNoHDVs as $hd): ?>
                <tr>
                    <td><?= htmlspecialchars($hd['tour_id']) ?></td>
                    <td><?= number_format($hd['so_tien']) ?>đ</td>
                    <td><?= $hd['loai_cong_no'] ?></td>
                    <td><?php if($hd['anh_hoa_don']): ?><a href="<?= $hd['anh_hoa_don'] ?>" target="_blank">Xem ảnh</a><?php endif; ?></td>
                    <td><?= $hd['trang_thai'] ?></td>
                    <td><?= $hd['ngay_gui'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
