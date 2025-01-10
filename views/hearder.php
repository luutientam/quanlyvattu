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
                <li><a href="index.php?act=quanlydonhang"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'quanlydonhang') ? 'active' : '' ?>">Đơn
                        Hàng</a>
                </li>
                <li><a href="index.php?act=nhacungcap"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'nhacungcap') ? 'active' : '' ?>">Nhà Cung
                        Cấp</a>
                </li>
                <!-- Giỏ hàng với biểu tượng FontAwesome -->
                <li>
                    <a href="index.php?act=giohang"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'giohang') ? 'active' : '' ?>">
                        <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="user-info">
            <!-- Hiển thị tên người dùng từ session -->
            <span>Xin chào,
                <?php 
                 if (isset($_SESSION['maNV'])) {
                    echo "<span id='maNV'>" . htmlspecialchars($_SESSION['maNV']) . "</span>" . " - ". htmlspecialchars($_SESSION['tenNV']);
                } else {
                    echo '<p id="maNV">[MaNhanVien]</p>';
                    header("Location: ../views/Login.php");
                    exit();
                }
                ?></span>
            <a href="../views/Login.php">Đăng xuất</a>
        </div>
    </header>
</body>

</html>