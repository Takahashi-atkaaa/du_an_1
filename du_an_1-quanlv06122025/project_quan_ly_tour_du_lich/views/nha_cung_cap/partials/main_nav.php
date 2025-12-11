<?php
$currentTab = $currentTab ?? '';
?>
<div class="supplier-nav sticky-top bg-white border-bottom shadow-sm mb-4" style="top:0; z-index:1030;">
    <div class="container-fluid py-3">
        <ul class="nav nav-pills flex-wrap gap-2">
            <li class="nav-item">
                <a class="nav-link <?php echo $currentTab === 'dashboard' ? 'active' : ''; ?>" href="index.php?act=nhaCungCap/dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentTab === 'baoGia' ? 'active' : ''; ?>" href="index.php?act=nhaCungCap/baoGia">
                    <i class="bi bi-file-earmark-text"></i> Báo giá
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentTab === 'dichVu' ? 'active' : ''; ?>" href="index.php?act=nhaCungCap/dichVu">
                    <i class="bi bi-briefcase"></i> Dịch vụ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentTab === 'congNo' ? 'active' : ''; ?>" href="index.php?act=nhaCungCap/congNo">
                    <i class="bi bi-cash-stack"></i> Công nợ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $currentTab === 'hopDong' ? 'active' : ''; ?>" href="index.php?act=nhaCungCap/hopDong">
                    <i class="bi bi-file-earmark-check"></i> Lịch sử hợp tác
                </a>
            </li>
            <li class="nav-item ms-auto">
                <a class="nav-link text-danger" href="index.php?act=auth/logout">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </a>
            </li>
        </ul>
    </div>
</div>

