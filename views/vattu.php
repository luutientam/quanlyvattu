<head>
    <meta charset="UTF-8"> <!-- Thiết lập mã hóa ký tự UTF-8 để hỗ trợ tiếng Việt và các ký tự đặc biệt -->

    <link rel="stylesheet" href="../Css/style.css?v=1.0"> <!-- Liên kết đến tệp CSS để định dạng giao diện -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Liên kết đến thư viện jQuery để hỗ trợ các thao tác JavaScript -->
</head>

<?php
// views/index.php

// Kết nối đến Controller và Model để lấy dữ liệu từ API
require_once '../controllers/MainController.php';
require_once '../models/db.php';

// Gửi yêu cầu GET tới API để lấy dữ liệu loại vật tư
$url = "http://localhost/quanlyvattu/controllers\LoaiVatTu_api.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Cấu hình trả về kết quả của API
$response = curl_exec($ch); // Thực thi yêu cầu GET
curl_close($ch); // Đóng kết nối
$dataLoaiVatTu = json_decode($response, true); // Giải mã dữ liệu JSON từ API

// Gửi yêu cầu GET tới API để lấy dữ liệu nhà cung cấp
$url = "http://localhost/quanlyvattu/controllers/NhaCungCap_api.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$dataNCC = json_decode($response, true);

// Gửi yêu cầu GET tới API để lấy dữ liệu vật tư
$url = "http://localhost/quanlyvattu/controllers/VatTu_api.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
?>

<body>
    <!-- Nội dung chính -->
    <main class="content">
        <!-- Form tìm kiếm vật tư -->
        <form id="searchForm" method="POST">
            <div class="search-bar">
                <input type="text" id="txtTimKiem" placeholder="Tìm kiếm vật tư..." name="txtTimKiem">
                <!-- Trường nhập từ khóa tìm kiếm -->
                <button type="button" class="btn-search" id="btnSearch">Tìm kiếm</button> <!-- Nút tìm kiếm -->
                <select name="loai-vat-tu" id="loai-vat-tu">
                    <!-- Dropdown chọn loại vật tư -->
                    <option value="all">Tất cả loại vật tư</option> <!-- Tùy chọn tất cả loại vật tư -->
                    <?php foreach ($dataLoaiVatTu['data'] as $loai) { ?>
                    <!-- Lặp qua các loại vật tư -->
                    <option value="<?= $loai['ma_loai_vat_tu'] ?>">
                        <?= $loai['ten_loai_vat_tu'] ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <h2>Danh Sách Vật Tư</h2>
        <!-- Nút mở modal để thêm vật tư -->
        <button class="btn-add" id="btnOpenModal">Thêm Vật Tư</button>

        <!-- Bảng danh sách vật tư -->
        <table class="table">
            <thead>
                <tr>
                    <!-- Các tiêu đề của bảng -->
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
                <!-- Lặp qua từng vật tư và hiển thị trong bảng -->
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
                        <!-- Thao tác xóa và sửa vật tư -->
                        <a href="#" class="xoa" data-id="<?= $vatTu['ma_vat_tu'] ?>"
                            onclick="deleteVatTu(event, <?= $vatTu['ma_vat_tu'] ?>)">
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

    <!-- Script tìm kiếm vật tư -->
    <script>
    document.getElementById('btnSearch').addEventListener('click', function() {
        var keyword = document.getElementById('txtTimKiem').value.trim(); // Lấy từ khóa tìm kiếm
        var keywordLoaiVatTu = document.getElementById('loai-vat-tu').value; // Lấy loại vật tư

        // Tạo URL với các tham số tìm kiếm
        var url =
            `http://localhost/quanlyvattu/controllers/VatTu_api.php?keyword=${encodeURIComponent(keyword)}&ma_loai_vat_tu=${encodeURIComponent(keywordLoaiVatTu)}`;

        // Gửi yêu cầu fetch để lấy dữ liệu vật tư từ API
        fetch(url)
            .then(response => response.json()) // Chuyển dữ liệu nhận được thành JSON
            .then(data => {
                var vatTuTableBody = document.getElementById('vatTuTableBody');
                vatTuTableBody.innerHTML = ''; // Xóa các hàng cũ trong bảng

                if (data.data && data.data.length > 0) { // Kiểm tra nếu có dữ liệu
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
                        vatTuTableBody.innerHTML += row; // Thêm một dòng mới vào bảng
                    });
                } else {
                    vatTuTableBody.innerHTML =
                        '<tr><td colspan="10">Không tìm thấy vật tư</td></tr>'; // Hiển thị khi không tìm thấy vật tư
                }
            })
            .catch(error => console.error('Lỗi:', error)); // Xử lý lỗi nếu có
    });
    </script>

    <!-- Modal thêm vật tư -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Thêm Vật Tư</h2>

            <!-- Form thêm vật tư -->
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
                        <?php foreach ($dataNCC['data'] as $mncc) { ?>
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
                    <select id="ma_loai_vat_tu" name="ma_loai_vat_tu" required>
                        <?php foreach ($dataLoaiVatTu['data']  as $loai) { ?>
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
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Vật Tư</h2>

            <!-- Form thêm vật tư -->
            <form id="editMaterialForm" method="POST">
                <div class="form-group">
                    <label for="ma_vat_tu">Mã Vật tư:</label>
                    <input type="text" id="edit_ma_vat_tu" name="ma_vat_tu_sua" placeholder="Nhập mã vật tư..."
                        readonly>
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
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <select id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua" required>
                        <?php foreach ($dataNCC['data'] as $mncc) { ?>
                        <option value="<?= $mncc['ma_nha_cung_cap'] ?>">
                            <?= $mncc['ma_nha_cung_cap'] . ' - ' . $mncc['ten_nha_cung_cap'] ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="so_luong">Số lượng:</label>
                    <input type="number" id="so_luong_sua" name="so_luong_sua" placeholder="Nhập số lượng..." required>
                </div>
                <div class="form-group">
                    <label for="loai_vat_tu">Loại Vật Tư:</label>
                    <select id="ma_loai_vat_tu_sua" name="ma_loai_vat_tu_sua" required>
                        <?php foreach ($dataLoaiVatTu['data']  as $loai) { ?>
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

    <!-- Script thêm vật tư -->
    <script>
    // Sự kiện khi người dùng gửi form thêm vật tư
    $("#materialForm").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định

        // Lấy dữ liệu từ form
        var materialData = {
            ma_vat_tu: $("#ma_vat_tu").val(),
            ten_vat_tu: $("#ten_vat_tu").val(),
            mo_ta: $("#mo_ta").val(),
            don_vi: $("#don_vi").val(),
            gia: $("#gia").val(),
            so_luong: $("#so_luong").val(),
            ma_nha_cung_cap: $("#ma_nha_cung_cap").val(),
            ma_loai_vat_tu: $("#ma_loai_vat_tu").val()
        };

        // Gửi yêu cầu POST tới API để thêm vật tư
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/VatTu_api.php',
            method: 'POST',
            data: materialData,
            success: function(response) {
                alert('Thêm vật tư thành công!');
                location.reload(); // Tải lại trang sau khi thêm vật tư
            },
            error: function(error) {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });
    });
    </script>
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
            formJSON[key] = value; // Tạo một đối tượng JSON từ FormData
        });

        // Gửi yêu cầu PUT đến API để cập nhật vật tư
        fetch("http://localhost/quanlyvattu/controllers/VatTu_api.php", {
                method: "PUT", // Sử dụng phương thức PUT để cập nhật dữ liệu
                body: JSON.stringify(formJSON), // Chuyển đối tượng JSON thành chuỗi
                headers: {
                    'Content-Type': 'application/json' // Chỉ định rằng body là JSON
                }
            })
            .then(response => response.json()) // Xử lý dữ liệu trả về từ server
            .then(data => {
                if (data.status === 200) { // Kiểm tra xem yêu cầu có thành công không
                    alert(data.message); // Thông báo nếu thành công
                    document.getElementById('modalEdit').style.display = 'none'; // Đóng modal sửa
                    window.location.href =
                        "http://localhost/quanlyvattu/index.php"; // Chuyển hướng trang sau khi cập nhật
                } else {
                    alert(data.message); // Thông báo lỗi nếu yêu cầu không thành công
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi yêu cầu:', error); // Hiển thị lỗi trong console
                alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.'); // Thông báo lỗi cho người dùng
            });
    });

    // Đóng modal khi nhấn nút đóng
    document.getElementById("btnCloseModalEdit").addEventListener("click", function() {
        document.getElementById("modalEdit").style.display = 'none'; // Đóng modal sửa
    });
    </script>

    <!-- script xóa -->
    <script>
    // Lắng nghe sự kiện nhấp vào nút xóa vật tư
    document.querySelectorAll('.xoa').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault(); // Ngừng hành động mặc định của thẻ a

            var maVatTu = this.getAttribute('data-id'); // Lấy giá trị id vật tư từ thuộc tính data-id

            if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) { // Hiển thị thông báo xác nhận
                fetch("http://localhost/quanlyvattu/controllers/VatTu_api.php", {
                        method: "DELETE", // Sử dụng phương thức DELETE để xóa dữ liệu
                        body: JSON.stringify({
                            ma_vat_tu: maVatTu // Truyền id vật tư vào body của yêu cầu
                        }),
                        headers: {
                            'Content-Type': 'application/json' // Chỉ định loại nội dung là JSON
                        }
                    })
                    .then(response => response.json()) // Xử lý dữ liệu trả về từ server
                    .then(data => {
                        if (data.status === 200) { // Kiểm tra nếu yêu cầu thành công
                            alert(data.message); // Thông báo thành công
                            window.location.href =
                                "http://localhost/quanlyvattu/index.php"; // Chuyển hướng trang sau khi xóa
                        } else {
                            alert(data.message); // Thông báo lỗi nếu yêu cầu không thành công
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi khi gửi yêu cầu:', error); // Hiển thị lỗi trong console
                        alert(
                            'Đã xảy ra lỗi trong quá trình gửi yêu cầu.'
                        ); // Thông báo lỗi cho người dùng
                    });
            }
        });
    });

    // Đóng modal khi nhấn nút đóng
    document.getElementById("btnCloseModalEdit").addEventListener("click", function() {
        document.getElementById("modalEdit").style.display = 'none'; // Đóng modal sửa
    });

    document.getElementById("btnCloseModalDelete").addEventListener("click", function() {
        document.getElementById("modalDelete").style.display = 'none'; // Đóng modal xóa
    });
    </script>

    <script>
    // Modal xử lý
    const modal = document.getElementById("modal"); // Lấy modal để hiển thị
    const btnOpenModal = document.getElementById("btnOpenModal"); // Lấy nút mở modal
    const btnCloseModal = document.getElementById("btnCloseModal"); // Lấy nút đóng modal

    btnOpenModal.addEventListener("click", () => {
        modal.style.display = "flex"; // Mở modal khi nhấn nút
    });

    btnCloseModal.addEventListener("click", () => {
        modal.style.display = "none"; // Đóng modal khi nhấn nút đóng
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none"; // Đóng modal khi nhấn ra ngoài modal
        }
    });

    const modalEdit = document.getElementById("modalEdit"); // Lấy modal sửa
    const btnOpenModalEdit = document.querySelectorAll("#btnOpenModalEdit"); // Lấy tất cả nút mở modal sửa
    const btnCloseModalEdit = document.getElementById("btnCloseModalEdit"); // Lấy nút đóng modal sửa

    for (const btn of btnOpenModalEdit) {
        btn.addEventListener("click", () => {
            modalEdit.style.display = "flex"; // Mở modal sửa khi nhấn nút mở
        });
    }
    btnCloseModalEdit.addEventListener("click", () => {
        modalEdit.style.display = "none"; // Đóng modal sửa khi nhấn nút đóng
    });

    window.addEventListener("click", (e) => {
        if (e.target === modalEdit) {
            modalEdit.style.display = "none"; // Đóng modal sửa khi nhấn ra ngoài modal
        }
    });

    // Xử lý form thêm vật tư
    const form = document.getElementById("materialForm"); // Lấy form thêm vật tư
    const tableBody = document.querySelector(".table tbody"); // Lấy phần thân bảng chứa dữ liệu vật tư

    // Sửa vật tư
    function openEditModal(id) {
        // Hiển thị modal sửa
        const modalEdit = document.getElementById("modalEdit");
        modalEdit.style.display = "flex"; // Hiển thị modal sửa

        // Gán ID vào input hidden để gửi khi sửa
        document.getElementById("edit_ma_vat_tu").value = id;

        // Lấy dữ liệu từ bảng và điền vào modal edit
        const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
        //document.getElementById("ma_vat_tu_sua").value = row.cells[0].innerText;
        document.getElementById("ten_vat_tu_sua").value = row.cells[1].innerText;
        document.getElementById("mo_ta_sua").value = row.cells[2].innerText;
        document.getElementById("don_vi_sua").value = row.cells[3].innerText;
        document.getElementById("gia_sua").value = row.cells[4].innerText;
        document.getElementById("so_luong_sua").value = row.cells[6].innerText;
    }
    </script>
</body>