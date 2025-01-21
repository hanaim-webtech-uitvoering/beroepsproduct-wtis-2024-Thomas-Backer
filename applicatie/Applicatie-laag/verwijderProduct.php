<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_index'])) {

    $product_index = (int)$_POST['product_index'];

    if (isset($_SESSION['winkelmandje'][$product_index])) {

        unset($_SESSION['winkelmandje'][$product_index]);

        $_SESSION['winkelmandje'] = array_values($_SESSION['winkelmandje']); // Reindex the array

    }

}

header('Location: PHP/winkelmandje.php');

exit;
?>