<?php
require_once "db.php";

class DonHangModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDonHang($keyword = '')
    {
        $query = "
        SELECT 
            don_hang.ma_don_hang, 
            khach_hang.ten_khach_hang, 
            don_hang.trang_thai, 
            don_hang.ngay_dat_hang, 
            don_hang.ma_nhan_vien
        FROM 
            don_hang
        INNER JOIN 
            khach_hang 
        ON 
            don_hang.ma_khach_hang = khach_hang.ma_khach_hang";

        if (!empty($keyword)) {
            $query .= " WHERE don_hang.ma_don_hang LIKE :keyword OR khach_hang.ten_khach_hang LIKE :keyword";
        }

        $stmt = $this->db->prepare($query);
        if (!empty($keyword)) {
            $keyword = "%$keyword%";
            $stmt->bindParam(':keyword', $keyword);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            // Thêm đơn hàng
            $query = "
                INSERT INTO don_hang (ma_don_hang, ngay_giao_hang, tong_gia_tri, trang_thai, ma_nhan_vien, ma_khach_hang)
                VALUES (:ma_don_hang, :ngay_giao_hang, :tong_gia_tri, :trang_thai, :ma_nhan_vien, :ma_khach_hang)";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_don_hang', $data['ma_don_hang']);
            $stmt->bindParam(':ngay_giao_hang', $data['ngay_giao_hang']);
            $stmt->bindParam(':tong_gia_tri', $data['tong_gia_tri']);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            $stmt->bindParam(':ma_nhan_vien', $data['ma_nhan_vien']);
            $stmt->bindParam(':ma_khach_hang', $data['ma_khach_hang']);
            $stmt->execute();

            // Thêm chi tiết đơn hàng
            $queryChiTiet = "
                INSERT INTO chi_tiet_don_hang (ma_don_hang, ma_vat_tu, so_luong, thanh_tien)
                VALUES (:ma_don_hang, :ma_vat_tu, :so_luong, :thanh_tien)";

            $stmtChiTiet = $this->db->prepare($queryChiTiet);

            foreach ($data['chi_tiet_don_hang'] as $chiTiet) {
                $thanh_tien = $chiTiet['so_luong'] * $chiTiet['gia'];
                $stmtChiTiet->bindParam(':ma_don_hang', $data['ma_don_hang']);
                $stmtChiTiet->bindParam(':ma_vat_tu', $chiTiet['ma_vat_tu']);
                $stmtChiTiet->bindParam(':so_luong', $chiTiet['so_luong']);
                $stmtChiTiet->bindParam(':thanh_tien', $thanh_tien);
                $stmtChiTiet->execute();
            }

            $this->db->commit();

            return json_encode([
                'status' => 201,
                'message' => 'Tạo đơn hàng thành công'
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode([
                'status' => 500,
                'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage()
            ]);
        }
    }

    public function update($ma_don_hang, $data)
    {
        try {
            $query = "
                UPDATE don_hang
                SET 
                    trang_thai = :trang_thai
                WHERE ma_don_hang = :ma_don_hang";

            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_don_hang', $ma_don_hang);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
