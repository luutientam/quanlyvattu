<head>
    <link rel="stylesheet" href="../views/Css/style.css?v=1.1">
    <link rel="stylesheet" href="../views/Css/footer.css?v=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<?php include "../views/hearderKH.php"?>

<?php 
if (isset($_GET['act'])) {
    
    switch ($_GET['act']) {
        case 'giohang':
            include "../views/cart.php";
            break;
        default:
            include "../views/vattuKH.php";
            break;
    }
} else {
    include "../views/vattuKH.php";
}

?>
<?php include "../views/footerKH.php"?>