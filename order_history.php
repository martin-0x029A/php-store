<?php
session_start();
include 'auth.php';
requireAuth();

$db = new SQLite3('db/store.db');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .status-processing { color: #856404; background-color: #fff3cd; }
        .status-shipped { color: #155724; background-color: #d4edda; }
        .status-delivered { color: #0c5460; background-color: #d1ecf1; }
    </style>
</head>
<body>
    <h1>Your Order History</h1>
    <a href="index.php">Back to Store</a>
    
    <?php
    $orders = $db->query("
        SELECT o.*, COUNT(oi.product_id) as items 
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = {$_SESSION['user']['id']}
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    
    if (!$orders->numColumns()): ?>
        <p>No orders found</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Items</th>
                <th>Status</th>
                <th>Details</th>
            </tr>
            <?php while ($order = $orders->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                    <td>$<?= number_format($order['total'], 2) ?></td>
                    <td><?= $order['items'] ?></td>
                    <td>
                        <span class="status-<?= $order['status'] ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </td>
                    <td><a href="order_details.php?id=<?= $order['id'] ?>">View</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html> 