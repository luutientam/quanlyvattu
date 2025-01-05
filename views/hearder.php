<?php
// Bắt đầu session để lấy dữ liệu người dùng
session_start();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <link rel="stylesheet" href="./Css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="navbar">
        <div class="logo"></div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= !isset($_GET['act']) ? 'active' : '' ?>">Vật Tư</a></li>
                <li><a href="index.php?act=loaivattu"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'loaivattu') ? 'active' : '' ?>">Loại Vật
                        Tư</a></li>
                <li><a href="index.php?act=thongke"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'thongke') ? 'active' : '' ?>">Thống Kê</a>
                </li>
                <li><a href="index.php?act=quanlydonhang"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'quanlydonhang') ? 'active' : '' ?>">Đơn Hàng</a>
                </li>
                <li><a href="index.php?act=baocao"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'baocao') ? 'active' : '' ?>">Báo Cáo</a>
                </li>
                <!-- Giỏ hàng với biểu tượng FontAwesome -->
                <li>
                    <a href="index.php?act=giohang" class="<?= (isset($_GET['act']) && $_GET['act'] === 'giohang') ? 'active' : '' ?>">
                        <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="user-info">
            <!-- Hiển thị tên người dùng từ session -->
            <span>Xin chào,
                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Khách'; ?></span>
            <a href="../views/Login.php">Đăng xuất</a>
        </div>
    </header>
</body>

</html>