
<div class="container mt-4">
    <h2 class="mb-3">So sánh chi tiết dự toán & chi phí thực tế</h2>
    <table class="table table-bordered table-hover">
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
