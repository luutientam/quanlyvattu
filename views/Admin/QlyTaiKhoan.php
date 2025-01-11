<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </style>
</head>
<?php

$url = "http://localhost/quanlyvattu/controllers/NhanVien_api.php";
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
        <!-- <form id="searchForm" method="POST">
            <div class="search-bar">
                <input type="text" id="txtTimKiem" placeholder="Tìm kiếm nhân viên..." name="txtTimKiem">
                <button type="button" class="btn-search" id="btnSearch">Tìm kiếm</button>
            </div>
        </form> -->

        <!-- Bảng danh sách vật tư -->
        <h2>Danh sách thông tin và tài khoản Nhân viên</h2>
        <button class="btn-add" id="btnOpenModal">Thêm Nhân viên</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã nhân viên</th>
                    <th>Tên nhân viên</th>
                    <th>Email</th>
                    <th>Mã tài khoản</th>
                    <th>Ngày tạo</th>
                    <th>Tên đăng nhập</th>
                    <th>Mật khẩu</th>
                    <th>Loại tài khoản</th>
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="nhanVienTableBody">
                <?php foreach ($data['data'] as $nv) { ?>
                <tr>
                    <td><?= $nv['ma_nhan_vien'] ?></td>
                    <td><?= $nv['ten_nhan_vien'] ?></td>
                    <td><?= $nv['email'] ?></td>
                    <td><?= $nv['ma_tai_khoan'] ?></td>
                    <td><?= $nv['ngay_tao'] ?></td>
                    <td><?= $nv['ten_dang_nhap'] ?></td>
                    <td><?= $nv['mat_khau'] ?></td>
                    <td><?= $nv['loai_tai_khoan'] ?></td>
                    <td style="border-right: none;">
                        <a href="#" class="xoa" data-id="<?= $nv['ma_nhan_vien'] ?>"
                            data-matk="<?= $nv['ma_tai_khoan'] ?>"
                            onclick="deleteLoaiVatTu(event, <?= $nv['ma_nhan_vien'] ?>)"><i
                                class='bx bx-trash-alt'></i></a>

                        <a class="sua" onclick="openEditModal('<?= $nv['ma_nhan_vien'] ?>')"><i
                                class='bx bx-edit'></i></a>

                    </td>

                </tr>
                <?php } ?>

            </tbody>
        </table>
    </main>
    <!-- Modal tim kiem  -->
    <script>
    // document.getElementById('btnSearch').addEventListener('click', function() {
    //     var keyword = document.getElementById('txtTimKiem').value.trim();
    //     var keywordNV = document.getElementById('loai-vat-tu').value;

    //     // Tạo URL với cả từ khóa và loại vật tư
    //     var url =
    //         `http://localhost/quanlyvattu/controllers/NhanVien_api.php?keyword=${encodeURIComponent(keyword)}`;

    //     // Gửi yêu cầu fetch
    //     fetch(url)
    //         .then(response => response.json())
    //         .then(data => {
    //             var nhanVienTableBody = document.getElementById('nhanVienTableBody');
    //             nhanVienTableBody.innerHTML = ''; // Xóa dữ liệu cũ trong bảng

    //             if (data.data && data.data.length > 0) {
    //                 data.data.forEach(nhanVien => {
    //                     var row = `
    //                     <tr>
    //                         <td>${nhanVien.ma_nhan_vien}</td>
    //                         <td>${nhanVien.ten_nhan_vien}</td>
    //                         <td>${nhanVien.email}</td>
    //                         <td>${nhanVien.ma_tai_khoan}</td>
    //                         <td>${nhanVien.ngay_tao}</td>
    //                         <td>${nhanVien.ten_dang_nhap}</td>
    //                         <td>${nhanVien.mat_khau}</td>
    //                         <td>${nhanVien.loai_tai_khoan}</td>
    //                         <td style="border-right: none;">
    //                             <a href="#" class="xoa" data-id="<?= $nv['ma_nhan_vien'] ?>"
    //                                 data-matk="<?= $nv['ma_tai_khoan'] ?>"
    //                                 onclick="deleteLoaiVatTu(event, <?= $nv['ma_nhan_vien'] ?>)"><i
    //                                 class='bx bx-trash-alt'></i></a>

    //                             <a class="sua" onclick="openEditModal('<?= $nv['ma_nhan_vien'] ?>')"><i
    //                                 class='bx bx-edit'></i></a>

    //                         </td>
    //                     </tr>
    //                 `;
    //                     nhanVienTableBody.innerHTML += row;
    //                 });
    //             } else {
    //                 nhanVienTableBody.innerHTML = '<tr><td colspan="10">Không tìm thấy nhân viên</td></tr>';
    //             }
    //         })
    //         .catch(error => console.error('Lỗi:', error));
    // });
    </script>
    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Thêm Nhân viên</h2>
            <form id="addNhanVienForm">
                <div class="form-group">
                    <label for="ten_nhan_vien">Tên nhân viên: </label>
                    <input type="text" id="ten_nhan_vien" name="ten_nhan_vien" required>
                </div>
                <div class="form-group">
                    <label for="email">Email: </label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="ten_dang_nhap">Tên đăng nhập: </label>
                    <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required>
                </div>
                <div class="form-group">
                    <label for="mat_khau">Mật khẩu: </label>
                    <input type="text" id="mat_khau" name="mat_khau" required>
                </div>
                <div class="form-group">
                    <label for="loai_tai_khoan">Loại tài khoản:</label>
                    <select id="loai_tai_khoan" name="loai_tai_khoan" required>
                        <option value="1">Quản lý</option>
                        <option value="2">Nhân viên</option>
                        <option value="3">Khách hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>
    <div id="modalEdit" class="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Cập nhật thông tin Nhân viên</h2>
            <form id="editNhanVienForm">
                <input type="hidden" id="ma_nhan_vien_sua" name="ma_nhan_vien">
                <input type="hidden" id="ma_tai_khoan_sua" name="ma_tai_khoan">
                <div class="form-group">
                    <label for="ten_nhan_vien_sua">Tên nhân viên:</label>
                    <input type="text" id="ten_nhan_vien_sua" name="ten_nhan_vien" required>
                </div>
                <div class="form-group">
                    <label for="email_sua">Email:</label>
                    <input type="text" id="email_sua" name="email" required>
                </div>
                <div class="form-group">
                    <label for="ten_dang_nhap_sua">Tên đăng nhập:</label>
                    <input type="text" id="ten_dang_nhap_sua" name="ten_dang_nhap" required>
                </div>
                <div class="form-group">
                    <label for="mat_khau_sua">Mật khẩu:</label>
                    <input type="password" id="mat_khau_sua" name="mat_khau" required>
                </div>
                <div class="form-group">
                    <label for="loai_tai_khoan">Loại tài khoản:</label>
                    <select id="loai_tai_khoan" name="loai_tai_khoan" required>
                        <option value="1">Quản lý</option>
                        <option value="2">Nhân viên</option>
                        <option value="3">Khách hàng</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Cập nhật</button>
            </form>
        </div>
    </div>

    <script>
    // Gửi yêu cầu POST khi người dùng nhấn nút "Thêm Vật Tư"
    $("#addNhanVienForm").on("submit", function(event) {
        event.preventDefault(); // Ngừng submit mặc định của form
        // Lấy dữ liệu từ form
        var materialData = {
            ten_nhan_vien: $("#ten_nhan_vien").val(),
            email: $("#email").val(),
            ten_dang_nhap: $("#ten_dang_nhap").val(),
            mat_khau: $("#mat_khau").val(),
            loai_tai_khoan: $("#loai_tai_khoan").val(),
        };
        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/NhanVien_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(materialData),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 201) {
                    alert("Thêm Nhân viên thành công!");

                    // Đóng modal
                    $("#modal").hide();

                    // Reset form
                    $("#addNhanVienForm")[0].reset();
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);

                } else if (response && response.status === 409) {
                    alert("Tên đăng nhập đã tồn tại. Vui lòng nhập tên khác.");
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

    <script>
    // Lắng nghe sự kiện submit của form sửa nhân viên
    document.getElementById("editNhanVienForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngừng gửi form theo cách truyền thống

        const formData = new FormData(this);
        const formJSON = {};
        formData.forEach((value, key) => {
            formJSON[key] = value;
        });

        // Gửi yêu cầu PUT đến API để cập nhật nhân viên
        fetch("http://localhost/quanlyvattu/controllers/NhanVien_api.php", {
                method: "PUT",
                body: JSON.stringify(formJSON),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 200) {
                    alert("Cập nhật thông tin nhân viên thành công!");
                    document.getElementById('modalEdit').style.display = 'none';
                    window.location.reload(); // Tải lại trang
                } else {
                    alert("Lỗi: " + (data.message || 'Có lỗi xảy ra trong quá trình cập nhật.'));
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

            var maNhanVien = this.getAttribute('data-id');
            var maTaiKhoan = this.getAttribute('data-matk');

            if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) {
                fetch("http://localhost/quanlyvattu/controllers/NhanVien_api.php", {
                        method: "DELETE",
                        body: JSON.stringify({
                            ma_nhan_vien: maNhanVien,
                            ma_tai_khoan: maTaiKhoan
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
                                "http://localhost/quanlyvattu/views/Admin/index.php";
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
    </script>
    <script>
    // Mở modal thêm nhân viên
    const addModal = document.getElementById("modal");
    const btnOpenModal = document.getElementById("btnOpenModal");
    const btnCloseAddModal = document.querySelector("#modal .close");

    btnOpenModal.addEventListener("click", () => {
        addModal.style.display = "block"; // Hiển thị modal
    });

    btnCloseAddModal.addEventListener("click", () => {
        addModal.style.display = "none"; // Ẩn modal
    });

    // Mở modal sửa nhân viên
    const editModal = document.getElementById("modalEdit");
    const btnCloseEditModal = document.getElementById("btnCloseModalEdit");

    btnCloseEditModal.addEventListener("click", () => {
        editModal.style.display = "none"; // Ẩn modal sửa
    });

    window.addEventListener("click", (e) => {
        // Ẩn modal nếu click ra ngoài
        if (e.target === addModal) {
            addModal.style.display = "none";
        }
        if (e.target === editModal) {
            editModal.style.display = "none";
        }
    });

    function openEditModal(maNhanVien) {
        const modalEdit = document.getElementById("modalEdit");
        modalEdit.style.display = "flex"; // Hiển thị modal chỉnh sửa

        // Tìm hàng tương ứng trong bảng
        const row = document.querySelector(`a.sua[onclick*="${maNhanVien}"]`).closest('tr');

        // Gán dữ liệu từ hàng vào form
        document.getElementById("ma_nhan_vien_sua").value = maNhanVien; // Mã nhân viên
        document.getElementById("ten_nhan_vien_sua").value = row.cells[1].innerText; // Tên nhân viên
        document.getElementById("email_sua").value = row.cells[2].innerText; // Email
        document.getElementById("ma_tai_khoan_sua").value = row.cells[3].innerText; // Mã tài khoản
        document.getElementById("ten_dang_nhap_sua").value = row.cells[5].innerText; // Tên đăng nhập
        document.getElementById("mat_khau_sua").value = row.cells[6].innerText; // Mật khẩu
    }
    </script>