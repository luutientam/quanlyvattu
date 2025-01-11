<?php
// Import file db.php để lấy đối tượng kết nối cơ sở dữ liệu
require_once "db.php";

// Định nghĩa class VatTuModel để quản lý dữ liệu của bảng `vat_tu`
class VatTuModel
{
    // Thuộc tính kết nối cơ sở dữ liệu
    private $db;

    // Các thuộc tính đại diện cho các cột trong bảng `vat_tu`
    public $ma_vat_tu;        // Mã vật tư
    public $ten_vat_tu;       // Tên vật tư
    public $mo_ta;            // Mô tả vật tư
    public $don_vi;           // Đơn vị tính
    public $gia;              // Giá
    public $ma_nha_cung_cap;  // Mã nhà cung cấp
    public $so_luong;         // Số lượng tồn kho
    public $ngay_tao;         // Ngày tạo
    public $ma_loai_vat_tu;   // Mã loại vật tư

    // Constructor khởi tạo đối tượng với kết nối cơ sở dữ liệu
    public function __construct($db)
    {
        $this->db = $db; // Gán đối tượng kết nối cho thuộc tính $db
    }

    /**
     * Lấy danh sách vật tư với từ khóa tìm kiếm và lọc theo loại vật tư
     */
    public function getDanhSachVatTu($keyword = '', $maLoaiVatTu = 'all')
    {
        // Câu lệnh SQL để tìm kiếm vật tư theo từ khóa
        $query = "
            SELECT 
                vat_tu.ma_vat_tu, 
                vat_tu.ten_vat_tu, 
                vat_tu.mo_ta, 
                vat_tu.don_vi, 
                vat_tu.gia, 
                vat_tu.ma_nha_cung_cap, 
                vat_tu.so_luong, 
                vat_tu.ngay_tao, 
                loai_vat_tu.ten_loai_vat_tu 
            FROM 
                vat_tu 
            JOIN 
                loai_vat_tu 
            ON 
                loai_vat_tu.ma_loai_vat_tu = vat_tu.ma_loai_vat_tu 
            WHERE 
                (vat_tu.ma_vat_tu LIKE :keyword OR 
                vat_tu.ten_vat_tu LIKE :keyword OR 
                vat_tu.mo_ta LIKE :keyword OR 
                vat_tu.don_vi LIKE :keyword OR 
                vat_tu.gia LIKE :keyword OR 
                vat_tu.ma_nha_cung_cap LIKE :keyword OR 
                vat_tu.so_luong LIKE :keyword OR 
                vat_tu.ngay_tao LIKE :keyword OR 
                loai_vat_tu.ten_loai_vat_tu LIKE :keyword)";

        // Thêm điều kiện lọc nếu mã loại vật tư không phải "all"
        if ($maLoaiVatTu !== 'all') {
            $query .= " AND vat_tu.ma_loai_vat_tu = :maLoaiVatTu";
        }

        // Chuẩn bị truy vấn
        $stmt = $this->db->prepare($query);

        // Gắn giá trị cho các tham số
        $keyword = "%$keyword%";
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        if ($maLoaiVatTu !== 'all') {
            $stmt->bindParam(':maLoaiVatTu', $maLoaiVatTu, PDO::PARAM_STR);
        }

        // Thực thi truy vấn và trả về đối tượng PDOStatement
        $stmt->execute();
        return $stmt;
    }

    /**
     * Tạo mới một vật tư
     */
    public function create($dataPOST)
    {
        // Kiểm tra xem mã vật tư đã tồn tại trong bảng hay chưa
        $checkQuery = "SELECT COUNT(*) FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        // Nếu mã vật tư đã tồn tại, trả về lỗi
        if ($count > 0) {
            return json_encode([
                'status' => 409, // Conflict
                'message' => 'Mã vật tư đã tồn tại. Vui lòng nhập mã khác.'
            ]);
        }

        // Thực hiện chèn dữ liệu mới vào bảng `vat_tu`
        $query = "INSERT INTO vat_tu SET
        ma_vat_tu = :ma_vat_tu, 
        ten_vat_tu = :ten_vat_tu, 
        mo_ta = :mo_ta, 
        don_vi = :don_vi, 
        gia = :gia, 
        ma_nha_cung_cap = :ma_nha_cung_cap, 
        so_luong = :so_luong, 
        ma_loai_vat_tu = :ma_loai_vat_tu";

        $stmt = $this->db->prepare($query);

        // Gắn giá trị cho các tham số
        $stmt->bindParam(':ma_vat_tu', $dataPOST['ma_vat_tu']);
        $stmt->bindParam(':ten_vat_tu', $dataPOST['ten_vat_tu']);
        $stmt->bindParam(':mo_ta', $dataPOST['mo_ta']);
        $stmt->bindParam(':don_vi', $dataPOST['don_vi']);
        $stmt->bindParam(':gia', $dataPOST['gia']);
        $stmt->bindParam(':ma_nha_cung_cap', $dataPOST['ma_nha_cung_cap']);
        $stmt->bindParam(':so_luong', $dataPOST['so_luong']);
        $stmt->bindParam(':ma_loai_vat_tu', $dataPOST['ma_loai_vat_tu']);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            return json_encode([
                'status' => 201, // Created
                'message' => 'Tạo vật tư thành công'
            ]);
        } else {
            return json_encode([
                'status' => 500, // Internal Server Error
                'message' => 'Lỗi khi tạo vật tư.'
            ]);
        }
    }

    /**
     * Xóa vật tư theo mã
     */
    public function delete($ma_vat_tu)
    {
        try {
            // Câu lệnh SQL để xóa vật tư theo mã
            $query = "DELETE FROM vat_tu WHERE ma_vat_tu = :ma_vat_tu";
            $stmt = $this->db->prepare($query);

            // Gắn giá trị tham số
            $stmt->bindParam(':ma_vat_tu', $ma_vat_tu);

            // Thực thi câu lệnh
            $stmt->execute();

            // Kiểm tra xem có bản ghi nào bị xóa hay không
            if ($stmt->rowCount() > 0) {
                return true; // Xóa thành công
            } else {
                return false; // Mã vật tư không tồn tại
            }
        } catch (Exception $e) {
            return false; // Xử lý lỗi
        }
    }

    /**
     * Cập nhật thông tin vật tư
     */
    public function update($ma_vat_tu, $data)
    {
        try {
            // Câu lệnh SQL để cập nhật thông tin vật tư
            $query = "
                UPDATE vat_tu
                SET 
                    ten_vat_tu = :ten_vat_tu,
                    mo_ta = :mo_ta,
                    don_vi = :don_vi,
                    gia = :gia,
                    ma_nha_cung_cap = :ma_nha_cung_cap,
                    so_luong = :so_luong,
                    ma_loai_vat_tu = :ma_loai_vat_tu
                WHERE ma_vat_tu = :ma_vat_tu
            ";
            $stmt = $this->db->prepare($query);

            // Gắn giá trị cho các tham số
            $stmt->bindParam(':ma_vat_tu', $ma_vat_tu);
            $stmt->bindParam(':ten_vat_tu', $data['ten_vat_tu_sua']);
            $stmt->bindParam(':mo_ta', $data['mo_ta_sua']);
            $stmt->bindParam(':don_vi', $data['don_vi_sua']);
            $stmt->bindParam(':gia', $data['gia_sua']);
            $stmt->bindParam(':ma_nha_cung_cap', $data['ma_nha_cung_cap_sua']);
            $stmt->bindParam(':so_luong', $data['so_luong_sua']);
            $stmt->bindParam(':ma_loai_vat_tu', $data['ma_loai_vat_tu_sua']);

            // Thực thi câu lệnh
            if ($stmt->execute()) {
                return true; // Cập nhật thành công
            }
            return false; // Cập nhật thất bại
        } catch (Exception $e) {
            return false; // Xử lý lỗi
        }
    }
}