<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/VatTuModel.php";

$db = new db();
$connect = $db->connect();
$vatTu = new VatTuModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $read = $vatTu->getDanhSachVatTu($keyword);
        $num = $read->rowCount();

        if ($num > 0) {
            $vatTu_array = [];
            $vatTu_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $vatTu_item = array(
                    'ma_vat_tu' => $ma_vat_tu,
                    'ten_vat_tu' => $ten_vat_tu,
                    'mo_ta' => $mo_ta,
                    'don_vi' => $don_vi,
                    'gia' => $gia,
                    'ma_nha_cung_cap' => $ma_nha_cung_cap,
                    'so_luong' => $so_luong,
                    'ngay_tao' => $ngay_tao,
                    'ten_loai_vat_tu' => $ten_loai_vat_tu
                );
                array_push($vatTu_array['data'], $vatTu_item);
            }
            echo json_encode($vatTu_array);
        } else {
            echo json_encode(['message' => 'Không tìm thấy vật tư']);
        }
        break;

    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }

        $insertMaterial = $vatTu->create($inputData);
        $response = json_decode($insertMaterial, true);
        echo json_encode($response);
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!empty($inputData)) {
            if (isset($inputData['ma_vat_tu'])) {
                $ma_vat_tu = $inputData['ma_vat_tu'];
                $result = $vatTu->update($ma_vat_tu, $inputData);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Vật tư đã được cập nhật thành công.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Lỗi xảy ra khi cập nhật vật tư.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Thiếu thông tin mã vật tư để cập nhật.'
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

        if (isset($inputData['ma_vat_tu']) && is_numeric($inputData['ma_vat_tu'])) {
            $ma_vat_tu = $inputData['ma_vat_tu'];
            $result = $vatTu->delete($ma_vat_tu);

            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Vật tư đã được xóa thành công.'
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Lỗi xảy ra khi xóa vật tư. Vui lòng thử lại.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin mã vật tư hoặc mã vật tư không hợp lệ.'
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
