<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="./views/Css/stylelogin.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Đăng Ký</h1>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="fullname">Họ và tên</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Nhập họ và tên" required>
                </div>
                <div class="form-group">
                    <label for="username">Tên người dùng</label>
                    <input type="text" id="username" name="username" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu"
                        required>
                </div>
                <button type="submit" class="btn-submit">Đăng Ký</button>
                <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </form>
        </div>
    </div>
</body>

</html>