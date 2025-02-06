<?php
session_start();
include 'auth.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        .auth-container { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Login</h1>
        <form action="do_login.php" method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html> 