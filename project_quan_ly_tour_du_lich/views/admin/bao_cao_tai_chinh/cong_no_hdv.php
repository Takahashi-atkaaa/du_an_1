<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
    body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    .card-hdv { background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); padding: 32px; margin-top: 40px; }
    .table-hdv th { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
    .table-hdv td, .table-hdv th { text-align: center; vertical-align: middle; }
    .badge-pos { font-size: 1rem; padding: 8px 16px; border-radius: 8px; }
    .badge-du { background: #10b981; color: #fff; }
    .badge-no { background: #ef4444; color: #fff; }
    .badge-zero { background: #6b7280; color: #fff; }
</style>
<div class="container">
    <div class="card-hdv">
        <h2 class="mb-4" style="color:#764ba2"><i class="fas fa-user-tie"></i> Tổng quan công nợ HDV</h2>
        <table class="table table-bordered table-hdv">
            <thead>
                <tr>
                    <th>HDV</th>
                    <th>Tour</th>
                    <th>Tổng thu</th>
                    <th>Tổng chi</th>
                    <th>Công nợ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($congNoHDV as $row): ?>
                    <tr>
                        <td><i class="fas fa-user"></i> <?= htmlspecialchars($row['ten_hdv']) ?></td>
                        <td><i class="fas fa-route"></i> <?= htmlspecialchars($row['ten_tour']) ?></td>
                        <td><span class="badge badge-pos"><?= number_format($row['tong_thu']) ?>đ</span></td>
                        <td><span class="badge badge-zero"><?= number_format($row['tong_chi']) ?>đ</span></td>
                        <td>
                            <?php if($row['cong_no'] > 0): ?>
                                <span class="badge badge-du"><i class="fas fa-arrow-up"></i> <?= number_format($row['cong_no']) ?>đ</span>
                            <?php elseif($row['cong_no'] < 0): ?>
                                <span class="badge badge-no"><i class="fas fa-arrow-down"></i> <?= number_format($row['cong_no']) ?>đ</span>
                            <?php else: ?>
                                <span class="badge badge-zero"><i class="fas fa-minus"></i> 0đ</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
