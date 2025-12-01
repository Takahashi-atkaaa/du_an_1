<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Người dùng - Admin</title>

    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: "Segoe UI", Arial, sans-serif; }

        body {
            background:#f4f6f9;
        }

        .admin-container {
            width:90%;
            max-width:1200px;
            margin:30px auto;
            background:#fff;
            padding:20px;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            font-size:26px;
            margin-bottom:10px;
            color:#222;
            font-weight:700;
        }

        a.back {
            color:#007bff;
            text-decoration:none;
            font-size:14px;
            display:inline-block;
            margin-bottom:20px;
        }
        a.back:hover {
            text-decoration:underline;
        }

        table {
            width:100%;
            border-collapse: collapse;
            margin-top:15px;
        }

        th {
            background:#007bff;
            color:white;
            padding:12px;
            text-align:left;
            font-size:15px;
        }

        td {
            padding:12px;
            font-size:14px;
            border-bottom:1px solid #e5e5e5;
        }

        tr:hover {
            background:#f6faff;
        }

        .status.active {
            color:#28a745;
            font-weight:600;
        }
        .status.inactive {
            color:#dc3545;
            font-weight:600;
        }

        .btn-detail {
            padding:6px 12px;
            border:none;
            background:#28a745;
            color:#fff;
            border-radius:5px;
            cursor:pointer;
            font-size:13px;
        }
        .btn-detail:hover {
            opacity:0.9;
        }

        /* ======= MODAL ======= */
        .modal {
            display:none;
            position:fixed;
            top:0; left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.55);
            justify-content:center;
            align-items:center;
            z-index:9999;
        }

        .modal-content {
            width:420px;
            background:#fff;
            padding:20px 25px;
            border-radius:10px;
            box-shadow:0 5px 20px rgba(0,0,0,0.3);
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from { transform:scale(0.8); opacity:0; }
            to { transform:scale(1); opacity:1; }
        }

        .close-btn {
            float:right;
            font-size:22px;
            font-weight:bold;
            cursor:pointer;
        }
        .close-btn:hover { color:red; }

        .detail-item {
            margin-bottom:10px;
            font-size:15px;
        }

        .detail-item strong {
            min-width:120px;
            display:inline-block;
            color:#333;
        }
    </style>
</head>

<body>
<div class="admin-container">

    <h1>Quản lý Người dùng</h1>
    <a href="index.php?act=admin/dashboard" class="back">← Quay lại Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>

        <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['ho_ten'] ?></td>

                    <td>
                        <?= $user['trang_thai'] ?>
                    </td>

                    <td>
                        <button class="btn-detail"
                            onclick='openDetailModal(<?= json_encode($user) ?>)'>
                            Xem chi tiết
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">Không có người dùng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- ========== MODAL ========== -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>

        <h3 style="margin-bottom:15px;">Chi tiết người dùng</h3>

        <p class="detail-item"><strong>ID:</strong> <span id="m_id"></span></p>
        <p class="detail-item"><strong>Tên đăng nhập:</strong> <span id="m_username"></span></p>
        <p class="detail-item"><strong>Họ tên:</strong> <span id="m_name"></span></p>
        <p class="detail-item"><strong>Vai trò:</strong> <span id="m_role"></span></p>
        <p class="detail-item"><strong>Email:</strong> <span id="m_email"></span></p>
        <p class="detail-item"><strong>Số điện thoại:</strong> <span id="m_phone"></span></p>
        <p class="detail-item"><strong>Trạng thái:</strong> <span id="m_status"></span></p>
    </div>
</div>


<script>
function openDetailModal(user) {
    document.getElementById("m_id").innerText = user.id;
    document.getElementById("m_username").innerText = user.ten_dang_nhap;
    document.getElementById("m_name").innerText = user.ho_ten;
    document.getElementById("m_role").innerText = user.vai_tro;
    document.getElementById("m_email").innerText = user.email;
    document.getElementById("m_phone").innerText = user.so_dien_thoai;
    document.getElementById("m_status").innerText =
        user.trang_thai;

    document.getElementById("detailModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("detailModal").style.display = "none";
}
</script>

</body>
</html>
