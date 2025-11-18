<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Lịch Khởi Hành - Admin</title>
    <link rel="stylesheet" href="public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Chi tiết Lịch Khởi Hành #<?php echo $lichKhoiHanh['id']; ?></h1>
        <nav>
            <a href="index.php?act=lichKhoiHanh/index">← Quay lại danh sách</a>
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

            <!-- Thông tin lịch khởi hành -->
            <h2>Thông tin Lịch Khởi Hành</h2>
            <table>
                <tr>
                    <th>Tour</th>
                    <td><?php echo htmlspecialchars($lichKhoiHanh['ten_tour'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <th>Ngày khởi hành</th>
                    <td><?php echo $lichKhoiHanh['ngay_khoi_hanh'] ? date('d/m/Y', strtotime($lichKhoiHanh['ngay_khoi_hanh'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Giờ xuất phát</th>
                    <td><?php echo $lichKhoiHanh['gio_xuat_phat'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Ngày kết thúc</th>
                    <td><?php echo $lichKhoiHanh['ngay_ket_thuc'] ? date('d/m/Y', strtotime($lichKhoiHanh['ngay_ket_thuc'])) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Giờ kết thúc</th>
                    <td><?php echo $lichKhoiHanh['gio_ket_thuc'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Điểm tập trung</th>
                    <td><?php echo htmlspecialchars($lichKhoiHanh['diem_tap_trung'] ?? ''); ?></td>
                </tr>
                <tr>
                    <th>Số chỗ</th>
                    <td><?php echo $lichKhoiHanh['so_cho'] ?? 50; ?> chỗ</td>
                </tr>
                <tr>
                    <th>Số booking</th>
                    <td><?php echo $lichKhoiHanh['so_booking'] ?? 0; ?> booking</td>
                </tr>
                <tr>
                    <th>Tổng người đã đặt</th>
                    <td><?php echo $lichKhoiHanh['tong_nguoi_dat'] ?? 0; ?> người</td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td>
                        <?php
                        $statusLabels = [
                            'SapKhoiHanh' => 'Sắp khởi hành',
                            'DangChay' => 'Đang chạy',
                            'HoanThanh' => 'Hoàn thành'
                        ];
                        echo $statusLabels[$lichKhoiHanh['trang_thai']] ?? $lichKhoiHanh['trang_thai'];
                        ?>
                    </td>
                </tr>
            </table>

            <!-- Phân bổ nhân sự -->
            <h2>Phân bổ Nhân sự</h2>
            
            <!-- Form thêm nhân sự -->
            <form method="POST" action="index.php?act=lichKhoiHanh/phanBoNhanSu" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                <table>
                    <tr>
                        <th>Nhân sự</th>
                        <td>
                            <select name="nhan_su_id" required>
                                <option value="">-- Chọn nhân sự --</option>
                                <?php foreach ($nhanSuList as $ns): ?>
                                    <option value="<?php echo $ns['nhan_su_id']; ?>">
                                        <?php echo htmlspecialchars($ns['ho_ten'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($ns['vai_tro'] ?? ''); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>
                            <select name="vai_tro" required>
                                <option value="HDV">HDV</option>
                                <option value="TaiXe">Tài xế</option>
                                <option value="HauCan">Hậu cần</option>
                                <option value="DieuHanh">Điều hành</option>
                                <option value="Khac">Khác</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td><textarea name="ghi_chu" rows="2" style="width: 100%;"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" style="padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Thêm nhân sự</button>
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Danh sách nhân sự đã phân bổ -->
            <?php if (!empty($phanBoNhanSu)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nhân sự</th>
                            <th>Vai trò</th>
                            <th>Email/SĐT</th>
                            <th>Trạng thái</th>
                            <th>Thời gian xác nhận</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phanBoNhanSu as $pb): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pb['ho_ten'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($pb['vai_tro']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($pb['email'] ?? ''); ?><br>
                                    <?php echo htmlspecialchars($pb['so_dien_thoai'] ?? ''); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'ChoXacNhan' => 'Chờ xác nhận',
                                        'DaXacNhan' => 'Đã xác nhận',
                                        'TuChoi' => 'Từ chối',
                                        'Huy' => 'Hủy'
                                    ];
                                    echo $statusLabels[$pb['trang_thai']] ?? $pb['trang_thai'];
                                    ?>
                                </td>
                                <td><?php echo $pb['thoi_gian_xac_nhan'] ? date('d/m/Y H:i', strtotime($pb['thoi_gian_xac_nhan'])) : 'N/A'; ?></td>
                                <td>

                                    <a href="index.php?act=lichKhoiHanh/deleteNhanSu&id=<?php echo $pb['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                       onclick="return confirm('Xóa phân bổ này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có nhân sự nào được phân bổ.</p>
            <?php endif; ?>

            <!-- Phân bổ dịch vụ -->
            <h2>Phân bổ Dịch vụ</h2>
            
            <!-- Form thêm dịch vụ -->
            <form method="POST" action="index.php?act=lichKhoiHanh/phanBoDichVu" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 4px;">
                <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                <table>
                    <tr>
                        <th>Loại dịch vụ</th>
                        <td>
                            <select name="loai_dich_vu" required>
                                <option value="Xe">Xe</option>
                                <option value="KhachSan">Khách sạn</option>
                                <option value="VeMayBay">Vé máy bay</option>
                                <option value="NhaHang">Nhà hàng</option>
                                <option value="DiemThamQuan">Điểm tham quan</option>
                                <option value="Visa">Visa</option>
                                <option value="BaoHiem">Bảo hiểm</option>
                                <option value="Khac">Khác</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Tên dịch vụ</th>
                        <td><input type="text" name="ten_dich_vu" required style="width: 100%;"></td>
                    </tr>
                    <tr>
                        <th>Nhà cung cấp</th>
                        <td>
                            <select name="nha_cung_cap_id">
                                <option value="">-- Chọn nhà cung cấp --</option>
                                <?php foreach ($nhaCungCapList as $ncc): ?>
                                    <option value="<?php echo $ncc['id_nha_cung_cap']; ?>">
                                        <?php echo htmlspecialchars($ncc['ten_don_vi'] ?? 'N/A'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Số lượng</th>
                        <td><input type="number" name="so_luong" value="1" min="1" required></td>
                    </tr>
                    <tr>
                        <th>Đơn vị</th>
                        <td><input type="text" name="don_vi" placeholder="VD: phòng, xe, vé..."></td>
                    </tr>
                    <tr>
                        <th>Ngày bắt đầu</th>
                        <td><input type="date" name="ngay_bat_dau"></td>
                    </tr>
                    <tr>
                        <th>Ngày kết thúc</th>
                        <td><input type="date" name="ngay_ket_thuc"></td>
                    </tr>
                    <tr>
                        <th>Giờ bắt đầu</th>
                        <td><input type="time" name="gio_bat_dau"></td>
                    </tr>
                    <tr>
                        <th>Giờ kết thúc</th>
                        <td><input type="time" name="gio_ket_thuc"></td>
                    </tr>
                    <tr>
                        <th>Địa điểm</th>
                        <td><input type="text" name="dia_diem" style="width: 100%;"></td>
                    </tr>
                    <tr>
                        <th>Giá tiền</th>
                        <td><input type="number" name="gia_tien" step="0.01" min="0"></td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td><textarea name="ghi_chu" rows="2" style="width: 100%;"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Thêm dịch vụ</button>
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Danh sách dịch vụ đã phân bổ -->
            <?php if (!empty($phanBoDichVu)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Loại</th>
                            <th>Tên dịch vụ</th>
                            <th>Nhà cung cấp</th>
                            <th>Số lượng</th>
                            <th>Thời gian</th>
                            <th>Địa điểm</th>
                            <th>Giá tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($phanBoDichVu as $pb): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pb['loai_dich_vu']); ?></td>
                                <td><?php echo htmlspecialchars($pb['ten_dich_vu']); ?></td>
                                <td><?php echo htmlspecialchars($pb['ten_don_vi'] ?? 'N/A'); ?></td>
                                <td><?php echo $pb['so_luong']; ?> <?php echo htmlspecialchars($pb['don_vi'] ?? ''); ?></td>
                                <td>
                                    <?php if ($pb['ngay_bat_dau']): ?>
                                        <?php echo date('d/m/Y', strtotime($pb['ngay_bat_dau'])); ?>
                                        <?php if ($pb['gio_bat_dau']): ?>
                                            <?php echo $pb['gio_bat_dau']; ?>
                                        <?php endif; ?>
                                        <?php if ($pb['ngay_ket_thuc'] && $pb['ngay_ket_thuc'] != $pb['ngay_bat_dau']): ?>
                                            - <?php echo date('d/m/Y', strtotime($pb['ngay_ket_thuc'])); ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($pb['dia_diem'] ?? ''); ?></td>
                                <td><?php echo $pb['gia_tien'] ? number_format($pb['gia_tien']) : 'N/A'; ?> VNĐ</td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'ChoXacNhan' => 'Chờ xác nhận',
                                        'DaXacNhan' => 'Đã xác nhận',
                                        'TuChoi' => 'Từ chối',
                                        'Huy' => 'Hủy',
                                        'HoanTat' => 'Hoàn tất'
                                    ];
                                    echo $statusLabels[$pb['trang_thai']] ?? $pb['trang_thai'];
                                    ?>
                                </td>
                                <td>
                                    <form method="POST" action="index.php?act=lichKhoiHanh/updateTrangThaiDichVu" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $pb['id']; ?>">
                                        <input type="hidden" name="lich_khoi_hanh_id" value="<?php echo $lichKhoiHanh['id']; ?>">
                                        <select name="trang_thai" onchange="this.form.submit()">
                                            <option value="ChoXacNhan" <?php echo $pb['trang_thai'] == 'ChoXacNhan' ? 'selected' : ''; ?>>Chờ xác nhận</option>
                                            <option value="DaXacNhan" <?php echo $pb['trang_thai'] == 'DaXacNhan' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                            <option value="TuChoi" <?php echo $pb['trang_thai'] == 'TuChoi' ? 'selected' : ''; ?>>Từ chối</option>
                                            <option value="Huy" <?php echo $pb['trang_thai'] == 'Huy' ? 'selected' : ''; ?>>Hủy</option>
                                            <option value="HoanTat" <?php echo $pb['trang_thai'] == 'HoanTat' ? 'selected' : ''; ?>>Hoàn tất</option>
                                        </select>
                                    </form>
                                    <a href="index.php?act=lichKhoiHanh/deleteDichVu&id=<?php echo $pb['id']; ?>&lich_khoi_hanh_id=<?php echo $lichKhoiHanh['id']; ?>" 
                                       onclick="return confirm('Xóa phân bổ này?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>Tổng chi phí dịch vụ: <?php echo number_format($tongChiPhi); ?> VNĐ</strong></p>
            <?php else: ?>
                <p>Chưa có dịch vụ nào được phân bổ.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

