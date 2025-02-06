<?php

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

function requireAdmin() {
    requireAuth();
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}
?> 