<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'ma_vat_tu' => $_POST['ma_vat_tu'],
        'ten_vat_tu' => $_POST['ten_vat_tu'],
        'mo_ta' => $_POST['mo_ta'],
        'don_vi' => $_POST['don_vi'],
        'gia' => $_POST['gia'],
        'ma_nha_cung_cap' => $_POST['ma_nha_cung_cap'],
        'so_luong_toi_thieu' => $_POST['so_luong_toi_thieu'],
        'so_luong_ton' => $_POST['so_luong_ton'],
        'ma_loai_vat_tu' => $_POST['loai_vat_tu']
    ];
    echo json_encode($data);
    header('Location: ../index.php');
}

?>