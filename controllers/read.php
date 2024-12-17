<?php
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: DELETE");// Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.
header("Allow: GET, POST, OPTIONS, PUT, DELETE");// Liệt kê tất cả các phương thức HTTP được máy chủ hỗ trợ.
//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

include_once '../models/VatTuModel.php';

$db = new db();
$connect = $db->connect();  
$vatTuModel = new VatTuModel($connect);
$keyword = '';
$read = $vatTuModel->getDanhSachVatTu($keyword);

$num = $read->rowCount();// đếm kết quả trả về 
if($num>0){
    $vatTu_array = [];
    $vatTu_array['data'] = [];
    while($row = $read->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $vatTu_item = array(
            'ma_vat_tu' => $ma_vat_tu,
            'ten_vat_tu' => $ten_vat_tu,
            'mo_ta' => $mo_ta,
            'don_vi' => $don_vi,
            'gia' => $gia,
            'ma_nha_cung_cap' => $ma_nha_cung_cap,
            'so_luong_toi_thieu' => $so_luong_toi_thieu,
            'so_luong_ton' => $so_luong_ton,
            'ngay_tao' => $ngay_tao,
            'ma_loai_vat_tu' => $ma_loai_vat_tu
        );
        array_push( $vatTu_array['data'], $vatTu_item);
    }
    echo json_encode($vatTu_array);
}
?>