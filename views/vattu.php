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
<?php
// views/index.php
require_once '../controllers/MainController.php';

$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    $keyword = $_POST['txtTimKiem'];
    $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
}else{
    $keyword = '';
    $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
}

?>

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
        <form method="POST">
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm vật tư..." name="txtTimKiem">
                <button class="btn-search">Tìm kiếm</button>
                <select name="loai-vat-tu" id="loai-vat-tu">
                    <option value="all">Tất cả loại vật tư</option>
                    <?php foreach ($loaiVatTu as $loai) { ?>
                    <option value="<?= $loai['ma_loai_vat_tu'] ?>">
                        <?= $loai['ten_loai_vat_tu'] ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </form>

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
                <?php foreach ($danhSachVatTu as $vatTu) { ?>
                <tr>
                    <td><?= $vatTu['ma_vat_tu'] ?></td>
                    <td><?= $vatTu['ten_vat_tu'] ?></td>
                    <td><?= $vatTu['mo_ta'] ?></td>
                    <td><?= $vatTu['don_vi'] ?></td>
                    <td><?= $vatTu['gia'] ?></td>
                    <td><?= $vatTu['ma_nha_cung_cap'] ?></td>
                    <td><?= $vatTu['so_luong_toi_thieu'] ?></td>
                    <td><?= $vatTu['so_luong_ton'] ?></td>
                    <td><?= $vatTu['ngay_tao'] ?></td>
                    <td><?= $vatTu['ten_loai_vat_tu'] ?></td>
                    <td>
                        <a href="../controllers/delete_material.php?id=<?= $vatTu['ma_vat_tu'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>

                        <a href="suasv.php?ID=<?= $vatTu['ma_vat_tu'] ?>">Sửa</a>
                    </td>
                </tr>
                <?php } ?>

            </tbody>
        </table>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Thêm Vật Tư</h2>
            <form id="materialForm" action="../controllers/MaterialController.php" method="POST">
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
                    <label for="loai_vat_tu">Loại Vật Tư:</label>
                    <select id="loai_vat_tu" name="loai_vat_tu" required>
                        <?php foreach ($loaiVatTu as $loai) { ?>
                        <option value="<?= $loai['ma_loai_vat_tu'] ?>">
                            <?= $loai['ten_loai_vat_tu'] ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>
    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Vật Tư</h2>
            <form id="editMaterialForm" action="../controllers/MaterialController.php" method="POST">
                <input type="hidden" id="edit_ma_vat_tu" name="ma_vat_tu">

                <div class="form-group">
                    <label for="edit_ten_vat_tu">Tên Vật tư:</label>
                    <input type="text" id="edit_ten_vat_tu" name="ten_vat_tu" required>
                </div>

                <div class="form-group">
                    <label for="edit_mo_ta">Mô tả:</label>
                    <textarea id="edit_mo_ta" name="mo_ta" required></textarea>
                </div>

                <div class="form-group">
                    <label for="edit_don_vi">Đơn vị:</label>
                    <input type="text" id="edit_don_vi" name="don_vi" required>
                </div>

                <div class="form-group">
                    <label for="edit_gia">Giá:</label>
                    <input type="number" id="edit_gia" name="gia" required>
                </div>

                <div class="form-group">
                    <label for="edit_ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="edit_ma_nha_cung_cap" name="ma_nha_cung_cap" required>
                </div>

                <div class="form-group">
                    <label for="edit_so_luong_toi_thieu">Số lượng tối thiểu:</label>
                    <input type="number" id="edit_so_luong_toi_thieu" name="so_luong_toi_thieu" required>
                </div>

                <div class="form-group">
                    <label for="edit_so_luong_ton">Số lượng tồn:</label>
                    <input type="number" id="edit_so_luong_ton" name="so_luong_ton" required>
                </div>

                <div class="form-group">
                    <label for="edit_loai_vat_tu">Loại Vật Tư:</label>
                    <select id="edit_loai_vat_tu" name="loai_vat_tu" required>
                        <?php foreach ($loaiVatTu as $loai) { ?>
                        <option value="<?= $loai['ma_loai_vat_tu'] ?>">
                            <?= $loai['ten_loai_vat_tu'] ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>

                <button type="submit" class="btn-submit">Cập nhật</button>
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
    </script>
</body>