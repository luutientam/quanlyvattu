<?php
require_once '../models/MaterialModel.php';
require_once '../controllers/vatTuJson.php';
include_once '../models/VatTuModel.php';
include_once '../controllers/MaterialController.php';
include_once '../models/VatTuModel.php';
include_once '../models/db.php';
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: DELETE"); // Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.
header("Allow: GET, POST, OPTIONS, PUT, DELETE"); // Liệt kê tất cả các phương thức HTTP được máy chủ hỗ trợ.
//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$db = new db();
$connect = $db->connect();

class MaterialController
{
    public function xuLyYeuCau()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            switch ($action) {
                case 'addMaterial':
                    $this->addMaterial();
                    break;
                case 'themLoaiVatTu':
                    $this->themLoaiVatTu();
                    break;
                case 'deleteVatTu':
                    $this->deleteVatTu();
                    break;
                case 'deleteLoaiVatTu':
                    $this->deleteLoaiVatTu();
                    break;
                case 'suaVatTu':
                    $this->suaVatTu();
                    break;
                default:
                    echo "Hành động không hợp lệ!";
                    break;
            }
        } else {
            echo "Không có hành động nào được chỉ định!";
        }
    }
    public function addMaterial()
    {
        // Kiểm tra dữ liệu được gửi lên
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ form
            $ten_vat_tu = $_POST['ten_vat_tu'] ?? null;
            $mo_ta = $_POST['mo_ta'] ?? null;
            $don_vi = $_POST['don_vi'] ?? null;
            $gia = $_POST['gia'] ?? null;
            $ma_nha_cung_cap = $_POST['ma_nha_cung_cap'] ?? null;
            $so_luong_toi_thieu = $_POST['so_luong_toi_thieu'] ?? null;
            $so_luong_ton = $_POST['so_luong_ton'] ?? null;
            $loai_vat_tu = $_POST['loai_vat_tu'] ?? null;

            // Kiểm tra xem tất cả các trường dữ liệu có hợp lệ không
            if ($ten_vat_tu && $mo_ta && $don_vi && $gia && $ma_nha_cung_cap && $so_luong_toi_thieu && $so_luong_ton && $loai_vat_tu) {
                // Khởi tạo đối tượng VatTuModel
                // Giả sử $this->db đã được khởi tạo từ trước
                $vatTu = new VatTuModel($connect);
                // Gán giá trị cho đối tượng VatTuModel
                $vatTu->ten_vat_tu = $ten_vat_tu;
                $vatTu->mo_ta = $mo_ta;
                $vatTu->don_vi = $don_vi;
                $vatTu->gia = $gia;
                $vatTu->ma_nha_cung_cap = $ma_nha_cung_cap;
                $vatTu->so_luong_toi_thieu = $so_luong_toi_thieu;
                $vatTu->so_luong_ton = $so_luong_ton;
                $vatTu->ma_loai_vat_tu = $loai_vat_tu;

                // Gọi phương thức addMaterial từ model để lưu dữ liệu vào cơ sở dữ liệu
                if ($vatTu->addMaterial()) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Vật tư đã được thêm thành công!',
                        'data' => [
                            'ten_vat_tu' => $ten_vat_tu,
                            'mo_ta' => $mo_ta,
                            'don_vi' => $don_vi,
                            'gia' => $gia,
                            'ma_nha_cung_cap' => $ma_nha_cung_cap,
                            'so_luong_toi_thieu' => $so_luong_toi_thieu,
                            'so_luong_ton' => $so_luong_ton,
                            'loai_vat_tu' => $loai_vat_tu
                        ]
                    ];
                    echo json_encode($response);
                } else {
                    echo json_encode(["status" => "error", "message" => "Không thể thêm vật tư vào cơ sở dữ liệu."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Dữ liệu không đầy đủ hoặc không hợp lệ."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Phương thức không hợp lệ."]);
        }
    }

    public function themLoaiVatTu()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_loai_vat_tu' => $_POST['ma_loai_vat_tu'],
                'ten_loai_vat_tu' => $_POST['ten_loai_vat_tu'],
                'mo_ta' => $_POST['mo_ta'],
            ];
            $materialModel = new MaterialModel();
            if ($materialModel->themLoaiVatTu($data)) {
                header('Location: index.php?act=loaivattu');
                exit();
            } else {
                echo "Không thể thêm dữ liệu!";
            }
        }
    }
    public function suaVatTu()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_vat_tu' => $_POST['ma_vat_tu_sua'],
                'ten_vat_tu' => $_POST['ten_vat_tu_sua'],
                'mo_ta' => $_POST['mo_ta_sua'],
                'don_vi' => $_POST['don_vi_sua'],
                'gia' => $_POST['gia_sua'],
                'ma_nha_cung_cap' => $_POST['ma_nha_cung_cap_sua'],
                'so_luong_toi_thieu' => $_POST['so_luong_toi_thieu_sua'],
                'so_luong_ton' => $_POST['so_luong_ton_sua'],
                'ma_loai_vat_tu' => $_POST['loai_vat_tu_sua']
            ];
            $materialModel = new MaterialModel();
            if ($materialModel->suaVatTu($data)) {
                header('Location: ../index.php');
                exit();
            } else {
                echo "Không thể thêm dữ liệu!";
            }
        }
    }
    public function deleteVatTu()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $materialModel = new MaterialModel();

            if ($materialModel->deleteVatTu($id)) {
                header('Location: ../index.php?message=success');
            } else {
                header('Location: ../index.php?message=error');
            }
            exit();
        }
    }
    public function deleteLoaiVatTu()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $materialModel = new MaterialModel();

            if ($materialModel->deleteLoaiVatTu($id)) {
                header('Location: index.php?act=loaivattu&message=success');
            } else {
                header('Location: index.php?act=loaivattu&message=error');
            }
            exit();
        }
    }
}
$controller = new MaterialController();
$controller->xuLyYeuCau();
