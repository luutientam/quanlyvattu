<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/DonHangModel.php";
require_once "../models/GetDuLieu.php";

$db = new db();
$connect = $db->connect();

if (!$connect) {
    echo json_encode(['message' => 'Kết nối cơ sở dữ liệu thất bại']);
    exit();
}

$donHangModel = new DonHangModel($connect);
$getDuLieu = new GetDuLieu();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['action']) && $_GET['action'] === 'getGiaVatTu') {
            $maVatTu = $_GET['ma_vat_tu'];
            $gia = $getDuLieu->getGiaVatTu($maVatTu);
            if ($gia !== null) {
                echo json_encode(['status' => 'success', 'gia' => $gia]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy giá sản phẩm']);
            }
        } else {
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
        echo $response;
        break;

    case 'DELETE':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData) || !isset($inputData['ma_don_hang'])) {
            echo json_encode(['message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        $ma_don_hang = $inputData['ma_don_hang'];
        $response = $donHangModel->delete($ma_don_hang);
        echo $response;
        break;
}
?>