<?php

class YeuCauDacBiet
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function findOne($khachHangId, $tourId)
    {
        $sql = "SELECT *
                FROM yeu_cau_dac_biet
                WHERE khach_hang_id = ? AND tour_id = ?
                ORDER BY id DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([(int)$khachHangId, (int)$tourId]);
        return $stmt->fetch();
    }

    public function upsert($khachHangId, $tourId, $noiDung)
    {
        $existing = $this->findOne($khachHangId, $tourId);

        if ($existing) {
            $sql = "UPDATE yeu_cau_dac_biet
                    SET noi_dung = ?
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$noiDung, (int)$existing['id']]);
        }

        $sql = "INSERT INTO yeu_cau_dac_biet (khach_hang_id, tour_id, noi_dung)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            (int)$khachHangId,
            (int)$tourId,
            $noiDung
        ]);
    }
}

