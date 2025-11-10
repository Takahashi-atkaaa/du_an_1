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
        $sql = "SELECT * FROM nguoi_dung ORDER BY ngay_tao DESC";
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
        $sql = "INSERT INTO nguoi_dung (ten_dang_nhap, ho_ten, email, mat_khau, vai_tro, ngay_tao) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            $data['ten_dang_nhap'] ?? ($data['email'] ?? ''),
            $data['ho_ten'] ?? '',
            $data['email'] ?? '',
            $data['mat_khau'] ?? '',
            $data['vai_tro'] ?? 'KhachHang',
            $data['ngay_tao'] ?? date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật người dùng
    public function update($id, $data) {
        $sql = "UPDATE nguoi_dung SET ho_ten = ?, email = ?, vai_tro = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['ho_ten'] ?? '',
            $data['email'] ?? '',
            $data['vai_tro'] ?? 'KhachHang',
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
