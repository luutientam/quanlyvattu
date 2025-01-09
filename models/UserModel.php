<?php
class UserModel {
    private $dbConnection;

    // Constructor nhận đối tượng PDO
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
    }

    // Phương thức đăng nhập
    public function login($username, $password) {

        // Chuẩn bị câu lệnh SQL an toàn
        $stmt = $this->dbConnection->prepare("SELECT * FROM tai_khoan tk 
                                                LEFT JOIN nhan_vien nv ON tk.ma_tai_khoan = nv.ma_tai_khoan 
                                                LEFT JOIN khach_hang kh ON tk.ma_tai_khoan = kh.ma_tai_khoan 
                                                JOIN vai_tro vt ON tk.loai_tai_khoan = vt.ma_vai_tro 
                                                WHERE tk.ten_dang_nhap = :username AND tk.mat_khau = :password");

        // Sử dụng bindParam() để gán giá trị cho tham số
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            // Lấy kết quả nếu có
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Trả về kết quả đăng nhập thành công dưới dạng JSON
                return json_encode([
                    'status' => 200,
                    'message' => 'Đăng nhập thành công !',
                    'data' => $result]);
            } else {
                // Nếu không có kết quả, đăng nhập thất bại
                return json_encode([
                    'status' => 401, 
                    'message' => 'Sai tài khoản hoặc mật khẩu']);
            }
        } else {
            // Trả về lỗi khi thực thi câu lệnh
            return json_encode([
                'status' => 500, 
                'message' => 'Lỗi khi thực thi câu lệnh']);
        }
    }
}
?>