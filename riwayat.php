<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header("Location: login.php");
    exit;
}

$buyer_id = $_SESSION['user_id'];
$history = mysqli_query($koneksi, "
    SELECT t.*, p.nama_kopi, p.daerah_asal 
    FROM transactions t 
    JOIN products p ON t.product_id = p.id 
    WHERE t.buyer_id = '$buyer_id' 
    ORDER BY t.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Belanja - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #FDF5E6; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        h1 { margin: 0; color: #8B4513; }
        .btn-back { text-decoration: none; color: white; background: #666; padding: 8px 15px; border-radius: 5px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f9f9f9; color: #8B4513; }
        .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; background: #dff0d8; color: #3c763d; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📜 Riwayat Belanja</h1>
        <a href="index.php" class="btn-back">← Kembali ke Toko</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($history)): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                <td>
                    <strong><?= $row['nama_kopi'] ?></strong><br>
                    <small><?= $row['daerah_asal'] ?></small>
                </td>
                <td><?= $row['qty'] ?> kg</td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td><span class="status"><?= ucfirst($row['status']) ?></span></td>
            </tr>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($history) == 0): ?>
                <tr><td colspan="5" style="text-align: center; color: #999;">Belum ada riwayat transaksi.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

