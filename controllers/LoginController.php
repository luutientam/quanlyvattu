<?php
session_start();

require_once '../models/Database.php';  // Kết nối đến cơ sở dữ liệu
require_once '../models/UserModel.php'; // Model xử lý đăng nhập
// Tạo kết nối cơ sở dữ liệu
// Tạo kết nối cơ sở dữ liệu
$db = new Database();
$connection = $db->getConnection();

// Khởi tạo LoginController với kết nối database
$loginController = new LoginController($connection);
$error = $loginController->login();
class LoginController {

    private $userModel;

    public function __construct($dbConnection) {
        $this->userModel = new UserModel($dbConnection);
    }

    // Xử lý đăng nhập
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Kiểm tra đăng nhập
            $user = $this->userModel->login($username, $password);

            if ($user) {
                // Lưu thông tin người dùng vào session
                $_SESSION['user_id'] = $user['ma_nhan_vien'];  // Mã người dùng
                $_SESSION['username'] = $user['ten_nhan_vien']; // Tên người dùng
                $_SESSION['role'] = $user['ten_vai_tro']; // Vai trò

                // Chuyển hướng dựa trên vai trò
                if ($user['ten_vai_tro'] === "Nhân viên") {
                    header("Location: ../controllers/indexKH.php");
                } elseif ($user['ten_vai_tro'] === 2) {
                    header("Location: ../controllers/index.php");
                }
                exit();
            } else {
                return "Tên đăng nhập hoặc mật khẩu không đúng."; // Nếu không có kết quả
            }
        }
        return null;
    }
}



?>