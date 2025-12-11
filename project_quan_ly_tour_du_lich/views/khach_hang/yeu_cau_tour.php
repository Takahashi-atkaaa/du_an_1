<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu tour theo mong muốn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(120deg,#f6f8fb 60%,#e3f0ff 100%); }
        .split-col {
            min-height: 520px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .divider-col {
            border-left: 2px solid #e0e0e0;
            min-height: 400px;
            display: flex;
            align-items: stretch;
        }
        .form-section, .list-section {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.10);
            padding: 2.5rem 2.2rem 2.2rem 2.2rem;
            transition: box-shadow 0.2s;
        }
        .form-section:hover, .list-section:hover {
            box-shadow: 0 12px 36px rgba(0,153,255,0.10);
        }
        .form-section h2, .list-section h2 {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }
        .form-group label {
            color: #1976d2;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 12px;
            font-size: 1.08rem;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e0e0e0;
            background: #f8fbff;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 0 2px #e3f0ff;
        }
        .btn-primary {
            background: linear-gradient(90deg,#0099ff 60%,#00c6a7 100%);
            border: none;
            border-radius: 12px;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,153,255,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg,#00c6a7 60%,#0099ff 100%);
            box-shadow: 0 4px 16px rgba(0,153,255,0.13);
        }
        .list-scroll {
            max-height: 420px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #b3b3b3 #f6f8fb;
        }
        .list-scroll::-webkit-scrollbar {
            width: 7px;
        }
        .list-scroll::-webkit-scrollbar-thumb {
            background: #e0e0e0;
            border-radius: 8px;
        }
        .list-scroll::-webkit-scrollbar-track {
            background: #f6f8fb;
        }
        .list-group-item {
            border: none;
            border-radius: 16px !important;
            margin-bottom: 14px;
            box-shadow: 0 2px 12px rgba(0,153,255,0.04);
            background: #f9fbfd;
            padding: 1.1rem 1.2rem 0.8rem 1.2rem;
            font-size: 1.07rem;
        }
        .list-group-item:last-child { margin-bottom: 0; }
        .list-label { color: #888; font-weight: 500; min-width: 110px; display: inline-block; }
        .list-value { color: #1976d2; font-weight: 700; }
        .badge.bg-success, .badge.bg-warning {
            font-size: 1rem;
            border-radius: 8px;
            padding: 0.5em 1.2em;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0,153,255,0.07);
        }
        .badge.bg-success { background: linear-gradient(90deg,#43e97b 60%,#38f9d7 100%)!important; color: #197d2b!important; }
        .badge.bg-warning { background: linear-gradient(90deg,#ffe259 60%,#ffa751 100%)!important; color: #a67c00!important; }
        .text-secondary.small { color: #b3b3b3!important; }
        @media (max-width: 991px) {
            .form-section, .list-section { padding: 1.2rem 0.7rem; }
        }
        @media (max-width: 767px) {
            .divider-col { border-left: none; border-top: 2px solid #e0e0e0; min-height: 0; margin-top: 2rem; }
            .split-col { min-height: 0; }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4 text-center fw-bold" style="letter-spacing:1px;">Gửi yêu cầu tour theo mong muốn</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-0 align-items-stretch">
            <div class="col-lg-6 split-col pe-lg-4 mb-4 mb-lg-0">
                <div class="form-section">
                    <h2 class="text-primary"><i class="bi bi-pencil-square me-2"></i>Nhập thông tin yêu cầu</h2>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <i class="bi bi-check-circle"></i>
                            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="index.php?act=khachHang/guiYeuCauTour">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Địa điểm mong muốn:</label>
                            <input type="text" name="dia_diem" required class="form-control" placeholder="VD: Đà Nẵng, Hội An">
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Thời gian dự kiến:</label>
                            <input type="text" name="thoi_gian" required class="form-control" placeholder="VD: 10/01/2026 - 15/01/2026">
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Số lượng người:</label>
                            <input type="number" name="so_nguoi" min="1" required class="form-control" placeholder="VD: 2">
                        </div>
                        <div class="form-group mb-3">
                            <label class="fw-bold">Yêu cầu đặc biệt:</label>
                            <textarea name="yeu_cau_dac_biet" rows="3" class="form-control" placeholder="Nhập yêu cầu đặc biệt (nếu có)..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            <i class="bi bi-send me-2"></i>Gửi yêu cầu
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-lg-1 d-none d-lg-flex divider-col"></div>
            <div class="col-lg-5 split-col ps-lg-4 mt-4 mt-lg-0">
                <div class="list-section h-100 d-flex flex-column">
                    <h2 class="text-success"><i class="bi bi-list-check me-2"></i>Yêu cầu đã gửi</h2>
                    <div class="list-scroll flex-grow-1">
                        <?php if (!empty($allYeuCau)): ?>
                            <ul class="list-group">
                                <?php foreach ($allYeuCau as $yc): ?>
                                    <?php
                                        $parts = [];
                                        foreach (explode("\n", $yc['noi_dung'] ?? '') as $row) {
                                            $kv = explode(": ", $row, 2);
                                            if (count($kv) == 2) $parts[$kv[0]] = $kv[1];
                                        }
                                        $thoiGianGui = !empty($yc['created_at']) ? date('d/m/Y H:i', strtotime($yc['created_at'])) : 
                                                      (!empty($yc['thoi_gian_gui']) ? date('d/m/Y H:i', strtotime($yc['thoi_gian_gui'])) : 'N/A');
                                    ?>
                                    <li class="list-group-item d-flex flex-column align-items-start">
                                        <div><span class="list-label">Địa điểm:</span> <span class="list-value"><?php echo htmlspecialchars($parts['Địa điểm'] ?? 'N/A'); ?></span></div>
                                        <div><span class="list-label">Thời gian:</span> <span class="list-value"><?php echo htmlspecialchars($parts['Thời gian'] ?? 'N/A'); ?></span></div>
                                        <div><span class="list-label">Số người:</span> <span class="list-value"><?php echo htmlspecialchars($parts['Số người'] ?? 'N/A'); ?></span></div>
                                        <?php if (!empty($parts['Yêu cầu đặc biệt'])): ?>
                                            <div><span class="list-label">Yêu cầu đặc biệt:</span> <span class="list-value"><?php echo htmlspecialchars($parts['Yêu cầu đặc biệt']); ?></span></div>
                                        <?php endif; ?>
                                        <div class="text-secondary small mt-1"><i class="bi bi-clock"></i> Gửi lúc: <?php echo htmlspecialchars($thoiGianGui); ?></div>
                                        <div class="mt-2">
                                            <?php if ($yc['trang_thai'] === 'DaGui'): ?>
                                                <span class="badge bg-success px-3 py-1">Đã gửi</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark px-3 py-1">Chưa gửi</span>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted text-center py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                                Bạn chưa có yêu cầu nào.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

