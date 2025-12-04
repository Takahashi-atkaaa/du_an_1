<?php

?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang khách hàng - Du lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .banner { background: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80') center/cover no-repeat; height: 350px; position: relative; }
        .banner-overlay { position: absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); }
        .banner-content { position: absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:#fff; text-align:center; }
        .tour-card img { height: 180px; object-fit: cover; }
        .tour-card { box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-radius: 12px; }
        .navbar { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .footer { background: #222; color: #fff; padding: 32px 0; margin-top: 48px; }
    </style>
</head>
<body>
    <!-- Nút hỗ trợ nổi đẹp -->
    <button type="button" class="btn btn-lg btn-danger rounded-circle shadow-lg position-fixed d-flex align-items-center justify-content-center" style="bottom: 30px; right: 30px; z-index: 1050; width: 64px; height: 64px;" data-bs-toggle="modal" data-bs-target="#supportModal">
        <i class="bi bi-question-circle-fill fs-2"></i>
    </button>
    <!-- Modal hỗ trợ đẹp -->
    <div class="modal fade" id="supportModal" tabindex="-1" aria-labelledby="supportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-gradient bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="supportModalLabel"><i class="bi bi-headset me-2"></i>Hỗ trợ khách hàng</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <span class="fw-semibold"><i class="bi bi-telephone-fill text-success me-2"></i>Hotline:</span> <span class="badge bg-warning text-dark fs-6 ms-1">1900 1234</span>
                    </div>
                    <form autocomplete="off">
                        <div class="mb-3">
                            <label for="supportName" class="form-label">Tên của bạn</label>
                            <input type="text" class="form-control rounded-3" id="supportName" placeholder="Nhập tên của bạn">
                        </div>
                        <div class="mb-3">
                            <label for="supportEmail" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-3" id="supportEmail" placeholder="Nhập email">
                        </div>
                        <div class="mb-3">
                            <label for="supportMessage" class="form-label">Nội dung hỗ trợ</label>
                            <textarea class="form-control rounded-3" id="supportMessage" rows="3" placeholder="Nhập nội dung cần hỗ trợ..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow"><i class="bi bi-send me-1"></i>Gửi yêu cầu</button>
                    </form>
                    <div class="mt-3 small text-muted">Chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất!</div>
                </div>
            </div>
        </div>
    </div>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow rounded-4 mt-3 mb-4 px-2 py-2" style="background: linear-gradient(90deg, #f8fafc 60%, #e0f2fe 100%);">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2 fs-3 text-primary" href="#">
                <i class="bi bi-globe2"></i> Code not Bug 
            </a>
            <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-semibold d-flex align-items-center gap-1" href="#"><i class="bi bi-house-door"></i> Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-semibold d-flex align-items-center gap-1" href="#"><i class="bi bi-stars"></i> Tour nổi bật</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-semibold d-flex align-items-center gap-1" href="#"><i class="bi bi-gift"></i> Ưu đãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-semibold d-flex align-items-center gap-1" href="#"><i class="bi bi-chat-dots"></i> Đánh giá</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-semibold d-flex align-items-center gap-1" href="#"><i class="bi bi-headset"></i> Hỗ trợ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-pill fw-bold text-danger border border-danger d-flex align-items-center gap-1" href="index.php?act=khachHang/yeuCauTour"><i class="bi bi-plus-circle"></i> Đặt tour theo yêu cầu</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <style>
        .navbar-nav .nav-link {
            transition: background 0.18s, color 0.18s, box-shadow 0.18s;
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            background: linear-gradient(90deg, #e0f2fe 60%, #bae6fd 100%);
            color: #0d6efd !important;
            box-shadow: 0 2px 8px rgba(13,110,253,0.08);
        }
        .navbar-brand {
            letter-spacing: 1px;
        }
        @media (max-width: 991px) {
            .navbar-nav .nav-link { margin-bottom: 8px; }
        }
    </style>
    <!-- Slideshow banner -->
    <div class="position-relative" style="height: 350px;">
        <div id="bannerCarousel" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="3500">
            <div class="carousel-inner h-100">
                <div class="carousel-item active h-100">
                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1500&q=80" class="d-block w-100 h-100" style="object-fit:cover;" alt="Banner 1">
                </div>
                <div class="carousel-item h-100">
                    <img src="https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=1500&q=80" class="d-block w-100 h-100" style="object-fit:cover;" alt="Banner 2">
                </div>
                <div class="carousel-item h-100">
                    <img src="https://sacotravel.com/wp-content/uploads/2023/03/DL3.jpg" class="d-block w-100 h-100" style="object-fit:cover;" alt="Banner 3">
                </div>
            </div>
            <!-- Các nút điều khiển vẫn giữ nguyên, nhưng carousel sẽ tự động chạy -->
            <div class="banner-overlay"></div>
            <div class="banner-content">
                <h1 class="display-4 fw-bold">Khám phá thế giới cùng DuLichPro</h1>
                <p class="lead">Đặt tour dễ dàng, nhận ưu đãi hấp dẫn, trải nghiệm tuyệt vời!</p>
                <a href="#" class="btn btn-warning btn-lg">Xem tour hot</a>
            </div>
        </div>
    </div>
    <div class="container mt-5">
                    <!-- Form tìm kiếm/lọc tour đẹp -->
                    <form method="GET" action="index.php" class="mb-4 row g-3 align-items-end bg-white p-3 rounded-4 shadow-sm">
                        <input type="hidden" name="act" value="khachHang/dashboard">
                        <div class="col-12 col-md-5">
                            <label class="form-label fw-semibold"><i class="bi bi-search me-1"></i> Tìm kiếm tên tour</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control border-0" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Nhập tên tour...">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label fw-semibold"><i class="bi bi-tags me-1"></i> Loại tour</label>
                            <select name="loai_tour" class="form-select border-0 bg-light">
                                <option value="">Tất cả</option>
                                <option value="TrongNuoc" <?php if(($_GET['loai_tour'] ?? '')==='TrongNuoc') echo 'selected'; ?>>Trong nước</option>
                                <option value="QuocTe" <?php if(($_GET['loai_tour'] ?? '')==='QuocTe') echo 'selected'; ?>>Quốc tế</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-3 d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill shadow"><i class="bi bi-search me-1"></i> Tìm kiếm</button>
                        </div>
                    </form>
            <!-- Section: Trải nghiệm cho mọi người (đặt bên ngoài, trên cùng) -->
            <div class="mt-5">
                <h2 class="fw-bold mb-4">Trải nghiệm cho mọi người</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="experience-card position-relative rounded-4 shadow-sm" style="height:320px;">
                            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Sing - Thái" class="w-100 h-100 object-fit-cover rounded-4">
                            <div class="experience-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-4 rounded-4" style="background: linear-gradient(120deg,rgba(0,123,255,0.7) 60%,rgba(0,0,0,0.2) 100%);">
                                <h3 class="fw-bold text-white mb-2">Chốt Gấp Kèo Sing - Thái</h3>
                                <div class="mb-2 text-white fs-5">Deal du lịch HOT nhất Singapore & Thái Lan</div>
                                <a href="#" class="btn btn-light rounded-pill px-4 fw-bold">Khám phá</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="experience-card position-relative rounded-4 shadow-sm" style="height:320px;">
                            <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=800&q=80" alt="Càng Mua Càng Hời" class="w-100 h-100 object-fit-cover rounded-4">
                            <div class="experience-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-4 rounded-4" style="background: linear-gradient(120deg,rgba(255,165,0,0.7) 60%,rgba(0,0,0,0.2) 100%);">
                                <h3 class="fw-bold text-white mb-2">Càng Mua Càng Hời</h3>
                                <div class="mb-2 text-white fs-5">Ưu đãi hấp dẫn. Càng mua nhiều - càng thêm lợi.</div>
                                <a href="#" class="btn btn-light rounded-pill px-4 fw-bold">Khám phá</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="experience-card position-relative rounded-4 shadow-sm" style="height:320px;">
                            <img src="https://megaworldcons.com/wp-content/uploads/2022/11/my-1.jpg" alt="Zone Châu Âu - Hoa Kỳ" class="w-100 h-100 object-fit-cover rounded-4">
                            <div class="experience-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-4 rounded-4" style="background: linear-gradient(120deg,rgba(0,180,180,0.7) 60%,rgba(0,0,0,0.2) 100%);">
                                <h3 class="fw-bold text-white mb-2">Zone Châu Âu - Hoa Kỳ</h3>
                                <div class="mb-2 text-white fs-5">Gợi ý du lịch hàng đầu Châu Âu và Hoa Kỳ.</div>
                                <a href="#" class="btn btn-light rounded-pill px-4 fw-bold">Khám phá</a>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .experience-card {
                        transition: transform 0.2s, box-shadow 0.2s;
                        cursor: pointer;
                        height: 320px;
                        display: flex;
                        flex-direction: column;
                        justify-content: flex-end;
                    }
                    .experience-card:hover {
                        transform: translateY(-8px) scale(1.03);
                        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
                    }
                    .experience-overlay {
                        pointer-events: none;
                    }
                    .experience-overlay .btn {
                        pointer-events: auto;
                    }
                    @media (max-width: 768px) {
                        .experience-card { height: 180px; }
                        .experience-overlay { padding: 0.5rem; }
                        .experience-overlay h3 { font-size: 1.1rem; }
                        .experience-overlay .fs-5 { font-size: 0.95rem !important; }
                    }
                </style>
            </div>
                <!-- Section: Bạn muốn đi đâu chơi? (Demo tĩnh kiểu Klook) -->
                <div class="mt-5">
                    <h2 class="fw-bold mb-4 text-center">Bạn muốn đi đâu chơi?</h2>
                    <div class="container">
                        <div class="row justify-content-center g-4">
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80" alt="Thượng Hải" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">Thượng Hải</h5>
                                        <small class="text-light fs-6">225 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=800&q=80" alt="Bangkok" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">Bangkok</h5>
                                        <small class="text-light fs-6">581 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80" alt="Đà Nẵng" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">Đà Nẵng</h5>
                                        <small class="text-light fs-6">146 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://static.vinwonders.com/production/dia-diem-chup-anh-dep-o-ha-noi-banner.jpg" alt="Hà Nội" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">Hà Nội</h5>
                                        <small class="text-light fs-6">154 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://th.bing.com/th/id/R.96434e839831461c67b7ac9f29f23bf4?rik=0OgVma5kPjCGwA&pid=ImgRaw&r=0" alt="TP. Hồ Chí Minh" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">TP. Hồ Chí Minh</h5>
                                        <small class="text-light fs-6">240 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-6 d-flex justify-content-center">
                                <div class="destination-card position-relative rounded-4 shadow-sm w-100" style="height:260px; max-width:180px;">
                                    <img src="https://sacotravel.com/wp-content/uploads/2023/03/DL3.jpg" alt="Đài Bắc" class="w-100 h-100 object-fit-cover rounded-4">
                                    <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                                        <h5 class="fw-bold text-white mb-1">Đài Bắc</h5>
                                        <small class="text-light fs-6">394 hoạt động</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        .destination-card {
                            transition: transform 0.2s, box-shadow 0.2s;
                            cursor: pointer;
                            height: 260px;
                            display: flex;
                            flex-direction: column;
                            justify-content: flex-end;
                            max-width: 180px;
                        }
                        .destination-card:hover {
                            transform: translateY(-8px) scale(1.04);
                            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
                        }
                        .destination-overlay {
                            pointer-events: none;
                        }
                        @media (max-width: 768px) {
                            .destination-card { height: 140px; max-width: 100px; }
                            .destination-overlay { padding: 0.5rem; }
                            .destination-overlay h5 { font-size: 0.95rem; }
                        }
                    </style>
                    </br>
                    </br>
                    </br>
                </div>
        <h2 class="mb-4 fw-bold"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Tour trong nước</h2>
        <?php if (!empty($tourTrongNuoc)): ?>
        <div class="d-flex flex-row flex-nowrap overflow-auto pb-2" style="gap: 32px;">
            <?php foreach ($tourTrongNuoc as $tour): ?>
            <div class="tour-card card border-0 shadow-lg rounded-4" style="min-width:320px; max-width:340px;">
                <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1465156799763-2c087c332922?auto=format&fit=crop&w=600&q=80'); ?>" class="card-img-top rounded-top-4" alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold mb-2"><i class="bi bi-map me-1"></i><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                    <?php $gia = isset($tour['gia_tour']) && $tour['gia_tour'] !== null ? $tour['gia_tour'] : (isset($tour['gia_co_ban']) && $tour['gia_co_ban'] !== null ? $tour['gia_co_ban'] : 0); ?>
                    <?php 
                        $moTa = $tour['mo_ta_ngan'] ?? $tour['mo_ta'] ?? '';
                        $moTaRutGon = mb_strlen($moTa) > 80 ? mb_substr($moTa, 0, 80) . '...' : $moTa;
                    ?>
                    <p class="card-text text-muted mb-2"><?php echo htmlspecialchars($moTaRutGon); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold text-danger fs-5"><i class="bi bi-cash-coin me-1"></i><?php echo number_format((float)$gia); ?>đ</span>
                        <a href="index.php?act=khachHang/datTour&id=<?php echo $tour['tour_id'] ?? ''; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow"><i class="bi bi-cart-plus"></i> Đặt ngay</a>
                    </div>
                    <div class="mt-3">
                        <a href="index.php?act=khachHang/chiTietTour&id=<?php echo $tour['tour_id']; ?>" class="btn btn-outline-info btn-sm rounded-pill">Xem chi tiết tour</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info rounded-4 shadow-sm">Hiện chưa có tour trong nước nào.</div>
        <?php endif; ?>
        <h2 class="mb-4 fw-bold mt-5"><i class="bi bi-airplane-engines text-primary me-2"></i>Tour quốc tế</h2>
        <?php if (!empty($tourQuocTe)): ?>
        <div class="d-flex flex-row flex-nowrap overflow-auto pb-2" style="gap: 32px;">
            <?php foreach ($tourQuocTe as $tour): ?>
            <div class="tour-card card border-0 shadow-lg rounded-4" style="min-width:320px; max-width:340px;">
                <img src="<?php echo htmlspecialchars($tour['hinh_anh'] ?? 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=600&q=80'); ?>" class="card-img-top rounded-top-4" alt="<?php echo htmlspecialchars($tour['ten_tour']); ?>">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold mb-2"><i class="bi bi-globe2 me-1"></i><?php echo htmlspecialchars($tour['ten_tour']); ?></h5>
                    <?php 
                        $moTaQT = $tour['mo_ta_ngan'] ?? $tour['mo_ta'] ?? '';
                        $moTaRutGonQT = mb_strlen($moTaQT) > 80 ? mb_substr($moTaQT, 0, 80) . '...' : $moTaQT;
                    ?>
                    <p class="card-text text-muted mb-2"><?php echo htmlspecialchars($moTaRutGonQT); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="fw-bold text-danger fs-5"><i class="bi bi-cash-coin me-1"></i><?php echo number_format($tour['gia_tour'] ?? $tour['gia_co_ban'] ?? 0); ?>đ</span>
                        <a href="index.php?act=khachHang/datTour&id=<?php echo $tour['id'] ?? $tour['tour_id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow"><i class="bi bi-cart-plus"></i> Đặt ngay</a>
                    </div>
                    <div class="mt-3">
                        <a href="index.php?act=khachHang/chiTietTour&id=<?php echo $tour['id'] ?? $tour['tour_id']; ?>" class="btn btn-outline-info btn-sm rounded-pill">Xem chi tiết tour</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info rounded-4 shadow-sm">Hiện chưa có tour quốc tế nào.</div>
        <?php endif; ?>
        <?php if (!isset($danhGiaTot)) $danhGiaTot = [];?>
        <div class="mt-5">
            <h2 class="fw-bold mb-4">Ưu đãi đặc biệt</h2>
            <div class="alert alert-success">Giảm ngay 10% cho khách hàng mới! Tặng voucher 500.000đ cho nhóm từ 5 người trở lên.</div>
        </div>
        <div class="mt-5">
            <h2 class="fw-bold mb-4">Đánh giá khách hàng</h2>
            <div class="row g-4">
                <?php foreach ($danhGiaTot as $dg): ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="fst-italic">“<?php echo htmlspecialchars($dg['noi_dung'] ?? $dg['noi_dung'] ?? ''); ?>”</p>
                            <div class="d-flex align-items-center mt-3">
                                <img src="<?php echo htmlspecialchars($dg['anh'] ?? ($dg['anh_dai_dien'] ?? 'https://randomuser.me/api/portraits/men/1.jpg')); ?>" class="rounded-circle me-2" width="40" height="40">
                                <span class="fw-bold"><?php echo htmlspecialchars($dg['ten_khach_hang'] ?? $dg['ten'] ?? 'Ẩn danh'); ?></span>
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-info text-dark">Tiêu chí: <?php echo htmlspecialchars($dg['tieu_chi'] ?? $dg['loai_danh_gia'] ?? ''); ?></span>
                                <span class="badge bg-success ms-2">Đánh giá: <?php echo htmlspecialchars($dg['diem'] ?? $dg['diem'] ?? ''); ?>*</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
            <!-- Section: Bạn muốn đi đâu chơi? (Demo tĩnh kiểu Klook) -->
            <div class="mt-5">
                <h2 class="fw-bold mb-4">Bạn muốn đi đâu chơi?</h2>
                <div class="d-flex flex-row flex-nowrap overflow-auto pb-2" style="gap: 24px;">
                    <!-- Card điểm đến đẹp kiểu Klook -->
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" alt="Thượng Hải" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">Thượng Hải</h6>
                            <small class="text-light">225 hoạt động</small>
                        </div>
                    </div>
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80" alt="Bangkok" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">Bangkok</h6>
                            <small class="text-light">581 hoạt động</small>
                        </div>
                    </div>
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80" alt="Đà Nẵng" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">Đà Nẵng</h6>
                            <small class="text-light">146 hoạt động</small>
                        </div>
                    </div>
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://static.vinwonders.com/production/dia-diem-chup-anh-dep-o-ha-noi-banner.jpg" alt="Hà Nội" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">Hà Nội</h6>
                            <small class="text-light">154 hoạt động</small>
                        </div>
                    </div>
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://th.bing.com/th/id/R.96434e839831461c67b7ac9f29f23bf4?rik=0OgVma5kPjCGwA&pid=ImgRaw&r=0" alt="TP. Hồ Chí Minh" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">TP. Hồ Chí Minh</h6>
                            <small class="text-light">240 hoạt động</small>
                        </div>
                    </div>
                    <div class="destination-card position-relative rounded-4 shadow-sm" style="min-width:200px; max-width:220px; height:260px; background: #fff;">
                        <img src="https://sacotravel.com/wp-content/uploads/2023/03/DL3.jpg" alt="Đài Bắc" class="w-100 h-100 object-fit-cover rounded-4">
                        <div class="destination-overlay position-absolute bottom-0 start-0 w-100 p-3 rounded-bottom-4" style="background: linear-gradient(180deg,rgba(0,0,0,0) 40%,rgba(0,0,0,0.7) 100%);">
                            <h6 class="fw-bold text-white mb-1">Đài Bắc</h6>
                            <small class="text-light">394 hoạt động</small>
                        </div>
                    </div>
                </div>
                <style>
                    .destination-card {
                        transition: transform 0.2s, box-shadow 0.2s;
                        cursor: pointer;
                    }
                    .destination-card:hover {
                        transform: translateY(-8px) scale(1.04);
                        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
                    }
                    .destination-overlay {
                        pointer-events: none;
                    }
                    @media (max-width: 768px) {
                        .destination-card { min-width: 140px; max-width: 160px; height: 160px; }
                        .destination-overlay { padding: 0.5rem; }
                        .destination-overlay h6 { font-size: 0.95rem; }
                    }
                </style>
            </div>
    </div>
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-2">&copy; Dự án được thực hiện bởi 2 thành viên</p>
            <a href="#" class="text-light me-3">Chính sách bảo mật</a>
            <a href="#" class="text-light">Liên hệ hỗ trợ vui lòng gọi: 0346858035 </a>
        </div>
        <!-- Section: Trải nghiệm cho mọi người -->

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = document.querySelector('#bannerCarousel');
        if (myCarousel) {
            var carousel = new bootstrap.Carousel(myCarousel, {
                interval: 1800,
                ride: 'carousel'
            });
        }
    });
    </script>
</body>
</html>


