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
                <li><a href="index.php" class="<?= !isset($_GET['act']) ? 'active' : '' ?>">Quản lí tài khoản</a></li>
            </ul>
        </nav>
        <div class="user-info">
            <!-- Hiển thị tên người dùng từ session -->
            <span>Xin chào,
                <?php 
                session_start();
                
                if (isset($_SESSION['maADMIN'])) {
                    echo "<span id='maADMIN'>" . htmlspecialchars($_SESSION['maADMIN']) . "</span>";
                } else {
                    echo '<p id="maADMIN">[MaAdmin]</p>';
                    header("Location: ../views/Login.php");
                    exit();
                }
                echo " - ";
                echo isset($_SESSION['tenADMIN']) ? htmlspecialchars($_SESSION['tenADMIN']) : '[TenADMIN]';  ?></span>
            <a href="../Login.php">Đăng xuất</a>
        </div>
    </header>
</body>

</html>