<?php
class UserModel {
    private $dbConnection;

    // Constructor nhận đối tượng mysqli
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Phương thức đăng nhập
    public function login($username, $password) {

        // Chuẩn bị câu lệnh SQL an toàn
        $stmt = $this->dbConnection->prepare("SELECT * FROM nguoi_dung WHERE ten_nguoi_dung = ? AND mat_khau = ?");
        
        if ($stmt === false) {
            die("Lỗi chuẩn bị câu lệnh: " . $this->dbConnection->error);
        }
    
        // Gán giá trị cho câu lệnh chuẩn bị
        $stmt->bind_param("ss", $username, $password);
    
        // Thực thi câu lệnh
        if (!$stmt->execute()) {
            die("Lỗi khi thực thi câu lệnh: " . $stmt->error);
        }
    
        // Lấy kết quả từ câu lệnh chuẩn bị
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            // Sửa lỗi, tránh thông báo không mong muốn
            return false;
        }
    }
}

?>