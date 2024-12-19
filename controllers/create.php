<?php
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: POST");// Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.

//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

require_once "../models/db.php";
require_once "../models/VatTuModel.php";

$db = new db();
$connect = $db->connect();

$vatTu = new VatTuModel($connect);
// $data = json_decode(file_get_contents("php://input"));
// // $url = "http://localhost/quanlyvattu/controllers/vatTuJson.php";
// // $jsonData = file_get_contents($url);

// // // Giải mã JSON thành đối tượng PHP
// // $data = json_decode($jsonData);

// $vatTu->ma_vat_tu = $data->ma_vat_tu;
// $vatTu->ten_vat_tu = $data->ten_vat_tu;
// $vatTu->mo_ta = $data->mo_ta;
// $vatTu->don_vi = $data->don_vi;
// $vatTu->gia = $data->gia;
// $vatTu->ma_nha_cung_cap = $data->ma_nha_cung_cap;
// $vatTu->so_luong_toi_thieu = $data->so_luong_toi_thieu;
// $vatTu->so_luong_ton = $data->so_luong_ton;
// $vatTu->ma_loai_vat_tu = $data->ma_loai_vat_tu;

// if($vatTu->create()){
//     echo json_encode(array("message","Vật tư đã được tạo"));
// }else{
//     echo json_encode(array("message","Vật tư không được tạo"));
// }
$requestmethod = $_SERVER['REQUEST_METHOD'];
if($requestmethod == 'POST') {
    $inputdata = json_decode(file_get_contents("php://input"), true);

    if(empty($inputdata)) {
        //var_dump($_POST);
        $insertMaterial = $vatTu->create($_POST);
    } else {
        $insertMaterial =  $vatTu->create($inputdata);

    }
    //echo $insertCustomer;
    // Nếu thêm dữ liệu thành công, chuyển hướng về index.php
    $response = json_decode($insertMaterial, true);
    if ($response['status'] == 201) {
        header('Location: ../index.php');
        exit;
    } else {
        echo $insertMaterial; // Hiển thị lỗi nếu không thành công
    }
} else {
    $data = [
        'status' => 405,
        'message' => $requestmethod . ' Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}


?>