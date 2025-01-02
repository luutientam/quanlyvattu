<?php
require_once "db.php";

class VatTuModel
{
    private $db;
    public $ma_vat_tu;
    public $ten_vat_tu;
    public $mo_ta;
    public $don_vi;
    public $gia;
    public $ma_nha_cung_cap;
    public $so_luong_toi_thieu;
    public $so_luong_ton;
    public $ngay_tao;
    public $ma_loai_vat_tu;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getDanhSachVatTu($keyword)
    {
        $query = "SELECT * FROM vat_tu JOIN loai_vat_tu ON loai_vat_tu.ma_loai_vat_tu = vat_tu.ma_loai_vat_tu where ten_vat_tu like N'%" . $keyword . "%'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function create($dataPOST)
{
    // Kiểm tra trùng lặp `ma_vat_tu`
    $checkQuery = "SELECT COUNT(*) FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";
    $checkStmt = $this->db->prepare($checkQuery);
    $checkStmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
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
        ma_vat_tu = :ma_vat_tu, 
        ten_vat_tu = :ten_vat_tu, 
        mo_ta = :mo_ta, 
        don_vi = :don_vi, 
        gia = :gia, 
        ma_nha_cung_cap = :ma_nha_cung_cap, 
        so_luong_toi_thieu = :so_luong_toi_thieu, 
        so_luong_ton = :so_luong_ton, 
        ma_loai_vat_tu = :ma_loai_vat_tu";

    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
    $stmt->bindParam(':ten_vat_tu', $dataPOST['ten_vat_tu']);
    $stmt->bindParam(':mo_ta', $dataPOST['mo_ta']);
    $stmt->bindParam(':don_vi', $dataPOST['don_vi']);
    $stmt->bindParam(':gia', $dataPOST['gia']);
    $stmt->bindParam(':ma_nha_cung_cap', $dataPOST['ma_nha_cung_cap']);
    $stmt->bindParam(':so_luong_toi_thieu', $dataPOST['so_luong_toi_thieu']);
    $stmt->bindParam(':so_luong_ton', $dataPOST['so_luong_ton']);
    $stmt->bindParam(':ma_loai_vat_tu', $dataPOST['ma_loai_vat_tu']);

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



    public function delete($ma_vat_tu)
    {
        try {
            // Câu lệnh SQL để xóa vật tư
            $query = "DELETE FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";

            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào tham số
            $stmt->bindParam(':ma_vat_tu', $ma_vat_tu);

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

    public function update($ma_vat_tu, $data)
    {
        try {
            // Câu lệnh SQL để cập nhật vật tư
            $query = "
                UPDATE vat_tu
                SET 
                    ten_vat_tu = :ten_vat_tu,
                    mo_ta = :mo_ta,
                    don_vi = :don_vi,
                    gia = :gia,
                    ma_nha_cung_cap = :ma_nha_cung_cap,
                    so_luong_toi_thieu = :so_luong_toi_thieu,
                    so_luong_ton = :so_luong_ton,
                    ma_loai_vat_tu = :ma_loai_vat_tu
                WHERE ma_vat_tu = :ma_vat_tu
            ";

            // Chuẩn bị câu lệnh
            $stmt = $this->db->prepare($query);

            // Gắn giá trị vào các tham số
            $stmt->bindParam(':ma_vat_tu', $ma_vat_tu);
            $stmt->bindParam(':ten_vat_tu', $data['ten_vat_tu_sua']);
            $stmt->bindParam(':mo_ta', $data['mo_ta_sua']);
            $stmt->bindParam(':don_vi', $data['don_vi_sua']);
            $stmt->bindParam(':gia', $data['gia_sua']);
            $stmt->bindParam(':ma_nha_cung_cap', $data['ma_nha_cung_cap_sua']);
            $stmt->bindParam(':so_luong_toi_thieu', $data['so_luong_toi_thieu_sua']);
            $stmt->bindParam(':so_luong_ton', $data['so_luong_ton_sua']);
            $stmt->bindParam(':ma_loai_vat_tu', $data['loai_vat_tu_sua']);

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