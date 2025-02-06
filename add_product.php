<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['image']['tmp_name'];
        $imageType = $_FILES['image']['type'];
        
        // Validate image type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageType, $allowedTypes)) {
            $_SESSION['error'] = "Invalid image type. Only JPG, PNG, and GIF are allowed.";
            header('Location: admin_add.php');
            exit();
        }
        
        // Convert to base64
        $imageData = file_get_contents($tempPath);
        $base64Image = 'data:' . $imageType . ';base64,' . base64_encode($imageData);
    } else {
        $_SESSION['error'] = "Please upload an image";
        header('Location: admin_add.php');
        exit();
    }

    // Sanitize other inputs
    $name = SQLite3::escapeString($_POST['name']);
    $price = (float)$_POST['price'];
    $description = SQLite3::escapeString($_POST['description']);
    
    // Insert into database
    $stmt = $db->prepare('INSERT INTO products (name, price, description, image_url) 
                         VALUES (:name, :price, :description, :image_url)');
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':image_url', $base64Image);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error adding product!";
    }
}

header('Location: index.php');
exit();
?> 