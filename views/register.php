<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="./Css/stylelogin.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</head>

<body>
    <div class="auth-container">
        <div class="auth-form">
            <h1>Đăng Ký</h1>
            <form id="FormDangKy" action="" method="POST">
                <div class="form-group">
                    <label for="hoVaTen">Họ và tên <span style="color: red;font-size: 20px;"> *</span></label>
                    <input type="text" id="hoVaTen" name="hoVaTen" placeholder="Nhập họ và tên ..." required>
                </div>
                <div class="form-group">
                    <label for="soDienThoai">Số điện thoại <span style="color: red;font-size: 20px;"> *</span></label>
                    <input type="text" id="soDienThoai" name="soDienThoai" placeholder="Nhập số điện thoại ..."
                        required>
                </div>
                <div class="form-group">
                    <label for="email">Email </label>
                    <input type="text" id="email" name="email" placeholder="Nhập email ...">
                </div>
                <div class="form-group">
                    <label for="diaChi">Địa chỉ </label>
                    <input type="text" id="diaChi" name="diaChi" placeholder="Nhập địa chỉ ...">
                </div>
                <div class="form-group">
                    <label for="tenDangNhap">Tên đăng nhập <span style="color: red;font-size: 20px;"> *</span></label>
                    <input type="text" id="tenDangNhap" name="tenDangNhap" placeholder="Nhập tên đăng nhập ... "
                        required>
                </div>
                <div class="form-group">
                    <label for="matKhau">Mật khẩu <span style="color: red;font-size: 20px;"> *</span></label>
                    <input type="password" id="matKhau" name="matKhau" placeholder="Nhập mật khẩu ..." required>
                </div>
                <!-- <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu"
                        required>
                </div> -->
                <button type="submit" class="btn-submit">Đăng Ký</button>
                <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
            </form>
        </div>
    </div>
    <script>
    // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
    $("#FormDangKy").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định của form

        // Lấy dữ liệu từ form
        var khachHangData = {
            ten_khach_hang: $("#hoVaTen").val(),
            so_dien_thoai: $("#soDienThoai").val(),
            email: $("#email").val(),
            dia_chi: $("#diaChi").val(),
            ten_dang_nhap: $("#tenDangNhap").val(),
            mat_khau: $("#matKhau").val(),
            loai_tai_khoan: 3,
        };

        // Kiểm tra nếu có trường nào trống
        if (!khachHangData.ten_khach_hang || !khachHangData.so_dien_thoai || !khachHangData.ten_dang_nhap || !
            khachHangData.mat_khau) {
            $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc.</p>');
            return;
        }

        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/DangKy_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(khachHangData),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 201) {
                    $("#FormDangKy")[0].reset();
                    alert("Đăng kí tài khoản thành công!");
                    window.location.href = "Login.php";
                } else if (response && response.status === 409) {
                    alert("Tên đăng nhập đã tồn tại. Vui lòng nhập tên khác.");
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