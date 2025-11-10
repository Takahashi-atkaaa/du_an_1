<?php
// Model cho Tour - tương tác với cơ sở dữ liệu
class Tour 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour
    public function getAll() {
        $sql = "SELECT * FROM tours ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tour theo ID
    public function findById($id) {
        $sql = "SELECT * FROM tours WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm tour theo điều kiện
    public function find($conditions = []) {
        $sql = "SELECT * FROM tours";
        $params = [];
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "$key = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Thêm tour mới
    public function insert($data) {
        $sql = "INSERT INTO tours (ten_tour, mo_ta, gia, so_ngay, so_dem, diem_khoi_hanh, diem_den, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten_tour'] ?? '',
            $data['mo_ta'] ?? '',
            $data['gia'] ?? 0,
            $data['so_ngay'] ?? 1,
            $data['so_dem'] ?? 0,
            $data['diem_khoi_hanh'] ?? '',
            $data['diem_den'] ?? '',
            $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);
    }

    // Cập nhật tour
    public function update($id, $data) {
        $sql = "UPDATE tours SET ten_tour = ?, mo_ta = ?, gia = ?, so_ngay = ?, so_dem = ?, 
                diem_khoi_hanh = ?, diem_den = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten_tour'] ?? '',
            $data['mo_ta'] ?? '',
            $data['gia'] ?? 0,
            $data['so_ngay'] ?? 1,
            $data['so_dem'] ?? 0,
            $data['diem_khoi_hanh'] ?? '',
            $data['diem_den'] ?? '',
            $id
        ]);
    }

    // Xóa tour
    public function delete($id) {
        $sql = "DELETE FROM tours WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
