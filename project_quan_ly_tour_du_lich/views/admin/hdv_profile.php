<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hồ sơ HDV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h3>Hồ sơ: <?php echo htmlspecialchars($hdv['ho_ten'] ?? '') ?></h3>
    <div>
      <a href="index.php?act=admin/quanLyHDV" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="card mb-3">
        <div class="card-body text-center">
          <?php if (!empty($hdv['anh'])): ?>
            <img src="<?php echo htmlspecialchars($hdv['anh']) ?>" class="img-fluid rounded mb-2">
          <?php else: ?>
            <div class="bg-secondary text-white rounded p-5 mb-2">No image</div>
          <?php endif; ?>
          <h5><?php echo htmlspecialchars($hdv['ho_ten'] ?? '') ?></h5>
          <p><?php echo htmlspecialchars($hdv['so_dien_thoai'] ?? '') ?></p>
          <p><?php echo htmlspecialchars($hdv['email'] ?? '') ?></p>
        </div>
      </div>
      <div class="card mb-3">
        <div class="card-body">
          <h6>Thông tin</h6>
          <p><strong>Ngày sinh:</strong> <?php echo htmlspecialchars($hdv['ngay_sinh'] ?? '') ?></p>
          <p><strong>Chứng chỉ:</strong><br><?php echo nl2br(htmlspecialchars($hdv['chung_chi'] ?? '')) ?></p>
          <p><strong>Ngôn ngữ:</strong> <?php echo htmlspecialchars($hdv['ngon_ngu'] ?? '') ?></p>
          <p><strong>Sức khoẻ:</strong> <?php echo htmlspecialchars($hdv['suc_khoe'] ?? '') ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
          <h6>Kinh nghiệm</h6>
          <p><?php echo nl2br(htmlspecialchars($hdv['kinh_nghiem'] ?? '')) ?></p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h6>Lịch sử dẫn tour</h6>
          <?php if (!empty($history)): ?>
            <ul class="list-group">
              <?php foreach($history as $h): ?>
                <li class="list-group-item">
                  <strong><?php echo htmlspecialchars($h['start_time'] ?? '') ?></strong>
                  - <?php echo htmlspecialchars($h['title'] ?? ($h['ten_tour'] ?? 'Tour')) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p>Chưa có lịch sử.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
