<!-- views/admin/bao_cao_tai_chinh/tong_quan_canh_bao.php -->

<!-- Demo giao diện khi có cảnh báo -->
<div class="container" style="max-width:900px;margin:40px auto;">
    <h2 style="color:#d9534f"><i class="fa fa-exclamation-triangle"></i> Tổng quan các dự toán có cảnh báo</h2>
    <table class="table table-bordered table-hover" style="background:#fff;">
        <thead style="background:#f8d7da;">
            <tr>
                <th>STT</th>
                <th>Tên tour</th>
                <th>Loại cảnh báo</th>
                <th>Chi phí thực tế</th>
                <th>Dự toán</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Tour Đà Nẵng 3N2Đ</td>
                <td><span class="badge bg-danger">Vượt dự toán</span></td>
                <td>120,000,000 đ</td>
                <td>100,000,000 đ</td>
                <td><a href="index.php?act=admin/soSanhDuToan&du_toan_id=1" class="btn btn-info btn-sm">Xem chi tiết</a></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Tour Sapa 2N1Đ</td>
                <td><span class="badge bg-warning text-dark">Gần vượt dự toán</span></td>
                <td>89,000,000 đ</td>
                <td>100,000,000 đ</td>
                <td><a href="index.php?act=admin/soSanhDuToan&du_toan_id=2" class="btn btn-info btn-sm">Xem chi tiết</a></td>
            </tr>
        </tbody>
    </table>
    <div class="alert alert-info mt-3">
        <i class="fa fa-info-circle"></i> Các tour có cảnh báo cần được kiểm tra lại chi phí để tránh vượt ngân sách!
    </div>
</div>
<!-- Thêm Bootstrap và FontAwesome nếu chưa có -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
