<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
// views/index.php
require_once '../models/GetDuLieu.php';
require_once "../models/db.php";
require_once "../models/DonHangModel.php";


$getDuLieu = new GetDuLieu();
$VatTu = $getDuLieu->getVatTu();
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
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Lỗi khi giải mã JSON: " . json_last_error_msg();
    exit();
}
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
    <?php
    session_start(); // Khởi tạo session
    ?>
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
                    <label for="ma_khach_hang">Mã khách hàng:</label>
                    <input type="number" id="ma_khach_hang" name="ma_khach_hang" placeholder="Nhập mã khách hàng..." required>
                </div>
                <div class="form-group">
                    <label for="ma_nhan_vien">Mã nhân viên:</label>
                    <input value="<?php echo isset($_SESSION['maNV']) ? $_SESSION['maNV'] : ''; ?>" type="number" id="ma_nhan_vien" name="ma_nhan_vien" placeholder="Nhập mã nhân viên..." readonly>
                </div>
                <div class="form-group">
                    <label for="ngay_giao_hang">Ngày Giao Hàng:</label>
                    <input type="date" id="ngay_giao_hang" name="ngay_giao_hang" placeholder="Nhập ngày giao hàng..." required>
                </div>
                <div class="form-group">
                    <label for="trang_thai">Trạng Thái:</label>
                    <input type="text" id="trang_thai" name="trang_thai" placeholder="Nhập trạng thái..." required>
                </div>
                <h3>Danh Sách Vật Tư</h3>
                <div class="form-group">
                    <label for="vat_tu">Vật Tư:</label>
                    <select id="vat_tu" name="ma_vat_tu">
                        <?php foreach ($VatTu as $vattu) { ?>
                            <option value="<?= $vattu['ma_vat_tu'] ?>">
                                <?= $vattu['ten_vat_tu'] ?>
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
    <script>
        $("#materialForm").on("submit", function(event) {
            event.preventDefault(); // Ngừng submit mặc định của form

            // Lấy các giá trị từ form và tạo cấu trúc dữ liệu
            var materialData = {
                ma_don_hang: $("#ma_don_hang").val(),
                ma_khach_hang: $("#ma_khach_hang").val(),
                ma_nhan_vien: $("#ma_nhan_vien").val(),
                ngay_giao_hang: $("#ngay_giao_hang").val(),
                trang_thai: $("#trang_thai").val(),
                tong_gia_tri: 0, // Chưa tính tổng giá trị, sẽ tính sau
                chi_tiet_don_hang: getChiTietDonHangData()
            };

            // Tính tổng giá trị của đơn hàng dựa trên chi tiết vật tư
            materialData.tong_gia_tri = materialData.chi_tiet_don_hang.reduce((total, item) => total + (item.so_luong * item.gia), 0);

            // Kiểm tra nếu có trường nào trống
            if (!materialData.ma_don_hang || !materialData.ma_khach_hang || !materialData.ma_nhan_vien || materialData.chi_tiet_don_hang.length === 0) {
                alert('Vui lòng điền đầy đủ thông tin và thêm ít nhất một vật tư.');
                return;
            }

            // Gửi yêu cầu POST đến API
            fetch('http://localhost/quanlyvattu/controllers/DonHang_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(materialData) // Chuyển dữ liệu sang định dạng JSON
                })
                .then(response => response.json()) // Chuyển đổi phản hồi từ server thành JSON
                .then(data => {
                    if (data.status === 201) {
                        alert("Tạo đơn hàng thành công!");

                        // Đóng modal và reset form
                        $("#modal").hide();
                        $("#materialForm")[0].reset();

                        // Reload trang sau 2 giây để cập nhật dữ liệu
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        alert('Lỗi khi tạo đơn hàng: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Lỗi khi tạo đơn hàng');
                });
        });


        // Hàm để lấy dữ liệu chi tiết vật tư
        function getChiTietDonHangData() {
            const chiTietData = [];
            const rows = document.querySelectorAll("#vatTuTable tbody tr");

            rows.forEach(row => {
                const maVatTu = row.dataset.maVatTu;
                const soLuong = row.dataset.soLuong;
                const gia = row.dataset.gia;

                if (maVatTu && soLuong && gia) {
                    chiTietData.push({
                        ma_vat_tu: maVatTu,
                        so_luong: parseInt(soLuong),
                        gia: parseFloat(gia)
                    });
                }
            });

            return chiTietData;
        }
    </script>
    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Đơn Hàng</h2>
            <form id="editMaterialForm">
                <!-- <input type="hidden" name="_method" value="PUT"> -->
                <input type="hidden" id="edit_ma_don_hang" name="ma_don_hang">

                <div class="form-group">
                    <label for="trang_thai">Trạng Thái:</label>
                    <input type="text" id="trang_thai_sua" name="trang_thai_sua"
                        placeholder="Nhập trạng thái sửa..." required>
                </div>

                <button type="submit" class="btn-submit">Cập nhật</button>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('vat_tu').addEventListener('change', function() {
            const maLoaiVatTu = this.value;
            document.getElementById('vat_tu').addEventListener('change', function() {
                const maVatTu = this.value;
                const giaInput = document.getElementById('gia');

                if (maVatTu) {
                    fetch(`http://localhost/quanlyvattu/controllers/DonHang_api.php?action=getGiaVatTu&ma_vat_tu=${maVatTu}`)
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
            document.getElementById("ngay_dat_hang_sua").value = row.cells[2].innerText;
            document.getElementById("ngay_giao_hang_sua").value = row.cells[3].innerText;
            document.getElementById("tong_gia_tri_sua").value = row.cells[4].innerText;
            document.getElementById("trang_thai_sua").value = row.cells[6].innerText;
            document.getElementById("ma_nhan_vien_sua").value = row.cells[7].innerText;
        }
    </script>
    <script>
        document.getElementById('vat_tu').addEventListener('change', function() {
            const maVatTu = this.value;
            const giaInput = document.getElementById('gia');

            if (maVatTu) {
                fetch(`http://localhost/quanlyvattu/controllers/DonHang_api.php?action=getGiaVatTu&ma_vat_tu=${maVatTu}`)
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
                giaInput.value = ''; // Xóa giá nếu không chọn sản phẩm
            }
        });

        document.getElementById('btnAddVatTu').addEventListener('click', function() {
            const vatTuSelect = document.getElementById('vat_tu');
            const maVatTu = vatTuSelect.value;
            const tenVatTu = vatTuSelect.options[vatTuSelect.selectedIndex].text;
            const gia = document.getElementById('gia').value;
            const soLuong = document.getElementById('so_luong').value;

            if (maVatTu && gia && soLuong) {
                const table = document.getElementById('vatTuTable').getElementsByTagName('tbody')[0];
                const newRow = table.insertRow();

                // Gán dữ liệu vào thuộc tính `data` của hàng
                newRow.dataset.maVatTu = maVatTu;
                newRow.dataset.soLuong = soLuong;
                newRow.dataset.gia = gia;

                const cell1 = newRow.insertCell(0);
                const cell2 = newRow.insertCell(1);
                const cell3 = newRow.insertCell(2);
                const cell4 = newRow.insertCell(3);
                const cell5 = newRow.insertCell(4);

                cell1.textContent = tenVatTu;
                cell2.textContent = soLuong;
                cell3.textContent = gia;
                cell4.textContent = (gia * soLuong).toFixed(2);
                cell5.innerHTML = '<button type="button" class="btnDeleteVatTu">Xóa</button>';

                // Clear input fields
                vatTuSelect.value = '';
                document.getElementById('gia').value = '';
                document.getElementById('so_luong').value = '';

                // Add event listener for delete button
                cell5.querySelector('.btnDeleteVatTu').addEventListener('click', function() {
                    table.deleteRow(newRow.rowIndex - 1);
                });
            } else {
                alert('Vui lòng chọn loại vật tư, nhập giá và số lượng.');
            }
        });


        document.getElementById('btnSubmitOrder').addEventListener('click', function() {
            const maDonHang = document.getElementById('ma_don_hang').value;
            const maNguoiTao = document.getElementById('ma_nguoi_tao').value;
            const vatTuTable = document.getElementById('vatTuTable').getElementsByTagName('tbody')[0];
            const chiTiet = [];

            for (let i = 0; i < vatTuTable.rows.length; i++) {
                const row = vatTuTable.rows[i];
                const maVatTu = row.cells[0].textContent;
                const soLuong = row.cells[1].textContent;
                const donGia = row.cells[2].textContent;
                const thanhTien = row.cells[3].textContent;

                chiTiet.push({
                    ma_vat_tu: maVatTu,
                    so_luong: soLuong,
                    don_gia: donGia,
                    thanh_tien: thanhTien
                });
            }

            const data = {
                ma_don_hang: maDonHang,
                ma_nguoi_tao: maNguoiTao,
                chi_tiet: chiTiet
            };

            fetch('http://localhost/quanlyvattu/controllers/DonHang_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 201) {
                        alert('Tạo đơn hàng thành công');
                    } else {
                        alert('Lỗi khi tạo đơn hàng: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    alert('Lỗi khi tạo đơn hàng');
                });
        });
    </script>
</body>