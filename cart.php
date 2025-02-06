<?php
session_start();
$db = new SQLite3('db/store.db');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Shopping Cart</h1>
    <a href="index.php">Continue Shopping</a>
    
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $productId => $quantity):
                $product = $db->querySingle("SELECT * FROM products WHERE id = $productId", true);
                $subtotal = $product['price'] * $quantity;
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td>$<?= number_format($product['price'], 2) ?></td>
                    <td><?= $quantity ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Total</td>
                <td>$<?= number_format($total, 2) ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html> 