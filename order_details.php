<?php
session_start();
include 'auth.php';
requireAuth();

$db = new SQLite3('db/store.db');
$orderId = (int)$_GET['id'];

// Verify order ownership
$order = $db->querySingle("
    SELECT * FROM orders 
    WHERE id = $orderId 
    AND user_id = {$_SESSION['user']['id']}
", true);

if (!$order) {
    $_SESSION['error'] = "Order not found!";
    header('Location: order_history.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h1>Order #<?= $order['id'] ?></h1>
    <p>Date: <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
    <p>Status: <?= ucfirst($order['status']) ?></p>
    
    <h3>Items:</h3>
    <table>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        <?php
        $items = $db->query("
            SELECT p.name, oi.price, oi.quantity 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = $orderId
        ");
        
        while ($item = $items->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>$<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="3">Total</td>
            <td>$<?= number_format($order['total'], 2) ?></td>
        </tr>
    </table>
    <a href="order_history.php">Back to Order History</a>
</body>
</html> 