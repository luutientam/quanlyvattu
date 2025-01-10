<?php
require_once "db.php";
class LoaiVatTuModel {
    private $db;
    public $ma_loai_vat_tu;
    public $ten_loai_vat_tu;
    public $mo_ta;
    public $ngay_tao;

    public function __construct($db)
    {
        $this->db = $db;
    }
    public function getDanhSachLoaiVatTu($keyword)
    {
        $query = "SELECT * FROM loai_vat_tu";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create($dataPOST)
    {
        // // Kiểm tra trùng lặp `ma_vat_tu`
        // $checkQuery = "SELECT COUNT(*) FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";
        // $checkStmt = $this->db->prepare($checkQuery);
        // $checkStmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
        // $checkStmt->execute();
        // $count = $checkStmt->fetchColumn();

        // if ($count > 0) {
        //     // Trả về lỗi nếu mã vật tư đã tồn tại
        //     return json_encode([
        //         'status' => 409,
        //         'message' => 'Mã vật tư đã tồn tại. Vui lòng nhập mã khác.'
        //     ]);
        // }

        // Thực hiện chèn nếu không trùng lặp
        $query = "INSERT INTO loai_vat_tu SET
        ten_loai_vat_tu = :ten_loai_vat_tu, 
        mo_ta = :mo_ta";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ten_loai_vat_tu', $dataPOST['ten_loai_vat_tu']);
        $stmt->bindParam(':mo_ta', $dataPOST['mo_ta']);


        if ($stmt->execute()) {
            return json_encode([
                'status' => 201,
                'message' => 'Tạo loại vật tư thành công'
            ]);
        } else {
            return json_encode([
                'status' => 500,
                'message' => 'Lỗi khi tạo loại vật tư.'
            ]);
        }
    }



    public function delete($ma_loai_vat_tu)
    {
        try {
            // Câu lệnh SQL để xóa vật tư
            $query = "DELETE FROM loai_vat_tu WHERE ma_loai_vat_tu = :ma_loai_vat_tu";

            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào tham số
            $stmt->bindParam(':ma_loai_vat_tu', $ma_loai_vat_tu);

            // Thực thi câu lệnh
            $stmt->execute();

            // Kiểm tra số lượng bản ghi bị ảnh hưởng
            if ($stmt->rowCount() > 0) {
                return true; // Xóa thành công
            } else {
                return false; // Không có bản ghi nào bị xóa (mã vật tư không tồn tại)
            }
        } catch (Exception $e) {
            return false; // Bắt lỗi nếu xảy ra
        }
    }

    public function update($ma_loai_vat_tu, $data)
    {
        try {
            // Câu lệnh SQL để cập nhật vật tư
            $query = "UPDATE loai_vat_tu SET ten_loai_vat_tu = :ten_loai_vat_tu , mo_ta = :mo_ta WHERE ma_loai_vat_tu = :ma_loai_vat_tu";
            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào các tham số
            $stmt->bindParam(':ma_loai_vat_tu', $ma_loai_vat_tu);
            $stmt->bindParam(':ten_loai_vat_tu', $data['ten_loai_vat_tu']);
            $stmt->bindParam(':mo_ta', $data['mo_ta']);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                return true; // Cập nhật thành công
            }
            return false; // Cập nhật thất bại
        } catch (Exception $e) {
            return false; // Bắt lỗi nếu xảy ra
        }
    }
}
?>