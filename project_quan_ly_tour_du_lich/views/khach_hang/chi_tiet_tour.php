<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .tour-header { background: linear-gradient(120deg,#0099ff 60%,#00c6a7 100%); color: #fff; padding: 40px 0 36px 0; border-radius: 0 0 32px 32px; }
        .tour-img-main-box { position: relative; }
        .tour-price-badge {
            position: absolute;
            right: 18px;
            bottom: 18px;
            background: #fff;
            color: #ffb300;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.13);
            padding: 7px 22px 6px 22px;
            z-index: 10;
            border: 2px solid #ffe082;
            letter-spacing: 1px;
        }
        @media (max-width: 768px) {
            .tour-price-badge { right: 6px; bottom: 6px; font-size: 1rem; padding: 4px 10px; }
        }
        .tour-img-main { width:100%; aspect-ratio: 16/9; max-height:380px; object-fit:cover; border-radius:18px; box-shadow:0 6px 32px rgba(0,0,0,0.13); border:4px solid #fff; }
        .tour-info-box { background: #fff; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); padding: 32px; margin-top: -70px; }
        .tour-gallery { gap: 10px; }
        .tour-gallery img { width: 74px; height: 74px; object-fit:cover; border-radius:10px; border:2px solid #e0e0e0; transition: transform 0.2s; cursor:pointer; background:#fafbfc; }
        .tour-gallery img:hover { transform: scale(1.08); border-color: #0099ff; }
        .section-title { font-weight: bold; color: #0099ff; margin-top: 32px; letter-spacing: 0.5px; }
        .table th, .table td { vertical-align: middle; }
        @media (max-width: 768px) {
            .tour-info-box { padding: 12px; }
            .tour-header { padding: 24px 0 18px 0; }
        }
    </style>
</head>
<body>
    <div class="tour-header text-center">
        <h1 class="display-4 fw-bold mb-2"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tên tour'); ?></h1>
        <div class="lead mb-2">Loại tour: <span class="fw-bold text-light"><?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></span></div>
    </div>
    <div class="container tour-info-box">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="tour-img-main-box mb-3">
                    <img src="<?php echo htmlspecialchars($anhChinh['url_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=800&q=80'); ?>" class="tour-img-main shadow" alt="Ảnh tour chính">
                    <span class="tour-price-badge shadow">
                        <?php echo number_format($tour['gia_tour'] ?? $tour['gia_co_ban'] ?? 0); ?><span style="font-size:1.1rem;">đ</span>
                    </span>
                </div>
                <?php if (!empty($hinhAnhList)): ?>
                <div class="tour-gallery d-flex flex-wrap align-items-center">
                    <?php foreach ($hinhAnhList as $ha): ?>
                        <img src="<?php echo htmlspecialchars($ha['url_anh']); ?>" alt="Hình ảnh tour" title="<?php echo htmlspecialchars($ha['mo_ta'] ?? ''); ?>"
                        onclick="document.querySelector('.tour-img-main').src=this.src"/>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <h3 class="section-title">Mô tả tour</h3>
                <div class="mb-3 fs-5 text-secondary"> <?php echo nl2br(htmlspecialchars($tour['mo_ta'] ?? 'Chưa có mô tả.')); ?> </div>
                <h4 class="section-title">Thông tin khởi hành</h4>
                <?php if (!empty($lichKhoiHanhList)): ?>
                    <table class="table table-bordered table-sm bg-white">
                        <thead><tr><th>Ngày khởi hành</th><th>Ngày kết thúc</th><th>Điểm tập trung</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                        <?php foreach ($lichKhoiHanhList as $lk): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($lk['ngay_khoi_hanh'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($lk['ngay_ket_thuc'])); ?></td>
                                <td><?php echo htmlspecialchars($lk['diem_tap_trung']); ?></td>
                                <td><?php echo htmlspecialchars($lk['trang_thai']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-muted">Chưa có lịch khởi hành.</div>
                <?php endif; ?>
                <h4 class="section-title">Hướng dẫn viên</h4>
                <?php if (!empty($tour['hdv_info'])): ?>
                    <div class="mb-2">
                        <b><?php echo htmlspecialchars($tour['hdv_info']['ho_ten'] ?? ''); ?></b> - <?php echo htmlspecialchars($tour['hdv_info']['email'] ?? ''); ?>, SĐT: <?php echo htmlspecialchars($tour['hdv_info']['so_dien_thoai'] ?? ''); ?>
                    </div>
                <?php else: ?>
                    <div class="text-muted">Chưa có thông tin hướng dẫn viên.</div>
                <?php endif; ?>
            </div>
        </div>
        <h3 class="section-title">Lịch trình chi tiết</h3>
        <?php if (!empty($lichTrinhList)): ?>
            <table class="table table-bordered table-sm bg-white">
                <thead><tr><th>Ngày</th><th>Địa điểm</th><th>Hoạt động</th></tr></thead>
                <tbody>
                <?php foreach ($lichTrinhList as $lt): ?>
                    <tr>
                        <td><?php echo $lt['ngay_thu']; ?></td>
                        <td><?php echo htmlspecialchars($lt['dia_diem']); ?></td>
                        <td><?php echo htmlspecialchars($lt['hoat_dong']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-muted">Chưa cập nhật lịch trình.</div>
        <?php endif; ?>
        <?php if (!empty($tour['nhat_ky'])): ?>
            <h4 class="section-title">Nhật ký tour</h4>
            <ul>
                <?php foreach ($tour['nhat_ky'] as $nk): ?>
                    <li><?php echo htmlspecialchars($nk['noi_dung']); ?> (<?php echo date('d/m/Y', strtotime($nk['ngay_ghi'])); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if (!empty($tour['yeu_cau_dac_biet'])): ?>
            <h4 class="section-title">Yêu cầu đặc biệt</h4>
            <ul>
                <?php foreach ($tour['yeu_cau_dac_biet'] as $yc): ?>
                    <li><?php echo htmlspecialchars($yc['mo_ta']); ?> (Mức độ: <?php echo htmlspecialchars($yc['muc_do_uu_tien']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="mt-4">
            <a href="index.php?act=khachHang/datTour&id=<?php echo $tour['tour_id'] ?? $tour['id']; ?>" class="btn btn-warning btn-lg">Đặt tour ngay</a>
            <a href="index.php?act=khachHang/dashboard" class="btn btn-outline-secondary ms-2">Quay lại trang chủ</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


