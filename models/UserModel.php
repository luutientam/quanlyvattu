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
        $sql = "SELECT * FROM nguoi_dung WHERE ten_nguoi_dung = '$username' AND mat_khau = '$password'";
        try{
            $result = mysqli_query($this->dbConnection, $sql);
        }catch(Exception $e){
            return false;
        }
       

        // Nếu có kết quả, trả về thông tin người dùng, nếu không, trả về false
        if ($result && mysqli_num_rows($result) > 0) {
            // Sử dụng fetch_assoc để lấy kết quả dưới dạng mảng
            return mysqli_fetch_assoc($result);
        } else {
            return false; // Không tìm thấy người dùng
        }
    }
    
}
?>