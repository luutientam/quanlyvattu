<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *'); // Cho phép truy cập từ mọi nguồn
header('Content-Type: application/json'); // Định nghĩa kiểu dữ liệu trả về là JSON
header('Access-Control-Allow-Methods: PUT'); // Chỉ cho phép phương thức PUT
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

// Gọi các file cần thiết
require_once "../models/db.php";
require_once "../models/VatTuModel.php";

// Kết nối đến cơ sở dữ liệu
$db = new db();
$connect = $db->connect();

// Khởi tạo model
$vatTu = new VatTuModel($connect);

// Kiểm tra phương thức yêu cầu
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'PUT') {
    // Lấy dữ liệu từ yêu cầu
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Kiểm tra nếu dữ liệu có
    if (!empty($inputData)) {
        if (isset($inputData['ma_vat_tu'])) {
            $ma_vat_tu = $inputData['ma_vat_tu'];

            // Gọi phương thức update để cập nhật dữ liệu
            $result = $vatTu->update($ma_vat_tu, $inputData);

            // Trả về phản hồi
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
} else {
    // Phương thức không được hỗ trợ
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ]);
}
?>
