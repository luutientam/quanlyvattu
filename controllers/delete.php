<?php
// Cấu hình các header cho API
header('Access-Control-Allow-Origin: *'); // Cho phép truy cập từ mọi nguồn
header('Content-Type: application/json'); // Định nghĩa kiểu dữ liệu trả về là JSON
header('Access-Control-Allow-Methods: DELETE'); // Chỉ cho phép phương thức DELETE
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin');

// Gọi các file cần thiết
require_once "../models/db.php";
require_once "../models/VatTuModel.php";

// Kết nối đến cơ sở dữ liệu
$db = new db();
$connect = $db->connect();

// Kiểm tra kết nối cơ sở dữ liệu
if (!$connect) {
    echo json_encode([
        'status' => 500,
        'message' => 'Không thể kết nối đến cơ sở dữ liệu.'
    ]);
    exit;
}

// Khởi tạo model
$vatTu = new VatTuModel($connect);

// Kiểm tra phương thức yêu cầu
$requestMethod = $_SERVER['REQUEST_METHOD'];
if ($requestMethod == 'DELETE') {
    // Lấy dữ liệu từ yêu cầu (JSON hoặc query string)
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Kiểm tra mã vật tư
    if (isset($inputData['ma_vat_tu']) && is_numeric($inputData['ma_vat_tu'])) {
        $ma_vat_tu = $inputData['ma_vat_tu'];

        // Gọi phương thức xóa vật tư
        $result = $vatTu->delete($ma_vat_tu);

        // Trả về phản hồi
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
} else {
    // Phương thức không được hỗ trợ
    http_response_code(405); // Method Not Allowed
    echo json_encode([
        'status' => 405,
        'message' => $requestMethod . ' Method Not Allowed'
    ]);
}
?>
