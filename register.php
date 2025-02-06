<?php
session_start();
include 'auth.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        .auth-container { max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Register</h1>
        <form action="do_register.php" method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html> 