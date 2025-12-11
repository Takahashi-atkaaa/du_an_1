<?php
$pageTitle = 'Lịch trình chi tiết - HDV';
$currentPage = 'lichTrinhChiTiet';
ob_start();
?>

<style>
    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(255, 255, 255, 0.1);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 30px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -35px;
        top: 10px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--accent-gold);
        border: 3px solid var(--primary-dark);
        box-shadow: 0 0 0 2px var(--accent-gold);
        z-index: 1;
    }

    .timeline-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-left: 4px solid var(--accent-gold);
        border-radius: 2px;
        padding: 25px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .timeline-card:hover {
        transform: translateX(5px);
        border-color: var(--accent-gold);
    }

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .day-badge {
        display: inline-block;
        background: rgba(212, 175, 55, 0.2);
        color: var(--accent-gold);
        padding: 8px 16px;
        border-radius: 2px;
        font-weight: 600;
        margin-bottom: 15px;
        border: 1px solid rgba(212, 175, 55, 0.3);
        font-size: 12px;
    }

    .action-buttons {
        position: sticky;
        top: 2rem;
    }

    .quick-action-btn {
        width: 100%;
        margin-bottom: 10px;
        border-radius: 2px;
        padding: 12px 16px;
        text-align: left;
        transition: all 0.3s;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-light);
        text-decoration: none;
        display: block;
        font-size: 13px;
    }

    .quick-action-btn:hover {
        transform: translateX(5px);
        background: rgba(212, 175, 55, 0.1);
        border-color: var(--accent-gold);
        color: var(--accent-gold);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.3);
        color: #dc3545;
    }

    .alert-success {
        background: rgba(25, 135, 84, 0.1);
        border-color: rgba(25, 135, 84, 0.3);
        color: #198754;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .badge-primary {
        background: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
        border: 1px solid rgba(13, 110, 253, 0.3);
    }

    .badge-warning {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .badge-success {
        background: rgba(25, 135, 84, 0.2);
        color: #198754;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }

    .badge-danger {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }

    .badge-secondary {
        background: rgba(108, 117, 125, 0.2);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.3);
    }

    @media (max-width: 768px) {
        .timeline {
            padding-left: 20px;
        }
        .timeline-item {
            padding-left: 20px;
        }
        .timeline-item::before {
            left: -25px;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>📅 Lịch trình chi tiết</h1>
            <p style="color: var(--text-muted); margin-top: 10px;"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour'); ?></p>
        </div>
        <div>
            <a href="javascript:window.history.back();" class="btn btn-secondary">
                ← Quay lại
            </a>
        </div>
    </div>
</div>

<!-- Alerts -->
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        ⚠ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <div>
        <!-- Thông tin tour -->
        <div class="info-card">
            <h5 style="margin-bottom: 20px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">ℹ️ Thông tin Tour</h5>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">Tên tour</div>
                    <div style="font-weight: 600; color: var(--text-light);"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour không xác định'); ?></div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">Loại tour</div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($tour['loai_tour'] ?? 'N/A'); ?></div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">📅 Ngày khởi hành</div>
                    <div style="font-weight: 600; color: var(--text-light);">
                        <?php 
                        if (!empty($tour['ngay_khoi_hanh'])) {
                            echo date('d/m/Y', strtotime($tour['ngay_khoi_hanh']));
                            if (!empty($tour['gio_xuat_phat'])) {
                                echo ' ' . date('H:i', strtotime($tour['gio_xuat_phat']));
                            }
                        } else {
                            echo 'Chưa xác định';
                        }
                        ?>
                    </div>
                </div>
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">📅 Ngày kết thúc</div>
                    <div style="font-weight: 600; color: var(--text-light);">
                        <?php 
                        if (!empty($tour['ngay_ket_thuc'])) {
                            echo date('d/m/Y', strtotime($tour['ngay_ket_thuc']));
                            if (!empty($tour['gio_ket_thuc'])) {
                                echo ' ' . date('H:i', strtotime($tour['gio_ket_thuc']));
                            }
                        } else {
                            echo 'Chưa xác định';
                        }
                        ?>
                    </div>
                </div>
                <?php if (!empty($tour['diem_tap_trung'])): ?>
                <div style="grid-column: 1 / -1;">
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">📍 Điểm tập trung</div>
                    <div style="color: var(--text-light);"><?php echo htmlspecialchars($tour['diem_tap_trung']); ?></div>
                </div>
                <?php endif; ?>
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">Trạng thái</div>
                    <div>
                        <?php
                        $statusClass = match($tour['trang_thai'] ?? '') {
                            'SapKhoiHanh' => 'badge-primary',
                            'DangChay' => 'badge-warning',
                            'HoanThanh' => 'badge-success',
                            'DaHuy' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                        $statusText = match($tour['trang_thai'] ?? '') {
                            'SapKhoiHanh' => 'Sắp khởi hành',
                            'DangChay' => 'Đang chạy',
                            'HoanThanh' => 'Hoàn thành',
                            'DaHuy' => 'Đã hủy',
                            default => $tour['trang_thai'] ?? 'N/A'
                        };
                        ?>
                        <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                    </div>
                </div>
                <?php if (!empty($tour['so_cho'])): ?>
                <div>
                    <div style="color: var(--text-muted); font-size: 11px; margin-bottom: 5px;">👥 Số chỗ</div>
                    <div style="color: var(--text-light);"><?php echo $tour['so_cho']; ?> chỗ</div>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($tour['mo_ta'])): ?>
            <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <h6 style="color: var(--text-muted); margin-bottom: 10px; font-size: 12px;">Mô tả tour</h6>
                <p style="color: var(--text-light); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($tour['mo_ta'])); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Lịch trình chi tiết -->
        <?php if (!empty($lichTrinhList)): ?>
        <div class="info-card">
            <h5 style="margin-bottom: 25px; color: var(--accent-gold); font-size: 18px; letter-spacing: 1px;">
                ✅ Lịch trình chi tiết từng ngày
            </h5>
            <div class="timeline">
                <?php foreach ($lichTrinhList as $index => $lichTrinh): ?>
                    <div class="timeline-item">
                        <div class="timeline-card">
                            <span class="day-badge">
                                📆 Ngày <?php echo htmlspecialchars($lichTrinh['ngay_thu'] ?? ($index + 1)); ?>
                            </span>
                            
                            <?php if (!empty($lichTrinh['dia_diem'])): ?>
                            <div style="margin-bottom: 15px;">
                                <h6 style="color: var(--accent-gold); margin-bottom: 8px; font-size: 14px;">
                                    📍 Địa điểm
                                </h6>
                                <p style="margin: 0; color: var(--text-light);"><?php echo htmlspecialchars($lichTrinh['dia_diem']); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($lichTrinh['hoat_dong'])): ?>
                            <div>
                                <h6 style="color: var(--accent-gold); margin-bottom: 8px; font-size: 14px;">
                                    🎯 Hoạt động
                                </h6>
                                <div style="color: var(--text-light); line-height: 1.8;">
                                    <?php echo nl2br(htmlspecialchars($lichTrinh['hoat_dong'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="info-card">
            <div class="alert alert-info" style="margin: 0;">
                ℹ️ Chưa có lịch trình chi tiết cho tour này.
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <div class="action-buttons">
            <div class="info-card">
                <h5 style="margin-bottom: 20px; color: var(--accent-gold); font-size: 16px; letter-spacing: 1px;">⚡ Hành động nhanh</h5>
                
                <a href="index.php?act=hdv/tour_detail&id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    ℹ️ Chi tiết tour
                </a>
                
                <a href="index.php?act=hdv/khach&tour_id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    👥 Danh sách khách
                </a>
                
                <a href="index.php?act=hdv/checkin&tour_id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    ✓ Check-in & Điểm danh
                </a>
                
                <a href="index.php?act=hdv/nhat_ky&tour_id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    📝 Nhật ký tour
                </a>
                
                <a href="index.php?act=hdv/yeu_cau_dac_biet&tour_id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    ⚠️ Yêu cầu đặc biệt
                </a>
                
                <?php if ($tour['trang_thai'] === 'HoanThanh'): ?>
                <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour['id']; ?>" 
                   class="quick-action-btn">
                    ⭐ Đánh giá & Phản hồi
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($tour['ghi_chu'])): ?>
            <div class="info-card">
                <h6 style="margin-bottom: 10px; color: var(--accent-gold); font-size: 14px;">📌 Ghi chú</h6>
                <p style="margin: 0; color: var(--text-light); font-size: 12px; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($tour['ghi_chu'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
