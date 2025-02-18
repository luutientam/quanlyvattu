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
                <li><a href="indexKH.php" class="<?= !isset($_GET['act']) ? 'active' : '' ?>">Vật Tư</a></li>
                <li>
                    <a href="indexKH.php?act=giohang"
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
                session_start();
                
                if (isset($_SESSION['maKH'])) {
                    echo "<span id='maKH'>" . htmlspecialchars($_SESSION['maKH']) . "</span>";
                } else {
                    echo '<p id="maKH">[MaKhachHang]</p>';
                    header("Location: ../views/Login.php");
                    exit();
                }
                echo " - ";
                echo isset($_SESSION['tenKH']) ? htmlspecialchars($_SESSION['tenKH']) : '[TenKhachHang]';  ?></span>
            <a href="../views/Login.php">Đăng xuất</a>
        </div>
    </header>
</body>

</html>