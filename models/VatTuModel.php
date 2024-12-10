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
}
?>