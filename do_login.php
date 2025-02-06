<?php
include 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new SQLite3('db/store.db');
    $username = SQLite3::escapeString($_POST['username']);
    
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username);
    $result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    
    if ($result && password_verify($_POST['password'], $result['password'])) {
        $_SESSION['user'] = $result;
        header('Location: index.php');
    } else {
        $_SESSION['error'] = "Invalid credentials!";
        header('Location: login.php');
    }
    exit();
} 