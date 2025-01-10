<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/KhachHangModel.php";

$db = new db();
$connect = $db->connect();
$khachHang = new KhachHangModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $keyword = '';
        $read = $khachHang->getDanhSachKhachHang();
        $num = $read->rowCount();

        if ($num > 0) {
            $khachHang_array = [];
            $khachHang_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $khachHang_item = array(
                    'ma_khach_hang' => $ma_khach_hang,
                    'ten_khach_hang' => $ten_khach_hang,

                );
                array_push($khachHang_array['data'], $khachHang_item);
            }
            echo json_encode($khachHang_array);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode([
            'status' => 405,
            'message' => $requestMethod . ' Method Not Allowed'
        ]);
        break;
}
