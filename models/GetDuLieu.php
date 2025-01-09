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

    public function getDonHang() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM don_hang";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $maDonHang = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $maDonHang[] = $row;
            }
            mysqli_close($conn);
            return $maDonHang;
        } else {
            echo "Lỗi trong truy vấn getDonHang: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }

    public function getChiTietDonHang() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM chi_tiet_don_hang";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $maDonHang = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $maDonHang[] = $row;
            }
            mysqli_close($conn);
            return $maDonHang;
        } else {
            echo "Lỗi trong truy vấn getChiTietDonHang: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }


    

    public function getGiaVatTu($maVatTu) {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");
    
        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return null;
        }
    
        $sql = "SELECT gia FROM vat_tu WHERE ma_vat_tu = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $maVatTu); // Truyền tham số `ma_vat_tu`
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $row = $result->fetch_assoc()) {
            mysqli_close($conn);
            return $row['gia']; // Trả về giá
        } else {
            echo "Không tìm thấy giá cho mã vật tư: " . $maVatTu;
            mysqli_close($conn);
            return null;
        }
    }

    public function getVatTu() {
        $conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

        if (!$conn) {
            echo "Kết nối thất bại: " . mysqli_connect_error();
            return [];
        }

        $sql = "SELECT * FROM vat_tu";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $loaiVatTu = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $VatTu[] = $row;
            }
            mysqli_close($conn);
            return $VatTu;
        } else {
            echo "Lỗi trong truy vấn getVatTu: " . mysqli_error($conn);
            mysqli_close($conn);
            return [];
        }
    }
}
?>