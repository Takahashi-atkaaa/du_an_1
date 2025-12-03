<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo Cáo Tài Chính - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header .breadcrumb {
            color: #666;
            font-size: 14px;
        }
        
        .header .breadcrumb a {
            color: #667eea;
            text-decoration: none;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }
        
        .stat-card.revenue .icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .stat-card.expense .icon {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
        }
        
        .stat-card.profit .icon {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .stat-card .value {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-card .change {
            font-size: 13px;
        }
        
        .stat-card .change.positive {
            color: #10b981;
        }
        
        .stat-card .change.negative {
            color: #ef4444;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .card h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .quick-links {
            display: grid;
            gap: 15px;
        }
        
        .quick-link {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .quick-link:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateX(5px);
        }
        
        .quick-link i {
            width: 40px;
            height: 40px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        
        .quick-link:hover i {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .top-tours {
            list-style: none;
        }
        
        .tour-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .tour-item:last-child {
            border-bottom: none;
        }
        
        .tour-name {
            color: #333;
            font-weight: 500;
        }
        
        .tour-revenue {
            color: #667eea;
            font-weight: 700;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: rgba(255, 255, 255, 0.95);
            color: #667eea;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }
        
        .back-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="index.php?act=admin/dashboard" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Quay lại Dashboard
        </a>
        
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> Báo Cáo Tài Chính</h1>
            <div class="breadcrumb">
                <a href="index.php?act=admin/dashboard">Dashboard</a> / 
                <span>Báo cáo tài chính</span>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card revenue">
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <h3>TỔNG THU THÁNG NÀY</h3>
                <div class="value"><?= number_format($tongThu) ?>đ</div>
                <div class="change positive">
                    <i class="fas fa-arrow-up"></i> Tháng <?= date('m/Y') ?>
                </div>
            </div>
            
            <div class="stat-card expense">
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>TỔNG CHI THÁNG NÀY</h3>
                <div class="value"><?= number_format($tongChi) ?>đ</div>
                <div class="change">
                    <i class="fas fa-calendar"></i> Tháng <?= date('m/Y') ?>
                </div>
            </div>
            
            <div class="stat-card profit">
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3>LỢI NHUẬN</h3>
                <div class="value <?= $loiNhuan >= 0 ? 'positive' : 'negative' ?>">
                    <?= number_format($loiNhuan) ?>đ
                </div>
                <div class="change <?= $loiNhuan >= 0 ? 'positive' : 'negative' ?>">
                    <?php if($loiNhuan >= 0): ?>
                        <i class="fas fa-arrow-up"></i> Khả quan
                    <?php else: ?>
                        <i class="fas fa-arrow-down"></i> Cần cải thiện
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="content-grid">
            <div class="card">
                <h2><i class="fas fa-star"></i> Top 5 Tour Doanh Thu Cao Nhất</h2>
                <ul class="top-tours">
                    <?php if(empty($topTours)): ?>
                        <li class="tour-item">
                            <span style="color: #999;">Chưa có dữ liệu</span>
                        </li>
                    <?php else: ?>
                        <?php foreach($topTours as $index => $item): ?>
                            <li class="tour-item">
                                <div>
                                    <span style="color: #667eea; font-weight: 700; margin-right: 10px;">
                                        #<?= $index + 1 ?>
                                    </span>
                                    <span class="tour-name">
                                        <?= htmlspecialchars($item['tour']['ten_tour']) ?>
                                    </span>
                                </div>
                                <span class="tour-revenue">
                                    <?= number_format($item['doanh_thu']) ?>đ
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="card">
                <h2><i class="fas fa-link"></i> Truy Cập Nhanh</h2>
                <div class="quick-links">
                    <a href="index.php?act=admin/lichSuGiaoDich" class="quick-link">
                        <i class="fas fa-history"></i>
                        <div>
                            <div style="font-weight: 600;">Lịch sử giao dịch</div>
                            <div style="font-size: 12px; color: #999;">Xem chi tiết các giao dịch</div>
                        </div>
                    </a>
                    
                    <a href="index.php?act=admin/thuChiTour" class="quick-link">
                        <i class="fas fa-route"></i>
                        <div>
                            <div style="font-weight: 600;">Thu chi từng tour</div>
                            <div style="font-size: 12px; color: #999;">Báo cáo theo tour</div>
                        </div>
                    </a>
                    
                    <a href="index.php?act=admin/congNo" class="quick-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <div>
                            <div style="font-weight: 600;">Công nợ</div>
                            <div style="font-size: 12px; color: #999;">Quản lý công nợ KH/NCC</div>
                        </div>
                    </a>
                    
                    <a href="index.php?act=admin/laiLoTour" class="quick-link">
                        <i class="fas fa-chart-bar"></i>
                        <div>
                            <div style="font-weight: 600;">Lãi lỗ từng tour</div>
                            <div style="font-size: 12px; color: #999;">Phân tích lãi lỗ</div>
                        </div>
                    </a>
                    
                    <a href="index.php?act=admin/duToanTour" class="quick-link">
                        <i class="fas fa-calculator"></i>
                        <div>
                            <div style="font-weight: 600;">Dự toán tour</div>
                            <div style="font-size: 12px; color: #999;">Quản lý dự toán chi phí</div>
                        </div>
                    </a>
                    
                    <a href="index.php?act=admin/soSanhDuToan" class="quick-link">
                        <i class="fas fa-balance-scale"></i>
                        <div>
                            <div style="font-weight: 600;">So sánh dự toán</div>
                            <div style="font-size: 12px; color: #999;">Dự toán vs Thực tế</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
