<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/UserModel.php";

$db = new db();
$connect = $db->connect();
$login = new UserModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }

        $dangNhap = $login->login($inputData['ten_dang_nhap'], $inputData['mat_khau']);
        $response = json_decode($dangNhap, true);
        if ($response['status'] == '200') {
            session_start();
            
            // Kiểm tra vai trò và thông tin người dùng
            if (isset($response['data']['ma_nhan_vien']) && ($response['data']['ten_vai_tro'] == "Quản lý" || $response['data']['ten_vai_tro'] == "Nhân viên")) {
                // Lưu thông tin người dùng vào session
                $_SESSION['maNV'] = $response['data']['ma_nhan_vien'];  // Mã người dùng
                $_SESSION['tenNV'] = $response['data']['ten_nhan_vien']; // Tên người dùng
                $_SESSION['role'] = $response['data']['ten_vai_tro']; // Vai trò
            } else {
                // Lưu thông tin người dùng vào session cho khách hàng
                $_SESSION['maKH'] = $response['data']['ma_khach_hang'];  // Mã khách hàng
                $_SESSION['tenKH'] = $response['data']['ten_khach_hang']; // Tên khách hàng
                $_SESSION['role'] = $response['data']['ten_vai_tro']; // Vai trò
            }
        }
        
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