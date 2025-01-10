<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
require_once '../controllers/MainController.php';
$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
$ok = '';
$url = "http://localhost/quanlyvattu/controllers/LoaiVatTu_api.php";
// Gửi yêu cầu GET để lấy dữ liệu từ API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
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
                <?php foreach ($data['data'] as $vatTu) { ?>
                <tr>
                    <td><?= $vatTu['ma_loai_vat_tu'] ?></td>
                    <td><?= $vatTu['ten_loai_vat_tu'] ?></td>
                    <td><?= $vatTu['mo_ta'] ?></td>
                    <td><?= $vatTu['ngay_tao'] ?></td>
                    <td style="border-right: none;">
                        <a href="#" class="xoa" data-id="<?= $vatTu['ma_loai_vat_tu'] ?>"
                            onclick="deleteLoaiVatTu(event, <?= $vatTu['ma_loai_vat_tu'] ?>)"><i
                                class='bx bx-trash-alt'></i></a>

                        <a class="sua" onclick="openEditModal('<?= $vatTu['ma_loai_vat_tu'] ?>')"><i
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
            <h2>Thêm loại vật tư</h2>
            <form id="materialForm" method="POST">
                <div class="form-group">
                    <label for="ten_loai_vat_tu">Tên loại vật tư:</label>
                    <input type="text" id="ten_loai_vat_tu" name="ten_loai_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <input type="text" id="mo_ta" name="mo_ta">
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa loại vật tư</h2>
            <form id="editMaterialForm" method="POST">
                <input type="hidden" id="ma_loai_vat_tu_sua" name="ma_loai_vat_tu">
                <div class="form-group">
                    <label for="ten_loai_vat_tu">Tên loại vật tư:</label>
                    <input type="text" id="ten_loai_vat_tu_sua" name="ten_loai_vat_tu" required>
                </div>
                <div class="form-group">
                    <label for="mo_ta">Mô tả:</label>
                    <input type="text" id="mo_ta_sua" name="mo_ta">
                </div>
                <button type="submit" class="btn-submit">Cập nhật loại vật tư</button>
            </form>
        </div>
    </div>
    <script>
    // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
    $("#materialForm").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định của form
        // Lấy dữ liệu từ form
        var materialData = {
            ten_loai_vat_tu: $("#ten_loai_vat_tu").val(),
            mo_ta: $("#mo_ta").val(),
        };

        // Kiểm tra nếu có trường nào trống
        if (!materialData.ten_loai_vat_tu) {
            $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc.</p>');
            return;
        }

        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/LoaiVatTu_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(materialData),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 201) {
                    alert("Thêm loại vật tư thành công!");

                    // Đóng modal
                    $("#modal").hide();

                    // Reset form
                    $("#materialForm")[0].reset();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2);
                } else if (response && response.status === 409) {
                    alert("Mã loại vật tư đã tồn tại. Vui lòng nhập mã khác.");
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

    <script>
    // Lắng nghe sự kiện submit của form sửa vật tư
    document.getElementById("editMaterialForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngừng gửi form theo cách truyền thống

        const formData = new FormData(this);
        const formJSON = {};
        formData.forEach((value, key) => {
            formJSON[key] = value;
        });


        // Gửi yêu cầu PUT đến API để cập nhật vật tư
        fetch("http://localhost/quanlyvattu/controllers/LoaiVatTu_api.php", {
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
                    window.location.href =
                        "http://localhost/quanlyvattu/controllers/index.php?act=loaivattu";
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

    // Lắng nghe sự kiện nhấp vào nút xóa vật tư
    document.querySelectorAll('.xoa').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();

            var maLoaiVatTu = this.getAttribute('data-id');

            if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) {
                fetch("http://localhost/quanlyvattu/controllers/LoaiVatTu_api.php", {
                        method: "DELETE",
                        body: JSON.stringify({
                            ma_loai_vat_tu: maLoaiVatTu
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 200) {
                            alert(data.message);
                            window.location.href =
                                "http://localhost/quanlyvattu/controllers/index.php?act=loaivattu";
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
        document.getElementById("ma_loai_vat_tu_sua").value = id;

        // Lấy dữ liệu từ bảng và điền vào modal edit
        const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
        document.getElementById("ten_loai_vat_tu_sua").value = row.cells[1].innerText;
        document.getElementById("mo_ta_sua").value = row.cells[2].innerText;
    }
    </script>
</body>