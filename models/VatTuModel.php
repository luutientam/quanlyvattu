<?php
require_once "Database.php";

class VatTuModel {
    public function getDanhSachVatTu($keyword) {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM vat_tu JOIN loai_vat_tu ON loai_vat_tu.ma_loai_vat_tu = vat_tu.ma_loai_vat_tu where ten_vat_tu like N'%".$keyword."%'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $danhSachVatTu = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $danhSachVatTu[] = $row;
            }
            mysqli_close($conn);
            return $danhSachVatTu;
        } else {
            echo "Lỗi trong truy vấn getDanhSachVatTu: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }
    public function addVatTu($data) {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }
        $sql = "INSERT INTO vat_tu (ma_vat_tu, ten_vat_tu, mo_ta, don_vi, gia, ma_nha_cung_cap, so_luong_toi_thieu, so_luong_ton, ma_loai_vat_tu) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->$conn->prepare($sql);
        return $stmt->execute([
            $data['ma_vat_tu'],
            $data['ten_vat_tu'],
            $data['mo_ta'],
            $data['don_vi'],
            $data['gia'],
            $data['ma_nha_cung_cap'],
            $data['so_luong_toi_thieu'],
            $data['so_luong_ton'],
            $data['ma_loai_vat_tu']
        ]);
    }
    // public function xoaVatTu($maVatTu) {
    //     $sql = "DELETE FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':ma_vat_tu', $maVatTu);
    //     return $stmt->execute();
    // }
}
?>