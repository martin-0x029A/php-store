<?php
include 'auth.php';
requireAuth();

$db = new SQLite3('db/store.db');

// Create orders table if not exists
$db->exec('CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    total REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
)');

// Create order items table
$db->exec('CREATE TABLE IF NOT EXISTS order_items (
    order_id INTEGER,
    product_id INTEGER,
    quantity INTEGER,
    price REAL,
    FOREIGN KEY(order_id) REFERENCES orders(id),
    FOREIGN KEY(product_id) REFERENCES products(id)
)');

// Process checkout
if (!empty($_SESSION['cart'])) {
    $db->exec('BEGIN TRANSACTION');
    
    try {
        // Create order
        $stmt = $db->prepare('INSERT INTO orders (user_id, total) VALUES (:user_id, :total)');
        $stmt->bindValue(':user_id', $_SESSION['user']['id']);
        $stmt->bindValue(':total', calculateCartTotal());
        $stmt->execute();
        $orderId = $db->lastInsertRowID();
        
        // Add order items
        foreach ($_SESSION['cart'] as $productId => $quantity) {
            $product = $db->querySingle("SELECT * FROM products WHERE id = $productId", true);
            
            $stmt = $db->prepare('INSERT INTO order_items 
                (order_id, product_id, quantity, price)
                VALUES (:order_id, :product_id, :quantity, :price)');
            $stmt->bindValue(':order_id', $orderId);
            $stmt->bindValue(':product_id', $productId);
            $stmt->bindValue(':quantity', $quantity);
            $stmt->bindValue(':price', $product['price']);
            $stmt->execute();
        }
        
        $db->exec('COMMIT');
        $_SESSION['message'] = "Order placed successfully!";
        unset($_SESSION['cart']);
    } catch (Exception $e) {
        $db->exec('ROLLBACK');
        $_SESSION['error'] = "Error processing order: " . $e->getMessage();
    }
}

header('Location: index.php');

function calculateCartTotal() {
    $total = 0;
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $db->querySingle("SELECT price FROM products WHERE id = $productId", true);
        $total += $product['price'] * $quantity;
    }
    return $total;
}
?> 