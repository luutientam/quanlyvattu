<?php
// Kết nối cơ sở dữ liệu
require_once '../models/Database.php';

// Tạo đối tượng controller
// Tạo đối tượng Database
$db = new Database();
//ngonnnn
// Truyền đối tượng Database (không phải mysqli) vào LoginController

session_unset(); // Xóa tất cả các biến session
// session_destroy(); // Hủy session
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="./Css/stylelogin.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Đăng Nhập</h1>
            <?php if (!empty($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form id="FormDangNhap" method="POST">
                <div class="form-group">
                    <label for="tenDangNhap">Tên người dùng</label>
                    <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên người dùng" required>
                </div>
                <div class="form-group">
                    <label for="matKhau">Mật khẩu</label>
                    <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn-submit">Đăng Nhập</button>
                <p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
            </form>
        </div>
    </div>
    <script>
    // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
    $("#FormDangNhap").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định của form

        // Lấy dữ liệu từ form
        var taiKhoanData = {
            ten_dang_nhap: $("#tenDangNhap").val(),
            mat_khau: $("#matKhau").val(),
        };

        // Kiểm tra nếu có trường nào trống
        if (!taiKhoanData.ten_dang_nhap || !taiKhoanData.mat_khau) {
            $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ tài khoản mật khẩu.</p>');
            return;
        }

        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/DangNhap_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(taiKhoanData),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 200) {
                    // $("#FormDangNhap")[0].reset();
                    // alert("Đăng nhập thành công.");
                    if (response.data.ten_vai_tro === "Quản lý") {
                        window.location.href = "http://localhost/quanlyvattu/views/Admin";
                    } else if (response.data.ten_vai_tro === "Nhân viên") {
                        window.location.href = "http://localhost/quanlyvattu";
                    } else if (response.data.ten_vai_tro === "Khách hàng") {
                        window.location.href = "../KH/index.php";
                    }
                } else if (response && response.status === 401) {
                    alert("Tài khoản hoặc mật khẩu không đúng.");
                } else {
                    // Hiển thị thông báo lỗi nếu không có status 201
                    $("#responseMessage").html(
                        `<p style="color: red;">Lỗi: ${response.message || 'Không xác định lỗi'}</p>`
                    );
                }
            },
            error: function(xhr, status, error) {
                // Xử lý lỗi từ phía server
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                    "Đã có lỗi xảy ra.";
                $("#responseMessage").html(`<p style="color: red;">Lỗi: ${errorMessage}</p>`);
            }
        });
    });
    </script>
</body>

</html>