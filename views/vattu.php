<head>
    <link rel="stylesheet" href="../Css/style.css">
    <style>
    .content {
        padding: 20px;
        background: linear-gradient(to left, #c7c7c7, #6894a3);
        border-radius: 30px;
        margin: 30px;
    }
    </style>
</head>

<body>

    <!-- Banner -->
    <section class="banner">
        <h1>Quản Lý Vật Tư</h1>
        <p>Đơn giản hóa việc quản lý vật tư và loại vật tư</p>
        <!-- <button class="btn-main">Khám phá ngay</button> -->
    </section>

    <!-- Nội dung chính -->
    <main class="content">
        <!-- Thanh tìm kiếm -->
        <div class="search-bar">
            <input type="text" placeholder="Tìm kiếm vật tư..." name="txtTimKiem">
            <button class="btn-search">Tìm kiếm</button>
            <select name="loai-vat-tu" id="loai-vat-tu">
                <option value="all">Tất cả loại vật tư</option>
                <?php
            // Kết nối tới cơ sở dữ liệu
                          $conn = mysqli_connect("localhost","root","","quanlyvattu");

            // Truy vấn dữ liệu từ bảng users
                          $sql = "SELECT * FROM loai_vat_tu";
                          $result = mysqli_query($conn, $sql);

            // Kiểm tra và hiển thị dữ liệu
            
                          if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="loai1">'.$row['ten_loai_vat_tu'].'</option>';
                        }
                        } else {
                            
                        }

            // Đóng kết nối
                          mysqli_close($conn);
                        ?>
            </select>
        </div>

        <!-- Bảng danh sách vật tư -->
        <h2>Danh Sách Vật Tư</h2>
        <button class="btn-add" id="btnOpenModal">Thêm Vật Tư</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Vật Tư</th>
                    <th>Tên Vật Tư</th>
                    <th>Mô tả</th>
                    <th>Đơn vị</th>
                    <th>Giá</th>
                    <th>Mã nhà cc</th>
                    <th>Số lượng tối thiểu</th>
                    <th>Số lượng tồn</th>
                    <th>Ngày tạo</th>
                    <th>Tên loại</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                  $conn = mysqli_connect("localhost","root","","quanlyvattu");
        $sql = "SELECT * FROM vat_tu vt join loai_vat_tu lvt on lvt.ma_loai_vat_tu = vt.ma_loai_vat_tu";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                <td>'.$row["ma_vat_tu"].'</td>
                <td>'.$row["ten_vat_tu"].'</td>
                <td>'.$row["mo_ta"].'</td>
                <td>'.$row["don_vi"].'</td>
                <td>'.$row["gia"].'</td>
                <td>'.$row["ma_nha_cung_cap"].'</td>
                <td>'.$row["so_luong_toi_thieu"].'</td>
                <td>'.$row["so_luong_ton"].'</td>
                <td>'.$row["ngay_tao"].'</td>
                <td>'.$row["ten_loai_vat_tu"].'</td>

                <td><a href="xoasv.php?ID='.$row["ma_vat_tu"].'">Xoa</a>
                     <a href="suasv.php?ID='.$row["ma_vat_tu"].'">Sua</a></td>
                </tr>';
            }
        }else{
            echo "khong co du lieu";
        }
    ?>
            </tbody>
        </table>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Thêm Vật Tư</h2>
            <form id="materialForm">
                <div class="form-group">
                    <label for="ma_vat_tu">Mã Vật tư:</label>
                    <input type="text" id="ma_vat_tu" name="ma_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="ten_vat_tu">Tên Vật tư:</label>
                    <input type="text" id="ten_vat_tu" name="ten_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <textarea id="mo_ta" name="mo_ta" required></textarea>
                </div>
                <div class="form-group">
                    <label for="don_vi">Đơn vị:</label>
                    <input type="text" id="don_vi" name="don_vi" required>
                </div>
                <div class="form-group">
                    <label for="gia">Giá:</label>
                    <input type="number" id="gia" name="gia" required>
                </div>
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap" name="ma_nha_cung_cap" required>
                </div>
                <div class="form-group">
                    <label for="so_luong_toi_thieu">Số lượng Tối thiểu:</label>
                    <input type="number" id="so_luong_toi_thieu" name="so_luong_toi_thieu" required>
                </div>
                <div class="form-group">
                    <label for="so_luong_ton">Số lượng Tồn:</label>
                    <input type="number" id="so_luong_ton" name="so_luong_ton" required>
                </div>
                <div class="form-group">
                    <label for="ngay_tao">Ngày tạo:</label>
                    <input type="date" id="ngay_tao" name="ngay_tao" required>
                </div>
                <div class="form-group">
                    <label for="loai_vat_tu">Loại Vật Tư:</label>
                    <select id="loai_vat_tu" name="loai_vat_tu" required>
                        <?php
            // Kết nối tới cơ sở dữ liệu
                          $conn = mysqli_connect("localhost","root","","quanlyvattu");

            // Truy vấn dữ liệu từ bảng users
                          $sql = "SELECT * FROM loai_vat_tu";
                          $result = mysqli_query($conn, $sql);

            // Kiểm tra và hiển thị dữ liệu
            
                          if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="loai1">'.$row['ten_loai_vat_tu'].'</option>';
                        }
                        } else {
                            
                        }

            // Đóng kết nối
                          mysqli_close($conn);
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>

    <script>
    // Modal xử lý
    const modal = document.getElementById("modal");
    const btnOpenModal = document.getElementById("btnOpenModal");
    const btnCloseModal = document.getElementById("btnCloseModal");

    btnOpenModal.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    btnCloseModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    // Xử lý form thêm vật tư
    const form = document.getElementById("materialForm");
    const tableBody = document.querySelector(".table tbody");

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const maVatTu = document.getElementById("ma_vat_tu").value;
        const tenVatTu = document.getElementById("ten_vat_tu").value;
        const loaiVatTu = document.getElementById("loai_vat_tu").value;
        const soLuong = document.getElementById("so_luong").value;

        const newRow = `
    <tr>
      <td>${maVatTu}</td>
      <td>${tenVatTu}</td>
      <td>${loaiVatTu}</td>
      <td>${soLuong}</td>
      <td>
        <button class="btn-edit">Sửa</button>
        <button class="btn-delete">Xóa</button>
      </td>
    </tr>
  `;

        tableBody.innerHTML += newRow;
        modal.style.display = "none";
        form.reset();
    });
    </script>
</body>