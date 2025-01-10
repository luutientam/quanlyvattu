<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/LoaiVatTuModel.php";

$db = new db();
$connect = $db->connect();
$loaiVatTu = new LoaiVatTuModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $keyword = '';
        $read = $loaiVatTu->getDanhSachLoaiVatTu($keyword);
        $num = $read->rowCount();

        if ($num > 0) {
            $loaiVatTu_array = [];
            $loaiVatTu_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $loaiVatTu_item = array(
                    'ma_loai_vat_tu' => $ma_loai_vat_tu,
                    'ten_loai_vat_tu' => $ten_loai_vat_tu,
                    'mo_ta' => $mo_ta,
                    'ngay_tao' => $ngay_tao,
                );
                array_push($loaiVatTu_array['data'], $loaiVatTu_item);
            }
            echo json_encode($loaiVatTu_array);
        }
        break;

    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }

        $themLoaiVT = $loaiVatTu->create($inputData);
        $response = json_decode($themLoaiVT, true);
        echo json_encode($response);
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!empty($inputData)) {
            if (isset($inputData['ma_loai_vat_tu'])) {
                $ma_loai_vat_tu = $inputData['ma_loai_vat_tu'];
                $result = $loaiVatTu->update($ma_loai_vat_tu, $inputData);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Loại vật tư đã được cập nhật thành công.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Lỗi xảy ra khi cập nhật loại vật tư.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Thiếu thông tin mã loại vật tư để cập nhật.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Dữ liệu không hợp lệ.'
            ]);
        }
        break;

    case 'DELETE':
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (isset($inputData['ma_loai_vat_tu']) && is_numeric($inputData['ma_loai_vat_tu'])) {
            $ma_vat_tu = $inputData['ma_loai_vat_tu'];
            $result = $loaiVatTu->delete($ma_vat_tu);

            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Loại vật tư đã được xóa thành công.'
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Lỗi xảy ra khi xóa loại vật tư. Vui lòng thử lại.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin mã loại vật tư hoặc mã loại vật tư không hợp lệ.'
            ]);
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