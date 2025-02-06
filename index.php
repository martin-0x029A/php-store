<?php
session_start();
include 'auth.php';
$db = new SQLite3('db/store.db');

// Display messages
if (isset($_SESSION['message'])) {
    echo '<div style="padding:10px;background:#d4edda;color:#155724;">'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
}
if (isset($_SESSION['error'])) {
    echo '<div style="padding:10px;background:#f8d7da;color:#721c24;">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Store</title>
    <style>
        .products { display: flex; gap: 20px; flex-wrap: wrap; }
        .product { border: 1px solid #ddd; padding: 10px; width: 200px; }
        .cart { position: fixed; right: 20px; top: 20px; background: white; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="cart">
        <?php if (isLoggedIn()): ?>
            Welcome, <?= $_SESSION['user']['username'] ?>!
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a> | <a href="register.php">Register</a>
        <?php endif; ?>
        
        🛒 Cart: <?= count($_SESSION['cart'] ?? []) ?> items
        <a href="cart.php">View Cart</a>
        
        <?php if (isLoggedIn()): ?>
            <a href="order_history.php" style="margin-left: 20px;">Order History</a>
        <?php endif; ?>
        
        <?php if (isAdmin()): ?>
            <a href="admin_add.php" style="margin-left: 20px;">Add Product</a>
            <a href="admin_orders.php" style="margin-left: 20px;">Manage Orders</a>
        <?php endif; ?>
    </div>

    <h1>Welcome to Our Store</h1>
    
    <div class="products">
        <?php
        $result = $db->query('SELECT * FROM products');
        while ($product = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <div class="product">
                <?php if ($product['image_url']): ?>
                    <img src="<?= $product['image_url'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 100%; height: 150px; object-fit: contain;">
                <?php endif; ?>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>$<?= number_format($product['price'], 2) ?></p>
                <div style="display: flex; gap: 10px;">
                    <form action="add_to_cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                    <a href="admin_edit.php?id=<?= $product['id'] ?>">Edit</a>
                    <form action="delete_product.php" method="post" onsubmit="return confirm('Delete this product?')">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" style="color: red;">Delete</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html> 