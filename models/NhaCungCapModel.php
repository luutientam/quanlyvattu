<?php
require_once "db.php";

class NhaCungCapModel
{
    private $db;
    public $ma_nha_cung_cap;
    public $ten_nha_cung_cap;
    public $so_dien_thoai;
    public $email;
    public $dia_chi;
    public $ngay_tao;
    

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getDanhSachNhaCungCap($keyword)
    {
        $query = "SELECT * FROM nha_cung_cap";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create($dataPOST)
{

    // Kiểm tra trùng lặp `ma_nha_cung_cap`
    $checkQuery = "SELECT COUNT(*) FROM nha_cung_cap WHERE ma_nha_cung_cap = :ma_nha_cung_cap";
    $checkStmt = $this->db->prepare($checkQuery);
    $checkStmt->bindParam(':ma_nha_cung_cap', $dataPOST['ma_nha_cung_cap']);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count > 0) {
        // Trả về lỗi nếu nhà cung cấp đã tồn tại
        return json_encode([
            'status' => 409,
            'message' => 'Mã nhà cung cấp đã tồn tại. Vui lòng nhập mã khác.'
        ]);
    }

    // Thực hiện chèn nếu không trùng lặp
    $query = "INSERT INTO nha_cung_cap SET
        ma_nha_cung_cap = :ma_nha_cung_cap, 
        ten_nha_cung_cap = :ten_nha_cung_cap, 
        so_dien_thoai = :so_dien_thoai, 
        email = :email, 
        dia_chi = :dia_chi";

        
        
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':ma_nha_cung_cap', $dataPOST['ma_nha_cung_cap']);
    $stmt->bindParam(':ten_nha_cung_cap', $dataPOST['ten_nha_cung_cap']);
    $stmt->bindParam(':so_dien_thoai', $dataPOST['so_dien_thoai']);
    $stmt->bindParam(':email', $dataPOST['email']);
    $stmt->bindParam(':dia_chi', $dataPOST['dia_chi']);
    

    if ($stmt->execute()) {
        return json_encode([
            'status' => 201,
            'message' => 'Tạo nhà cung cấp thành công'
        ]);
    } else {
        return json_encode([
            'status' => 500,
            'message' => 'Lỗi khi tạo nhà cung cấp.'
        ]);
    }
}



    public function delete($ma_nha_cung_cap)
    {
        try {
            // Câu lệnh SQL để xóa vật tư
            $query = "DELETE FROM nha_cung_cap WHERE ma_nha_cung_cap = :ma_nha_cung_cap";

            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào tham số
            $stmt->bindParam(':ma_nha_cung_cap', $ma_nha_cung_cap);

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

    public function update($ma_nha_cung_cap, $data)
    {
        try {
            // Câu lệnh SQL để cập nhật vật tư
            $query = "
            UPDATE nha_cung_cap
            SET 
            ten_nha_cung_cap = :ten_nha_cung_cap,
            so_dien_thoai = :so_dien_thoai,
            email = :email,
            dia_chi = :dia_chi
            WHERE ma_nha_cung_cap = :ma_nha_cung_cap
        ";


            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào các tham số
            $stmt->bindParam(':ma_nha_cung_cap', $ma_nha_cung_cap);
            $stmt->bindParam(':ten_nha_cung_cap', $data['ten_nha_cung_cap_sua']);
            $stmt->bindParam(':so_dien_thoai', $data['so_dien_thoai_sua']);
            $stmt->bindParam(':email', $data['email_sua']);
            $stmt->bindParam(':dia_chi', $data['dia_chi_sua']);
            

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                return true; // Cập nhật thành công
            }
            return false; // Cập nhật thất bại
        } catch (Exception $e) {
            return false; // Bắt lỗi nếu xảy ra
        }
    }


    // public function getDanhSachVatTu($keyword) {
    //     $sql = "SELECT * FROM vat_tu JOIN loai_vat_tu ON loai_vat_tu.ma_loai_vat_tu = vat_tu.ma_loai_vat_tu where ten_vat_tu like N'%".$keyword."%'";
    //     $result = mysqli_query($conn, $sql);

    //     if ($result) {
    //         $danhSachVatTu = [];
    //         while ($row = mysqli_fetch_assoc($result)) {
    //             $danhSachVatTu[] = $row;
    //         }
    //         mysqli_close($conn);
    //         return $danhSachVatTu;
    //     } else {
    //         echo "Lỗi trong truy vấn getDanhSachVatTu: " . mysqli_error($conn);
    //         mysqli_close($conn);
    //         return [];
    //     }
    // }
}