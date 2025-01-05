<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require_once "../models/db.php";
require_once "../models/cartModel.php";

$db = new db();
$connect = $db->connect();
$cartModel = new cartModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $insertCart = $cartModel->create($inputData);
        echo json_encode(json_decode($insertCart, true));
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (!empty($inputData) && isset($inputData['ma_gio_hang'])) {
            $ma_gio_hang = $inputData['ma_gio_hang'];
            $result = $cartModel->update($ma_gio_hang, $inputData);
            echo json_encode([
                'status' => $result ? 200 : 500,
                'message' => $result ? 'Giỏ hàng đã được cập nhật thành công.' : 'Lỗi xảy ra khi cập nhật Giỏ hàng.'
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Dữ liệu không hợp lệ hoặc thiếu mã giỏ hàng.'
            ]);
        }
        break;

    case 'DELETE':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (isset($inputData['ma_gio_hang']) && is_numeric($inputData['ma_gio_hang'])) {
            $ma_gio_hang = $inputData['ma_gio_hang'];
            $result = $cartModel->delete($ma_gio_hang);
            echo json_encode([
                'status' => $result ? 200 : 500,
                'message' => $result ? 'Xóa thành công.' : 'Lỗi xảy ra khi xóa nhà cung cấp.'
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin vật tư hoặc mã vật tư không hợp lệ.'
            ]);
        }
        break;

    default:
        $read = $cartModel->read();
        $num = $read->rowCount();

        if ($num > 0) {
            $gioHang_array = [];
            $gioHang_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $gioHang_item = [
                    'ma_gio_hang' => $ma_gio_hang,
                    'ma_khach_hang' => $ma_khach_hang,
                    'ma_vat_tu' => $ma_vat_tu,
                    'ten_vat_tu' => $ten_vat_tu,
                    'so_luong' => $so_luong,
                    'gia' => $gia*$so_luong,
                ];
                array_push($gioHang_array['data'], $gioHang_item);
            }
            echo json_encode($gioHang_array);
        } else {
            echo json_encode([
                'status' => 404,
                'message' => 'Không tìm thấy dữ liệu nhà cung cấp.'
            ]);
        }
        break;
}