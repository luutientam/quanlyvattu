<?php
// controllers/MainController.php
include_once '../models/LoaiVatTuModel.php';
include_once '../models/VatTuModel.php';

class MainController {
    public function getLoaiVatTu() {
        $loaiVatTuModel = new LoaiVatTuModel();
        return $loaiVatTuModel->getLoaiVatTu();
    }

    public function getDanhSachVatTu($keyword) {
        $vatTuModel = new VatTuModel();
        return $vatTuModel->getDanhSachVatTu($keyword);
    }
    
}
?>