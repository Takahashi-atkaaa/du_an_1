<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Tour</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>
    <div class="container">
        <a href="<?php echo BASE_URL; ?>index.php?act=tour/index">← Quay lại danh sách</a>
        <?php if (!empty($error)) : ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (!empty($tour)) : ?>
            <div class="tour-detail">
                <h1><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h1>
                <p><?php echo htmlspecialchars($tour['mo_ta'] ?? ''); ?></p>
                <p><strong>Loại tour:</strong> <?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></p>
                <p><strong>Giá cơ bản:</strong> <?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?> VNĐ</p>
                <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($tour['trang_thai'] ?? ''); ?></p>
                <a href="<?php echo BASE_URL; ?>index.php?act=booking/create&tour_id=<?php echo isset($tour['tour_id']) ? urlencode($tour['tour_id']) : ''; ?>">Đặt tour</a>
            </div>

            <div class="tour-extra">
                <h2>Lịch trình chi tiết</h2>
                <?php if (!empty($lichTrinhList)) : ?>
                    <ul>
                        <?php foreach ($lichTrinhList as $lichTrinh) : ?>
                            <li>
                                <strong>Ngày <?php echo htmlspecialchars($lichTrinh['ngay_thu']); ?>:</strong>
                                <?php echo htmlspecialchars($lichTrinh['dia_diem']); ?> -
                                <?php echo htmlspecialchars($lichTrinh['hoat_dong']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Chưa có lịch trình chi tiết.</p>
                <?php endif; ?>
            </div>

            <div class="tour-extra">
                <h2>Lịch khởi hành</h2>
                <?php if (!empty($lichKhoiHanhList)) : ?>
                    <table border="1" cellpadding="6" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Ngày khởi hành</th>
                                <th>Ngày kết thúc</th>
                                <th>Điểm tập trung</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lichKhoiHanhList as $lichKhoiHanh) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lichKhoiHanh['ngay_khoi_hanh']); ?></td>
                                    <td><?php echo htmlspecialchars($lichKhoiHanh['ngay_ket_thuc']); ?></td>
                                    <td><?php echo htmlspecialchars($lichKhoiHanh['diem_tap_trung']); ?></td>
                                    <td><?php echo htmlspecialchars($lichKhoiHanh['trang_thai']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>Chưa có lịch khởi hành nào.</p>
                <?php endif; ?>
            </div>

            <div class="tour-extra">
                <h2>Hình ảnh tour</h2>
                <?php if (!empty($hinhAnhList)) : ?>
                    <div class="tour-images">
                        <?php foreach ($hinhAnhList as $anh) : ?>
                            <figure>
                                <img src="<?php echo htmlspecialchars(BASE_URL . $anh['url_anh']); ?>" alt="<?php echo htmlspecialchars($anh['mo_ta'] ?? 'Hình ảnh tour'); ?>" style="max-width: 240px;">
                                <?php if (!empty($anh['mo_ta'])) : ?>
                                    <figcaption><?php echo htmlspecialchars($anh['mo_ta']); ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p>Chưa có hình ảnh nào.</p>
                <?php endif; ?>
            </div>

            <div class="tour-extra">
                <h2>Yêu cầu đặc biệt của khách</h2>
                <?php if (!empty($yeuCauList)) : ?>
                    <ul>
                        <?php foreach ($yeuCauList as $yeuCau) : ?>
                            <li>
                                <strong>ID khách hàng:</strong> <?php echo htmlspecialchars($yeuCau['khach_hang_id']); ?> -
                                <?php echo htmlspecialchars($yeuCau['noi_dung']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Chưa có yêu cầu đặc biệt.</p>
                <?php endif; ?>
            </div>

            <div class="tour-extra">
                <h2>Nhật ký tour</h2>
                <?php if (!empty($nhatKyList)) : ?>
                    <ul>
                        <?php foreach ($nhatKyList as $nhatKy) : ?>
                            <li>
                                <strong>Ngày ghi:</strong> <?php echo htmlspecialchars($nhatKy['ngay_ghi']); ?> |
                                <strong>Nhân sự ID:</strong> <?php echo htmlspecialchars($nhatKy['nhan_su_id']); ?>
                                <div><?php echo nl2br(htmlspecialchars($nhatKy['noi_dung'])); ?></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Chưa có nhật ký tour.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


