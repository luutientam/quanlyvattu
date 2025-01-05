<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/DonHangModel.php";

$db = new db();
$connect = $db->connect();

if (!$connect) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại']);
    exit();
}

$donHangModel = new DonHangModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $read = $donHangModel->getDonHang($keyword);
        $num = count($read);
        if ($num > 0) {
            $donhang_array = [];
            $donhang_array['data'] = [];
            foreach ($read as $row) {
                $donhang_item = array(
                    'ma_don_hang' => $row['ma_don_hang'],
                    'ten_khach_hang' => $row['ten_khach_hang'],
                    'trang_thai' => $row['trang_thai'],
                    'ngay_dat_hang' => $row['ngay_dat_hang'],
                    'ma_nhan_vien' => $row['ma_nhan_vien']
                );
                array_push($donhang_array['data'], $donhang_item);
            }
            echo json_encode($donhang_array);
        } else {
            echo json_encode(['message' => 'Không tìm thấy đơn hàng']);
        }
        break;

    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $response = $donHangModel->create($inputData);
        echo $response;
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData) || !isset($inputData['ma_don_hang'])) {
            echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $ma_don_hang = $inputData['ma_don_hang'];
        $response = $donHangModel->update($ma_don_hang, $inputData);
        if ($response) {
            echo json_encode(['status' => 200, 'message' => 'Cập nhật đơn hàng thành công']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Lỗi khi cập nhật đơn hàng']);
        }
        break;

    // case 'DELETE':
    //     $inputData = json_decode(file_get_contents("php://input"), true);
    //     if (empty($inputData) || !isset($inputData['ma_don_hang'])) {
    //         echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
    //         exit();
    //     }

    //     $ma_don_hang = $inputData['ma_don_hang'];
    //     $response = $donHangModel->delete($ma_don_hang);
    //     if ($response) {
    //         echo json_encode(['status' => 200, 'message' => 'Xóa đơn hàng thành công']);
    //     } else {
    //         echo json_encode(['status' => 500, 'message' => 'Lỗi khi xóa đơn hàng']);
    //     }
    //     break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Phương thức không được phép']);
        break;
}
