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
        $loaiVatTuModel = new LoaiVatTuModel();
        return $loaiVatTuModel->getMaNhaCungCap();
    }
    
    // public function getDanhSachVatTu($keyword) {
    //     $db = new db();
    //     $connect = $db->connect();  
    //     $vatTuModel = new VatTuModel($connect);
    //     $read = $vatTuModel->getDanhSachVatTu($keyword);
    
    //     $num = $read->rowCount();// đếm kết quả trả về 
    //     if($num>0){
    //         $vatTu_array = [];
    //         $vatTu_array['data'] = [];
    //         while($row = $read->fetch(PDO::FETCH_ASSOC)){
    //             extract($row);

    //             $vatTu_item = array(
    //                 'ma_vat_tu' => $ma_vat_tu,
    //                 'ten_vat_tu' => $ten_vat_tu,
    //                 'mo_ta' => $mo_ta,
    //                 'don_vi' => $don_vi,
    //                 'gia' => $gia,
    //                 'ma_nha_cung_cap' => $ma_nha_cung_cap,
    //                 'so_luong_toi_thieu' => $so_luong_toi_thieu,
    //                 'so_luong_ton' => $so_luong_ton,
    //                 'ma_loai_vat_tu' => $loai_vat_tu
    //             );
    //             array_push( $vatTu_array['data'], $vatTu_item);
    //         }
    //         echo json_encode($vatTu_array);
    //     }
    //     return $vatTuModel->getDanhSachVatTu($keyword);
    // }
    
}
?>