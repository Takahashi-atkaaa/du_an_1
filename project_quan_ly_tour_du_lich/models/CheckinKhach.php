<?php

class CheckinKhach
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getByLichKhoiHanh($lichKhoiHanhId)
    {
        $sql = "SELECT *
                FROM tour_checkin
                WHERE lich_khoi_hanh_id = ?
                ORDER BY updated_at DESC, checkin_time DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$lichKhoiHanhId]);
        return $stmt->fetchAll();
    }

    public function findOne($lichKhoiHanhId, $bookingId, $khachHangId)
    {
        $sql = "SELECT *
                FROM tour_checkin
                WHERE lich_khoi_hanh_id = ?
                  AND booking_id = ?
                  AND khach_hang_id = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$lichKhoiHanhId, (int)$bookingId, (int)$khachHangId]);
        return $stmt->fetch();
    }

    public function insert($data)
    {
        $sql = "INSERT INTO tour_checkin (
                    booking_id, khach_hang_id, lich_khoi_hanh_id,
                    ho_ten, so_cmnd, so_passport, ngay_sinh, gioi_tinh,
                        quoc_tich, dia_chi, so_dien_thoai, email,
                    checkin_time, checkout_time, trang_thai, ghi_chu
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$data['booking_id'],
            (int)$data['khach_hang_id'],
            (int)$data['lich_khoi_hanh_id'],
            $data['ho_ten'] ?? '',
            $data['so_cmnd'] ?? null,
            $data['so_passport'] ?? null,
            $data['ngay_sinh'] ?? null,
            $data['gioi_tinh'] ?? 'Khac',
            $data['quoc_tich'] ?? 'Việt Nam',
            $data['dia_chi'] ?? null,
            $data['so_dien_thoai'] ?? null,
            $data['email'] ?? null,
            $data['checkin_time'] ?? null,
            $data['checkout_time'] ?? null,
            $data['trang_thai'] ?? 'ChuaCheckIn',
            $data['ghi_chu'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE tour_checkin
                SET trang_thai = ?,
                    ghi_chu = ?,
                    checkin_time = ?,
                    checkout_time = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['trang_thai'] ?? 'ChuaCheckIn',
            $data['ghi_chu'] ?? null,
            $data['checkin_time'] ?? null,
            $data['checkout_time'] ?? null,
            (int)$id
        ]);
    }
}

