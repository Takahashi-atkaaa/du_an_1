<?php

class NhatKyTour
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getByHDVAndTour($nhanSuId, $tourId = null)
    {
        $sql = "SELECT nkt.*, t.ten_tour
                FROM nhat_ky_tour nkt
                INNER JOIN tour t ON nkt.tour_id = t.tour_id
                WHERE nkt.nhan_su_id = ?";
        $params = [(int)$nhanSuId];

        if ($tourId) {
            $sql .= " AND nkt.tour_id = ?";
            $params[] = (int)$tourId;
        }

        $sql .= " ORDER BY nkt.ngay_ghi DESC, nkt.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id, $nhanSuId)
    {
        $sql = "SELECT * FROM nhat_ky_tour WHERE id = ? AND nhan_su_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id, (int)$nhanSuId]);
        return $stmt->fetch();
    }

    public function insert($data)
    {
        $sql = "INSERT INTO nhat_ky_tour (tour_id, nhan_su_id, noi_dung, ngay_ghi)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$data['tour_id'],
            (int)$data['nhan_su_id'],
            $data['noi_dung'] ?? '',
            $data['ngay_ghi'] ?? date('Y-m-d')
        ]);
    }

    public function update($id, $nhanSuId, $data)
    {
        $sql = "UPDATE nhat_ky_tour
                SET tour_id = ?, noi_dung = ?, ngay_ghi = ?
                WHERE id = ? AND nhan_su_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$data['tour_id'],
            $data['noi_dung'] ?? '',
            $data['ngay_ghi'] ?? date('Y-m-d'),
            (int)$id,
            (int)$nhanSuId
        ]);
    }

    // Lấy nhật ký theo lịch khởi hành
    public function getByLichKhoiHanh($lichKhoiHanhId)
    {
        $sql = "SELECT nkt.*, 
                       t.ten_tour,
                       nd.ho_ten as nhan_su_ten
                FROM nhat_ky_tour nkt
                INNER JOIN lich_khoi_hanh lk ON nkt.tour_id = lk.tour_id
                LEFT JOIN tour t ON nkt.tour_id = t.tour_id
                LEFT JOIN nhan_su ns ON nkt.nhan_su_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE lk.id = ?
                ORDER BY nkt.ngay_ghi DESC, nkt.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$lichKhoiHanhId]);
        return $stmt->fetchAll();
    }

    // Lấy nhật ký theo ID (không cần nhanSuId cho admin)
    public function findByIdAdmin($id)
    {
        $sql = "SELECT nkt.*, t.ten_tour, nd.ho_ten as nhan_su_ten
                FROM nhat_ky_tour nkt
                LEFT JOIN tour t ON nkt.tour_id = t.tour_id
                LEFT JOIN nhan_su ns ON nkt.nhan_su_id = ns.nhan_su_id
                LEFT JOIN nguoi_dung nd ON ns.nguoi_dung_id = nd.id
                WHERE nkt.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    // Xóa nhật ký (admin)
    public function delete($id)
    {
        $sql = "DELETE FROM nhat_ky_tour WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([(int)$id]);
    }
}

