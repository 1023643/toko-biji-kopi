<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: ../login.php");
    exit;
}

$seller_id = $_SESSION['user_id'];

// Stats
$products_res = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM products WHERE seller_id='$seller_id'");
$total_products = mysqli_fetch_assoc($products_res)['total'];

$sales_res = mysqli_query($koneksi, "SELECT COUNT(*) as count, SUM(total_harga) as revenue FROM transactions WHERE seller_id='$seller_id'");
$sales_data = mysqli_fetch_assoc($sales_res);
$total_sales = $sales_data['count'];
$total_revenue = $sales_data['revenue'] ?? 0;

// Recent Orders
$orders = mysqli_query($koneksi, "
    SELECT t.*, p.nama_kopi, u.nama_lengkap as pembeli 
    FROM transactions t 
    JOIN products p ON t.product_id = p.id 
    JOIN users u ON t.buyer_id = u.id 
    WHERE t.seller_id='$seller_id' 
    ORDER BY t.created_at DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; margin: 0; background: #f4f4f4; }
        .sidebar { width: 250px; background: #8B4513; color: white; position: fixed; height: 100vh; padding: 20px; }
        .content { margin-left: 290px; padding: 20px; }
        .sidebar h2 { margin-top: 0; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover { background: #A0522D; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        .stat-card h3 { margin: 0; color: #666; font-size: 14px; }
        .stat-card p { margin: 10px 0 0; font-size: 24px; font-weight: bold; color: #8B4513; }
        
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>☕ Seller Panel</h2>
    <a href="dashboard.php" style="background: #A0522D;">📊 Dashboard</a>
    <a href="produk_saya.php">📦 Produk Saya</a>
    <a href="tambah_produk.php">➕ Tambah Produk</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h1>Halo, <?= $_SESSION['nama'] ?>!</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Produk</h3>
            <p><?= $total_products ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Penjualan</h3>
            <p><?= $total_sales ?></p>
        </div>
        <div class="stat-card">
            <h3>Pendapatan</h3>
            <p>Rp <?= number_format($total_revenue, 0, ',', '.') ?></p>
        </div>
    </div>

    <div class="card">
        <h3>🛒 Pesanan Terbaru</h3>
        <?php if (mysqli_num_rows($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pembeli</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                    <td><?= $row['pembeli'] ?></td>
                    <td><?= $row['nama_kopi'] ?></td>
                    <td><?= $row['qty'] ?></td>
                    <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="color: grey;">Belum ada pesanan masuk.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
