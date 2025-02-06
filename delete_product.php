<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    $productId = (int)$_POST['id'];
    
    // Delete product
    $db->exec("DELETE FROM products WHERE id = $productId");
    
    // Remove from cart if exists
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    
    $_SESSION['message'] = "Product deleted successfully!";
}

header('Location: index.php');
exit(); 