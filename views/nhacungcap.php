<?php
require_once '../controllers/MainController.php';
require_once '../models/db.php';

$controller = new MainController();
$nhaCungCap = $controller->getMaNhaCungCap();

$url = "http://localhost/quanlyvattu/controllers/NhaCungCap_api.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Nhà Cung Cấp</title>
</head>
<body>
    <main class="content">
        <form method="POST">
            <div class="search-bar">
                <input type="text" placeholder="Tìm kiếm nhà cung cấp..." name="txtTimKiem">
                <button class="btn-search">Tìm kiếm</button>
                <select name="nha-cung-cap" id="nha-cung-cap">
                    <option value="all">Tất cả nhà cung cấp</option>
                    <?php foreach ($nhaCungCap as $loai) { ?>
                        <option value="<?= $loai['ma_nha_cung_cap']  ?>">
                            <?= $loai['ten_nha_cung_cap'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <h2>Danh Sách Nhà Cung Cấp</h2>
        <button class="btn-add" id="btnOpenModal">Thêm Nhà Cung Cấp</button>
        <button class="btn-add" id="btnDeleteSelected">Xóa Tất Cả Đã Chọn</button>
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
                    <th><button id="selectAllBtn">Chọn Tất Cả</button></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['data'] as $ncc) { ?>
                    <tr>
                        <td><?= $ncc['ma_nha_cung_cap'] ?></td>
                        <td><?= $ncc['ten_nha_cung_cap'] ?></td>
                        <td><?= $ncc['so_dien_thoai'] ?></td>
                        <td><?= $ncc['email'] ?></td>
                        <td><?= $ncc['dia_chi'] ?></td>
                        <td><?= $ncc['ngay_tao'] ?></td>
                        <td style="border-right: none;">
                            <a href="#" class="xoa" data-id="<?= $ncc['ma_nha_cung_cap'] ?>" onclick="deleteNhaCungCap(event, <?= $ncc['ma_nha_cung_cap'] ?>)">
                                <i class='bx bx-trash-alt'></i>
                            </a>
                            <a id="btnOpenModalEdit" onclick="openEditModal('<?= $ncc['ma_nha_cung_cap'] ?>')" class="sua">
                                <i class='bx bx-edit'></i>
                            </a>
                        </td>
                        <td><input type="checkbox" class="selectItem" data-id="<?= $ncc['ma_nha_cung_cap'] ?>"></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <span class="close" id="btnCloseModal">&times;</span>
            <h2>Thêm Nhà Cung Cấp</h2>
            <form id="materialForm" method="POST">
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap" name="ma_nha_cung_cap" placeholder="Nhập mã nhà cung cấp..." required>
                </div>
                <div class="form-group">
                    <label for="ten_nha_cung_cap">Tên Nhà Cung Cấp:</label>
                    <input type="text" id="ten_nha_cung_cap" name="ten_nha_cung_cap" placeholder="Nhập tên nhà cung cấp..." required>
                </div>
                <div class="form-group">
                    <label for="so_dien_thoai">Số Điện Thoại:</label>
                    <input type="text" id="so_dien_thoai" name="so_dien_thoai" placeholder="Nhập số điện thoại..." required>
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
            <form id="editMaterialForm">
                <input type="hidden" id="edit_nha_cung_cap" name="ma_nha_cung_cap">
                <div class="form-group">
                    <label for="ma_nha_cung_cap">Mã Nhà Cung Cấp:</label>
                    <input type="text" id="ma_nha_cung_cap_sua" name="ma_nha_cung_cap_sua" placeholder="Nhập mã nhà cung cấp..." readonly>
                </div>
                <div class="form-group">
                    <label for="ten_nha_cung_cap">Tên Nhà Cung Cấp:</label>
                    <input type="text" id="ten_nha_cung_cap_sua" name="ten_nha_cung_cap_sua" placeholder="Nhập tên nhà cung cấp..." required>
                </div>
                <div class="form-group">
                    <label for="so_dien_thoai">Số Điện Thoại:</label>
                    <input type="text" id="so_dien_thoai_sua" name="so_dien_thoai_sua" placeholder="Nhập số điện thoại..." required>
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
                                var row = element.closest('tr');
                                row.parentNode.removeChild(row);
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

        document.getElementById('selectAllBtn').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.selectItem');
            var allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });
        });

        document.getElementById('btnDeleteSelected').addEventListener('click', function() {
            var selectedIds = [];
            document.querySelectorAll('.selectItem:checked').forEach(function(checkbox) {
                selectedIds.push(checkbox.getAttribute('data-id'));
            });

            if (selectedIds.length > 0 && confirm("Bạn có chắc chắn muốn xóa các nhà cung cấp đã chọn không?")) {
                fetch("http://localhost/quanlyvattu/controllers/NhaCungCap_api.php", {
                        method: "DELETE",
                        body: JSON.stringify({
                            ma_nha_cung_cap: selectedIds
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 200) {
                            alert(data.message);
                            selectedIds.forEach(function(id) {
                                var row = document.querySelector(`.selectItem[data-id="${id}"]`).closest('tr');
                                row.parentNode.removeChild(row);
                            });
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

        document.getElementById("btnCloseModalEdit").addEventListener("click", function() {
            document.getElementById("modalEdit").style.display = 'none';
        });

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

        const form = document.getElementById("materialForm");
        const tableBody = document.querySelector(".table tbody");

        function openEditModal(id) {
            const modalEdit = document.getElementById("modalEdit");
            modalEdit.style.display = "flex";

            document.getElementById("edit_nha_cung_cap").value = id;

            const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
            document.getElementById("ma_nha_cung_cap_sua").value = row.cells[0].innerText;
            document.getElementById("ten_nha_cung_cap_sua").value = row.cells[1].innerText;
            document.getElementById("so_dien_thoai_sua").value = row.cells[2].innerText;
            document.getElementById("email_sua").value = row.cells[3].innerText;
            document.getElementById("dia_chi_sua").value = row.cells[4].innerText;
        }
    </script>
</body>
</html>