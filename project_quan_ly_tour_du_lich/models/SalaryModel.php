<?php
class SalaryModel {
    private $conn;

    public function __construct()
    {
        require_once 'config/database.php';  // file bạn đang dùng để kết nối DB
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Tạo lương mới cho HDV
    public function createSalary($data)
    {
        $sql = "INSERT INTO hdan_salary (
                    nhan_su_id, 
                    tour_id,
                    base_salary, 
                    commission_percentage, 
                    commission_amount, 
                    total_amount, 
                    payment_status, 
                    created_at
                ) 
                VALUES (
                    :nhan_su_id, 
                    :tour_id, 
                    :base_salary, 
                    :commission_percentage, 
                    :commission_amount, 
                    :total_amount, 
                    :payment_status,
                    NOW()
                )";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nhan_su_id'            => $data['nhan_su_id'],
            ':tour_id'               => $data['tour_id'],
            ':base_salary'           => $data['base_salary'],
            ':commission_percentage' => $data['commission_percentage'],
            ':commission_amount'     => $data['commission_amount'],
            ':total_amount'          => $data['total_amount'],
            ':payment_status'        => $data['payment_status'],
        ]);
    }

    // Lấy lương theo HDV
    public function getSalaryByGuideId($guideId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM hdan_salary WHERE nhan_su_id = :id");
        $stmt->execute([':id' => $guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả lương
    public function all()
    {
        $stmt = $this->conn->query("SELECT * FROM hdan_salary ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
