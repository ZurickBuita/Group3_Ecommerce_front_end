<?php
if (isset($_SESSION['user'])) {
    if ($_SESSION['user'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else if ($_SESSION['user'] === 'customer') {
        header("Location: customer/category.php");
    }
}
?>