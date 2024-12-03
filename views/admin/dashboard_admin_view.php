<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin người dùng</title>
</head>

<body>
    <h1>Danh sách người dùng</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Mã người dùng</th>
                <th>Tên người dùng</th>
                <th>Mật khẩu</th>
                <th>Email</th>
                <th>Mã vai trò</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Kết nối tới cơ sở dữ liệu
            $conn = mysqli_connect("localhost","root","","quanlyvattu");

            // Truy vấn dữ liệu từ bảng users
            $sql = "SELECT * FROM nguoi_dung";
            $result = mysqli_query($conn, $sql);

            // Kiểm tra và hiển thị dữ liệu
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row['ma_nguoi_dung'] . '</td>
                            <td>' . $row['ten_nguoi_dung'] . '</td>
                            <td>' . $row['mat_khau'] . '</td>
                            <td>' . $row['email'] . '</td>
                            <td>' . $row['ma_vai_tro'] . '</td>
                            <td>' . $row['ngay_tao'] . '</td>
                            <td><a href="xoand.php?ID='.$row["ma_nguoi_dung"].'">Xoa</a>
                            <a href="suand.php?ID='.$row["ma_nguoi_dung"].'">Sua</a></td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="3">Không có người dùng nào trong cơ sở dữ liệu.</td></tr>';
            }

            // Đóng kết nối
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</body>

</html>
