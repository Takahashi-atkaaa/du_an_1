<?php
/**
 * Controller quản lý lương, thưởng và hoa hồng HDV
 * Module: luong_thuong_hoa_hong
 */

require_once __DIR__ . '/../models/SalaryBonus.php';

class LuongThuongController {
    
    /**
     * Quản lý lương & thưởng HDV
     */
    public function quanLyLuongHDV() {
        requireRole('Admin');
        
        $salaryBonus = new SalaryBonus();
        // Tự động tạo lương cho các tour đã hoàn thành nhưng chưa có bản ghi
        $this->autoGenerateSalaryRecords();
        
        // Lấy danh sách lương
        $db = connectDB();
        $sql = "SELECT hs.*, nd.ho_ten, t.ten_tour 
                FROM hdv_salary hs
                JOIN nhan_su ns ON hs.nhan_su_id = ns.nhan_su_id
                JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                LEFT JOIN tour t ON hs.tour_id = t.tour_id
                ORDER BY hs.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $salary_list = $stmt->fetchAll();
        
        // Lấy danh sách thưởng chờ phê duyệt
        $sql = "SELECT hb.*, nd.ho_ten 
                FROM hdv_bonus hb
                JOIN nhan_su ns ON hb.nhan_su_id = ns.nhan_su_id
                JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE hb.approval_status = 'ChoPheDuyet'
                ORDER BY hb.created_at DESC";

        // Lấy danh sách tất cả thưởng (dùng để hiển thị trong tab Thưởng)
        $sqlAllBonus = "SELECT hb.*, nd.ho_ten
            FROM hdv_bonus hb
            JOIN nhan_su ns ON hb.nhan_su_id = ns.nhan_su_id
            JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
            ORDER BY hb.created_at DESC";

        $stmtAll = $db->prepare($sqlAllBonus);
        $stmtAll->execute();
        $bonus_list_all = $stmtAll->fetchAll();
        
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $bonus_list = $stmt->fetchAll();
        
        // Lấy thống kê
        $stats = [];
        
        // Tổng lương theo trạng thái
        foreach (['Pending', 'Approved', 'Paid'] as $status) {
            $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM hdv_salary WHERE payment_status = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$status]);
            $result = $stmt->fetch();
            $key = strtolower($status) . '_salary';
            $stats[$key] = $result['total'];
        }
        
        // Tổng thưởng chờ phê duyệt
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM hdv_bonus WHERE approval_status = 'ChoPheDuyet'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        $stats['pending_bonus'] = $result['total'];
        
        // Thống kê theo HDV (từ view)
        $sql = "SELECT * FROM view_hdv_salary_summary";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $summary_list = $stmt->fetchAll();
        
        // View gốc đã được di chuyển ra project_root/views/admin/quan_ly_luong_hdv.php
        require __DIR__ . '/../../../views/admin/quan_ly_luong_hdv.php';
    }
    
    /**
     * Duyệt/thanh toán lương HDV (AJAX)
     */
    public function approveSalary() {
        header('Content-Type: application/json');
        
        // Kiểm tra method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        // Lấy dữ liệu JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $salaryId = $input['salary_id'] ?? 0;
        $status = $input['status'] ?? '';
        
        if (!$salaryId || !in_array($status, ['Approved', 'Paid'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }
        
        try {
            $salaryBonus = new SalaryBonus();
            
            $data = [
                'payment_status' => $status,
                'payment_date' => $status === 'Paid' ? date('Y-m-d H:i:s') : null
            ];
            
            if ($salaryBonus->updateSalaryRecord($salaryId, $data)) {
                echo json_encode(['success' => true, 'message' => 'Updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Phê duyệt/từ chối thưởng HDV (AJAX)
     */
    public function approveBonus() {
        header('Content-Type: application/json');
        
        // Kiểm tra method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        
        // Lấy dữ liệu JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $bonusId = $input['bonus_id'] ?? 0;
        $status = $input['status'] ?? '';
        
        if (!$bonusId || !in_array($status, ['DuyetPhep', 'TuChoi'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }
        
        try {
            $db = connectDB();
            
            $sql = "UPDATE hdv_bonus SET approval_status = ?, approved_by = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            
            $userId = $_SESSION['user_id'] ?? null;
            
            if ($stmt->execute([$status, $userId, $bonusId])) {
                echo json_encode(['success' => true, 'message' => 'Updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        
        exit;
    }

    /**
     * Tự động tạo bản ghi lương cho các lịch khởi hành đã hoàn thành nhưng chưa có lương
     * Hỗ trợ nhiều HDV trên cùng lịch (hdv_id hoặc phân bổ vai trò HDV/HDVChinh/Guide)
     */
    private function autoGenerateSalaryRecords() {
        try {
            $conn = connectDB();

            $sql = "
                SELECT 
                    c.lich_id,
                    c.tour_id,
                    c.nhan_su_id,
                    c.ngay_khoi_hanh,
                    COALESCE(c.ngay_ket_thuc, c.ngay_khoi_hanh) AS ngay_ket_thuc,
                    ns.luong_co_ban,
                    ns.commission_percentage
                FROM (
                    -- HDV chính gán trực tiếp trên lịch
                    SELECT 
                        lk.id AS lich_id,
                        lk.tour_id,
                        lk.hdv_id AS nhan_su_id,
                        lk.ngay_khoi_hanh,
                        lk.ngay_ket_thuc,
                        lk.trang_thai
                    FROM lich_khoi_hanh lk
                    WHERE lk.hdv_id IS NOT NULL
                    
                    UNION ALL
                    
                    -- HDV được phân bổ qua bảng phân bổ nhân sự
                    SELECT 
                        lk.id AS lich_id,
                        lk.tour_id,
                        pbn.nhan_su_id,
                        lk.ngay_khoi_hanh,
                        lk.ngay_ket_thuc,
                        lk.trang_thai
                    FROM lich_khoi_hanh lk
                    JOIN phan_bo_nhan_su pbn 
                        ON pbn.lich_khoi_hanh_id = lk.id 
                        AND pbn.trang_thai NOT IN ('TuChoi')
                        AND pbn.vai_tro IN ('HDV', 'HDVChinh', 'Guide')
                ) c
                JOIN nhan_su ns ON ns.nhan_su_id = c.nhan_su_id
                WHERE (
                        c.trang_thai IN ('HoanThanh', 'HoanTat')
                        OR (c.ngay_ket_thuc IS NOT NULL AND c.ngay_ket_thuc < CURDATE())
                        OR (c.ngay_ket_thuc IS NULL AND c.ngay_khoi_hanh < CURDATE())
                )
                  AND NOT EXISTS (
                      SELECT 1 FROM hdv_salary hs 
                      WHERE hs.lich_khoi_hanh_id = c.lich_id 
                        AND hs.nhan_su_id = c.nhan_su_id
                  )
            ";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll();

            if (empty($rows)) {
                return;
            }

            $insertSql = "
                INSERT INTO hdv_salary 
                (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status, notes, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, 'Pending', 'Tự động tạo khi tour hoàn thành', NOW(), NOW())
            ";
            $insertStmt = $conn->prepare($insertSql);

            $revenueSql = "
                SELECT COALESCE(SUM(tong_tien), 0) AS revenue
                FROM booking
                WHERE tour_id = ? 
                  AND ngay_khoi_hanh = ?
                  AND trang_thai IN ('HoanTat', 'DaCoc')
            ";
            $revenueStmt = $conn->prepare($revenueSql);

            foreach ($rows as $row) {
                $revenueStmt->execute([
                    (int)$row['tour_id'],
                    $row['ngay_khoi_hanh']
                ]);
                $rev = (float)($revenueStmt->fetchColumn() ?: 0);

                $commissionPercentage = (float)($row['commission_percentage'] ?? 0);
                $commissionAmount = ($rev * $commissionPercentage) / 100;
                $baseSalary = (float)($row['luong_co_ban'] ?? 0);
                $total = $baseSalary + $commissionAmount;

                $insertStmt->execute([
                    (int)$row['nhan_su_id'],
                    (int)$row['tour_id'],
                    (int)$row['lich_id'],
                    $baseSalary,
                    $commissionPercentage,
                    $rev,
                    $commissionAmount,
                    $total
                ]);
            }
        } catch (Exception $e) {
            // bỏ qua để không chặn trang admin
        }
    }
}

