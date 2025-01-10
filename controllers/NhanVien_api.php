<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

require_once "../models/db.php";
require_once "../models/NhanVienModel.php";

$db = new db();
$connect = $db->connect();
$nhanVien = new NhanVienModel($connect);

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $read = $nhanVien->getDanhSachNhanVien();
        $num = $read->rowCount();

        if ($num > 0) {
            $nhanVien_array = [];
            $nhanVien_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $nhanVien_item = array(
                    'ma_nhan_vien' => $ma_nhan_vien,
                    'ten_nhan_vien' => $ten_nhan_vien,
                    'email' => $email,
                    'ma_tai_khoan' => $ma_tai_khoan,
                    'ngay_tao' => $ngay_tao,
                    'ten_dang_nhap' => $ten_dang_nhap,
                    'mat_khau' => $mat_khau,
                    'loai_tai_khoan' => $loai_tai_khoan,
                );
                array_push($nhanVien_array['data'], $nhanVien_item);
            }
            echo json_encode($nhanVien_array);
        }
        break;

    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }

        $result = $nhanVien->create($inputData);
        $response = json_decode($result, true);
        echo json_encode($response);
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!empty($inputData)) {
            if (isset($inputData['ma_nhan_vien'])) {
                $ma_nhan_vien = $inputData['ma_nhan_vien'];
                $result = $nhanVien->update($inputData);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Nhân viên đã được cập nhật thành công.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Lỗi xảy ra khi cập nhật nhân viên.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Thiếu thông tin mã nhân viên để cập nhật.'
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

        if (isset($inputData['ma_nhan_vien']) && isset($inputData['ma_tai_khoan'])) {
            $dataToDelete = [
                'ma_nhan_vien' => $inputData['ma_nhan_vien'],
                'ma_tai_khoan' => $inputData['ma_tai_khoan']
            ];
            $result = $nhanVien->delete($dataToDelete);

            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Nhân viên đã được xóa thành công.'
                ]);
            } else {
                echo json_encode([
                    'status' => 500,
                    'message' => 'Lỗi xảy ra khi xóa nhân viên. Vui lòng thử lại.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin mã nhân viên hoặc mã tài khoản không hợp lệ.'
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