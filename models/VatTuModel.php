<?php


class VatTuModel {
    
    public function getDanhSachVatTu() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM vat_tu JOIN loai_vat_tu ON loai_vat_tu.ma_loai_vat_tu = vat_tu.ma_loai_vat_tu";
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
        $sql = "INSERT INTO VatTu (ma_vat_tu, ten_vat_tu, mo_ta, don_vi, gia, ma_nha_cung_cap, so_luong_toi_thieu, so_luong_ton, loai_vat_tu) 
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
            $data['loai_vat_tu']
        ]);
    }
}
?>