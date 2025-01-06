<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                        <option value="<?= $loai['ma_loai_vat_tu']  ?>">
                            <?= $loai['ten_loai_vat_tu'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <!-- Bảng danh sách vật tư -->
        <h2>Danh Sách Vật Tư</h2>
        <table class="table">
            <thead>
                <tr>
                <th>Mã Vật Tư</th>
                <th>Tên Vật Tư</th>
                <th>Mô tả</th>
                <th>Giá</th>
                <th>Đơn vị</th>
                <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['data'] as $vatTu) { ?>
                    <tr>
                    <td><?= $vatTu['ma_vat_tu'] ?></td>
                        <td><?= $vatTu['ten_vat_tu'] ?></td>
                        <td><?= $vatTu['mo_ta'] ?></td>
                        <td><?= $vatTu['gia'] ?></td>
                        <td><?= $vatTu['don_vi'] ?></td>
                        <td style="border-right: none;">
                            <a href="#" class="addcart" onclick="openAddModal('<?= $vatTu['ma_vat_tu'] ?>')">
                                <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
                            </a>
                            
                        </td>

                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </main>
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
                so_luong_toi_thieu: $("#so_luong_toi_thieu").val(),
                so_luong_ton: $("#so_luong_ton").val(),
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

            // Tạo đối tượng FormData từ form
            var formData = new FormData(this);

            // Chuyển form data thành JSON
            var formJSON = {};
            formData.forEach((value, key) => {
                formJSON[key] = value;
            });

            // Gửi yêu cầu PUT đến API để cập nhật vật tư
            fetch("http://localhost/quanlyvattu/controllers/update.php", {
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
    </script>


    <script>
        // Lắng nghe sự kiện nhấp vào nút xóa vật tư
        document.querySelectorAll('.xoa').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault(); // Ngừng hành động mặc định của thẻ <a>

                // Lấy mã vật tư từ thuộc tính data-id
                var maVatTu = this.getAttribute('data-id');

                // Cảnh báo trước khi xóa vật tư
                if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) {
                    // Gửi yêu cầu DELETE đến API
                    fetch("http://localhost/quanlyvattu/controllers/delete.php", {
                            method: "DELETE", // Phương thức DELETE
                            body: JSON.stringify({
                                ma_vat_tu: maVatTu
                            }), // Chuyển mã vật tư thành JSON
                            headers: {
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json()) // Phân tích phản hồi từ server dưới dạng JSON
                        .then(data => {
                            // Kiểm tra trạng thái phản hồi từ server
                            if (data.status === 200) {
                                alert(data.message); // Thông báo xóa thành công
                                window.location.href = "http://localhost/quanlyvattu/index.php"; // Điều hướng về trang chính
                            } else {
                                alert(data.message); // Thông báo lỗi
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
        document.getElementById("btnCloseModalDelete").addEventListener("click", function() {
            document.getElementById("modalDelete").style.display = 'none';
        });
  
</body>