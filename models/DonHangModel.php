<?php
require_once "db.php";

class DonHangModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDonHang()
    {
        // Sửa câu truy vấn để lấy thông tin từ bảng `don_hang_mua` và bảng liên quan
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
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($dataPOST)
    {
        try {
            // Bắt đầu giao dịch
            $this->db->beginTransaction();

            // Kiểm tra trùng lặp `ma_don_hang`
            $checkQuery = "SELECT COUNT(*) FROM don_hang WHERE ma_don_hang = :ma_don_hang";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':ma_don_hang', $dataPOST['ma_don_hang']);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                // Nếu trùng lặp, rollback và trả về lỗi
                $this->db->rollBack();
                return json_encode([
                    'status' => 409,
                    'message' => 'Mã đơn hàng đã tồn tại. Vui lòng nhập mã khác.'
                ]);
            }

            // Chèn vào bảng `don_hang_mua`
            $queryDonHang = "INSERT INTO don_hang SET
                ma_don_hang = :ma_don_hang,
                ma_nha_cung_cap = :ma_nha_cung_cap,
                ngay_dat_hang = :ngay_dat_hang,
                ngay_giao_hang = :ngay_giao_hang,
                tong_gia_tri = :tong_gia_tri,
                trang_thai = :trang_thai,
                ma_nguoi_tao = :ma_nguoi_tao";

            $stmtDonHang = $this->db->prepare($queryDonHang);
            $stmtDonHang->bindParam(':ma_don_hang', $dataPOST['ma_don_hang']);
            $stmtDonHang->bindParam(':ma_nha_cung_cap', $dataPOST['ma_nha_cung_cap']);
            $stmtDonHang->bindParam(':ngay_dat_hang', $dataPOST['ngay_dat_hang']);
            $stmtDonHang->bindParam(':ngay_giao_hang', $dataPOST['ngay_giao_hang']);
            $stmtDonHang->bindParam(':tong_gia_tri', $dataPOST['tong_gia_tri']);
            $stmtDonHang->bindParam(':trang_thai', $dataPOST['trang_thai']);
            $stmtDonHang->bindParam(':ma_nguoi_tao', $dataPOST['ma_nguoi_tao']);
            $stmtDonHang->execute();

            // Chèn vào bảng `chi_tiet_don_hang_mua`
            $queryChiTiet = "INSERT INTO chi_tiet_don_hang (ma_don_hang, ma_vat_tu, so_luong, don_gia, thanh_tien)
                             VALUES (:ma_don_hang, :ma_vat_tu, :so_luong, :don_gia, :thanh_tien)";
            $stmtChiTiet = $this->db->prepare($queryChiTiet);

            foreach ($dataPOST['chi_tiet'] as $chiTiet) {
                $thanh_tien = $chiTiet['so_luong'] * $chiTiet['don_gia'];
                $stmtChiTiet->bindParam(':ma_don_hang', $dataPOST['ma_don_hang']);
                $stmtChiTiet->bindParam(':ma_vat_tu', $chiTiet['ma_vat_tu']);
                $stmtChiTiet->bindParam(':so_luong', $chiTiet['so_luong']);
                $stmtChiTiet->bindParam(':don_gia', $chiTiet['don_gia']);
                $stmtChiTiet->bindParam(':thanh_tien', $thanh_tien);
                $stmtChiTiet->execute();
            }

            // Commit giao dịch
            $this->db->commit();

            return json_encode([
                'status' => 201,
                'message' => 'Tạo đơn hàng thành công'
            ]);
        } catch (Exception $e) {
            // Rollback nếu có lỗi
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
            // Câu lệnh SQL để cập nhật đơn hàng
            $query = "
                UPDATE don_hang
                SET 
                    ma_nha_cung_cap = :ma_nha_cung_cap,
                    ngay_dat_hang = :ngay_dat_hang,
                    ngay_giao_hang = :ngay_giao_hang,
                    tong_gia_tri = :tong_gia_tri,
                    trang_thai = :trang_thai,
                    ma_nguoi_tao = :ma_nguoi_tao
                WHERE ma_don_hang = :ma_don_hang";

            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào các tham số
            $stmt->bindParam(':ma_don_hang', $ma_don_hang);
            $stmt->bindParam(':ma_nha_cung_cap', $data['ma_nha_cung_cap']);
            $stmt->bindParam(':ngay_dat_hang', $data['ngay_dat_hang']);
            $stmt->bindParam(':ngay_giao_hang', $data['ngay_giao_hang']);
            $stmt->bindParam(':tong_gia_tri', $data['tong_gia_tri']);
            $stmt->bindParam(':trang_thai', $data['trang_thai']);
            $stmt->bindParam(':ma_nguoi_tao', $data['ma_nguoi_tao']);

            // Thực thi câu lệnh
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
