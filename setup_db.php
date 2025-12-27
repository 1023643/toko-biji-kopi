<?php
$host = "localhost";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Create Database
$sql = "CREATE DATABASE IF NOT EXISTS toko_kopi";
if (mysqli_query($conn, $sql)) {
    echo "Database 'toko_kopi' berhasil dibuat/ditemukan.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

mysqli_select_db($conn, "toko_kopi");

// Table Users
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'seller', 'buyer') NOT NULL,
    status ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql)) {
    echo "Tabel 'users' aman.<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

// Table Products
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    nama_kopi VARCHAR(100) NOT NULL,
    daerah_asal VARCHAR(100),
    roast_level VARCHAR(50),
    acidity_level VARCHAR(50),
    harga DECIMAL(10,2) NOT NULL,
    stok INT DEFAULT 0,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) {
    echo "Tabel 'products' aman.<br>";
} else {
    echo "Error creating products table: " . mysqli_error($conn) . "<br>";
}

// Table Transactions
$sql = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    product_id INT NOT NULL,
    seller_id INT NOT NULL,
    qty INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'sent', 'completed', 'cancelled') DEFAULT 'paid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (seller_id) REFERENCES users(id)
)";
if (mysqli_query($conn, $sql)) {
    echo "Tabel 'transactions' aman.<br>";
} else {
    echo "Error creating transactions table: " . mysqli_error($conn) . "<br>";
}

// Table Cart
$sql = "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql)) {
    echo "Tabel 'cart' aman.<br>";
} else {
    echo "Error creating cart table: " . mysqli_error($conn) . "<br>";
}

// Seed Admin
$password_admin = password_hash("admin", PASSWORD_DEFAULT);
$check_admin = mysqli_query($conn, "SELECT * FROM users WHERE username='admin'");
if (mysqli_num_rows($check_admin) == 0) {
    $sql = "INSERT INTO users (username, password, nama_lengkap, role, status) VALUES ('admin', '$password_admin', 'Administrator', 'admin', 'approved')";
    if (mysqli_query($conn, $sql)) {
        echo "Akun ADMIN default berhasil dibuat (User: admin, Pass: admin).<br>";
    }
}

// Seed Mock Seller
$password_seller = password_hash("seller", PASSWORD_DEFAULT);
$check_seller = mysqli_query($conn, "SELECT * FROM users WHERE username='seller'");
if (mysqli_num_rows($check_seller) == 0) {
    $sql = "INSERT INTO users (username, password, nama_lengkap, role, status) VALUES ('seller', '$password_seller', 'Petani Kopi Jaya', 'seller', 'approved')"; // Auto approved for demo
    mysqli_query($conn, $sql);
    echo "Akun SELLER default berhasil dibuat (User: seller, Pass: seller).<br>";
}

// Seed Mock Buyer
$password_buyer = password_hash("buyer", PASSWORD_DEFAULT);
$check_buyer = mysqli_query($conn, "SELECT * FROM users WHERE username='buyer'");
if (mysqli_num_rows($check_buyer) == 0) {
    $sql = "INSERT INTO users (username, password, nama_lengkap, role, status) VALUES ('buyer', '$password_buyer', 'Pecinta Kopi', 'buyer', 'approved')";
    mysqli_query($conn, $sql);
    echo "Akun BUYER default berhasil dibuat (User: buyer, Pass: buyer).<br>";
}

echo "<hr>Setup Database Selesai! Silahkan hapus file ini jika sudah tidak diperlukan.";
?>

