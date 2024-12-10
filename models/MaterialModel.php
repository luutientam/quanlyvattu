<?php
require_once 'Database.php';

class MaterialModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function addMaterial($data) {
        $sql = "INSERT INTO vat_tu (
            ma_vat_tu, 
            ten_vat_tu, 
            mo_ta, 
            don_vi, 
            gia, 
            ma_nha_cung_cap, 
            so_luong_toi_thieu, 
            so_luong_ton, 
            ma_loai_vat_tu
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param(
            "sssssiisi", 
            $data['ma_vat_tu'], 
            $data['ten_vat_tu'], 
            $data['mo_ta'], 
            $data['don_vi'], 
            $data['gia'], 
            $data['ma_nha_cung_cap'], 
            $data['so_luong_toi_thieu'], 
            $data['so_luong_ton'], 
            $data['ma_loai_vat_tu']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function themLoaiVatTu($data){
        $sql = "INSERT INTO loai_vat_tu (
            ma_loai_vat_tu, 
            ten_loai_vat_tu, 
            mo_taa
        ) VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param(
            "sss", 
            $data['ma_loai_vat_tu'], 
            $data['ten_loai_vat_tu'], 
            $data['mo_ta']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function suaVatTu($data) {
        // Câu lệnh SQL sửa dữ liệu trong database
        $sql = "UPDATE vat_tu SET 
                    ten_vat_tu = ?, 
                    mo_ta = ?, 
                    don_vi = ?, 
                    gia = ?, 
                    ma_nha_cung_cap = ?, 
                    so_luong_toi_thieu = ?, 
                    so_luong_ton = ?, 
                    ma_loai_vat_tu = ?
                WHERE ma_vat_tu = ?";
    
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            return false;
        }
    
        // Bind các giá trị vào câu lệnh prepared statement
        $stmt->bind_param(
            "sssssiisi", 
            $data['ten_vat_tu'], 
            $data['mo_ta'], 
            $data['don_vi'], 
            $data['gia'], 
            $data['ma_nha_cung_cap'], 
            $data['so_luong_toi_thieu'], 
            $data['so_luong_ton'], 
            $data['ma_loai_vat_tu'],
            $data['ma_vat_tu']   // WHERE clause cần ID để xác định bản ghi cụ thể
        );
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function deleteVatTu($id) {
        $sql = "DELETE FROM vat_tu WHERE ma_vat_tu = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            return false;
        }
    
        $stmt->bind_param("s", $id); // 's' vì $id là kiểu chuỗi
        return $stmt->execute();
    }
    public function deleteLoaiVatTu($id) {
        $sql = "DELETE FROM loai_vat_tu WHERE ma_loai_vat_tu = ?";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            return false;
        }
    
        $stmt->bind_param("s", $id); // 's' vì $id là kiểu chuỗi
        return $stmt->execute();
    }
    
}
?>