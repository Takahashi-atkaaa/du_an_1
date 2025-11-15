<?php
class KhachHang 
{
    public $conn;
    
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT * FROM khach_hang";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM khach_hang WHERE khach_hang_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByNguoiDungId($nguoiDungId) {
        $sql = "SELECT * FROM khach_hang WHERE nguoi_dung_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nguoiDungId]);
        return $stmt->fetch();
    }

    public function insert($data) {
        $sql = "INSERT INTO khach_hang (nguoi_dung_id, dia_chi, gioi_tinh, ngay_sinh) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['nguoi_dung_id'],
            $data['dia_chi'] ?? null,
            $data['gioi_tinh'] ?? null,
            $data['ngay_sinh'] ?? null
        ]);
        return $this->conn->lastInsertId();
    }

    // Tìm hoặc tạo khách hàng từ thông tin người dùng
    public function findOrCreateByNguoiDungInfo($nguoiDungId, $diaChi = null, $gioiTinh = null, $ngaySinh = null) {
        // Tìm khách hàng hiện có
        $khachHang = $this->findByNguoiDungId($nguoiDungId);
        if ($khachHang) {
            return $khachHang;
        }
        
        // Tạo mới nếu chưa có
        $khachHangId = $this->insert([
            'nguoi_dung_id' => $nguoiDungId,
            'dia_chi' => $diaChi,
            'gioi_tinh' => $gioiTinh,
            'ngay_sinh' => $ngaySinh
        ]);
        
        return $this->findById($khachHangId);
    }

    // Lấy thông tin khách hàng với thông tin người dùng
    public function getKhachHangWithNguoiDung($khachHangId) {
        $sql = "SELECT kh.*, nd.ho_ten, nd.email, nd.so_dien_thoai, nd.vai_tro
                FROM khach_hang kh
                LEFT JOIN nguoi_dung nd ON kh.nguoi_dung_id = nd.id
                WHERE kh.khach_hang_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$khachHangId]);
        return $stmt->fetch();
    }
}
