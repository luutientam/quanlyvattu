<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
// views/index.php
require_once '../controllers/MainController.php';
require_once '../models/db.php';

$controller = new MainController();
$maDonHang = $controller->getLoaiVatTu();
$maNhaCungCap = $controller->getMaNhaCungCap();
$loaiVatTu = $controller->getLoaiVatTu();
// if ($_SERVER['REQUEST_METHOD'] == "POST"){
//     $keyword = $_POST['txtTimKiem'];
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }else{
//     $keyword = '';
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }
$url = "http://localhost/quanlyvattu/controllers/DonHang_api.php";
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
                <input type="text" placeholder="Tìm kiếm đơn hàng..." name="txtTimKiem">
                <button class="btn-search">Tìm kiếm</button>
                <select name="don-hang" id="don-hang">
                    <option value="all">Tất cả đơn hàng</option>
                    <?php foreach ($data['data'] as $donHang) { ?>
                        <option value="<?= $donHang['ma_don_hang']  ?>">
                            <?= $donHang['ma_don_hang'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>
       

        <!-- Bảng danh sách vật tư -->
        <!-- Bảng danh sách đơn hàng -->
        <h2>Danh Sách Đơn Hàng</h2>
        <button class="btn-add" id="btnOpenModal">Tạo Đơn Hàng</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Tên Khách Hàng</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Đặt</th>
                    <th>Mã Người Tạo</th>
                    <th style="border-right: none;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['data'] as $donHang) { ?>
                    <tr>
                        <td><?= $donHang['ma_don_hang'] ?></td>
                        <td><?= $donHang['ten_khach_hang'] ?></td>
                        <td><?= $donHang['trang_thai'] ?></td>
                        <td><?= $donHang['ngay_dat_hang'] ?></td>
                        <td><?= $donHang['ma_nhan_vien'] ?></td>
                        <td style="border-right: none;">
                            <a id="btnOpenModalEdit" onclick="openEditModal('<?= $donHang['ma_don_hang'] ?>')" class="sua">
                                <i class='bx bx-edit'></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </main>

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Tạo Đơn Hàng</h2>

            <form id="materialForm" method="POST">
                <div class="form-group">
                    <label for="ma_don_hang">Mã Đơn Hàng:</label>
                    <input type="number" id="ma_don_hang" name="ma_don_hang" placeholder="Nhập mã đơn hàng..." required>
                </div>

                <!-- Các trường thông tin bổ sung (nếu cần) -->
                <div class="form-group">
                    <label for="ma_nguoi_tao">Mã Người Tạo:</label>
                    <input type="number" id="ma_nguoi_tao" name="ma_nguoi_tao" placeholder="Nhập mã người tạo..." required>
                </div>

                <h3>Danh Sách Vật Tư</h3>
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

                <div class="form-group">
    <label for="gia">Giá:</label>
    <input type="number" id="gia" name="gia" placeholder="Giá sẽ được tự động cập nhật..." readonly>
</div>

                <div class="form-group">
                    <label for="so_luong">Số Lượng:</label>
                    <input type="number" id="so_luong" min="1" placeholder="Nhập số lượng...">
                </div>
                <button type="button" id="btnAddVatTu">Thêm Vật Tư</button>

                <!-- Bảng hiển thị danh sách vật tư -->
                <table style="color:black;border: 1px solid blue" id="vatTuTable" border :1px>
                    <thead>
                        <tr>
                            <th>Tên Vật Tư</th>
                            <th>Số Lượng</th>
                            <th>Giá</th>
                            <th>Thành Tiền</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Các vật tư được thêm sẽ hiển thị tại đây -->
                    </tbody>
                </table>
                <!-- Nút thêm đơn hàng -->
                <button type="submit" class="btn-submit">Thêm</button>
            </form>
        </div>
    </div>

    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Đơn Hàng</h2>
            <form id="editMaterialForm">
                <!-- <input type="hidden" name="_method" value="PUT"> -->
                <input type="hidden" id="edit_ma_don_hang" name="ma_don_hang">
                <div class="form-group">
                    <label for="ma_don_hang">Mã Vật tư:</label>
                    <input type="number" id="ma_don_hang_sua" name="ma_don_hang_sua" placeholder="Nhập mã đơn hàng sửa..." readonly>
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
                    <label for="ngay_giao_hang">Đơn vị:</label>
                    <input type="date" id="ngay_giao_hang_sua" name="ngay_giao_hang_sua" placeholder="Nhập ngày giao hàng..." required>
                </div>
                <div class="form-group">
                    <label for="tong_gia_tri">Tổng giá trị:</label>
                    <input type="number" id="tong_gia_tri" name="tong_gia_tri_sua" placeholder="Nhập tổng giá trị..." required>
                </div>

                <div class="form-group">
                    <label for="trang_thai">Trạng Thái:</label>
                    <input type="text" id="trang_thai_sua" name="trang_thai_sua"
                        placeholder="Nhập trạng thái sửa..." required>
                </div>
                <div class="form-group">
                    <label for="ma_nguoi_tao">Mã Người Tạo:</label>
                    <input type="number" id="ma_nguoi_tao_sua" name="ma_nguoi_tao_sua"
                        placeholder="Nhập mã người tạo sửa..." required>
                </div>


                <button type="submit" class="btn-submit">Cập nhật</button> 
            </form>
        </div>
    </div>


    <script>
    document.getElementById('loai_vat_tu').addEventListener('change', function () {
        const maLoaiVatTu = this.value;
        const giaInput = document.getElementById('gia');

        if (maLoaiVatTu) {
            // Gọi API để lấy giá
            fetch(`get_gia_vattu.php?ma_loai_vat_tu=${maLoaiVatTu}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        giaInput.value = data.gia; // Cập nhật giá
                    } else {
                        alert(data.message);
                        giaInput.value = ''; // Xóa giá nếu không tìm thấy
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    giaInput.value = ''; // Xóa giá khi có lỗi
                });
        } else {
            giaInput.value = ''; // Xóa giá nếu không chọn loại vật tư
        }
    });
</script>

    <script>
        // Gửi yêu cầu POST khi người dùng nhấn nút "Tạo đơn hàng"
        $("#materialForm").on("submit", function(event) {
            event.preventDefault(); // Ngừng submit mặc định của for
            var materialData = {
                ma_don_hang: $("#ma_don_hang").val(),
                ma_nha_cung_cap: $("#ma_nha_cung_cap").val(),
                ngay_dat_hang: $("#ngay_dat_hang").val(),
                ngay_giao_hang: $("#ngay_giao_hang").val(),
                tong_gia_tri: $("#tong_gia_tri").val(),
                trang_thai: $("#trang_thai").val(),
                ma_nguoi_tao: $("#ma_nguoi_tao").val(),
                // Thêm phần chi tiết vật tư vào đơn hàng
                chi_tiet_don_hang: getChiTietDonHangData()
            };

            // Kiểm tra nếu có trường nào trống
            if (!materialData.ma_don_hang || !materialData.ngay_giao_hang || !materialData.tong_gia_tri || materialData.chi_tiet_don_hang.length === 0) {
                $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc và thêm ít nhất một vật tư.</p>');
                return;
            }

            // Gửi yêu cầu POST đến API
            $.ajax({
                url: 'http://localhost/quanlyvattu/controllers/DonHang_api.php', // Địa chỉ của API
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(materialData),
                success: function(response) {
                    // Kiểm tra nếu phản hồi thành công
                    if (response && response.status === 201) {
                        alert("Tạo đơn hàng thành công!");

                        // Đóng modal
                        $("#modal").hide();

                        // Reset form
                        $("#materialForm")[0].reset();

                        // Reload trang sau 2 giây để cập nhật dữ liệu
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else if (response && response.status === 409) {
                        alert("Mã đơn hàng đã tồn tại. Vui lòng nhập mã khác.");
                    } else {
                        // Hiển thị thông báo lỗi nếu không có status 201
                        $("#responseMessage").html(
                            `<p style="color: red;">Lỗi: ${response.message || 'Không xác định lỗi'}</p>`
                        );
                    }
                },
                error: function(xhr, status, error) {
                    // Xử lý lỗi từ phía server
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Đã có lỗi xảy ra.";
                    $("#responseMessage").html(`<p style="color: red;">Lỗi: ${errorMessage}</p>`);
                }
            });

            // Đóng modal khi nhấn vào nút "Đóng"
            $("#btnCloseModal").click(function() {
                $("#modal").hide();
            });
        });

        // Hàm để lấy dữ liệu chi tiết vật tư
        function getChiTietDonHangData() {
            var chiTietData = [];
            $("#vatTuTable tbody tr").each(function() {
                var maVatTu = $(this).data('maVatTu');
                var soLuong = $(this).data('soLuong');
                if (maVatTu && soLuong) {
                    chiTietData.push({
                        ma_vat_tu: maVatTu,
                        so_luong: soLuong
                    });
                }
            });
            return chiTietData;
        }
    </script>

    <!-- <script>
        // Gửi yêu cầu POST khi người dùng nhấn nút "Tạo đơn hàng"
        $("#materialForm").on("submit", function(event) {
            event.preventDefault(); // Ngừng submit mặc định của form

            // Lấy dữ liệu từ form
            var materialData = {
                ma_don_hang: $("#ma_don_hang").val(),
                ma_nha_cung_cap: $("#ma_nha_cung_cap").val(),
                ngay_dat_hang: $("#ngay_dat_hang").val(),
                ngay_giao_hang: $("#ngay_giao_hang").val(),
                tong_gia_tri: $("#tong_gia_tri").val(),
                trang_thai: $("#trang_thai").val(),
                ma_nguoi_tao: $("#ma_nguoi_tao").val()
            };

            // Kiểm tra nếu có trường nào trống
            if (!materialData.ma_don_hang || !materialData.ngay_giao_hang || !materialData.tong_gia_tri) {
                $("#responseMessage").html('<p style="color: red;">Vui lòng điền đầy đủ các trường bắt buộc.</p>');
                return;
            }

            // Gửi yêu cầu POST đến API
            $.ajax({
                url: 'http://localhost/quanlyvattu/controllers/DonHang_api.php', // Địa chỉ của API
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(materialData),
                success: function(response) {
                    // Kiểm tra nếu phản hồi thành công
                    if (response && response.status === 201) {
                        alert("Tạo đơn hàng thành công!");

                        // Đóng modal
                        $("#modal").hide();

                        // Reset form
                        $("#materialForm")[0].reset();

                        // Reload trang sau 2 giây để cập nhật dữ liệu
                        setTimeout(function() {
                            window.location.reload();
                        }, 2);
                    } else if (response && response.status === 409) {
                        alert("Mã đơn hàng đã tồn tại. Vui lòng nhập mã khác.");
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
    </script> -->



    <!-- <>
        // Lắng nghe sự kiện submit của form sửa đơn hàng
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
            fetch("http://localhost/quanlyvattu/controllers/DonHang_api.php", {
                    method: "PUT", // Sử dụng phương thức PUT để cập nhật
                    body: JSON.stringify(formJSON),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json()) // Phân tích dữ liệu JSON từ phản hồi
                .then(data => {
                    // Kiểm tra trạng thái phản hồi từ server
                    if (data.status === 200) {
                        alert(data.message); // Hiển thị thông báo thành công
                        document.getElementById('modalEdit').style.display = 'none'; // Đóng modal
                        window.location.href = "http://localhost/quanlyvattu/index.php"; // Điều hướng về trang chính
                    } else {
                        alert(data.message); // Hiển thị thông báo lỗi
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
    </script> -->


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

        // Xử lý form tạo đơn hàng
        const form = document.getElementById("materialForm");
        const tableBody = document.querySelector(".table tbody");

        // Sửa vật tư
        function openEditModal(id) {
            // Hiển thị modal sửa
            const modalEdit = document.getElementById("modalEdit");
            modalEdit.style.display = "flex";

            // Gán ID vào input hidden
            document.getElementById("edit_ma_don_hang").value = id;

            // Lấy dữ liệu từ bảng và điền vào modal edit
            const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
            document.getElementById("ma_don_hang_sua").value = row.cells[0].innerText;
            document.getElementById("ma_nha_cung_cap_sua").value = row.cells[1].innerText;
            document.getElementById("ngay_dat_hang_sua").value = row.cells[2].innerText;
            document.getElementById("ngay_giao_hang_sua").value = row.cells[3].innerText;
            document.getElementById("tong_gia_tri_sua").value = row.cells[4].innerText;
            document.getElementById("trang_thai_sua").value = row.cells[6].innerText;
            document.getElementById("ma_nguoi_tao_sua").value = row.cells[7].innerText;
        }
    </script>
</body>