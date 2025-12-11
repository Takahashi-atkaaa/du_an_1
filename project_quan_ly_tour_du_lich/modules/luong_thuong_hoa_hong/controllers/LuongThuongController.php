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
        
        require __DIR__ . '/../views/admin/quan_ly_luong_hdv.php';
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
}

