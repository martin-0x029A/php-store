<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $fileName);
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
    $stmt->bindValue(':image_url', $targetDir . $fileName);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error adding product!";
    }
}

header('Location: index.php');
exit();
?> 