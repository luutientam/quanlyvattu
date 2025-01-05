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
    public function read(){
        $query = "SELECT ma_gio_hang,gh.ma_vat_tu,gh.ma_khach_hang,ten_vat_tu,gh.so_luong,gia FROM gio_hang gh join vat_tu vt on gh.ma_vat_tu = vt.ma_vat_tu join khach_hang kh on kh.ma_khach_hang = gh.ma_khach_hang";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create($dataPOST){
    // Kiểm tra trùng lặp `ma_vat_tu`
    $checkQuery = "SELECT COUNT(*) FROM gio_hang WHERE ma_gio_hang = :ma_gio_hang";
    $checkStmt = $this->db->prepare($checkQuery);
    $checkStmt->bindParam(':ma_gio_hang', $dataPOST['mma_gio_hanga_vat_tu']);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Trả về lỗi nếu mã vật tư đã tồn tại
        return json_encode([
            'status' => 409,
            'message' => 'Mã vật tư đã tồn tại. Vui lòng nhập mã khác.'
        ]);
    }

    // Thực hiện chèn nếu không trùng lặp
    $query = "INSERT INTO vat_tu SET
        ma_gio_hang = :ma_gio_hang, 
        ma_vat_tu = :ma_vat_tu, 
        ma_khach_hang = :ma_khach_hang, 
        gia = :gia";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
    $stmt->bindParam(':ten_vat_tu', $dataPOST['ten_vat_tu']);
    $stmt->bindParam(':mo_ta', $dataPOST['mo_ta']);
    $stmt->bindParam(':don_vi', $dataPOST['don_vi']);
    $stmt->bindParam(':gia', $dataPOST['gia']);

    if ($stmt->execute()) {
        return json_encode([
            'status' => 201,
            'message' => 'Tạo vật tư thành công'
        ]);
    } else {
        return json_encode([
            'status' => 500,
            'message' => 'Lỗi khi tạo vật tư.'
        ]);
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