<?php
require_once '../models/MaterialModel.php';
//Cho phép bất kỳ nguồn nào (origin) cũng có thể gửi yêu cầu đến tài nguyên trên máy chủ.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json'); // Xác định kiểu nội dung của phản hồi là JSON.
header("Access-Control-Allow-Methods: DELETE");// Chỉ định rằng máy chủ cho phép các yêu cầu HTTP với phương thức DELETE.
header("Allow: GET, POST, OPTIONS, PUT, DELETE");// Liệt kê tất cả các phương thức HTTP được máy chủ hỗ trợ.
//Cho phép các tiêu đề HTTP tùy chỉnh mà client có thể gửi trong yêu cầu (request).
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

class MaterialController {
    public function xuLyYeuCau() {
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
    public function addMaterial() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                // 'ma_vat_tu' => $_POST['ma_vat_tu'],
                'ten_vat_tu' => $_POST['ten_vat_tu'],
                'mo_ta' => $_POST['mo_ta'],
                'don_vi' => $_POST['don_vi'],
                'gia' => $_POST['gia'],
                'ma_nha_cung_cap' => $_POST['ma_nha_cung_cap'],
                'so_luong_toi_thieu' => $_POST['so_luong_toi_thieu'],
                'so_luong_ton' => $_POST['so_luong_ton'],
                'ma_loai_vat_tu' => $_POST['loai_vat_tu']
            ];
            // echo json_encode($data);
            $materialModel = new MaterialModel();
            if ($materialModel->addMaterial($data)) {
                header('Location: ../index.php');
                exit();
            } else {
                echo "Không thể thêm dữ liệu!";
            }
        }
    }
    public function themLoaiVatTu() {
        
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
    public function suaVatTu(){
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
    public function deleteVatTu() {
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
    public function deleteLoaiVatTu() {
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
?>