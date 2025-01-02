<?php
// Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung là JSON
header("Access-Control-Allow-Methods: POST"); // Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức POST.

header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

require_once "../models/db.php";
require_once "../models/VatTuModel.php";

$db = new db();
$connect = $db->connect();
$vatTu = new VatTuModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'POST') {
    // Nhận dữ liệu từ yêu cầu JSON (nếu có) hoặc từ form (nếu không có JSON)
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $inputData = $_POST;
    }

    // Gọi phương thức tạo vật tư
    $insertMaterial = $vatTu->create($inputData);
    $response = json_decode($insertMaterial, true);

    // Trả về phản hồi JSON
    header("Location: ../index.php");
    echo json_encode($response);
} else {
    // Xử lý yêu cầu không phải POST
    $data = [
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed',
    ];
    header("HTTP/1.0 405 Method Not Allowed");
    echo json_encode($data);
}

