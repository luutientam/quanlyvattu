<?php
// Kết nối tới cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "quanlyvattu");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy mã người dùng từ URL
$ma_nguoi_dung = $_GET['ID'];

// Truy vấn lấy dữ liệu người dùng
$sql = "SELECT * FROM nguoi_dung WHERE ma_nguoi_dung = '$ma_nguoi_dung'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

// Cập nhật dữ liệu khi nhấn nút "Cập nhật"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten_nguoi_dung = $_POST['ten_nguoi_dung'];
    $mat_khau = $_POST['mat_khau'];
    $email = $_POST['email'];
    $ma_vai_tro = $_POST['ma_vai_tro'];

    $update_sql = "UPDATE nguoi_dung SET 
        ten_nguoi_dung = '$ten_nguoi_dung', 
        mat_khau = '$mat_khau', 
        email = '$email', 
        ma_vai_tro = '$ma_vai_tro' 
        WHERE ma_nguoi_dung = '$ma_nguoi_dung'";

    if (mysqli_query($conn, $update_sql)) {
        echo "Cập nhật thành công!";
        header("Location: dashboard_admin_view.php"); // Quay lại trang danh sách
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin người dùng</title>
</head>

<body>
    <h1>Sửa thông tin người dùng</h1>
    <form method="POST">
        <label>Tên người dùng:</label>
        <input type="text" name="ten_nguoi_dung" value="<?php echo $row['ten_nguoi_dung']; ?>" required><br><br>
        <label>Mật khẩu:</label>
        <input type="text" name="mat_khau" value="<?php echo $row['mat_khau']; ?>" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br><br>
        <label>Mã vai trò:</label>
        <input type="text" name="ma_vai_tro" value="<?php echo $row['ma_vai_tro']; ?>" required><br><br>
        <button type="submit">Cập nhật</button>
    </form>
</body>

</html>
