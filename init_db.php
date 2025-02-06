<?php
$db = new SQLite3('db/store.db');

// Drop existing table if it has old structure
$db->exec('DROP TABLE IF EXISTS products');

// Create new table with correct columns
$db->exec('CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    price REAL NOT NULL,
    description TEXT,
    image_url TEXT
)');

// Insert sample products with prices
$db->exec("INSERT INTO products (name, price, description, image_url) VALUES
    ('iPhone 15', 999.99, 'Latest Apple smartphone', 'https://via.placeholder.com/200'),
    ('Samsung Galaxy S24', 899.99, 'Flagship Android phone', 'https://via.placeholder.com/200'),
    ('Google Pixel 8', 699.99, 'Best camera phone', 'https://via.placeholder.com/200')
");

// Add to existing init_db.php
$db->exec('CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    role TEXT DEFAULT "user"
)');

// Create admin user (password: admin123)
$db->exec("INSERT INTO users (username, password, role) VALUES 
    ('admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'admin')");

// Update orders table creation
$db->exec('CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    total REAL NOT NULL,
    status TEXT DEFAULT "processing",  // Add status field
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
)');

echo "Database reinitialized with correct schema!";
?> 