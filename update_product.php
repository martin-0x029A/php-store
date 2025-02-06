<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    $productId = (int)$_POST['id'];
    
    // Handle image update
    $base64Image = $_POST['existing_image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            $_SESSION['error'] = "Invalid image type. Only JPG, PNG, and GIF are allowed.";
            header("Location: admin_edit.php?id=$productId");
            exit();
        }
        
        $imageData = file_get_contents($tempPath);
        $base64Image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
    }

    // Sanitize inputs
    $name = SQLite3::escapeString($_POST['name']);
    $price = (float)$_POST['price'];
    $description = SQLite3::escapeString($_POST['description']);
    
    // Update product
    $stmt = $db->prepare('UPDATE products SET 
        name = :name,
        price = :price,
        description = :description,
        image_url = :image_url
        WHERE id = :id
    ');
    
    $stmt->bindValue(':id', $productId);
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':image_url', $base64Image);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating product!";
    }
}

header('Location: index.php');
exit();
?> 