<?php
class MaterialModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getAllMaterials() {
        $sql = "SELECT * FROM vat_tu vt JOIN loai_vat_tu lvt ON lvt.ma_loai_vat_tu = vt.ma_loai_vat_tu";
        return $this->db->query($sql);
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM loai_vat_tu";
        return $this->db->query($sql);
    }

    public function addMaterial($data) {
        $stmt = $this->db->prepare("
            INSERT INTO vat_tu (ma_vat_tu, ten_vat_tu, mo_ta, don_vi, gia, ma_nha_cung_cap, so_luong_toi_thieu, so_luong_ton, ngay_tao, ma_loai_vat_tu)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssdiissi", $data['ma_vat_tu'], $data['ten_vat_tu'], $data['mo_ta'], $data['don_vi'], $data['gia'], $data['ma_nha_cung_cap'], $data['so_luong_toi_thieu'], $data['so_luong_ton'], $data['ngay_tao'], $data['ma_loai_vat_tu']);
        return $stmt->execute();
    }
}
?>