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
$url = "http://localhost/quanlyvattu/controllers/NhaCungCap_api.php";
// Gửi yêu cầu GET để lấy dữ liệu từ API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$dataNCC = json_decode($response, true);

if (!isset($data['data'])) {
    $data['data'] = [];
}
?>


<body>
    <main class="content">
        <form method="POST">
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm nhà cung cấp..." name="txtTimKiem">
                <button class="btn-search">Tìm kiếm</button>

            </div>
        </form>

        <h2>Danh Sách Nhà Cung Cấp</h2>
        <button class="btn-add" id="btnOpenModal">Thêm Nhà Cung Cấp</button>

        <table class="table">
            <thead>
                <tr>
                    <th>Mã nhà cung cấp</th>
                    <th>Tên nhà cung cấp</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Ngày tạo</th>
                    <th style="border-right: none;">Thao tác</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataNCC['data'] as $ncc) { ?>
                <tr>
                    <td><?= $ncc['ma_nha_cung_cap'] ?></td>
                    <td><?= $ncc['ten_nha_cung_cap'] ?></td>
                    <td><?= $ncc['so_dien_thoai'] ?></td>
                    <td><?= $ncc['email'] ?></td>
                    <td><?= $ncc['dia_chi'] ?></td>
                    <td><?= $ncc['ngay_tao'] ?></td>
                    <td style="border-right: none;">
                        <a href="#" class="xoa" data-id="<?= $ncc['ma_nha_cung_cap'] ?>"
                            onclick="deleteNhaCungCap(event, <?= $ncc['ma_nha_cung_cap'] ?>)">
                            <i class='bx bx-trash-alt'></i>
                        </a>
                        <a id="btnOpenModalEdit" onclick="openEditModal('<?= $ncc['ma_nha_cung_cap'] ?>')" class="sua">
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
        var keyword = document.getElementById('txtTimKiem').value.trim();
        var keywordnhaCungCap = document.getElementById('nha-cung-cap').value;

        // Tạo URL với cả từ khóa và loại nhà cung cấp
        var url =
            `http://localhost/quanlyvattu/controllers/NhaCungCap_api.php?keyword=${encodeURIComponent(keyword)}&ma_nha_cung_cap=${encodeURIComponent(keywordnhaCungCap)}`;

        // Gửi yêu cầu fetch
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var nhaCungCapTableBody = document.getElementById('nhaCungCapTableBody');
                nhaCungCapTableBody.innerHTML = ''; // Xóa dữ liệu cũ trong bảng

                if (data.data && data.data.length > 0) {
                    data.data.forEach(nhaCungCap => {
                        var row = `
                        <tr>
                            <td>${nhaCungCap.ma_nha_cung_cap}</td>
                            <td>${nhaCungCap.ten_nha_cung_cap}</td>
                            <td>${nhaCungCap.so_dien_thoai}</td>
                            <td>${nhaCungCap.email}</td>
                            <td>${nhaCungCap.dia_chi}</td>
                            <td>${nhaCungCap.ngay_tao}</td>
                            <td>${nhaCungCap.ten_loai_vat_tu}</td>
                            <td style="border-right: none;">
                                <a href="#" class="xoa" data-id="${nhaCungCap.ma_nha_cung_cap}" onclick="deleteVatTu(event, ${nhaCungCap.ma_nha_cung_cap})">
                                    <i class='bx bx-trash-alt'></i>
                                </a>
                                <a id="btnOpenModalEdit" onclick="openEditModal('${nhaCungCap.ma_nha_cung_cap}')" class="sua">
                                    <i class='bx bx-edit'></i>
                                </a>
                            </td>
                        </tr>
                    `;
                        nhaCungCapTableBody.innerHTML += row;
                    });
                } else {
                    nhaCungCapTableBody.innerHTML =
                        '<tr><td colspan="10">Không tìm thấy nhà cung cấp</td></tr>';
                }
            })
            .catch(error => console.error('Lỗi:', error));
    });
    </script>


    <!-- Modal thêm -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Thêm Nhà Cung Cấp</h2>
            <form id="supplierForm" method="POST">
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap" name="ma_nha_cung_cap" placeholder="Nhập mã nhà cung cấp..."
                        required>
                </div>
                <div class="form-group">
                    <label for="ten_nha_cung_cap">Tên Nhà Cung Cấp:</label>
                    <input type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap"
                        placeholder="Nhập tên nhà cung cấp..." required>
                </div>
                <div class="form-group">
                    <label for="so_dien_thoai">Số Điện Thoại:</label>
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" placeholder="Nhập số điện thoại..."
                        required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Nhập email..." required>
                </div>
                <div class="form-group">
                    <label for="dia_chi">Địa chỉ:</label>
                    <input type="text" id="dia_chi" name="dia_chi" placeholder="Nhập địa chỉ..." required>
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>


    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Nhà Cung Cấp</h2>
            <form id="editsupplierForm">
                <input type="hidden" id="edit_nha_cung_cap" name="ma_nha_cung_cap">
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua"
                        placeholder="Nhập mã nhà cung cấp..." readonly>
                </div>
                <div class="form-group">
                    <label for="ten_nha_cung_cap">Tên Nhà Cung Cấp:</label>
                    <input type="text" id="ten_nha_cung_cap_sua" name="ten_nha_cung_cap_sua"
                        placeholder="Nhập tên nhà cung cấp..." required>
                </div>
                <div class="form-group">
                    <label for="so_dien_thoai">Số Điện Thoại:</label>
                    <input type="text" id="so_dien_thoai_sua" name="so_dien_thoai_sua"
                        placeholder="Nhập số điện thoại..." required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email_sua" name="email_sua" placeholder="Nhập email..." required>
                </div>
                <div class="form-group">
                    <label for="dia_chi">Địa chỉ:</label>
                    <input type="text" id="dia_chi_sua" name="dia_chi_sua" placeholder="Nhập địa chỉ..." required>
                </div>
                <button type="submit" class="btn-submit">Cập nhật</button>
            </form>
        </div>
    </div>


    <script>
    // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
    $("#supplierForm").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định của form
        // Lấy dữ liệu từ form
        // Lấy dữ liệu từ form
        var supplierData = {
            ma_nha_cung_cap: $("#ma_nha_cung_cap").val(),
            ten_nha_cung_cap: $("#ten_nha_cung_cap").val(),
            so_dien_thoai: $("#so_dien_thoai").val(),
            email: $("#email").val(),
            dia_chi: $("#dia_chi").val(),
        };
        // Kiểm tra nếu có trường nào trống
        if (!supplierData.ma_nha_cung_cap || !supplierData.ten_nha_cung_cap || !supplierData.so_dien_thoai) {
            $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc.</p>');
            return;
        }
        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/NhaCungCap_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(supplierData),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 201) {
                    alert("Thêm loại nhà cung cấp thành công!");

                    // Đóng modal
                    $("#modal").hide();

                    // Reset form
                    $("#supplierForm")[0].reset();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2);
                } else if (response && response.status === 409) {
                    alert("Mã loại nhà cung cấp đã tồn tại. Vui lòng nhập mã khác.");
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
    });
    </script>

    <!-- script sửa -->

    <script>
    // Lắng nghe sự kiện submit của form sửa ncc
    document.getElementById("editsupplierForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngừng gửi form theo cách truyền thống

        // Tạo đối tượng FormData từ form
        var formData = new FormData(this);

        // Chuyển form data thành JSON
        var formJSON = {};
        formData.forEach((value, key) => {
            formJSON[key] = value;
        });

        // Gửi yêu cầu PUT đến API để cập nhật nhà cung cấp
        fetch("http://localhost/quanlyvattu/controllers/NhaCungCap_api.php", {
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
    // Lắng nghe sự kiện nhấp vào nút xóa nhà cung cấp
    document.querySelectorAll('.xoa').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();

            var maNhaCungCap = this.getAttribute('data-id');

            if (confirm("Bạn có chắc chắn muốn xóa nhà cung cấp này không?")) {
                fetch("http://localhost/quanlyvattu/controllers/NhaCungCap_api.php", {
                        method: "DELETE",
                        body: JSON.stringify({
                            ma_nha_cung_cap: maNhaCungCap
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

    // Xử lý form thêm ncc
    const form = document.getElementById("supplierForm");
    const tableBody = document.querySelector(".table tbody");

    // Sửa ncc
    function openEditModal(id) {
        // Hiển thị modal sửa
        const modalEdit = document.getElementById("modalEdit");
        modalEdit.style.display = "flex";

        // Gán ID vào input hidden
        document.getElementById("edit_nha_cung_cap").value = id;

        // Lấy dữ liệu từ bảng và điền vào modal edit
        const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');

        // Nếu bạn gặp lỗi, có thể là vấn đề của cách tìm phần tử trong bảng. Thử thay thế bằng cách khác:
        const cells = row.querySelectorAll('td');

        document.getElementById("ma_nha_cung_cap_sua").value = cells[0].innerText;
        document.getElementById("ten_nha_cung_cap_sua").value = cells[1].innerText;
        document.getElementById("so_dien_thoai_sua").value = cells[2].innerText;
        document.getElementById("email_sua").value = cells[3].innerText;
        document.getElementById("dia_chi_sua").value = cells[4].innerText;
    }
    </script>
</body>