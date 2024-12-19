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
    public function create($dataPOST){
        $query = "INSERT INTO vat_tu SET
            ma_vat_tu=:ma_vat_tu, 
            ten_vat_tu=:ten_vat_tu, 
            mo_ta=:mo_ta, 
            don_vi=:don_vi, 
            gia=:gia, 
            ma_nha_cung_cap=:ma_nha_cung_cap, 
            so_luong_toi_thieu=:so_luong_toi_thieu, 
            so_luong_ton=:so_luong_ton, 
            ma_loai_vat_tu=:ma_loai_vat_tu";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':ma_vat_tu',$dataPOST['ma_vat_tu']);
            $stmt->bindParam(':ten_vat_tu',$dataPOST['ten_vat_tu']);
            $stmt->bindParam(':mo_ta',$dataPOST['mo_ta']);
            $stmt->bindParam(':don_vi',$dataPOST['don_vi']);
            $stmt->bindParam(':gia',$dataPOST['gia']);
            $stmt->bindParam(':ma_nha_cung_cap',$dataPOST['ma_nha_cung_cap']);
            $stmt->bindParam(':so_luong_toi_thieu',$dataPOST['so_luong_toi_thieu']);
            $stmt->bindParam(':so_luong_ton',$dataPOST['so_luong_ton']);
            $stmt->bindParam(':ma_loai_vat_tu', $dataPOST['ma_loai_vat_tu']);
            if($stmt->execute()){
                $data = [
                    'status' => 201,
                    'message' => 'Tạo vật tư thành công',
                ];
                header("HTTP/1.0 201 Created");
                return json_encode($data);
            } else {
                $data = [
                    'status' => 500,
                    'message' => 'Lỗi',
                ];
                header("HTTP/1.0 500 Error");
                return json_encode($data);
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
