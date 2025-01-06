<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require_once "../models/db.php";
require_once "../models/NhaCungCapModel.php";

$db = new db();
$connect = $db->connect();
$nhaCungCap = new NhaCungCapModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true) ?: $_POST;
        $insertMaterial = $nhaCungCap->create($inputData);
        echo json_encode(json_decode($insertMaterial, true));
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (!empty($inputData) && isset($inputData['ma_nha_cung_cap'])) {
            $ma_nha_cung_cap = $inputData['ma_nha_cung_cap'];
            $result = $nhaCungCap->update($ma_nha_cung_cap, $inputData);
            echo json_encode([
                'status' => $result ? 200 : 500,
                'message' => $result ? 'Nhà cung cấp đã được cập nhật thành công.' : 'Lỗi xảy ra khi cập nhật nhà cung cấp.'
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Dữ liệu không hợp lệ hoặc thiếu mã nhà cung cấp.'
            ]);
        }
        break;

    case 'DELETE':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (isset($inputData['ma_nha_cung_cap']) && is_numeric($inputData['ma_nha_cung_cap'])) {
            $ma_nha_cung_cap = $inputData['ma_nha_cung_cap'];
            $result = $nhaCungCap->delete($ma_nha_cung_cap);
            echo json_encode([
                'status' => $result ? 200 : 500,
                'message' => $result ? 'Nhà cung cấp đã được xóa thành công.' : 'Lỗi xảy ra khi xóa nhà cung cấp.'
            ]);
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin mã nhà cung cấp hoặc mã nhà cung cấp không hợp lệ.'
            ]);
        }
        break;

    default:
        $keyword = '';
        $read = $nhaCungCap->getDanhSachNhaCungCap($keyword);
        $num = $read->rowCount();

        if ($num > 0) {
            $NhaCungCap_array = [];
            $NhaCungCap_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $NhaCungCap_item = [
                    'ma_nha_cung_cap' => $ma_nha_cung_cap,
                    'ten_nha_cung_cap' => $ten_nha_cung_cap,
                    'so_dien_thoai' => $so_dien_thoai,
                    'email' => $email,
                    'dia_chi' => $dia_chi,
                    'ngay_tao' => $ngay_tao,
                ];
                array_push($NhaCungCap_array['data'], $NhaCungCap_item);
            }
            echo json_encode($NhaCungCap_array);
        } else {
            echo json_encode([
                'status' => 404,
                'message' => 'Không tìm thấy dữ liệu nhà cung cấp.'
            ]);
        }
        break;
}
