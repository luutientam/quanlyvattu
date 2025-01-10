<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    /* Thêm CSS cho ô input số lượng */
    .quantity-input {
        width: 50px;
        /* Điều chỉnh kích thước */
        text-align: center;
        /* Canh giữa văn bản */
        border: 1px solid #ccc;
        /* Đường viền */
        border-radius: 4px;
        /* Bo góc */
        padding: 5px;
        /* Khoảng cách bên trong */
    }

    .size {
        width: 100px;
    }
    </style>
</head>
<?php
// views/index.php
require_once '../controllers/MainController.php';
require_once '../models/db.php';

$controller = new MainController();
$loaiVatTu = $controller->getLoaiVatTu();
// if ($_SERVER['REQUEST_METHOD'] == "POST"){
//     $keyword = $_POST['txtTimKiem'];
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }else{
//     $keyword = '';
//     $danhSachVatTu = $controller->getDanhSachVatTu($keyword);
// }
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
        <!-- Thanh tìm kiếm -->
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
                    <th>Loại vật tư</th>
                    <th class="size">Số lượng</th>
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody id="vatTuTableBody">
                <?php foreach ($data['data'] as $vatTu) { ?>
                <tr>
                    <td><?= $vatTu['ma_vat_tu'] ?></td>
                    <td><?= $vatTu['ten_vat_tu'] ?></td>
                    <td><?= $vatTu['mo_ta'] ?></td>
                    <td><?= $vatTu['gia'] ?></td>
                    <td><?= $vatTu['don_vi'] ?></td>
                    <td><?= $vatTu['ten_loai_vat_tu'] ?></td>
                    <td><input type="number" id="quantity_<?= $vatTu['ma_vat_tu'] ?>" name="quantity" min="1" value="1"
                            class="quantity-input"></td>
                    <td style="border-right: none;">
                        <a href="#" class="addcart" onclick="themVatTu('<?= $vatTu['ma_vat_tu'] ?>')">
                            <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
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
            var keywordLoaiVatTu = document.getElementById('loai-vat-tu').value;

            // Tạo URL với cả từ khóa và loại vật tư
            var url = `http://localhost/quanlyvattu/controllers/VatTu_api.php?keyword=${encodeURIComponent(keyword)}&ma_loai_vat_tu=${encodeURIComponent(keywordLoaiVatTu)}`;

            // Gửi yêu cầu fetch
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var vatTuTableBody = document.getElementById('vatTuTableBody');
                    vatTuTableBody.innerHTML = ''; // Xóa dữ liệu cũ trong bảng

                    if (data.data && data.data.length > 0) {
                        data.data.forEach(vatTu => {
                            var row = `
                        <tr>
                            <td>${vatTu.ma_vat_tu}</td>
                            <td>${vatTu.ten_vat_tu}</td>
                            <td>${vatTu.mo_ta}</td>
                            <td>${vatTu.don_vi}</td>
                            <td>${vatTu.gia}</td>
                            <td>${vatTu.ten_loai_vat_tu}</td>
                            <td><input type="number" id="quantity_${vatTu.ma_vat_tu}" name="quantity" min="1" value="1" class="quantity-input"></td>
                            <td style="border-right: none;">
                                <a href="#" class="addcart" onclick="themVatTu('${vatTu.ma_vat_tu}')">
                                    <i class="fa fa-shopping-cart" style="font-size:24px;"></i>
                                </a>
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

    <!-- Modal -->
    <div id="addCartModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h2>Thêm vào giỏ hàng</h2>
            <form id="addCartForm">
                <input type="hidden" id="maVatTu" name="ma_vat_tu">
                <div class="form-group">
                    <label for="tenVatTu">Tên vật tư</label>
                    <input type="text" id="tenVatTu" name="ten_vat_tu" readonly>
                </div>
                <div class="form-group">
                    <label for="moTa">Mô tả</label>
                    <textarea id="moTa" name="mo_ta" readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="donVi">Đơn vị</label>
                    <input type="text" id="donVi" name="don_vi" readonly>
                </div>
                <div class="form-group">
                    <label for="gia">Giá</label>
                    <input type="text" id="gia" name="gia" readonly>
                </div>
                <div class="form-group">
                    <label for="quantity">Số lượng</label>
                    <input type="number" id="quantity" name="quantity" min="1" required>
                </div>
                <button type="submit" class="btn-submit">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
    <?php
    session_start(); // Bắt đầu session nếu chưa bắt đầu

    // Kiểm tra nếu session 'maKH' tồn tại
    if (isset($_SESSION['maKH'])) {
        $maKH = $_SESSION['maKH'];
    }
    
    ?>

    <script>
    var maKH = "<?php echo $maKH; ?>";

    function themVatTu(maVatTu) {
        var so_luong = document.getElementById('quantity_' + maVatTu).value;

        var dataThemVatTu = {
            ma_vat_tu: maVatTu,
            ma_khach_hang: maKH, // Sử dụng giá trị session đã nhúng vào JavaScript
            so_luong: so_luong,
        };
        // Gửi yêu cầu POST đến API
        $.ajax({
            url: 'http://localhost/quanlyvattu/controllers/cart_api.php', // Địa chỉ của API
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(dataThemVatTu),
            success: function(response) {
                // Kiểm tra nếu phản hồi thành công
                if (response && response.status === 201) {
                    alert("Thêm vật tư vào giỏ hàng thành công");
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
    }

    function closeAddModal() {
        // Ẩn modal
        document.getElementById('addCartModal').style.display = 'none';
    }
    </script>
    
    <script>
    function openAddModal(maVatTu) {
        // Đặt giá trị mã vật tư vào trường ẩn
        document.getElementById('maVatTu').value = maVatTu;
        // Hiển thị modal
        document.getElementById('addCartModal').style.display = 'block';
    }

    function closeAddModal() {
        // Ẩn modal
        document.getElementById('addCartModal').style.display = 'none';
    }

    // Thêm sự kiện submit cho form
    document.getElementById('addCartForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const maVatTu = document.getElementById('maVatTu').value;
        const quantity = document.getElementById('quantity').value;
        const data = {
            ma_vat_tu: maVatTu,
            so_luong: quantity
        };

        fetch('/controllers/cart_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                alert('Vật tư đã được thêm vào giỏ hàng!');
                closeAddModal();
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vật tư vào giỏ hàng.');
            });
    });
    </script>

</body>