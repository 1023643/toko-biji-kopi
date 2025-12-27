<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: ../login.php");
    exit;
}

$seller_id = $_SESSION['user_id'];
$products = mysqli_query($koneksi, "SELECT * FROM products WHERE seller_id='$seller_id' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk Saya - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; margin: 0; background: #f4f4f4; }
        .sidebar { width: 250px; background: #8B4513; color: white; position: fixed; height: 100vh; padding: 20px; }
        .content { margin-left: 290px; padding: 20px; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover { background: #A0522D; }
        
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>☕ Seller Panel</h2>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="produk_saya.php" style="background: #A0522D;">📦 Produk Saya</a>
    <a href="tambah_produk.php">➕ Tambah Produk</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="content">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Produk Saya</h1>
        <a href="tambah_produk.php" style="background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Tambah Baru</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Kopi</th>
                    <th>Asal</th>
                    <th>Roast</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td>
                        <img src="../<?= $row['gambar'] ?>" width="50" height="50" style="object-fit: cover; border-radius: 5px;">
                    </td>
                    <td><?= $row['nama_kopi'] ?></td>
                    <td><?= $row['daerah_asal'] ?></td>
                    <td><?= $row['roast_level'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?> kg</td>
                    <td>
                        <a href="#" style="color: blue;">Edit</a> | 
                        <a href="#" style="color: red;">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
