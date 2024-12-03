<head>
    <link rel="stylesheet" href="../views/Css/style.css">
    <link rel="stylesheet" href="../views/Css/footer.css">
</head>
<?php include "../views/hearder.php"?>

<?php 
if (isset($_GET['act'])) {
    
    switch ($_GET['act']) {
        case 'loaivattu':
            include "../views/loaivattu.php";
            break;
        case 'thongke':
            include "../views/thongke.php";
            break;
        case 'baocao':
            include "../views/baocao.php";
            break;
        default:
            include "../views/vattu.php";
            break;
    }
} else {
    include "../views/vattu.php";
}

?>
<?php include "../views/footer.php"?>