<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
</head>
<?php
// views/index.php
require_once '../controllers/MainController.php';
require_once '../models/db.php';

$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
$maNhaCungCap = $controller->getMaNhaCungCap();
// if ($_SERVER['REQUEST_METHOD'] == "POST"){
//     $keyword = $_POST['txtTimKiem'];
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }else{
//     $keyword = '';
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }
$url = "http://localhost/quanlyvattu/controllers/read.php";
// Gửi yêu cầu GET để lấy dữ liệu từ API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);


$data = json_decode($response, true);

?>

<body>

    <!-- Banner -->


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
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['data'] as $vatTu) { ?>
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
                    <td><?= $vatTu['ma_loai_vat_tu'] ?></td>
                    <td style="border-right: none;">
                        <a class="xoa"
                            href="../controllers/MaterialController.php?action=deleteVatTu&id=<?= $vatTu['ma_vat_tu'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa?')"><i class='bx bx-trash-alt'></i></a>

                        <a id="btnOpenModalEdit" onclick="openEditModal('<?= $vatTu['ma_vat_tu'] ?>')" class="sua"><i
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
            <form id="materialForm" method="POST">
                <div class="form-group">
                    <label for="ma_vat_tu">Mã Vật tư:</label>
                    <input type="text" id="ma_vat_tu" name="ma_vat_tu" placeholder="Nhập mã vật tư..." required>
                </div>
                <div class="form-group">
                    <label for="ten_vat_tu">Tên Vật tư:</label>
                    <input type="text" id="ten_vat_tu" name="ten_vat_tu" placeholder="Nhập tên vật tư..." required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <input type="text" id="mo_ta" name="mo_ta" placeholder="Nhập mô tả..." required>
                </div>
                <div class="form-group">
                    <label for="don_vi">Đơn vị:</label>
                    <input type="text" id="don_vi" name="don_vi" placeholder="Nhập đơn vị..." required>
                </div>
                <div class="form-group">
                    <label for="gia">Giá:</label>
                    <input type="number" id="gia" name="gia" placeholder="Nhập giá..." required>
                </div>
                <!-- <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap" name="ma_nha_cung_cap" placeholder="Nhập mã nhà cung cấp..."
                        required>
                </div> -->
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <select id="ma_nha_cung_cap" name="ma_nha_cung_cap" required>
                        <?php foreach ($maNhaCungCap as $mncc) { ?>
                        <option value="<?= $mncc['ma_nha_cung_cap'] ?>">
                            <?= $mncc['ma_nha_cung_cap'] .' - ' . $mncc['ten_nha_cung_cap']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="so_luong_toi_thieu">Số lượng Tối thiểu:</label>
                    <input type="number" id="so_luong_toi_thieu" name="so_luong_toi_thieu"
                        placeholder="Nhập số lượng tối thiểu..." required>
                </div>
                <div class="form-group">
                    <label for="so_luong_ton">Số lượng Tồn:</label>
                    <input type="number" id="so_luong_ton" name="so_luong_ton" placeholder="Nhập số lượng tồn..."
                        required>
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
            <form id="editMaterialForm" action="../controllers/MaterialController.php?action=suaVatTu" method="POST">
                <input type="hidden" id="edit_ma_vat_tu" name="ma_vat_tu">
                <div class="form-group">
                    <label for="ma_vat_tu">Mã Vật tư:</label>
                    <input type="text" id="ma_vat_tu_sua" name="ma_vat_tu_sua" placeholder="Nhập mã vật tư..." readonly>
                </div>
                <div class="form-group">
                    <label for="ten_vat_tu">Tên Vật tư:</label>
                    <input type="text" id="ten_vat_tu_sua" name="ten_vat_tu_sua" placeholder="Nhập tên vật tư..."
                        required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <input type="text" id="mo_ta_sua" name="mo_ta_sua" placeholder="Nhập mô tả..." required>
                </div>
                <div class="form-group">
                    <label for="don_vi">Đơn vị:</label>
                    <input type="text" id="don_vi_sua" name="don_vi_sua" placeholder="Nhập đơn vị..." required>
                </div>
                <div class="form-group">
                    <label for="gia">Giá:</label>
                    <input type="number_format" id="gia_sua" name="gia_sua" placeholder="Nhập giá..." required>
                </div>
                <!-- <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua"
                        placeholder="Nhập mã nhà cung cấp..." required>
                </div> -->
                <div class="form-group">
                    <label for="ma_nha_cung_cap_sua">Mã Nhà Cung Cấp:</label>
                    <select id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua" required>
                        <?php foreach ($maNhaCungCap as $mncc) { ?>
                        <option value="<?= $mncc['ma_nha_cung_cap'] ?>">
                            <?= $mncc['ma_nha_cung_cap'] .' - ' . $mncc['ten_nha_cung_cap']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="so_luong_toi_thieu">Số lượng Tối thiểu:</label>
                    <input type="number" id="so_luong_toi_thieu_sua" name="so_luong_toi_thieu_sua"
                        placeholder="Nhập số lượng tối thiểu..." required>
                </div>
                <div class="form-group">
                    <label for="so_luong_ton_sua">Số lượng Tồn:</label>
                    <input type="number" id="so_luong_ton_sua" name="so_luong_ton_sua"
                        placeholder="Nhập số lượng tồn..." required>
                </div>

                <div class="form-group">
                    <label for="edit_loai_vat_tu">Loại Vật Tư:</label>
                    <select id="edit_loai_vat_tu" name="loai_vat_tu_sua" required>
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
    const modalEdit = document.getElementById("modalEdit");
    const btnOpenModalEdit = document.querySelectorAll("#btnOpenModalEdit");
    const btnCloseModalEdit = document.getElementById("btnCloseModalEdit");

    for (const btn of btnOpenModalEdit) {
        btn.addEventListener("click", () => {
            modalEdit.style.display = "flex";
        });
    }
    btnCloseModalEdit.addEventListener("click", () => {
        modalEdit.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modalEdit) {
            modalEdit.style.display = "none";
        }
    });

    // Xử lý form thêm vật tư
    const form = document.getElementById("materialForm");
    const tableBody = document.querySelector(".table tbody");

    // Sửa vật tư
    function openEditModal(id) {
        // Hiển thị modal sửa
        const modalEdit = document.getElementById("modalEdit");
        modalEdit.style.display = "flex";

        // Gán ID vào input hidden
        document.getElementById("edit_ma_vat_tu").value = id;

        // Lấy dữ liệu từ bảng và điền vào modal edit
        const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
        document.getElementById("ma_vat_tu_sua").value = row.cells[0].innerText;
        document.getElementById("ten_vat_tu_sua").value = row.cells[1].innerText;
        document.getElementById("mo_ta_sua").value = row.cells[2].innerText;
        document.getElementById("don_vi_sua").value = row.cells[3].innerText;
        document.getElementById("gia_sua").value = row.cells[4].innerText;
        document.getElementById("ma_nha_cung_cap_sua").value = row.cells[5].innerText;
        document.getElementById("so_luong_toi_thieu_sua").value = row.cells[6].innerText;
        document.getElementById("so_luong_ton_sua").value = row.cells[7].innerText;
    }
    </script>
</body>