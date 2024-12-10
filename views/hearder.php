<?php
// Bắt đầu session để lấy dữ liệu người dùng
session_start();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <link rel="stylesheet" href="./Css/style.css">
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
                <li><a href="index.php?act=baocao"
                        class="<?= (isset($_GET['act']) && $_GET['act'] === 'baocao') ? 'active' : '' ?>">Báo Cáo</a>
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