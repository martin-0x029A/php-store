<?php
session_start();
include 'auth.php';
requireAdmin();

$db = new SQLite3('db/store.db');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        select { padding: 3px; }
    </style>
</head>
<body>
    <h1>Manage Orders</h1>
    <a href="index.php">Back to Store</a>
    
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        $orders = $db->query("
            SELECT o.*, u.username 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ");
        
        while ($order = $orders->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td>#<?= $order['id'] ?></td>
                <td><?= $order['username'] ?></td>
                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                <td>$<?= number_format($order['total'], 2) ?></td>
                <td>
                    <form action="update_order_status.php" method="post">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status">
                            <option value="processing" <?= $order['status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
                <td>
                    <a href="order_details.php?id=<?= $order['id'] ?>">View</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html> 