<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/DangKyModel.php";

$db = new db();
$connect = $db->connect();
$dangKy = new DangKyModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }
        
        $insertTaiKhoan = $dangKy->create($inputData);
        $response = json_decode($insertTaiKhoan, true);
        echo json_encode($response);
        break;

    default:
        http_response_code(405);
        echo json_encode([
            'status' => 405,
            'message' => $requestMethod . ' Method Not Allowed'
        ]);
        break;
}