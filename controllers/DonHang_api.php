<!-- <?php
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: GET");// Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.
header('Access-Control-Allow-Methods: PUT'); // Chỉ cho phép phương thức PUT
header("Access-Control-Allow-Methods: POST"); // Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức POST.

header("Allow: GET, POST, OPTIONS, PUT, DELETE");// Liệt kê tất cả các phương thức HTTP được máy chủ hỗ trợ.
//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

require_once "../models/db.php";
include_once '../models/DonHangModel.php';

$db = new db();
$connect = $db->connect();  

$donHangModel = new DonHangModel($connect);

$keyword = '';

$requestMethod = $_SERVER['REQUEST_METHOD'];

//đọc
switch ($requestMethod) {
    case 'GET':
        $keyword = '';
        $read = $donHangModel->getDonHang($keyword);
        $num = $read->rowCount();
        if ($num > 0) {
            $donhang_array = [];
            $donhang_array['data'] = [];
            while ($row = $read->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $donhang_item = array(
                    'ma_don_hang' => $ma_don_hang,
                    'ten_khach_hang' => $ten_khach_hang,
                    'trang_thai' => $trang_thai,
                    'ngay_dat_hang' => $ngay_dat_hang,
                    'ma_nhan_vien' => $ma_nhan_vien
                    
                );
                array_push($donhang_array['data'], $donhang_item);
            }
            echo json_encode($donhang_array);
        }
        break;

    case 'POST':
        $inputData = json_decode(file_get_contents("php://input"), true);
        if (empty($inputData)) {
            $inputData = $_POST;
        }

        $insertMaterial = $donHang->create($inputData);
        $response = json_decode($insertMaterial, true);
        echo json_encode($response);
        break;

    case 'PUT':
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (!empty($inputData)) {
            if (isset($inputData['ma_don_hang'])) {
                $ma_don_hang = $inputData['ma_don_hang'];
                $result = $donhang->update($ma_don_hang, $inputData);

                if ($result) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'Đơn hàng đã được cập nhật thành công.'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 500,
                        'message' => 'Lỗi xảy ra khi cập nhật đơn hàng.'
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

    default:
        http_response_code(405);
        echo json_encode([
            'status' => 405,
            'message' => $requestMethod . ' Method Not Allowed'
        ]);
        break;
}


// //read
// $read = $donHangModel->getDonHang();
// $num = $read->rowCount();// đếm kết quả trả về

// if($num>0){
//     $donHang_array = [];
//     $donHang_array['data'] = [];
//     while($row = $read->fetch(PDO::FETCH_ASSOC)){

//         extract($row);
//         $donHang_item = array(
//             'ma_don_hang' => $ma_don_hang,
//             'ten_khach_hang' => $ten_khach_hang,
//             'trang_thai' => $trang_thai,
//             'ngay_dat_hang' => $ngay_dat_hang,
//             'ma_nguoi_tao' => $ma_nguoi_tao
//         );
//         array_push( $donHang_array['data'], $donHang_item);
//     }
//     echo json_encode($donHang_array);
// }

// //create  

// $requestMethod = $_SERVER['REQUEST_METHOD'];

// if ($requestMethod == 'POST') {
//     // Nhận dữ liệu JSON từ yêu cầu
//     $inputData = json_decode(file_get_contents("php://input"), true);

//     if (!empty($inputData)) {
//         // Tách dữ liệu đơn hàng và chi tiết đơn hàng
//         $donHangData = $inputData['don_hang'];
//         $chiTietDonHangData = $inputData['chi_tiet_don_hang'];

//         // Bắt đầu giao dịch
//         $connect->beginTransaction();

//         try {
//             // Lưu thông tin vào bảng don_hang_mua
//             $resultDonHang = $donHangModel->create($donHangData);

//             if ($resultDonHang) {
//                 // Lấy mã đơn hàng vừa tạo (hoặc từ dữ liệu đầu vào)
//                 $maDonHang = $donHangData['ma_don_hang'];

//                 // Lưu thông tin vào bảng chi_tiet_don_hang_mua
//                 foreach ($chiTietDonHangData as $chiTiet) {
//                     $chiTiet['ma_don_hang'] = $maDonHang;
//                     $resultChiTiet = $donHangModel->create($chiTiet);

//                     if (!$resultChiTiet) {
//                         throw new Exception('Lỗi khi lưu chi tiết đơn hàng.');
//                     }
//                 }

//                 // Cam kết giao dịch
//                 $connect->commit();

//                 echo json_encode([
//                     'status' => 200,
//                     'message' => 'Đơn hàng và chi tiết đơn hàng đã được tạo thành công.'
//                 ]);
//             } else {
//                 throw new Exception('Lỗi khi lưu đơn hàng.');
//             }
//         } catch (Exception $e) {
//             // Rollback nếu có lỗi
//             $connect->rollBack();

//             echo json_encode([
//                 'status' => 500,
//                 'message' => $e->getMessage()
//             ]);
//         }
//     } else {
//         echo json_encode([
//             'status' => 400,
//             'message' => 'Dữ liệu không hợp lệ.'
//         ]);
//     }
// } else {
//     echo json_encode([
//         'status' => 405,
//         'message' => $requestMethod . ' Method Not Allowed'
//     ]);
// }

// //
// if ($requestMethod == 'GET' && isset($_GET['action']) && $_GET['action'] === 'getMaterials') {
//     try {
//         $materials = $materialModel->getAllMaterials();
//         echo json_encode([
//             'status' => 200,
//             'data' => $materials
//         ]);
//     } catch (Exception $e) {
//         echo json_encode([
//             'status' => 500,
//             'message' => $e->getMessage()
//         ]);
//     }
// }


// if ($requestMethod == 'POST') {
//     $inputData = json_decode(file_get_contents("php://input"), true);

//     if (!empty($inputData)) {
//         $donHangData = $inputData['don_hang'];
//         $chiTietDonHangData = $inputData['chi_tiet_don_hang'];

//         $connect->beginTransaction();
//         try {
//             $resultDonHang = $donHangModel->create($donHangData);

//             if ($resultDonHang) {
//                 foreach ($chiTietDonHangData as $chiTiet) {
//                     $resultChiTiet = $donHangModel->create($chiTiet);
//                     if (!$resultChiTiet) throw new Exception('Lỗi khi lưu chi tiết đơn hàng.');
//                 }
//                 $connect->commit();
//                 echo json_encode(['status' => 200, 'message' => 'Đơn hàng đã được tạo thành công.']);
//             } else {
//                 throw new Exception('Lỗi khi lưu đơn hàng.');
//             }
//         } catch (Exception $e) {
//             $connect->rollBack();
//             echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
//         }
//     } else {
//         echo json_encode(['status' => 400, 'message' => 'Dữ liệu không hợp lệ.']);
//     }
// }



// //update

// // Kiểm tra phương thức yêu cầu
// $requestMethod = $_SERVER['REQUEST_METHOD'];
// if ($requestMethod == 'PUT') {
//     // Lấy dữ liệu từ yêu cầu
//     $inputData = json_decode(file_get_contents("php://input"), true);

//     // Kiểm tra nếu dữ liệu có
//     if (!empty($inputData)) {
//         if (isset($inputData['ma_don_hang'])) {
//             $ma_don_hang = $inputData['ma_don_hang'];

//             // Gọi phương thức update để cập nhật dữ liệu
//             $result = $donHangModel->update($ma_don_hang, $inputData);

//             // Trả về phản hồi
//             if ($result) {
//                 echo json_encode([
//                     'status' => 200,
//                     'message' => 'Đơn hàng đã được cập nhật thành công.'
//                 ]);
//             } else {
//                 echo json_encode([
//                     'status' => 500,
//                     'message' => 'Lỗi xảy ra khi cập nhật đơn hàng.'
//                 ]);
//             }
//         } else {
//             echo json_encode([
//                 'status' => 400,
//                 'message' => 'Thiếu thông tin mã đơn hàng để cập nhật.'
//             ]);
//         }
//     } else {
//         echo json_encode([
//             'status' => 400,
//             'message' => 'Dữ liệu không hợp lệ.'
//         ]);
//     }
// } else {
//     // Phương thức không được hỗ trợ
//     http_response_code(405); // Method Not Allowed
//     echo json_encode([
//         'status' => 405,
//         'message' => $requestMethod . ' Method Not Allowed'
//     ]);
// }
// ?>
 

