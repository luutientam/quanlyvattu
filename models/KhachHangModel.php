<?php
require_once "db.php";

class KhachHangModel
{
    private $db;
    public $ma_khach_hang;
    public $ten_khach_hang;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getDanhSachKhachHang()
    {
        $query = "SELECT * FROM khach_hang";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
