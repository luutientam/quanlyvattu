<?php
// Kết nối cơ sở dữ liệu
require_once '../models/Database.php';
require_once '../controllers/LoginController.php';

// Tạo đối tượng controller
// Tạo đối tượng Database
$db = new Database();
//ngonnnn
// Truyền đối tượng Database (không phải mysqli) vào LoginController
$controller = new LoginController($db);


$error = $controller->login();  // Gọi hàm login và xử lý lỗi (nếu có)
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./Css/stylelogin.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Đăng Nhập</h1>
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="../controllers/indexKH.php" method="POST">
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