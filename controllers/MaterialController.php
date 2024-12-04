<?php
require_once '../models/MaterialModel.php';
require_once '../models/Database.php';

class MaterialController {
    private $materialModel;

    public function __construct() {
        $db = new Database();
        $this->materialModel = new MaterialModel($db->getConnection());
    }

    public function index() {
        $materials = $this->materialModel->getAllMaterials();
        $categories = $this->materialModel->getAllCategories();
        require_once '../views/materials/index.php';
    }

    public function create() {
        $categories = $this->materialModel->getAllCategories();
        require_once '../views/materials/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ma_vat_tu' => $_POST['ma_vat_tu'],
                'ten_vat_tu' => $_POST['ten_vat_tu'],
                'mo_ta' => $_POST['mo_ta'],
                'don_vi' => $_POST['don_vi'],
                'gia' => $_POST['gia'],
                'ma_nha_cung_cap' => $_POST['ma_nha_cung_cap'],
                'so_luong_toi_thieu' => $_POST['so_luong_toi_thieu'],
                'so_luong_ton' => $_POST['so_luong_ton'],
                'ngay_tao' => $_POST['ngay_tao'],
                'ma_loai_vat_tu' => $_POST['loai_vat_tu'],
            ];
            $this->materialModel->addMaterial($data);
            header('Location: ../public/index.php');
        }
    }
}
?>