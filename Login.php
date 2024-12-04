<?php
// Bắt đầu phiên
// Kiểm tra xem người dùng đã đăng nhập hay chưa

// 
if (isset($_SESSION['user_id'])) {
    // Nếu đã đăng nhập, chuyển hướng đến trang phù hợp dựa trên vai trò
    if ($_SESSION['role'] === 'Admin') {
        header("Location: quanlyvattu/");
    } elseif ($_SESSION['role'] === 'Quản lý kho') {
        header("Location: quanlyvattu");
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
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./views/Css/stylelogin.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Đăng Nhập</h1>
            <?php if ($error): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Tên người dùng</label>
                    <input type="text" id="username" name="username" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn-submit">Đăng Nhập</button>
                <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
            </form>
        </div>
    </div>
</body>

</html>