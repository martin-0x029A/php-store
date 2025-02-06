<?php
session_start();
$db = new SQLite3('db/store.db');

// Get existing product data
$productId = (int)$_GET['id'];
$product = $db->querySingle("SELECT * FROM products WHERE id = $productId", true);

if (!$product) {
    $_SESSION['error'] = "Product not found!";
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        .form-container { max-width: 500px; margin: 20px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Product</h1>
        <form action="update_product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">
            
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4" required><?= 
                    htmlspecialchars($product['description']) 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Current Image:</label>
                <img src="<?= $product['image_url'] ?>" style="max-width: 200px; display: block; margin-bottom: 10px;">
                <label>New Image (optional):</label>
                <input type="file" name="image" accept="image/*">
            </div>
            
            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html> 