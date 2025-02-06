<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    
    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add product to cart
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += 1;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }
}

header('Location: index.php');
exit();
?> 