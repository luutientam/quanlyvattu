<?php
require_once "db.php";

class DangKyModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function create($dataPOST)
    {
        try {
            $this->db->beginTransaction();

            $checkQuery = "SELECT COUNT(*) FROM tai_khoan WHERE ten_dang_nhap = :ten_dang_nhap";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':ten_dang_nhap', $dataPOST['ten_dang_nhap']);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $this->db->rollBack();
                return json_encode([
                    'status' => 409,
                    'message' => 'Tên đăng nhập đã tồn tại. Vui lòng nhập tên đăng nhập khác.'
                ]);
            }
            $maTaiKhoan = "MTK-".$dataPOST['ten_dang_nhap'];

            $queryTaiKhoan = "INSERT INTO tai_khoan SET
                ma_tai_khoan = :ma_tai_khoan,
                ten_dang_nhap = :ten_dang_nhap,
                mat_khau = :mat_khau,
                loai_tai_khoan = :loai_tai_khoan";

            $stmtTK = $this->db->prepare($queryTaiKhoan);
            $stmtTK->bindParam(':ma_tai_khoan', $maTaiKhoan);
            $stmtTK->bindParam(':ten_dang_nhap', $dataPOST['ten_dang_nhap']);
            $stmtTK->bindParam(':mat_khau', $dataPOST['mat_khau']);
            $stmtTK->bindParam(':loai_tai_khoan', $dataPOST['loai_tai_khoan']);

            $stmtTK->execute();
            
            $queryKhachHang = "INSERT INTO khach_hang SET
                ten_khach_hang = :ten_khach_hang,
                so_dien_thoai = :so_dien_thoai,
                email = :email,
                dia_chi = :dia_chi,
                ma_tai_khoan = :ma_tai_khoan";

            $stmt = $this->db->prepare($queryKhachHang);
            $stmt->bindParam(':ten_khach_hang', $dataPOST['ten_khach_hang']);
            $stmt->bindParam(':so_dien_thoai', $dataPOST['so_dien_thoai']);
            $stmt->bindParam(':email', $dataPOST['email']);
            $stmt->bindParam(':dia_chi', $dataPOST['dia_chi']);
            $stmt->bindParam(':ma_tai_khoan', $maTaiKhoan);

            $stmt->execute();
            $this->db->commit();
            return json_encode([
                'status' => 201,
                'message' => 'Tạo tài khoản thành công'
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode([
                'status' => 500,
                'message' => 'Lỗi khi tạo tài khoản: ' . $e->getMessage()
            ]);
        }
    }
}
?>