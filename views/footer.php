<head>
    <link rel="stylesheet" href="./Css/footer.css">
</head>

<body>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <h3>About Us</h3>
                <p>
                    We provide the best services and products for your needs.
                    Our commitment is to deliver excellence in every aspect.
                </p>
            </div>
            <div class="footer-middle">
                <h3>Liên kết nhanh</h3>
                <ul>
                    <li><a href="index.php" class="<?= !isset($_GET['act']) ? 'active' : '' ?>">Vật Tư</a></li>
                    <li><a href="index.php?act=loaivattu"
                            class="<?= (isset($_GET['act']) && $_GET['act'] === 'loaivattu') ? 'active' : '' ?>">Loại
                            Vật
                            Tư</a></li>
                    <li><a href="index.php?act=thongke"
                            class="<?= (isset($_GET['act']) && $_GET['act'] === 'thongke') ? 'active' : '' ?>">Thống
                            Kê</a>
                    </li>
                    <li><a href="index.php?act=baocao"
                            class="<?= (isset($_GET['act']) && $_GET['act'] === 'baocao') ? 'active' : '' ?>">Báo
                            Cáo</a>
                    </li>
                </ul>
            </div>
            <div class="footer-right">
                <h3>Liên hệ với chúng tôi</h3>
                <p>
                    Email: contact@yourwebsite.com <br>
                    Phone: +123 456 7890 <br>
                    Address: 123 Main Street, City, Country
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Your Website. All Rights Reserved.</p>
        </div>
    </footer>
</body>