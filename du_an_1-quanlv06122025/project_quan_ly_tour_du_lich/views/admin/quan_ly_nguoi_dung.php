<?php
$pageTitle = 'Quản lý Người dùng';
$currentPage = 'nguoiDung';
// Khởi tạo một mảng dữ liệu giả định nếu chưa có (chỉ để minh họa)
// TRONG CODE THỰC TẾ, DỮ LIỆU NÀY PHẢI ĐƯỢC LẤY TỪ DATABASE
if (!isset($users)) {
    $users = [
        ['id' => 1, 'ten_dang_nhap' => 'admin_01', 'ho_ten' => 'Nguyễn Văn A', 'email' => 'a.nguyen@example.com', 'so_dien_thoai' => '0912345678', 'vai_tro' => 'Admin', 'trang_thai' => 'Hoạt động', 'ngay_tao' => '2023-01-15 10:00:00'],
        ['id' => 2, 'ten_dang_nhap' => 'huongdanvien_b', 'ho_ten' => 'Lê Thị B', 'email' => 'b.le@example.com', 'so_dien_thoai' => '0901122334', 'vai_tro' => 'HDV', 'trang_thai' => 'Hoạt động', 'ngay_tao' => '2023-03-20 14:30:00'],
        ['id' => 3, 'ten_dang_nhap' => 'khachhang_c', 'ho_ten' => 'Phạm Văn C', 'email' => 'c.pham@example.com', 'so_dien_thoai' => '0887766554', 'vai_tro' => 'KhachHang', 'trang_thai' => 'Bị khóa', 'ngay_tao' => '2023-05-10 08:45:00'],
        ['id' => 4, 'ten_dang_nhap' => 'nhacungcap_d', 'ho_ten' => 'Trần Văn D', 'email' => 'd.tran@example.com', 'so_dien_thoai' => '0709988776', 'vai_tro' => 'NhaCungCap', 'trang_thai' => 'Hoạt động', 'ngay_tao' => '2023-07-01 16:20:00'],
    ];
}

ob_start();
?>

<style>
/* ================================================= */
/* PHẦN CSS TỪ GIAO DIỆN CŨ (Đã được giữ nguyên) */
/* ================================================= */
    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .filter-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .table-wrapper {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead {
        background: rgba(212, 175, 55, 0.1);
    }

    .table th {
        padding: 15px;
        text-align: left;
        font-size: 12px;
    
        letter-spacing: 1px;
        color: var(--accent-gold);
        font-weight: 600;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .table td {
        padding: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: var(--text-light);
        font-size: 13px;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .badge-role {
        padding: 6px 12px;
        border-radius: 2px;
        font-weight: 600;
        font-size: 11px;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .badge-Admin {
        background: rgba(255, 123, 123, 0.2);
        color: #ff7b7b;
        border: 1px solid rgba(255, 123, 123, 0.3);
    }

    .badge-HDV {
        background: rgba(102, 185, 255, 0.2);
        color: #66b9ff;
        border: 1px solid rgba(102, 185, 255, 0.3);
    }

    .badge-KhachHang {
        background: rgba(99, 230, 190, 0.2);
        color: #63e6be;
        border: 1px solid rgba(99, 230, 190, 0.3);
    }

    .badge-NhaCungCap {
        background: rgba(255, 216, 115, 0.2);
        color: #ffd873;
        border: 1px solid rgba(255, 216, 115, 0.3);
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-light);
        font-size: 13px;
        font-weight: 600;
    }

    .form-group .select {
        background: linear-gradient(90deg, #ffe082 0%, #fffde7 100%);
        border: 1px solid #d4af37;
        color: #222;
        padding: 12px 10px;
        font-size: 13px;
        border-radius: 4px;
        transition: all 0.3s;
        width: 100%;
        font-family: inherit;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23d4af37' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
    }

    .form-group .select:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        border-color: var(--accent-gold);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 2px;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert-info {
        background: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.3);
        color: #0dcaf0;
    }

    .welcome-admin {
        position: relative;
        background: linear-gradient(90deg, #2d2d2d 0%, #3a2e13 100%);
        border-radius: 8px;
        padding: 24px 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 12px rgba(212,175,55,0.10);
        display: flex;
        align-items: center;
        gap: 24px;
        overflow: hidden;
    }
    .welcome-admin .welcome-glow {
        content: '';
        position: absolute;
        top: 0; left: -60%;
        width: 60%; height: 100%;
        background: linear-gradient(120deg, rgba(255, 236, 140, 0.18) 0%, rgba(255, 236, 140, 0.45) 50%, rgba(255, 236, 140, 0.18) 100%);
        filter: blur(2px);
        animation: welcome-glow-move 2.8s linear infinite;
        z-index: 1;
    }
    @keyframes welcome-glow-move {
        0% { left: -60%; }
        100% { left: 100%; }
    }
    .welcome-admin .welcome-avatar {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4af37 60%, #fffde7 100%);
        display: flex; align-items: center; justify-content: center;
        font-size: 2.2rem; color: #fff; font-weight: bold;
        box-shadow: 0 0 0 4px rgba(212,175,55,0.12);
        z-index: 2;
    }
    .welcome-admin .welcome-text {
        z-index: 2;
    }
    .welcome-admin .welcome-title {
        margin: 0; color: #ffe082; font-size: 1.7rem; font-weight: 700;
        text-shadow: 0 2px 8px #2d2d2d;
    }
    .welcome-admin .welcome-desc {
        color: #fffde7; font-size: 1rem; margin-top: 6px;
        text-shadow: 0 1px 4px #2d2d2d;
    }
    /* ================================================= */
    /* CSS MỚI CHO MODAL/POPUP VÀ FORM */
    /* ================================================= */
    .modal {
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto; 
        background-color: rgba(0,0,0,0.7); 
        backdrop-filter: blur(5px);
        padding-top: 50px;
    }

    .modal-content {
        background-color: rgba(45, 45, 45, 0.9);
        margin: 5% auto; 
        padding: 30px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        width: 90%; 
        max-width: 600px; 
        border-radius: 4px;
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        position: relative;
        color: var(--text-light);
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .close-btn:hover,
    .close-btn:focus {
        color: var(--accent-gold);
        text-decoration: none;
        cursor: pointer;
    }

    #modalTitle {
        color: var(--accent-gold);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .modal-form label {
        display: block;
        margin-top: 10px;
        margin-bottom: 5px;
        font-weight: 600;
        color: var(--text-light); /* Màu label trong modal */
    }

    .modal-form input[type="text"], 
    .modal-form input[type="email"], 
    .modal-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-light);
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .modal-form select {
        /* Tạo kiểu dáng đẹp cho select trong form Sửa */
        background: rgba(255, 255, 255, 0.05) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23d4af37' d='M6 9L1 4h10z'/%3E%3C/svg%3E") no-repeat right 10px center;
        padding-right: 30px;
        appearance: none;
    }

    .modal-form input:focus, .modal-form select:focus {
        outline: none;
        border-color: #d4af37;
        box-shadow: 0 0 0 1px #d4af37;
    }

    .btn-save-modal {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: #d4af37;
        color: #222;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-save-modal:hover {
        background: #ffe082;
    }
</style>

<div class="welcome-admin">
    <div class="welcome-glow"></div>
    <div class="welcome-avatar">👑</div>
    <div class="welcome-text">
        <h2 class="welcome-title">Xin chào Quản trị viên!</h2>
        <div class="welcome-desc">Chúc bạn một ngày làm việc hiệu quả và vui vẻ.</div>
    </div>
</div>

<div class="page-header-section">
    <div>
        <h1>👥 Quản lý Người dùng</h1>
        <p style="color: var(--text-muted); margin-top: 10px;">Quản lý thông tin nhân viên, khách hàng và nhà cung cấp</p>
    </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($_SESSION['flash']['message'] ?? ''); ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="filter-section">
    <form method="get" action="">
        <input type="hidden" name="act" value="admin/quanLyNguoiDung">
        <div class="filter-row">
            <div class="form-group">
                <label>Lọc theo vai trò</label>
                <select name="role" class="select">
                    <option value="">-- Tất cả --</option>
                    <option value="Admin" <?php echo (isset($role) && $role === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="HDV" <?php echo (isset($role) && $role === 'HDV') ? 'selected' : ''; ?>>HDV</option>
                    <option value="KhachHang" <?php echo (isset($role) && $role === 'KhachHang') ? 'selected' : ''; ?>>Khách hàng</option>
                    <option value="NhaCungCap" <?php echo (isset($role) && $role === 'NhaCungCap') ? 'selected' : ''; ?>>Nhà cung cấp</option>
                </select>
            </div>
            <div class="form-group">
                <label style="opacity: 0;">Áp dụng</label>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    🔍 Áp dụng bộ lọc
                </button>
            </div>
            <div class="form-group">
                <label style="opacity: 0;">Reset</label>
                <a href="index.php?act=admin/quanLyNguoiDung" class="btn btn-secondary" style="width: 100%;">
                    🔄 Đặt lại
                </a>
            </div>
        </div>
    </form>
</div>

<div class="table-wrapper">
    <div style="padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: space-between; align-items: center;">
        <h5 style="margin: 0; color: var(--text-light); font-size: 16px;">Danh sách Người dùng</h5>
        <small style="color: var(--text-muted); font-size: 12px;"><?php echo count($users ?? []); ?> kết quả</small>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <!-- <th>Tên đăng nhập</th> -->
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th style="width: 150px;">Vai trò</th>
                    <!-- <th style="width: 150px;">Ngày tạo</th> -->
                    <th style="width: 150px; text-align: center;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <!-- <td><?php echo htmlspecialchars($u['ten_dang_nhap'] ?? ''); ?></td> -->
                            <td><?php echo htmlspecialchars($u['ho_ten'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['email'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($u['so_dien_thoai'] ?? '-'); ?></td>
                            <td>
                                <?php $v = $u['vai_tro'] ?? 'KhachHang'; ?>
                                <span class="badge-role badge-<?php echo htmlspecialchars($v); ?>">
                                    <?php echo htmlspecialchars($v); ?>
                                </span>
                            </td>
                            <!-- <td><?php echo htmlspecialchars($u['ngay_tao'] ?? ''); ?></td> -->
                            <td style="text-align: center;">
                                <button 
                                    class="btn btn-sm btn-info view-detail-btn" 
                                    data-id="<?php echo htmlspecialchars($u['id']); ?>"
                                    data-user='<?php echo json_encode($u); ?>'
                                    style="margin-right: 5px;"
                                >
                                    👀 Xem
                                </button>
                                <button 
                                    class="btn btn-sm btn-primary edit-btn" 
                                    data-id="<?php echo htmlspecialchars($u['id']); ?>"
                                    data-user='<?php echo json_encode($u); ?>'
                                >
                                    ✏️ Sửa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="empty-state" style="text-align: center; padding: 60px 20px; color: var(--text-muted);">
                            Không có dữ liệu phù hợp.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="userModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modalTitle"></h2>
        <div id="modalBody">
            </div>
        <div id="modalActions" style="margin-top: 20px; text-align: right;">
            </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('userModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalBody = document.getElementById('modalBody');
        const modalActions = document.getElementById('modalActions');
        const closeBtn = document.querySelector('.close-btn');

        // Hàm hiển thị Modal
        function showModal(title, bodyHTML, actionsHTML = '') {
            modalTitle.innerHTML = title;
            modalBody.innerHTML = bodyHTML;
            modalActions.innerHTML = actionsHTML;
            modal.style.display = 'block';
        }

        // Hàm đóng Modal
        function hideModal() {
            modal.style.display = 'none';
        }

        // Đóng Modal khi click nút X
        closeBtn.onclick = hideModal;

        // Đóng Modal khi click ngoài Modal
        window.onclick = function(event) {
            if (event.target == modal) {
                hideModal();
            }
        }
        
        // **********************************************
        // Xử lý nút Xem Chi Tiết
        // **********************************************
        document.querySelectorAll('.view-detail-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userData = JSON.parse(this.getAttribute('data-user'));

                // Tạo nội dung HTML cho Xem Chi Tiết
                const detailHTML = `
                    <div class="modal-detail-info">
                        <p><strong>ID:</strong> ${userData.id || 'N/A'}</p>
                        <p><strong>Ngày tạo:</strong> ${userData.ngay_tao || 'N/A'}</p>
                        <hr style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin: 15px 0;">
                        <p><strong>Tên đăng nhập:</strong> ${userData.ten_dang_nhap || '-'}</p>
                        <p><strong>Họ tên:</strong> ${userData.ho_ten || '-'}</p>
                        <p><strong>Email:</strong> ${userData.email || '-'}</p>
                        <p><strong>Số điện thoại:</strong> ${userData.so_dien_thoai || '-'}</p>
                        <p><strong>Vai trò:</strong> <span class="badge-role badge-${userData.vai_tro || 'KhachHang'}">${userData.vai_tro || 'KhachHang'}</span></p>
                        <p><strong>Trạng thái:</strong> <span style="font-weight: bold; color: ${userData.trang_thai === 'Hoạt động' ? '#63e6be' : '#ff7b7b'};">${userData.trang_thai || 'N/A'}</span></p>
                    </div>
                `;

                showModal('👀 Chi tiết Người dùng: ' + (userData.ho_ten || userData.ten_dang_nhap), detailHTML, '');
            });
        });

        // **********************************************
        // Xử lý nút Sửa (Form với đầy đủ các trường)
        // **********************************************
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userData = JSON.parse(this.getAttribute('data-user'));

                // Tạo form Sửa
                const editFormHTML = `
                    <form id="editUserForm" class="modal-form">
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <label>ID (Chỉ xem)</label>
                                <input type="text" value="${userData.id}" readonly style="background: rgba(255, 255, 255, 0.03); color: #aaa;">
                            </div>
                            <div style="flex: 1;">
                                <label>Ngày tạo (Chỉ xem)</label>
                                <input type="text" value="${userData.ngay_tao || 'N/A'}" readonly style="background: rgba(255, 255, 255, 0.03); color: #aaa;">
                            </div>
                        </div>

                        <label for="ten_dang_nhap">Tên đăng nhập</label>
                        <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" value="${userData.ten_dang_nhap || ''}" required>

                        <label for="ho_ten">Họ tên</label>
                        <input type="text" id="ho_ten" name="ho_ten" value="${userData.ho_ten || ''}" required>

                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="${userData.email || ''}" required>
                            </div>
                            <div style="flex: 1;">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="${userData.so_dien_thoai || ''}">
                            </div>
                        </div>

                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <label for="vai_tro">Vai trò</label>
                                <select id="vai_tro" name="vai_tro">
                                    <option value="Admin" ${userData.vai_tro === 'Admin' ? 'selected' : ''}>Admin</option>
                                    <option value="HDV" ${userData.vai_tro === 'HDV' ? 'selected' : ''}>HDV</option>
                                    <option value="KhachHang" ${userData.vai_tro === 'KhachHang' || !userData.vai_tro ? 'selected' : ''}>Khách hàng</option>
                                    <option value="NhaCungCap" ${userData.vai_tro === 'NhaCungCap' ? 'selected' : ''}>Nhà cung cấp</option>
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label for="trang_thai">Trạng thái</label>
                                <select id="trang_thai" name="trang_thai">
                                    <option value="Hoạt động" ${userData.trang_thai === 'Hoạt động' ? 'selected' : ''}>Hoạt động</option>
                                    <option value="Bị khóa" ${userData.trang_thai === 'Bị khóa' ? 'selected' : ''}>Bị khóa</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="${userData.id}">
                    </form>
                `;
                
                // Thêm nút Lưu
                const actionsHTML = `
                    <button type="button" class="btn-save-modal" id="saveEditBtn">
                        💾 Lưu Thay Đổi
                    </button>
                `;

                showModal('✏️ Sửa thông tin Người dùng: ' + (userData.ho_ten || userData.ten_dang_nhap), editFormHTML, actionsHTML);
                
                // **********************************************
                // Xử lý sự kiện Lưu (AJAX) - Cần được triển khai ở Backend
                // **********************************************
                document.getElementById('saveEditBtn').addEventListener('click', function() {
                    const form = document.getElementById('editUserForm');
                    const formData = new FormData(form);
                    
                    const data = {};
                    formData.forEach((value, key) => (data[key] = value));

                    console.log("Dữ liệu cần gửi đi:", data);
                    
                    // --- ĐOẠN CODE AJAX THỰC TẾ CẦN ĐƯỢC BỎ COMMENT VÀ CHỈNH SỬA ---
                    
                    fetch('index.php?act=admin/updateUser', { // Thay bằng URL API/Action thực tế của bạn
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            alert('Cập nhật thành công!');
                            hideModal();
                            // Tải lại trang hoặc cập nhật DOM
                            window.location.reload(); 
                        } else {
                            alert('Lỗi cập nhật: ' + (result.message || 'Lỗi không xác định'));
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi AJAX:', error);
                        alert('Đã xảy ra lỗi hệ thống khi cập nhật.');
                    });
                    
                    
                    // Gỉa lập:
                    // alert('Đã giả lập Lưu thành công. Dữ liệu đã được log ra console.');
                    // hideModal();
                    // window.location.reload();
                });
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>