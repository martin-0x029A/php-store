<?php
session_start();
include 'auth.php';
requireAdmin();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        .form-container { max-width: 500px; margin: 20px auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add New Product</h1>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Price:</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Product Image:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            
            <button type="submit">Add Product</button>
        </form>
        
        <p><a href="index.php">Back to Store</a></p>
    </div>
</body>
</html> 