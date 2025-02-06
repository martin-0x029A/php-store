<?php
session_start();
include 'auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    $orderId = (int)$_POST['order_id'];
    $status = SQLite3::escapeString($_POST['status']);
    
    $allowedStatuses = ['processing', 'shipped', 'delivered'];
    if (!in_array($status, $allowedStatuses)) {
        die("Invalid status");
    }
    
    $db->exec("UPDATE orders SET status = '$status' WHERE id = $orderId");
    $_SESSION['message'] = "Order status updated!";
}

header('Location: admin_orders.php');
exit();
