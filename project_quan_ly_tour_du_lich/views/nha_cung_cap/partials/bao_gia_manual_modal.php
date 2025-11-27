<div class="modal fade" id="modalBaoGiaThuCong" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gửi báo giá thủ công</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?act=nhaCungCap/storeBaoGiaThuCong">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Form này dùng khi bạn chủ động đề xuất dịch vụ cho một lịch khởi hành cụ thể.<br>
                        <small> Sau khi gửi, yêu cầu sẽ chuyển sang điều hành để phê duyệt.</small>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Lịch khởi hành *</label>
                            <select name="lich_khoi_hanh_id" class="form-select" required>
                                <option value="">-- Chọn --</option>
                                <?php foreach (($lichKhoiHanhOptions ?? []) as $opt): ?>
                                    <option value="<?php echo $opt['id']; ?>">
                                        #<?php echo $opt['id']; ?> - <?php echo htmlspecialchars($opt['ten_tour']); ?> (<?php echo date('d/m', strtotime($opt['ngay_khoi_hanh'])); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chọn từ danh mục có sẵn</label>
                            <select id="catalogTemplateSelect" class="form-select">
                                <option value="">-- Không chọn --</option>
                                <?php foreach (($catalogServices ?? []) as $service): ?>
                                    <option value="<?php echo $service['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($service['ten_dich_vu'], ENT_QUOTES); ?>"
                                        data-loai="<?php echo $service['loai_dich_vu']; ?>"
                                        data-gia="<?php echo $service['gia_tham_khao']; ?>"
                                        data-donvi="<?php echo htmlspecialchars($service['don_vi_tinh'] ?? '', ENT_QUOTES); ?>"
                                        data-mota="<?php echo htmlspecialchars($service['mo_ta'] ?? '', ENT_QUOTES); ?>">
                                        <?php echo htmlspecialchars($service['ten_dich_vu']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Chọn dịch vụ mẫu để tự động điền thông tin.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tên dịch vụ *</label>
                            <input type="text" id="formBaoGiaTenDichVu" name="ten_dich_vu" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Loại dịch vụ</label>
                            <select name="loai_dich_vu" id="formBaoGiaLoaiDichVu" class="form-select">
                                <?php foreach ($loaiDichVuMap as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Số lượng</label>
                            <input type="number" id="formBaoGiaSoLuong" name="so_luong" class="form-control" min="1" value="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Đơn vị</label>
                            <input type="text" id="formBaoGiaDonVi" name="don_vi" class="form-control" placeholder="phòng, suất, chuyến...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá đề xuất (VND)</label>
                            <input type="number" id="formBaoGiaGiaTien" name="gia_tien" class="form-control" min="0" step="1000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" id="formBaoGiaMoTa" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Gửi báo giá</button>
                </div>
            </form>
        </div>
    </div>
</div>
