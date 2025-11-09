<?php 
// Model cho NguoiDung - tương tác với cơ sở dữ liệu
class NguoiDung 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả người dùng
    public function getAll() {
        $sql = "SELECT * FROM nguoi_dung ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy người dùng theo ID
    public function findById($id) {
        $sql = "SELECT * FROM nguoi_dung WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm người dùng theo email
    public function findByEmail($email) {
        $sql = "SELECT * FROM nguoi_dung WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Tìm người dùng theo điều kiện
    public function find($conditions = []) {
        $sql = "SELECT * FROM nguoi_dung";
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
        return $stmt->fetch();
    }

    // Thêm người dùng mới
    public function insert($data) {
        $sql = "INSERT INTO nguoi_dung (ten, email, password, vai_tro, created_at) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['ten'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? '',
            $data['vai_tro'] ?? 'khach_hang',
            $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật người dùng
    public function update($id, $data) {
        $sql = "UPDATE nguoi_dung SET ten = ?, email = ?, vai_tro = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ten'] ?? '',
            $data['email'] ?? '',
            $data['vai_tro'] ?? 'khach_hang',
            $id
        ]);
    }

    // Xóa người dùng
    public function delete($id) {
        $sql = "DELETE FROM nguoi_dung WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}
