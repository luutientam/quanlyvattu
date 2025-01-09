<?php
class cartmodel
{
    private $db;

    public $ma_gio_hang;
    public $ma_vat_tu;
    public $ma_khach_hang;
    public $ten_vat_tu;
    public $so_luong;
    public $gia;
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function read($makh){
        $query = "SELECT ma_gio_hang,gh.ma_vat_tu,gh.ma_khach_hang,ten_vat_tu,gh.so_luong,gia FROM gio_hang gh join vat_tu vt on gh.ma_vat_tu = vt.ma_vat_tu join khach_hang kh on kh.ma_khach_hang = gh.ma_khach_hang where gh.ma_khach_hang = :ma_khach_hang";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ma_khach_hang', $makh);
        $stmt->execute();
        return $stmt;
    }
    public function create($dataPOST) {
        // Kiểm tra trùng lặp `ma_vat_tu` cho `ma_khach_hang`
        $checkQuery = "SELECT COUNT(*) FROM gio_hang WHERE ma_vat_tu = :ma_vat_tu AND ma_khach_hang = :ma_khach_hang";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
        $checkStmt->bindParam(':ma_khach_hang', $dataPOST['ma_khach_hang']);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();
    
        if ($count > 0) {
            // Nếu mã vật tư đã tồn tại, cập nhật số lượng
            $updateQuery = "UPDATE gio_hang SET so_luong = so_luong + :so_luong WHERE ma_vat_tu = :ma_vat_tu AND ma_khach_hang = :ma_khach_hang";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
            $updateStmt->bindParam(':ma_khach_hang', $dataPOST['ma_khach_hang']);
            $updateStmt->bindParam(':so_luong', $dataPOST['so_luong']);
    
            if ($updateStmt->execute()) {
                return json_encode([
                    'status' => 201,
                    'message' => 'Cập nhật số lượng vật tư trong giỏ hàng thành công'
                ]);
            } else {
                return json_encode([
                    'status' => 500,
                    'message' => 'Lỗi khi cập nhật vật tư trong giỏ hàng.'
                ]);
            }
        } else {
            // Thực hiện chèn nếu không trùng lặp
            $query = "INSERT INTO gio_hang SET
                ma_vat_tu = :ma_vat_tu, 
                ma_khach_hang = :ma_khach_hang, 
                so_luong = :so_luong";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
            $stmt->bindParam(':ma_khach_hang', $dataPOST['ma_khach_hang']);
            $stmt->bindParam(':so_luong', $dataPOST['so_luong']);
    
            if ($stmt->execute()) {
                return json_encode([
                    'status' => 201,
                    'message' => 'Thêm vật tư vào giỏ hàng thành công'
                ]);
            } else {
                return json_encode([
                    'status' => 500,
                    'message' => 'Lỗi khi thêm vật tư vào giỏ hàng.'
                ]);
            }
        }
    }
    public function delete($ma_gio_hang)
    {
        try {
            $query = "DELETE FROM gio_hang WHERE ma_gio_hang = :ma_gio_hang";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_gio_hang', $ma_gio_hang, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0; // Trả về true nếu có bản ghi bị xóa
        } catch (Exception $e) {
            error_log("Error deleting cart: " . $e->getMessage());
            return false;
        }
    }

    public function update($ma_gio_hang, $data)
    {
        try {
            $query = "UPDATE gio_hang SET so_luong = :so_luong WHERE ma_gio_hang = :ma_gio_hang";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':so_luong', $data['so_luong_sua'], PDO::PARAM_INT);
            $stmt->bindParam(':ma_gio_hang', $ma_gio_hang, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error updating cart: " . $e->getMessage());
            return false;
        }
    }
}
?>