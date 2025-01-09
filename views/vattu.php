<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
// views/index.php
require_once '../controllers/MainController.php';
require_once '../models/db.php';

$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
$maNhaCungCap = $controller->getMaNhaCungCap();

$url = "http://localhost/quanlyvattu/controllers/VatTu_api.php";
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
        <form id="searchForm" method="POST">
            <div class="search-bar">
                <input type="text" id="txtTimKiem" placeholder="Tìm kiếm vật tư..." name="txtTimKiem">
                <button type="button" class="btn-search" id="btnSearch">Tìm kiếm</button>
                <select name="loai-vat-tu" id="loai-vat-tu">
                    <option value="all">Tất cả loại vật tư</option>
                    <?php foreach ($loaiVatTu as $loai) { ?>
                        <option value="<?= $loai['ma_loai_vat_tu']  ?>">
                            <?= $loai['ten_loai_vat_tu'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>

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
                    <th>Số lượng</th>
                    <th>Ngày tạo</th>
                    <th>Tên loại</th>
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="vatTuTableBody">
                <?php foreach ($data['data'] as $vatTu) { ?>
                    <tr>
                        <td><?= $vatTu['ma_vat_tu'] ?></td>
                        <td><?= $vatTu['ten_vat_tu'] ?></td>
                        <td><?= $vatTu['mo_ta'] ?></td>
                        <td><?= $vatTu['don_vi'] ?></td>
                        <td><?= $vatTu['gia'] ?></td>
                        <td><?= $vatTu['ma_nha_cung_cap'] ?></td>
                        <td><?= $vatTu['so_luong'] ?></td>
                        <td><?= $vatTu['ngay_tao'] ?></td>
                        <td><?= $vatTu['ten_loai_vat_tu'] ?></td>
                        <td style="border-right: none;">
                            <a href="#" class="xoa" data-id="<?= $vatTu['ma_vat_tu'] ?>" onclick="deleteVatTu(event, <?= $vatTu['ma_vat_tu'] ?>)">
                                <i class='bx bx-trash-alt'></i>
                            </a>
                            <a id="btnOpenModalEdit" onclick="openEditModal('<?= $vatTu['ma_vat_tu'] ?>')" class="sua">
                                <i class='bx bx-edit'></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>




    <!-- Modal tim kiem  -->
    <script>
        document.getElementById('btnSearch').addEventListener('click', function() {
            var keyword = document.getElementById('txtTimKiem').value;
            fetch(`http://localhost/quanlyvattu/controllers/VatTu_api.php?keyword=${keyword}`)
                .then(response => response.json())
                .then(data => {
                    var vatTuTableBody = document.getElementById('vatTuTableBody');
                    vatTuTableBody.innerHTML = '';
                    if (data.data) {
                        data.data.forEach(vatTu => {
                            var row = `
                                <tr>
                                    <td>${vatTu.ma_vat_tu}</td>
                                    <td>${vatTu.ten_vat_tu}</td>
                                    <td>${vatTu.mo_ta}</td>
                                    <td>${vatTu.don_vi}</td>
                                    <td>${vatTu.gia}</td>
                                    <td>${vatTu.ma_nha_cung_cap}</td>
                                    <td>${vatTu.so_luong}</td>
                                    <td>${vatTu.ngay_tao}</td>
                                    <td>${vatTu.ten_loai_vat_tu}</td>
                                    <td style="border-right: none;">
                                        <a href="#" class="xoa" data-id="${vatTu.ma_vat_tu}" onclick="deleteVatTu(event, ${vatTu.ma_vat_tu})">
                                            <i class='bx bx-trash-alt'></i>
                                        </a>
                                        <a id="btnOpenModalEdit" onclick="openEditModal('${vatTu.ma_vat_tu}')" class="sua">
                                            <i class='bx bx-edit'></i>
                                        </a>
                                    </td>
                                </tr>
                            `;
                            vatTuTableBody.innerHTML += row;
                        });
                    } else {
                        vatTuTableBody.innerHTML = '<tr><td colspan="10">Không tìm thấy vật tư</td></tr>';
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        });
    </script>


    <!-- Modal thêm -->
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
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <select id="ma_nha_cung_cap" name="ma_nha_cung_cap" required>
                        <?php foreach ($maNhaCungCap as $mncc) { ?>
                            <option value="<?= $mncc['ma_nha_cung_cap'] ?>">
                                <?= $mncc['ma_nha_cung_cap'] . ' - ' . $mncc['ten_nha_cung_cap'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="so_luong">Số lượng:</label>
                    <input type="number" id="so_luong" name="so_luong" placeholder="Nhập số lượng..." required>
                </div>
                <div class="form-group">
                    <label for="loai_vat_tu">Loại Vật Tư:</label>
                    <select id="loai_vat_tu" name="ma_loai_vat_tu" required>
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

    <!-- script thêm -->

    <script>
        // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
        $("#materialForm").on("submit", function(event) {
            event.preventDefault(); // Ngừng submit mặc định của form

            // Lấy dữ liệu từ form
            var materialData = {
                ma_vat_tu: $("#ma_vat_tu").val(),
                ten_vat_tu: $("#ten_vat_tu").val(),
                mo_ta: $("#mo_ta").val(),
                don_vi: $("#don_vi").val(),
                gia: $("#gia").val(),
                ma_nha_cung_cap: $("#ma_nha_cung_cap").val(),
                so_luong: $("#so_luong").val(),
                ma_loai_vat_tu: $("#loai_vat_tu").val()
            };

            // Kiểm tra nếu có trường nào trống
            if (!materialData.ma_vat_tu || !materialData.ten_vat_tu || !materialData.gia) {
                $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc.</p>');
                return;
            }

            // Gửi yêu cầu POST đến API
            $.ajax({
                url: 'http://localhost/quanlyvattu/controllers/create.php', // Địa chỉ của API
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(materialData),
                success: function(response) {
                    // Kiểm tra nếu phản hồi thành công
                    if (response && response.status === 201) {
                        alert("Thêm vật tư thành công!");

                        // Đóng modal
                        $("#modal").hide();

                        // Reset form
                        $("#materialForm")[0].reset();

                        // Reload trang sau 2 giây để cập nhật dữ liệu
                        setTimeout(function() {
                            window.location.reload();
                        }, 2);
                    } else if (response && response.status === 409) {
                        alert("Mã vật tư đã tồn tại. Vui lòng nhập mã khác.");
                    } else {
                        // Hiển thị thông báo lỗi nếu không có status 201
                        $("#responseMessage").html(
                            `<p style="color: red;">Lỗi: ${response.message || 'Không xác định lỗi'}</p>`
                        );
                    }
                },
                error: function(xhr, status, error) {
                    // Xử lý lỗi từ phía server
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                        "Đã có lỗi xảy ra.";
                    $("#responseMessage").html(`<p style="color: red;">Lỗi: ${errorMessage}</p>`);
                }
            });

            // Gửi yêu cầu POST đến API
            $.ajax({
                url: 'http://localhost/quanlyvattu/controllers/VatTu_api.php', // Địa chỉ của API
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(materialData),
                success: function(response) {
                    // Kiểm tra nếu phản hồi thành công
                    if (response && response.status === 201) {
                        alert("Thêm vật tư thành công!");
                        // Đóng modal
                        $("#modal").hide();
                        // Reset form
                        $("#materialForm")[0].reset();
                        // Reload trang sau 2 giây để cập nhật dữ liệu
                        setTimeout(function() {
                            window.location.reload();
                        }, 2);
                    } else if (response && response.status === 409) {
                        alert("Mã vật tư đã tồn tại. Vui lòng nhập mã khác.");
                    } else {
                        // Hiển thị thông báo lỗi nếu không có status 201
                        $("#responseMessage").html(
                            `<p style="color: red;">Lỗi: ${response.message || 'Không xác định lỗi'}</p>`
                        );
                    }
                },
                error: function(xhr, status, error) {
                    // Xử lý lỗi từ phía server
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                        "Đã có lỗi xảy ra.";
                    $("#responseMessage").html(`<p style="color: red;">Lỗi: ${errorMessage}</p>`);
                }
            });

            // Đóng modal khi nhấn vào nút "Đóng"
            $("#btnCloseModal").click(function() {
                $("#modal").hide();
            });
        });
    </script>







    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Vật Tư</h2>
            <form id="editMaterialForm">
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
                    <input type="number" id="gia_sua" name="gia_sua" placeholder="Nhập giá..." required>
                </div>
                <div class="form-group">
                    <label for="so_luong_sua">Số lượng:</label>
                    <input type="number" id="so_luong_sua" name="so_luong_sua" placeholder="Nhập số lượng..." required>
                </div>
                <div class="form-group">
                    <label for="ma_nha_cung_cap_sua">Mã Nhà Cung Cấp:</label>
                    <select id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua" required>
                        <?php foreach ($maNhaCungCap as $mncc) { ?>
                            <option value="<?= $mncc['ma_nha_cung_cap'] ?>">
                                <?= $mncc['ma_nha_cung_cap'] . ' - ' . $mncc['ten_nha_cung_cap'] ?>
                            </option>
                        <?php } ?>
                    </select>
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


    <!-- script sửa -->

    <script>
        // Lắng nghe sự kiện submit của form sửa vật tư
        document.getElementById("editMaterialForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Ngừng gửi form theo cách truyền thống

            // Tạo đối tượng FormData từ form
            var formData = new FormData(this);

            // Chuyển form data thành JSON
            var formJSON = {};
            formData.forEach((value, key) => {
                formJSON[key] = value;
            });

            // Gửi yêu cầu PUT đến API để cập nhật vật tư
            fetch("http://localhost/quanlyvattu/controllers/VatTu_api.php", {
                    method: "PUT",
                    body: JSON.stringify(formJSON),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 200) {
                        alert(data.message);
                        document.getElementById('modalEdit').style.display = 'none';
                        window.location.href = "http://localhost/quanlyvattu/index.php";
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gửi yêu cầu:', error);
                    alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
                });
        });

        // Đóng modal khi nhấn nút đóng
        document.getElementById("btnCloseModalEdit").addEventListener("click", function() {
            document.getElementById("modalEdit").style.display = 'none';
        });
    </script>






    <!-- script xóa -->
    <!-- script xóa -->
    <script>
        // Lắng nghe sự kiện nhấp vào nút xóa vật tư
        document.querySelectorAll('.xoa').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault();

                var maVatTu = this.getAttribute('data-id');

                if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) {
                    fetch("http://localhost/quanlyvattu/controllers/VatTu_api.php", {
                            method: "DELETE",
                            body: JSON.stringify({
                                ma_vat_tu: maVatTu
                            }),
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 200) {
                                alert(data.message);
                                window.location.href = "http://localhost/quanlyvattu/index.php";
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi khi gửi yêu cầu:', error);
                            alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
                        });
                }
            });
        });

        // Đóng modal khi nhấn nút đóng
        document.getElementById("btnCloseModalEdit").addEventListener("click", function() {
            document.getElementById("modalEdit").style.display = 'none';
        });

        document.getElementById("btnCloseModalDelete").addEventListener("click", function() {
            document.getElementById("modalDelete").style.display = 'none';
        });
    </script>

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
            document.getElementById("so_luong_toi_thieu_sua").value = row.cells[6].innerText;
            document.getElementById("so_luong_ton_sua").value = row.cells[7].innerText;
        }
    </script>
</body>