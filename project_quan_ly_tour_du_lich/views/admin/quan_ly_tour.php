<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tour - Admin</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>Quản lý Tour</h1>
        <a href="<?php echo BASE_URL; ?>index.php?act=admin/dashboard">← Quay lại Dashboard</a>
        <div class="content">
            <h2>Danh sách Tour</h2>
            <p>
                <a href="<?php echo BASE_URL; ?>index.php?act=tour/create">+ Thêm tour</a>
            </p>
            <?php if (!empty($tours)) : ?>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên tour</th>
                        <th>Loại tour</th>
                        <th>Giá cơ bản</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours as $tour) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tour['tour_id']); ?></td>
 <!-- htmlspecialchars() là hàm PHP dùng để “thoát” (escape) các ký tự đặc biệt trước khi in ra HTML. Những ký tự như:
& → &amp;
< → &lt;
> → &gt;
" → &quot;
' → &#039;
Nếu bạn hiển thị dữ liệu người dùng nhập mà không dùng htmlspecialchars(), trình duyệt có thể hiểu đó là thẻ HTML hoặc script (gây lỗi hoặc bị tấn công XSS).
 Dùng hàm này đảm bảo nội dung hiển thị đúng dạng chữ, không bị thực thi như mã HTML. -->
                        
                        <td><?php echo htmlspecialchars($tour['ten_tour']); ?></td>
                        <td><?php echo htmlspecialchars($tour['loai_tour']); ?></td>
                        <td><?php echo number_format((float)$tour['gia_co_ban'], 0, ',', '.'); ?> đ</td>
                        <td><?php echo htmlspecialchars($tour['trang_thai']); ?></td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/update&id=<?php echo urlencode($tour['tour_id']); ?>">Sửa</a>
                            <!-- urlencode() là hàm PHP dùng để mã hoá chuỗi trước khi đưa lên URL. Nó thay thế các ký tự có thể gây lỗi (dấu cách, dấu tiếng Việt, ký tự đặc biệt như &, ?, =…) bằng dạng an toàn theo chuẩn percent-encoding (%xx) -->
                            <a href="<?php echo BASE_URL; ?>index.php?act=admin/chiTietTour&id=<?php echo urlencode($tour['tour_id']); ?>">Chi tiết</a>
                            |
                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/delete&id=<?php echo urlencode($tour['tour_id']); ?>" onclick="return confirm('Xóa tour này?');">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Chưa có tour nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>


