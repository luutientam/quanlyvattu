<?php
class GetDuLieu {
    public function getLoaiVatTu() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM loai_vat_tu";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $loaiVatTu = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $loaiVatTu[] = $row;
            }
            mysqli_close($conn);
            return $loaiVatTu;
        } else {
            echo "Lỗi trong truy vấn getLoaiVatTu: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }

    public function getMaNhaCungCap() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM nha_cung_cap";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $maNhaCungCap = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $maNhaCungCap[] = $row;
            }
            mysqli_close($conn);
            return $maNhaCungCap;
        } else {
            echo "Lỗi trong truy vấn getMaNhaCungCap: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }
}
?>