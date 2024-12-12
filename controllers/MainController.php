<?php
// controllers/MainController.php
include_once '../models/GetDuLieu.php';
include_once '../models/VatTuModel.php';

class MainController {
    public function getLoaiVatTu() {
        $loaiVatTuModel = new GetDuLieu();
        return $loaiVatTuModel->getLoaiVatTu();
    }

    public function getMaNhaCungCap() {
        $maNhaCungCap = new GetDuLieu();
        return $maNhaCungCap->getMaNhaCungCap();
    }
    public function getDanhSachVatTu($keyword) {
        $vatTuModel = new VatTuModel();
        return $vatTuModel->getDanhSachVatTu($keyword);
    }
    
}
?>