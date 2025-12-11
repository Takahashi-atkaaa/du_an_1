<?php
class SalaryBonus {
    public $conn;
    
    public function __construct() {
        $this->conn = connectDB();
    }
    
    /**
     * Lấy danh sách lương theo tour của một HDV
     * @param int $nhanSuId - ID nhân sự
     * @return array - Danh sách lương theo tour
     */
    public function getSalaryByTour($nhanSuId) {
        try {
            // Query lấy lương từ bảng hdv_salary
            // Nối với tour, lich_khoi_hanh, booking để tính toán doanh thu tour
            $sql = "
                SELECT 
                    hs.id as salary_id,
                    t.tour_id,
                    t.ten_tour,
                    lkh.ngay_khoi_hanh,
                    lkh.ngay_ket_thuc,
                    COALESCE(hs.tour_revenue, SUM(b.tong_tien)) as tour_revenue,
                    hs.commission_percentage,
                    hs.commission_amount,
                    hs.base_salary,
                    hs.bonus_amount,
                    hs.total_amount,
                    hs.payment_status,
                    hs.payment_date,
                    hs.notes
                FROM hdv_salary hs
                LEFT JOIN tour t ON hs.tour_id = t.tour_id
                LEFT JOIN lich_khoi_hanh lkh ON hs.lich_khoi_hanh_id = lkh.id
                LEFT JOIN booking b ON t.tour_id = b.tour_id 
                    AND b.ngay_khoi_hanh = lkh.ngay_khoi_hanh
                    AND b.trang_thai IN ('HoanTat', 'DaCoc')
                WHERE hs.nhan_su_id = ?
                GROUP BY hs.id
                ORDER BY lkh.ngay_khoi_hanh DESC
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nhanSuId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Nếu bảng chưa tồn tại, trả về mảng rỗng
            return [];
        }
    }
    
    /**
     * Lấy danh sách thưởng của một HDV
     * @param int $nhanSuId - ID nhân sự
     * @return array - Danh sách thưởng
     */
    public function getBonuses($nhanSuId) {
        try {
            $sql = "
                SELECT 
                    id,
                    bonus_type,
                    amount,
                    reason,
                    award_date,
                    approval_status,
                    approved_by,
                    notes,
                    created_at
                FROM hdv_bonus
                WHERE nhan_su_id = ?
                ORDER BY award_date DESC
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$nhanSuId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Nếu bảng chưa tồn tại, trả về mảng rỗng
            return [];
        }
    }
    
    /**
     * Lấy thống kê tổng lương, thưởng, hoa hồng của HDV
     * @param int $nhanSuId - ID nhân sự
     * @return array - Mảng chứa các thống kê tổng hợp
     */
    public function getSalarySummary($nhanSuId) {
        try {
            // Lấy thông tin cơ bản của HDV
            $sqlHDV = "SELECT luong_co_ban, commission_percentage FROM nhan_su WHERE nhan_su_id = ?";
            $stmtHDV = $this->conn->prepare($sqlHDV);
            $stmtHDV->execute([$nhanSuId]);
            $hdvData = $stmtHDV->fetch();
            
            // Lấy tổng lương từ hdv_salary - CHỈ lọc những bản ghi Pending (chưa duyệt) và Approved (đã duyệt)
            $sqlSalary = "
                SELECT 
                    COALESCE(SUM(base_salary), 0) as total_base_salary,
                    COALESCE(SUM(commission_amount), 0) as total_commission,
                    COALESCE(SUM(bonus_amount), 0) as total_bonus_in_salary,
                    COALESCE(SUM(total_amount), 0) as total_salary
                FROM hdv_salary
                WHERE nhan_su_id = ? AND payment_status IN ('Pending', 'Approved')
            ";
            $stmtSalary = $this->conn->prepare($sqlSalary);
            $stmtSalary->execute([$nhanSuId]);
            $salaryData = $stmtSalary->fetch();
            
            // Lấy tổng thưởng từ hdv_bonus - Chỉ lấy những thưởng đã được phê duyệt (DuyetPhep)
            $sqlBonus = "
                SELECT 
                    COALESCE(SUM(amount), 0) as total_bonus
                FROM hdv_bonus
                WHERE nhan_su_id = ? AND approval_status = 'DuyetPhep'
            ";
            $stmtBonus = $this->conn->prepare($sqlBonus);
            $stmtBonus->execute([$nhanSuId]);
            $bonusData = $stmtBonus->fetch();
            
            return [
                'base_salary' => $salaryData['total_base_salary'] ?? 0,
                'commission' => $salaryData['total_commission'] ?? 0,
                'bonus_in_salary' => $salaryData['total_bonus_in_salary'] ?? 0,
                'total_salary' => $salaryData['total_salary'] ?? 0,
                'total_bonus' => $bonusData['total_bonus'] ?? 0,
                'grand_total' => ($salaryData['total_salary'] ?? 0) + ($bonusData['total_bonus'] ?? 0),
                'commission_percentage' => $hdvData['commission_percentage'] ?? 0
            ];
        } catch (PDOException $e) {
            return [
                'base_salary' => 0,
                'commission' => 0,
                'bonus_in_salary' => 0,
                'total_salary' => 0,
                'total_bonus' => 0,
                'grand_total' => 0,
                'commission_percentage' => 0
            ];
        }
    }
    
    /**
     * Tạo bản ghi lương mới cho một tour
     * @param array $data - Dữ liệu lương
     * @return bool - Kết quả thêm mới
     */
    public function createSalaryRecord($data) {
        try {
            $sql = "
                INSERT INTO hdv_salary 
                (nhan_su_id, tour_id, lich_khoi_hanh_id, base_salary, commission_percentage, tour_revenue, commission_amount, bonus_amount, total_amount, payment_status, notes, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['nhan_su_id'],
                $data['tour_id'],
                $data['lich_khoi_hanh_id'],
                $data['base_salary'] ?? 0,
                $data['commission_percentage'] ?? 0,
                $data['tour_revenue'] ?? 0,
                $data['commission_amount'] ?? 0,
                $data['bonus_amount'] ?? 0,
                $data['total_amount'] ?? 0,
                $data['payment_status'] ?? 'Pending',
                $data['notes'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Cập nhật bản ghi lương
     * @param int $salaryId - ID của bản ghi lương
     * @param array $data - Dữ liệu cập nhật
     * @return bool - Kết quả cập nhật
     */
    public function updateSalaryRecord($salaryId, $data) {
        try {
            $updates = [];
            $params = [];
            
            if (isset($data['base_salary'])) {
                $updates[] = "base_salary = ?";
                $params[] = $data['base_salary'];
            }
            if (isset($data['commission_percentage'])) {
                $updates[] = "commission_percentage = ?";
                $params[] = $data['commission_percentage'];
            }
            if (isset($data['commission_amount'])) {
                $updates[] = "commission_amount = ?";
                $params[] = $data['commission_amount'];
            }
            if (isset($data['bonus_amount'])) {
                $updates[] = "bonus_amount = ?";
                $params[] = $data['bonus_amount'];
            }
            if (isset($data['total_amount'])) {
                $updates[] = "total_amount = ?";
                $params[] = $data['total_amount'];
            }
            if (isset($data['payment_status'])) {
                $updates[] = "payment_status = ?";
                $params[] = $data['payment_status'];
            }
            if (isset($data['notes'])) {
                $updates[] = "notes = ?";
                $params[] = $data['notes'];
            }
            
            $updates[] = "updated_at = NOW()";
            $params[] = $salaryId;
            
            $sql = "UPDATE hdv_salary SET " . implode(", ", $updates) . " WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Thêm thưởng cho HDV
     * @param array $data - Dữ liệu thưởng
     * @return bool - Kết quả thêm mới
     */
    public function addBonus($data) {
        try {
            $sql = "
                INSERT INTO hdv_bonus 
                (nhan_su_id, bonus_type, amount, reason, award_date, approval_status, approved_by, notes, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['nhan_su_id'],
                $data['bonus_type'] ?? 'KhongXacDinh',
                $data['amount'] ?? 0,
                $data['reason'] ?? null,
                $data['award_date'] ?? date('Y-m-d'),
                $data['approval_status'] ?? 'ChoPheDuyet',
                $data['approved_by'] ?? null,
                $data['notes'] ?? null
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Lấy chi tiết bản ghi lương
     * @param int $salaryId - ID của bản ghi lương
     * @return array - Chi tiết bản ghi lương
     */
    public function getSalaryDetail($salaryId) {
        try {
            $sql = "
                SELECT hs.*, t.ten_tour, lkh.ngay_khoi_hanh, lkh.ngay_ket_thuc
                FROM hdv_salary hs
                LEFT JOIN tour t ON hs.tour_id = t.tour_id
                LEFT JOIN lich_khoi_hanh lkh ON hs.lich_khoi_hanh_id = lkh.id
                WHERE hs.id = ?
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$salaryId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Tính toán hoa hồng dựa trên doanh thu tour và tỉ lệ hoa hồng
     * @param float $tourRevenue - Doanh thu tour
     * @param float $commissionPercentage - Tỉ lệ hoa hồng (%)
     * @return float - Số tiền hoa hồng
     */
    public function calculateCommission($tourRevenue, $commissionPercentage) {
        return ($tourRevenue * $commissionPercentage) / 100;
    }
}
?>
