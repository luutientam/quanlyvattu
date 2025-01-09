<!DOCTYPE html>
<html lang="vi">

<head>
    <link rel="stylesheet" href="../Css/style.css?v=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<?php
session_start();
$url = "http://localhost/quanlyvattu/controllers/cart_api.php?ma_khach_hang=" . $_SESSION['maKH'];
// Gửi yêu cầu GET để lấy dữ liệu từ API
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
?>

<body>
    <main class="content">
        <?php
        // Tính tổng số lượng trong giỏ hàng
        $total_quantity = 0;
        if (!empty($data['data']) && is_array($data['data'])):
            foreach ($data['data'] as $cart) {
                $total_quantity ++;
            }
        endif;
        ?>
        <h2>Giỏ Hàng (<?= $total_quantity ?>)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Giỏ Hàng</th>
                    <th>Mã Khách Hàng</th>
                    <th>Mã Vật Tư</th>
                    <th>Tên Vật Tư</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th style="border-right: none;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['data']) && is_array($data['data'])): ?>
                <?php foreach ($data['data'] as $cart): ?>
                <tr>
                    <td><?= $cart['ma_gio_hang'] ?></td>
                    <td><?= $cart['ma_khach_hang'] ?></td>
                    <td><?= $cart['ma_vat_tu'] ?></td>
                    <td><?= $cart['ten_vat_tu'] ?></td>
                    <td><?= $cart['so_luong'] ?></td>
                    <td><?= $cart['gia'] ?></td>
                    <td style="border-right: none;">
                        <a class="xoa" data-id="<?= $cart['ma_gio_hang'] ?>" onclick="deleteCart(this)">
                            <i class='bx bx-trash-alt'></i>
                        </a>
                        <a class="sua" data-id="<?= $cart['ma_gio_hang'] ?>" data-soluong="<?= $cart['so_luong'] ?>"
                            onclick="openEditModal('<?= $cart['ma_gio_hang'] ?>')">
                            <i class='bx bx-edit'></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9">Không có giỏ hàng nào!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <!-- Modal Sửa -->
    <div class="modal" id="modalEdit">
        <div class="modal-content">
            <span class="close" id="btnCloseModalEdit">&times;</span>
            <h2>Sửa Giỏ Hàng</h2>
            <form id="editCartForm">
                <input type="hidden" id="edit_ma_gio_hang" name="ma_gio_hang">
                <div class="form-group">
                    <label for="so_luong_sua">Số lượng:</label>
                    <input type="number" id="so_luong_sua" name="so_luong_sua" required>
                </div>
                <button type="submit" class="btn-submit">Cập nhật</button>
            </form>
        </div>
    </div>

    <script>
    // Lắng nghe sự kiện submit của form sửa vật tư
    document.getElementById("editCartForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Ngừng gửi form theo cách truyền thống

        // Tạo đối tượng FormData từ form
        var formData = new FormData(this);

        // Chuyển form data thành JSON
        var formJSON = {};
        formData.forEach((value, key) => {
            formJSON[key] = value;
        });

        // Gửi yêu cầu PUT đến API để cập nhật vật tư
        fetch("http://localhost/quanlyvattu/controllers/cart_api.php", {
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
                        "http://localhost/quanlyvattu/controllers/indexKH.php?act=giohang";
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi yêu cầu:', error);
                alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
            });
    });

    // Mở modal sửa
    function openEditModal(element) {
        const modalEdit = document.getElementById("modalEdit");
        modalEdit.style.display = "flex";
        document.getElementById("edit_ma_gio_hang").value = element.dataset.id;
        document.getElementById("so_luong_sua").value = element.dataset.soluong;
    }

    // Xóa giỏ hàng
    document.querySelectorAll('.xoa').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault(); // Ngừng hành động mặc định của thẻ <a>

            // Lấy mã vật tư từ thuộc tính data-id
            var ma_gio_hang = this.getAttribute('data-id');

            // Cảnh báo trước khi xóa vật tư
            if (confirm("Bạn có chắc chắn muốn xóa vật tư này không?")) {
                // Gửi yêu cầu DELETE đến API
                fetch("http://localhost/quanlyvattu/controllers/cart_api.php", {
                        method: "DELETE", // Phương thức DELETE
                        body: JSON.stringify({
                            ma_gio_hang: ma_gio_hang
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
                            window.location.href =
                                "http://localhost/quanlyvattu/controllers/indexKH.php?act=giohang"; // Điều hướng về trang chính
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

    // Đóng modal sửa
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
        document.getElementById("edit_ma_gio_hang").value = id;

        // Lấy dữ liệu từ bảng và điền vào modal edit
        const row = document.querySelector(`tr td:has(a.sua[onclick*="${id}"])`).closest('tr');
        document.getElementById("so_luong_sua").value = row.cells[4].innerText;
    }
    </script>