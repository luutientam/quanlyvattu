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
    public function addMaterial()
    {
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
        $this->ma_vat_tu = htmlspecialchars(strip_tags($this->ma_vat_tu));
        $this->ten_vat_tu = htmlspecialchars(strip_tags($this->ten_vat_tu));
        $this->mo_ta = htmlspecialchars(strip_tags($this->mo_ta));
        $this->don_vi = htmlspecialchars(strip_tags($this->don_vi));
        $this->gia = htmlspecialchars(strip_tags($this->gia));
        $this->ma_nha_cung_cap = htmlspecialchars(strip_tags($this->ma_nha_cung_cap));
        $this->so_luong_toi_thieu = htmlspecialchars(strip_tags($this->so_luong_toi_thieu));
        $this->so_luong_ton = htmlspecialchars(strip_tags($this->so_luong_ton));
        $this->ma_loai_vat_tu = htmlspecialchars(strip_tags($this->ma_loai_vat_tu));
        $stmt->bindParam(':ma_vat_tu', $this->ma_vat_tu);
        $stmt->bindParam(':ten_vat_tu', $this->ten_vat_tu);
        $stmt->bindParam(':mo_ta', $this->mo_ta);
        $stmt->bindParam(':don_vi', $this->don_vi);
        $stmt->bindParam(':gia', $this->gia);
        $stmt->bindParam(':ma_nha_cung_cap', $this->ma_nha_cung_cap);
        $stmt->bindParam(':so_luong_toi_thieu', $this->so_luong_toi_thieu);
        $stmt->bindParam(':so_luong_ton', $this->so_luong_ton);
        $stmt->bindParam(':ma_loai_vat_tu', $this->ma_loai_vat_tu);
        if ($stmt->execute()) {
            return true;
        }
        printf("Error %s. \n", $stmt->error);
        return false;
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
