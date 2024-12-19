<?php
// Kết nối tới cơ sở dữ liệu và bao gồm các file cần thiết
include_once '../models/VatTuModel.php';
include_once '../controllers/MaterialController.php';

// Tạo đối tượng Database và kết nối
$db = new db();
$connect = $db->connect();

$vatTu = new VatTuModel($db);

// Kiểm tra dữ liệu được gửi lên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ten_vat_tu = $_POST['ten_vat_tu'] ?? null;
    $mo_ta = $_POST['mo_ta'] ?? null;
    $don_vi = $_POST['don_vi'] ?? null;
    $gia = $_POST['gia'] ?? null;
    $ma_nha_cung_cap = $_POST['ma_nha_cung_cap'] ?? null;
    $so_luong_toi_thieu = $_POST['so_luong_toi_thieu'] ?? null;
    $so_luong_ton = $_POST['so_luong_ton'] ?? null;
    $loai_vat_tu = $_POST['loai_vat_tu'] ?? null;

    // Kiểm tra xem tất cả các trường dữ liệu có hợp lệ không
    if ($ten_vat_tu && $mo_ta && $don_vi && $gia && $ma_nha_cung_cap && $so_luong_toi_thieu && $so_luong_ton && $loai_vat_tu) {
        // Tạo mảng dữ liệu cho vật tư mới
        $vatTu->ten_vat_tu = $ten_vat_tu;
        $vatTu->mo_ta = $mo_ta;
        $vatTu->don_vi = $don_vi;
        $vatTu->gia = $gia;
        $vatTu->ma_nha_cung_cap = $ma_nha_cung_cap;
        $vatTu->so_luong_toi_thieu = $so_luong_toi_thieu;
        $vatTu->so_luong_ton = $so_luong_ton;
        $vatTu->ma_loai_vat_tu = $loai_vat_tu;

        // Gọi phương thức addMaterial từ model để lưu dữ liệu vào cơ sở dữ liệu
        if ($vatTu->addMaterial()) {
            $response = [
                'status' => 'success',
                'message' => 'Vật tư đã được thêm thành công!',
                'data' => [
                    'ten_vat_tu' => $ten_vat_tu,
                    'mo_ta' => $mo_ta,
                    'don_vi' => $don_vi,
                    'gia' => $gia,
                    'ma_nha_cung_cap' => $ma_nha_cung_cap,
                    'so_luong_toi_thieu' => $so_luong_toi_thieu,
                    'so_luong_ton' => $so_luong_ton,
                    'loai_vat_tu' => $loai_vat_tu
                ]
            ];
            echo json_encode($response);
        } else {
            echo json_encode(["status" => "error", "message" => "Không thể thêm vật tư vào cơ sở dữ liệu."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Dữ liệu không đầy đủ hoặc không hợp lệ."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ."]);
}
?>
