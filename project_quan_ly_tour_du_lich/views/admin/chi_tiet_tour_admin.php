<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Tour - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
    <style>
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: #f8f9fa;
            margin-right: 5px;
        }
        .tab.active {
            background: #007bff;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Chi tiết Tour: <?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></h1>
        <nav>
            <a href="index.php?act=admin/quanLyTour">← Quay lại danh sách</a>
            <a href="index.php?act=tour/update&id=<?php echo $tour['tour_id']; ?>">Sửa tour</a>
        </nav>

        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
                <div style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" onclick="showTab('thong-tin')">Thông tin Tour</button>
                <button class="tab" onclick="showTab('lich-trinh')">Lịch trình</button>
                <button class="tab" onclick="showTab('lich-khoi-hanh')">Lịch Khởi Hành</button>
                <button class="tab" onclick="showTab('hinh-anh')">Hình ảnh</button>
            </div>

            <!-- Tab: Thông tin Tour -->
            <div id="thong-tin" class="tab-content active">
                <h2>Thông tin Tour</h2>
                <table>
                    <tr>
                        <th>ID Tour</th>
                        <td>#<?php echo $tour['tour_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Tên tour</th>
                        <td><?php echo htmlspecialchars($tour['ten_tour'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Loại tour</th>
                        <td><?php echo htmlspecialchars($tour['loai_tour'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Mô tả</th>
                        <td><?php echo nl2br(htmlspecialchars($tour['mo_ta'] ?? '')); ?></td>
                    </tr>
                    <tr>
                        <th>Giá cơ bản</th>
                        <td><?php echo number_format((float)($tour['gia_co_ban'] ?? 0)); ?> VNĐ</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td><?php echo htmlspecialchars($tour['trang_thai'] ?? ''); ?></td>
                    </tr>
                    <?php if (!empty($tour['chinh_sach'])): ?>
                    <tr>
                        <th>Chính sách</th>
                        <td><?php echo nl2br(htmlspecialchars($tour['chinh_sach'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>

            <!-- Tab: Lịch trình -->
            <div id="lich-trinh" class="tab-content">
                <h2>Lịch trình Tour</h2>
                <?php if (!empty($lichTrinhList)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Địa điểm</th>
                                <th>Hoạt động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lichTrinhList as $lt): ?>
                                <tr>
                                    <td>Ngày <?php echo $lt['ngay_thu']; ?></td>
                                    <td><?php echo htmlspecialchars($lt['dia_diem']); ?></td>
                                    <td><?php echo htmlspecialchars($lt['hoat_dong']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có lịch trình.</p>
                <?php endif; ?>
            </div>

            <!-- Tab: Lịch Khởi Hành -->
            <div id="lich-khoi-hanh" class="tab-content">
                <h2>Lịch Khởi Hành</h2>
                <p><a href="index.php?act=tour/taoLichKhoiHanh&tour_id=<?php echo $tour['tour_id']; ?>">+ Tạo lịch khởi hành mới</a></p>
                
                <?php if (!empty($lichKhoiHanhList)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Ngày khởi hành</th>
                                <th>Giờ xuất phát</th>
                                <th>Ngày kết thúc</th>
                                <th>Giờ kết thúc</th>
                                <th>Điểm tập trung</th>
                                <th>Số chỗ</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lichKhoiHanhList as $lk): ?>
                                <tr>
                                    <td><?php echo $lk['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lk['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                                    <td><?php echo $lk['gio_xuat_phat'] ?? 'N/A'; ?></td>
                                    <td><?php echo $lk['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lk['ngay_ket_thuc'])) : 'N/A'; ?></td>
                                    <td><?php echo $lk['gio_ket_thuc'] ?? 'N/A'; ?></td>
                                    <td><?php echo htmlspecialchars($lk['diem_tap_trung'] ?? ''); ?></td>
                                    <td><?php echo $lk['so_cho'] ?? 50; ?></td>
                                    <td>
                                        <?php
                                        $statusLabels = [
                                            'SapKhoiHanh' => 'Sắp khởi hành',
                                            'DangChay' => 'Đang chạy',
                                            'HoanThanh' => 'Hoàn thành'
                                        ];
                                        echo $statusLabels[$lk['trang_thai']] ?? $lk['trang_thai'];
                                        ?>
                                    </td>
                                    <td>
                                        <a href="index.php?act=tour/chiTietLichKhoiHanh&id=<?php echo $lk['id']; ?>&tour_id=<?php echo $tour['tour_id']; ?>">Chi tiết & Phân bổ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Chưa có lịch khởi hành nào.</p>
                <?php endif; ?>
            </div>

            <!-- Tab: Hình ảnh -->
            <div id="hinh-anh" class="tab-content">
                <h2>Hình ảnh Tour</h2>
                <?php if (!empty($hinhAnhList)): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                        <?php foreach ($hinhAnhList as $anh): ?>
                            <div>
                                <img src="<?php echo htmlspecialchars($anh['url_anh']); ?>" alt="<?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?>" style="width: 100%; max-height: 200px; object-fit: cover;">
                                <p><?php echo htmlspecialchars($anh['mo_ta'] ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Chưa có hình ảnh nào.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ẩn tất cả tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Hiển thị tab được chọn
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

