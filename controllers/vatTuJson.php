<?php
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: POST");// Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.
header("Allow: GET, POST, OPTIONS, PUT, DELETE");// Liệt kê tất cả các phương thức HTTP được máy chủ hỗ trợ.
//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vatTu_array = [];
    $vatTu_array['dataToCreateVatTu'] = [];
    $vatTu_item = array(
        'ma_vat_tu' => $_POST['ma_vat_tu'],
        'ten_vat_tu' => $_POST['ten_vat_tu'],
        'mo_ta' => $_POST['mo_ta'],
        'don_vi' => $_POST['don_vi'],
        'gia' => $_POST['gia'],
        'ma_nha_cung_cap' => $_POST['ma_nha_cung_cap'],
        'so_luong_toi_thieu' => $_POST['so_luong_toi_thieu'],
        'so_luong_ton' => $_POST['so_luong_ton'],
        'ma_loai_vat_tu' => $_POST['loai_vat_tu']
    );
    array_push( $vatTu_array['dataToCreateVatTu'], $vatTu_item);
}
echo json_encode($vatTu_array);
?>