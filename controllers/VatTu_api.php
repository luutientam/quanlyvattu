<?php
// Cấu hình các header cho API để hỗ trợ giao tiếp giữa các ứng dụng
header('Access-Control-Allow-Origin: *'); // Cho phép tất cả các nguồn truy cập
header('Content-Type: application/json'); // Định dạng nội dung trả về là JSON
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Cho phép các phương thức HTTP này
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Access-Control-Allow-Origin'); // Cho phép các header cần thiết

// Yêu cầu các file chứa lớp xử lý cơ sở dữ liệu và model Vật tư
require_once "../models/db.php"; // File kết nối cơ sở dữ liệu
require_once "../models/VatTuModel.php"; // Model xử lý logic cho Vật tư

// Khởi tạo đối tượng kết nối cơ sở dữ liệu
$db = new db();
$connect = $db->connect();

// Tạo đối tượng model cho Vật tư, truyền kết nối cơ sở dữ liệu vào
$vatTu = new VatTuModel($connect);

// Lấy phương thức HTTP từ yêu cầu của người dùng
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Xử lý yêu cầu dựa trên phương thức HTTP
switch ($requestMethod) {
    case 'GET': // Xử lý yêu cầu đọc danh sách vật tư
        // Lấy thông tin từ query string (nếu có)
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : ''; // Từ khóa tìm kiếm
        $maLoaiVatTu = isset($_GET['ma_loai_vat_tu']) ? $_GET['ma_loai_vat_tu'] : 'all'; // Mã loại vật tư

        // Gọi hàm trong model để lấy danh sách vật tư
        $read = $vatTu->getDanhSachVatTu($keyword, $maLoaiVatTu);
        $num = $read->rowCount(); // Đếm số bản ghi trả về

        // Nếu có dữ liệu
        if ($num > 0) {
            $vatTu_array = []; // Mảng chứa danh sách vật tư
            $vatTu_array['data'] = [];

            // Lặp qua từng dòng dữ liệu
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row); // Tách dữ liệu từ dòng hiện tại
                // Tạo một mảng cho vật tư
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
                array_push($vatTu_array['data'], $vatTu_item); // Thêm vào mảng kết quả
            }
            echo json_encode($vatTu_array); // Trả về danh sách vật tư dạng JSON
        } else {
            // Không có dữ liệu trả về thông báo
            echo json_encode(['message' => 'Không tìm thấy vật tư']);
        }
        break;

    case 'POST': // Xử lý yêu cầu thêm vật tư mới
        // Lấy dữ liệu từ body của yêu cầu
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST; // Trường hợp gửi dữ liệu dạng form
        }

        // Gọi hàm tạo vật tư trong model
        $insertMaterial = $vatTu->create($inputData);
        $response = json_decode($insertMaterial, true); // Chuyển đổi phản hồi sang mảng
        echo json_encode($response); // Trả về phản hồi dạng JSON
        break;

    case 'PUT': // Xử lý yêu cầu cập nhật thông tin vật tư
        // Lấy dữ liệu từ body của yêu cầu
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!empty($inputData)) {
            if (isset($inputData['ma_vat_tu'])) { // Kiểm tra mã vật tư
                $ma_vat_tu = $inputData['ma_vat_tu'];
                $result = $vatTu->update($ma_vat_tu, $inputData); // Gọi hàm cập nhật

                if ($result) {
                    // Phản hồi thành công
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Vật tư đã được cập nhật thành công.'
                    ]);
                } else {
                    // Phản hồi lỗi
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Lỗi xảy ra khi cập nhật vật tư.'
                    ]);
                }
            } else {
                // Thiếu mã vật tư
                echo json_encode([
                    'status' => 400,
                    'message' => 'Thiếu thông tin mã vật tư để cập nhật.'
                ]);
            }
        } else {
            // Dữ liệu không hợp lệ
            echo json_encode([
                'status' => 400,
                'message' => 'Dữ liệu không hợp lệ.'
            ]);
        }
        break;

    case 'DELETE': // Xử lý yêu cầu xóa vật tư
        // Lấy dữ liệu từ body của yêu cầu
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (isset($inputData['ma_vat_tu']) && is_numeric($inputData['ma_vat_tu'])) {
            $ma_vat_tu = $inputData['ma_vat_tu'];
            $result = $vatTu->delete($ma_vat_tu); // Gọi hàm xóa vật tư

            if ($result) {
                // Xóa thành công
                echo json_encode([
                    'status' => 200,
                    'message' => 'Vật tư đã được xóa thành công.'
                ]);
            } else {
                // Lỗi khi xóa
                echo json_encode([
                    'status' => 500,
                    'message' => 'Lỗi xảy ra khi xóa vật tư. Vui lòng thử lại.'
                ]);
            }
        } else {
            // Thiếu mã vật tư hoặc mã không hợp lệ
            echo json_encode([
                'status' => 400,
                'message' => 'Thiếu thông tin mã vật tư hoặc mã vật tư không hợp lệ.'
            ]);
        }
        break;

    default: // Phản hồi lỗi nếu phương thức không được hỗ trợ
        http_response_code(405); // Mã lỗi 405 - Method Not Allowed
        echo json_encode([
            'status' => 405,
            'message' => $requestMethod . ' Method Not Allowed'
        ]);
        break;
}
