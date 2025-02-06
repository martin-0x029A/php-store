<?php
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    $username = SQLite3::escapeString($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
        
        $_SESSION['message'] = "Registration successful! Please login.";
        header('Location: login.php');
    } catch (Exception $e) {
        $_SESSION['error'] = "Username already exists!";
        header('Location: register.php');
    }
    exit();
} 