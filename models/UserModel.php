<?php
class UserModel {
    private $dbConnection;

    // Constructor nhận đối tượng mysqli
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Phương thức đăng nhập
    public function login($username, $password) {
        // Câu truy vấn SQL
        $query = "SELECT * FROM nguoi_dung WHERE ten_nguoi_dung = ? AND mat_khau = ?";
        
        // Chuẩn bị câu truy vấn SQL
        if ($stmt = $this->dbConnection->prepare($query)) {
            // Gắn giá trị vào câu truy vấn
            $stmt->bind_param("ss", $username, $password); // "ss" nghĩa là cả hai là kiểu string
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            // Kiểm tra kết quả
            if ($user) {
                return $user;  // Trả về thông tin người dùng nếu tìm thấy
            } else {
                return null;  // Trả về null nếu không có người dùng nào khớp
            }
        } else {
            // Trả về thông báo lỗi nếu không thể chuẩn bị câu truy vấn
            die("Lỗi truy vấn: " . $this->dbConnection->error);
        }
    }
    
}

?>