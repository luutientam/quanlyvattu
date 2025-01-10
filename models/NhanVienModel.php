<?php
require_once "db.php";

class NhanVienModel {
    private $db;
    public $ma_nhan_vien;
    public $ten_nhan_vien;
    public $email;
    public $ma_tai_khoan;
    public $ngay_tao;
    public $ten_dang_nhap;
    public $mat_khau;
    public $loai_tai_khoan;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getDanhSachNhanVien($keyword = '', $maLoaiVatTu = 'all') {
        $query = "SELECT * FROM nhan_vien 
                  JOIN tai_khoan tk ON nhan_vien.ma_tai_khoan = tk.ma_tai_khoan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create($dataPOST) {
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

            $maTaiKhoan = "MTK-" . $dataPOST['ten_dang_nhap'];
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

            $queryNhanVien = "INSERT INTO nhan_vien SET
                              ten_nhan_vien = :ten_nhan_vien,
                              email = :email,
                              ma_tai_khoan = :ma_tai_khoan";

            $stmt = $this->db->prepare($queryNhanVien);
            $stmt->bindParam(':ten_nhan_vien', $dataPOST['ten_nhan_vien']);
            $stmt->bindParam(':email', $dataPOST['email']);
            $stmt->bindParam(':ma_tai_khoan', $maTaiKhoan);
            $stmt->execute();

            $this->db->commit();
            return json_encode([
                'status' => 201,
                'message' => 'Tạo nhân viên thành công'
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode([
                'status' => 500,
                'message' => 'Lỗi khi tạo nhân viên: ' . $e->getMessage()
            ]);
        }
    }

    public function update($dataPOST) {
        try {
            $this->db->beginTransaction();

            $checkQuery = "SELECT COUNT(*) FROM tai_khoan WHERE ten_dang_nhap = :ten_dang_nhap AND ma_tai_khoan != :ma_tai_khoan";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':ten_dang_nhap', $dataPOST['ten_dang_nhap']);
            $checkStmt->bindParam(':ma_tai_khoan', $dataPOST['ma_tai_khoan']);
            $checkStmt->execute();
            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                $this->db->rollBack();
                return json_encode([
                    'status' => 409,
                    'message' => 'Tên đăng nhập đã tồn tại. Vui lòng nhập tên đăng nhập khác.'
                ]);
            }

            $queryTaiKhoan = "UPDATE tai_khoan SET
                              ten_dang_nhap = :ten_dang_nhap,
                              mat_khau = :mat_khau,
                              loai_tai_khoan = :loai_tai_khoan
                              WHERE ma_tai_khoan = :ma_tai_khoan";

            $stmtTK = $this->db->prepare($queryTaiKhoan);
            $stmtTK->bindParam(':ten_dang_nhap', $dataPOST['ten_dang_nhap']);
            $stmtTK->bindParam(':mat_khau', $dataPOST['mat_khau']);
            $stmtTK->bindParam(':loai_tai_khoan', $dataPOST['loai_tai_khoan']);
            $stmtTK->bindParam(':ma_tai_khoan', $dataPOST['ma_tai_khoan']);
            $stmtTK->execute();

            $queryNhanVien = "UPDATE nhan_vien SET
                              ten_nhan_vien = :ten_nhan_vien,
                              email = :email
                              WHERE ma_nhan_vien = :ma_nhan_vien";

            $stmt = $this->db->prepare($queryNhanVien);
            $stmt->bindParam(':ten_nhan_vien', $dataPOST['ten_nhan_vien']);
            $stmt->bindParam(':email', $dataPOST['email']);
            $stmt->bindParam(':ma_nhan_vien', $dataPOST['ma_nhan_vien']);
            $stmt->execute();

            $this->db->commit();
            return json_encode([
                'status' => 200,
                'message' => 'Cập nhật nhân viên thành công'
            ]);
        } catch (Exception $e) {
            $this->db->rollBack();
            return json_encode([
                'status' => 500,
                'message' => 'Lỗi khi cập nhật nhân viên: ' . $e->getMessage()
            ]);
        }
    }

    public function delete($data) {
        try {
            $query = "DELETE FROM nhan_vien WHERE ma_nhan_vien = :ma_nhan_vien";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_nhan_vien', $data['ma_nhan_vien']);
            $stmt->execute();

            $queryTK = "DELETE FROM tai_khoan WHERE ma_tai_khoan = :ma_tai_khoan";
            $stmtTK = $this->db->prepare($queryTK);
            $stmtTK->bindParam(':ma_tai_khoan', $data['ma_tai_khoan']);
            $stmtTK->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
?>