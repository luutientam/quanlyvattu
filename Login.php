<?php
// Bắt đầu phiên
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (isset($_SESSION['user_id'])) {
    // Nếu đã đăng nhập, chuyển hướng đến trang phù hợp dựa trên vai trò
    if ($_SESSION['role'] === 'Admin') {
        header("Location: quanlyvattu/");
    } elseif ($_SESSION['role'] === 'Quản lý kho') {
        header("Location: view/Quan");
    }
    exit();
}

// Kiểm tra xem form đăng nhập đã được gửi đi hay chưa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'Database.php';
    require_once 'src/models/User.php'; 
    require_once "model/Auth.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kết nối cơ sở dữ liệu
    $db = new Database();
    $connection = $db->getConnection();

    // Tìm người dùng
    // $userModel = new User($connection);
    $user = $userModel->login($username, $password);
    if ($user) {
        Auth::startSession();
        // Lưu thông tin người dùng vào phiên
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['ten_dang_nhap'];
        $_SESSION['role'] = $user['loai_tai_khoan'];

        // Chuyển hướng đến trang phù hợp dựa trên vai trò
        if ($user['loai_tai_khoan'] === 'Admin') {
            header("Location: views/admin/dashboard.php");
        } elseif ($user['loai_tai_khoan'] === 'Quản lý kho') {
            header("Location: views/faculty/dashboard.php");
        } 
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Lý Vật Tư</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
    </style>
    <link rel="stylesheet" href="views/Styles/login.css">
</head>

<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form method="POST" autocomplete="off">
            <div class="form-group">
                <label for="username">Tên đăng nhập</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Đăng Nhập</button>
            <a href="forgot_password.php" class="forgot-password-link">Quên mật khẩu?</a>
        </form>
        <p class="note">Lưu ý: Vui lòng nhập đúng thông tin đăng nhập.</p>
    </div>
</body>

</html>