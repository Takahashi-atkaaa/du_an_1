<?php
$pageTitle = 'Chi tiết Tour';
$currentPage = 'tours';
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

    .two-column-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    @media (max-width: 992px) {
        .two-column-layout {
            grid-template-columns: 1fr;
        }
    }

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .info-card-header {
        color: var(--accent-gold);
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .info-row {
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: var(--text-muted);
        font-size: 12px;
        margin-bottom: 5px;
    }

    .info-value {
        color: var(--text-light);
        font-size: 14px;
        font-weight: 500;
    }

    .action-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
        margin-bottom: 15px;
    }

    .action-card:hover {
        transform: translateY(-4px);
        background: rgba(45, 45, 45, 0.7);
        border-color: var(--accent-gold);
        color: inherit;
    }

    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 15px;
    }

    .action-icon.bg-primary { background: rgba(13, 110, 253, 0.2); color: #0d6efd; }
    .action-icon.bg-success { background: rgba(25, 135, 84, 0.2); color: #198754; }
    .action-icon.bg-info { background: rgba(13, 202, 240, 0.2); color: #0dcaf0; }
    .action-icon.bg-warning { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .action-icon.bg-danger { background: rgba(220, 53, 69, 0.2); color: #dc3545; }
    .action-icon.bg-secondary { background: rgba(108, 117, 125, 0.2); color: #6c757d; }

    .action-card h6 {
        color: var(--text-light);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .action-card small {
        color: var(--text-muted);
        font-size: 11px;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 2px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
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
</style>

<!-- Page Header -->
<div class="page-header-section">
    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1>🗺️ <?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour không xác định'); ?></h1>
            <p style="color: var(--text-muted); margin-top: 10px;">Chi tiết lịch trình tour</p>
        </div>
        <div>
            <button onclick="window.history.back();" class="btn btn-secondary">
                ← Quay lại
            </button>
        </div>
    </div>
</div>

<div class="two-column-layout">
    <div>
        <!-- Thông tin tour -->
        <div class="info-card">
            <div class="info-card-header">ℹ️ Thông tin Tour</div>
            <div class="info-row">
                <div class="info-label">Tên tour:</div>
                <div class="info-value"><?php echo htmlspecialchars($tour['ten_tour'] ?? 'Tour không xác định'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Ngày khởi hành:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($tour['ngay_khoi_hanh'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Ngày kết thúc:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($tour['ngay_ket_thuc'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Điểm tập trung:</div>
                <div class="info-value"><?php echo htmlspecialchars($tour['diem_tap_trung'] ?? 'Chưa xác định'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Trạng thái:</div>
                <div class="info-value">
                    <span class="badge <?php 
                        echo match($tour['trang_thai']) {
                            'SapKhoiHanh' => 'badge-primary',
                            'DangChay' => 'badge-warning',
                            'HoanThanh' => 'badge-success',
                            'DaHuy' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    ?>">
                        <?php 
                        echo match($tour['trang_thai']) {
                            'SapKhoiHanh' => 'Sắp khởi hành',
                            'DangChay' => 'Đang chạy',
                            'HoanThanh' => 'Hoàn thành',
                            'DaHuy' => 'Đã hủy',
                            default => $tour['trang_thai']
                        };
                        ?>
                    </span>
                </div>
            </div>
            <?php if (!empty($tour['ghi_chu'])): ?>
            <div class="info-row">
                <div class="info-label">Ghi chú:</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($tour['ghi_chu'] ?? '')); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Mô tả tour -->
        <?php if (!empty($tour['mo_ta'])): ?>
        <div class="info-card">
            <div class="info-card-header">📝 Mô tả Tour</div>
            <div style="color: var(--text-muted); line-height: 1.8;">
                <?php echo nl2br(htmlspecialchars($tour['mo_ta'] ?? '')); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div>
        <!-- Quick Actions -->
        <div class="info-card">
            <div class="info-card-header">⚡ Hành động nhanh</div>
            
            <a href="index.php?act=hdv/lich_trinh_chi_tiet&id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-primary">📅</div>
                <h6>Lịch trình chi tiết</h6>
                <small>Xem lịch trình từng ngày</small>
            </a>

            <a href="index.php?act=hdv/khach&tour_id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-success">👥</div>
                <h6>Danh sách Khách</h6>
                <small>Xem thông tin khách trong đoàn</small>
            </a>

            <a href="index.php?act=hdv/checkin&tour_id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-info">✓</div>
                <h6>Check-in & Điểm danh</h6>
                <small>Xác nhận và điểm danh khách</small>
            </a>

            <a href="index.php?act=hdv/nhat_ky&tour_id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-warning">📔</div>
                <h6>Nhật ký Tour</h6>
                <small>Ghi chú hành trình, sự cố</small>
            </a>

            <a href="index.php?act=hdv/yeu_cau_dac_biet&tour_id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-danger">⚠️</div>
                <h6>Yêu cầu đặc biệt</h6>
                <small>Ăn chay, bệnh lý, v.v.</small>
            </a>

            <?php if ($tour['trang_thai'] === 'HoanThanh'): ?>
            <a href="index.php?act=hdv/phan_hoi&tour_id=<?php echo $tour['id']; ?>" class="action-card">
                <div class="action-icon bg-secondary">⭐</div>
                <h6>Đánh giá & Phản hồi</h6>
                <small>Gửi đánh giá tour</small>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
