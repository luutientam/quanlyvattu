<?php
class UserModel {

    private $dbConnection;

    // Constructor nhận đối tượng mysqli
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Phương thức đăng nhập
    public function login($username, $password) {
        // Truy vấn cơ sở dữ liệu để tìm người dùng
        $stmt = $this->dbConnection->prepare("SELECT * FROM nguoi_dung WHERE ten_nguoi_dung = ? AND mat_khau = ?");
        $stmt->bind_param("ss", $username, $password); // "ss" kiểu chuỗi
        $stmt->execute();
        $result = $stmt->get_result();

        // Nếu có kết quả, trả về thông tin người dùng, nếu không, trả về false
        if ($result->num_rows > 0) {
            // Sử dụng fetch_assoc để lấy kết quả dưới dạng mảng
            return $result->fetch_assoc();
        } else {
            return false; // Không tìm thấy người dùng
        }
    }
}
?>