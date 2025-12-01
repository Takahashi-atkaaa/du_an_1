
<div class="container mt-4">
    <h2 class="mb-3">So sánh chi tiết dự toán & chi phí thực tế</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Loại chi phí</th>
                <th>Dự toán</th>
                <th>Thực tế</th>
                <th>Chênh lệch</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chiPhiSoSanh as $row): ?>
            <tr class="<?= $row['chenh_lech'] > 0 ? 'over' : 'under' ?>">
                <td><?= htmlspecialchars($row['loai_chi_phi']) ?></td>
                <td><?= number_format($row['du_toan']) ?>đ</td>
                <td><?= number_format($row['thuc_te']) ?>đ</td>
                <td>
                    <span class="badge <?= $row['chenh_lech'] > 0 ? 'badge-over' : 'badge-under' ?>">
                        <?= number_format($row['chenh_lech']) ?>đ
                    </span>
                </td>
                <td><?= htmlspecialchars($row['ghi_chu']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mt-4">
        <a href="?action=bao_cao_tai_chinh" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Quay lại</a>
    </div>
</div>
        <thead class="table-dark">
            <tr>
                <th>Hạng mục</th>
                <th>Dự toán (VNĐ)</th>
                <th>Thực tế (VNĐ)</th>
                <th>Chênh lệch</th>
                <th>Cảnh báo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_du_toan = 0;
            $total_thuc_te = 0;
            if (!isset($soSanh) || !is_array($soSanh)) {
                $soSanh = [];
            }
            foreach ($soSanh as $key => $item) {
                $total_du_toan += $item['du_toan'];
                $total_thuc_te += $item['thuc_te'];
                $chenh_lech = $item['thuc_te'] - $item['du_toan'];
                $canh_bao = '';
                if ($item['du_toan'] > 0 && $chenh_lech > 0) {
                    $canh_bao = '<span class="badge bg-danger">Vượt dự toán</span>';
                } elseif ($item['du_toan'] > 0 && $chenh_lech < 0) {
                    $canh_bao = '<span class="badge bg-success">Tiết kiệm</span>';
                }
                echo '<tr>';
                echo '<td>' . htmlspecialchars($key) . '</td>';
                echo '<td>' . number_format($item['du_toan']) . '</td>';
                echo '<td>' . number_format($item['thuc_te']) . '</td>';
                echo '<td>' . number_format($chenh_lech) . '</td>';
                echo '<td>' . $canh_bao . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
        <tfoot class="table-light">
            <tr>
                <th>Tổng cộng</th>
                <th><?= number_format($total_du_toan) ?></th>
                <th><?= number_format($total_thuc_te) ?></th>
                <th><?= number_format($total_thuc_te - $total_du_toan) ?></th>
                <th>
                    <?php
                    if ($total_du_toan > 0 && $total_thuc_te > $total_du_toan) {
                        echo '<span class="badge bg-danger">Lỗ</span>';
                    } elseif ($total_du_toan > 0 && $total_thuc_te < $total_du_toan) {
                        echo '<span class="badge bg-success">Lãi</span>';
                    } else {
                        echo '<span class="badge bg-secondary">Đạt dự toán</span>';
                    }
                    ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
