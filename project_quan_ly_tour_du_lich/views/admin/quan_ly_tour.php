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
            <?php if (isset($_SESSION['success'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <h2>Danh sách Tour</h2>
            <p>
                <a href="<?php echo BASE_URL; ?>index.php?act=tour/create">+ Thêm tour</a>
            </p>
            <form method="GET" action="<?php echo BASE_URL; ?>index.php" style="margin-bottom: 20px;">
                <input type="hidden" name="act" value="admin/quanLyTour">
                <label for="loai_tour">Lọc theo loại tour:</label>
                <select name="loai_tour" id="loai_tour" onchange="this.form.submit()">
                    <option value="">Tất cả</option>
                    <option value="TrongNuoc" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'TrongNuoc') ? 'selected' : ''; ?>>Trong nước</option>
                    <option value="QuocTe" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'QuocTe') ? 'selected' : ''; ?>>Quốc tế</option>
                    <option value="TheoYeuCau" <?php echo (isset($_GET['loai_tour']) && $_GET['loai_tour'] === 'TheoYeuCau') ? 'selected' : ''; ?>>Theo yêu cầu</option>
                </select>
            </form>
            <?php if (isset($tours) && count($tours) > 0) : ?>
            <table border="1" cellpadding="8" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên tour</th>
                        <th>Loại tour</th>
                        <th>Giá cơ bản</th>
                        <th>Trạng thái</th>
                        <th>QR Code</th>
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
                        <td style="text-align: center;">
                            <?php if (!empty($tour['qr_code_path']) && file_exists(PATH_ROOT . $tour['qr_code_path'])): ?>
                                <a href="<?php echo BASE_URL . htmlspecialchars($tour['qr_code_path']); ?>" target="_blank" title="Xem QR Code">
                                    <img src="<?php echo BASE_URL . htmlspecialchars($tour['qr_code_path']); ?>" 
                                         alt="QR Code" 
                                         style="width: 80px; height: 80px; border: 1px solid #ddd;">
                                </a>
                                <br>
                                <small><a href="<?php echo BASE_URL; ?>index.php?act=tour/bookOnline&tour_id=<?php echo $tour['tour_id']; ?>" target="_blank">Link đặt tour</a></small>
                            <?php else: ?>
                                <button onclick="window.location.href='<?php echo BASE_URL; ?>index.php?act=tour/generateQR&id=<?php echo $tour['tour_id']; ?>'" 
                                        style="padding: 5px 10px; font-size: 12px;">
                                    Tạo QR Code
                                </button>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>index.php?act=tour/update&id=<?php echo urlencode($tour['tour_id']); ?>">Sửa</a>
                            <!-- urlencode() là hàm PHP dùng để mã hoá chuỗi trước khi đưa lên URL. Nó thay thế các ký tự có thể gây lỗi (dấu cách, dấu tiếng Việt, ký tự đặc biệt như &, ?, =…) bằng dạng an toàn theo chuẩn percent-encoding (%xx) -->
                            <a href="<?php echo BASE_URL; ?>index.php?act=admin/chiTietTour&id=<?php echo urlencode($tour['tour_id']); ?>">Chi tiết</a>
                            |
                            <a href="<?php echo BASE_URL; ?>index.php?act=admin/danhSachKhachTheoTour&tour_id=<?php echo urlencode($tour['tour_id']); ?>">Danh sách khách</a>
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


