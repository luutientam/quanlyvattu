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
            if (isset($_GET['action']) && $_GET['action'] === 'getMaKhachHang') {
                $maKhachHang = $_GET['ma_khach_hang'];
                $ma = $getDuLieu->getMaKhachHang($maKhachHang);
                if ($ma !== null) {
                    echo json_encode(['status' => 'success', 'ma_khach_hang' => $ma]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy mã khách hàng']);
                }
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
        // Kiểm tra nếu không có mã khách hàng
        if (empty($inputData['ma_khach_hang'])) {
            echo json_encode(['status' => 400, 'message' => 'Mã khách hàng là bắt buộc']);
            exit();
        }
        $response = $donHangModel->create($inputData);
        echo $response;
        break;

    case 'PUT':
        // Lấy dữ liệu từ body yêu cầu
        $inputData = json_decode(file_get_contents("php://input"), true);

        // Kiểm tra dữ liệu có hợp lệ không
        if (empty($inputData) || !isset($inputData['ma_don_hang'])) {
            // Trả về lỗi nếu dữ liệu không hợp lệ
            echo json_encode(['status' => 400, 'message' => 'Dữ liệu không hợp lệ']);
            exit();
        }

        // Lấy mã đơn hàng từ dữ liệu
        $ma_don_hang = $inputData['ma_don_hang'];

        // Dịch trạng thái từ tiếng Anh sang tiếng Việt
        $status_mapping = [
            "pending" => "Chờ xử lý",
            "processing" => "Đang xử lý",
            "completed" => "Hoàn thành",
            "canceled" => "Đã hủy"
        ];

        // Kiểm tra trạng thái có hợp lệ không
        if (isset($inputData['trang_thai'])) {
            $trang_thai = $inputData['trang_thai']; // Trạng thái gửi lên từ frontend (tiếng Anh)
            $trang_thai_viet = isset($status_mapping[$trang_thai]) ? $status_mapping[$trang_thai] : "Không xác định";
            $inputData['trang_thai'] = $trang_thai_viet;  // Cập nhật lại trạng thái đã dịch sang tiếng Việt
        } else {
            $inputData['trang_thai'] = "Không xác định";  // Nếu không có trạng thái, gán mặc định
        }

        // Gọi phương thức update để cập nhật đơn hàng
        $response = $donHangModel->update($inputData);

        // Kiểm tra phản hồi từ phương thức update
        if ($response) {
            // Trả về thông báo thành công
            echo json_encode(['status' => 200, 'message' => 'Cập nhật đơn hàng thành công']);
        } else {
            // Trả về thông báo thất bại nếu không cập nhật được
            echo json_encode(['status' => 500, 'message' => 'Lỗi khi cập nhật đơn hàng']);
        }
        break;
}
