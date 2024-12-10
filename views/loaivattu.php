<!-- Banner -->
<?php
require_once '../controllers/MainController.php';
$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
$ok = '';
$danhSachVatTu = $controller->getDanhSachVatTu($ok);
?>

<body>
    <!--  -->
    <!-- Nội dung chính -->
    <main class="content">
        <!-- Thanh tìm kiếm -->
        <form method="POST">
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm vật tư..." name="txtTimKiem">
                <button class="btn-search">Tìm kiếm</button>
                <!-- <select name="loai-vat-tu" id="loai-vat-tu">
                <option value="all">Tất cả loại vật tư</option>
                <?php foreach ($loaiVatTu as $loai) { ?>
                <option value="<?= $loai['ma_loai_vat_tu'] ?>">
                    <?= $loai['ten_loai_vat_tu'] ?>
                </option>
                <?php } ?>
            </select> -->
            </div>
        </form>

        <!-- Bảng danh sách vật tư -->
        <h2>Danh Sách Vật Tư</h2>
        <button class="btn-add" id="btnOpenModal">Thêm loại vật tư</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã loại vật tư</th>
                    <th>Tên loại vật tư</th>
                    <th>Mô tả</th>
                    <th>Ngày tạo</th>
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loaiVatTu as $vatTu) { ?>
                <tr>
                    <td><?= $vatTu['ma_loai_vat_tu'] ?></td>
                    <td><?= $vatTu['ten_loai_vat_tu'] ?></td>
                    <td><?= $vatTu['mo_taa'] ?></td>
                    <td><?= $vatTu['ngay_tao'] ?></td>
                    <td style="border-right: none;">
                        <a class="xoa"
                            href="../controllers/MaterialController.php?action=deleteLoaiVatTu&id=<?= $vatTu['ma_loai_vat_tu'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa?')"><i class='bx bx-trash-alt'></i></a>

                        <a class="sua" href="suasv.php?ID=<?= $vatTu['ma_loai_vat_tu'] ?>"><i
                                class='bx bx-edit'></i></a>
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
            <form id="materialForm" action="../controllers/MaterialController.php?action=themLoaiVatTu" method="POST">
                <div class="form-group">
                    <label for="ma_loai_vat_tu">Mã loại vật tư:</label>
                    <input type="text" id="ma_loai_vat_tu" name="ma_loai_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="ten_loai_vat_tu">Tên loại vật tư:</label>
                    <input type="text" id="ten_loai_vat_tu" name="ten_loai_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <input type="text" id="mo_ta" name="mo_ta" required>
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>
    <script>
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
    </script>
</body>