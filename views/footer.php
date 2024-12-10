<head>
</head>

<body>
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <h3>Về Chúng Tôi</h3>
                <p>
                    Chúng tôi cung cấp các dịch vụ và sản phẩm tốt nhất cho nhu cầu của bạn. Cam kết của chúng tôi là
                    mang lại sự xuất sắc trong mọi khía cạnh.
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
                    <i class='bx bx-envelope'></i> quanlyvattu@gmail.com <br>
                    <i class='bx bx-phone-call'></i> +84968686868 <br>
                    <i class='bx bx-location-plus'></i> 54 Triều Khúc, Thanh Xuân, Hà Nội
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Your Website ok anh em. All Rights Reserved.</p>
        </div>
    </footer>
</body>