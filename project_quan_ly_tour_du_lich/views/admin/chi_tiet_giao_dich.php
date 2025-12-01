<?php
// File: views/admin/chi_tiet_giao_dich.php
// Trang hiển thị chi tiết giao dịch
// Biến $giao_dich chứa thông tin giao dịch được truyền từ controller
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết giao dịch</title>
    <link rel="stylesheet" href="/public/css/admin.css">
</head>
<body>
    <div class="container">
        <h2>Chi tiết giao dịch</h2>
        <?php if (isset($giao_dich)) { ?>
            <table class="table table-bordered">
                <tr><th>ID</th><td><?= htmlspecialchars($giao_dich['id']) ?></td></tr>
                <tr><th>Ngày giao dịch</th><td><?= htmlspecialchars($giao_dich['ngay_giao_dich']) ?></td></tr>
                <tr><th>Số tiền</th><td><?= htmlspecialchars($giao_dich['so_tien']) ?></td></tr>
                <tr><th>Loại giao dịch</th><td><?= htmlspecialchars($giao_dich['loai_giao_dich']) ?></td></tr>
                <tr><th>Mô tả</th><td><?= htmlspecialchars($giao_dich['mo_ta']) ?></td></tr>
                <!-- Thêm các trường khác nếu cần -->
            </table>
        <?php } else { ?>
            <p>Không tìm thấy thông tin giao dịch.</p>
        <?php } ?>
        <a href="/admin/bao-cao-tai-chinh" class="btn btn-primary">Quay lại</a>
    </div>
</body>
</html>
