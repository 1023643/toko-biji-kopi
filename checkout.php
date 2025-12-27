<?php
session_start();
include 'koneksi.php';

// Check Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'buyer') {
    echo "<script>alert('Hanya akun Pembeli yang dapat melakukan pemesanan.'); window.history.back();</script>";
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Fetch Cart Data
$cart_query = mysqli_query($koneksi, "
    SELECT c.id as cart_id, c.qty, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = '$buyer_id'
");

if (mysqli_num_rows($cart_query) == 0) {
    header("Location: keranjang.php");
    exit;
}

$cart_items = [];
$total_bayar = 0;
while($row = mysqli_fetch_assoc($cart_query)) {
    $cart_items[] = $row;
    $total_bayar += ($row['harga'] * $row['qty']);
}

// Handle Payment
if (isset($_POST['bayar'])) {
    
    // Process Each Item
    $success_count = 0;
    
    foreach ($cart_items as $item) {
        $product_id = $item['id'];
        $seller_id = $item['seller_id'];
        $qty = $item['qty'];
        $total = $item['harga'] * $qty;
        
        // Insert Transaction
        $sql = "INSERT INTO transactions (buyer_id, product_id, seller_id, qty, total_harga, status) 
                VALUES ('$buyer_id', '$product_id', '$seller_id', '$qty', '$total', 'paid')";
        
        if (mysqli_query($koneksi, $sql)) {
            // Update Stock
            mysqli_query($koneksi, "UPDATE products SET stok = stok - $qty WHERE id='$product_id'");
            $success_count++;
        }
    }
    
    if ($success_count > 0) {
        // Clear Cart
        mysqli_query($koneksi, "DELETE FROM cart WHERE user_id='$buyer_id'");
        echo "<script>alert('Pembelian Berhasil! Terima kasih.'); window.location='riwayat.php';</script>";
    } else {
        echo "<script>alert('Gagal memproses transaksi.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #FDF5E6; display: flex; justify-content: center; padding-top: 50px; }
        .card { background: white; padding: 40px; border-radius: 12px; max-width: 600px; width: 100%; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #8B4513; text-align: center; }
        .summary-list { margin-bottom: 20px; border-bottom: 2px dashed #ddd; padding-bottom: 20px; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .total-row { display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: #8B4513; margin-top: 20px; }
        .btn-pay { width: 100%; background: #27ae60; color: white; padding: 15px; border: none; border-radius: 8px; font-size: 18px; font-weight: bold; margin-top: 20px; cursor: pointer; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

<div class="card">
    <h1>🛒 Konfirmasi Pembayaran</h1>
    
    <div class="summary-list">
        <h3>Ringkasan Pesanan</h3>
        <?php foreach ($cart_items as $item): ?>
        <div class="item-row">
            <div>
                <strong><?= $item['nama_kopi'] ?></strong> (<?= $item['qty'] ?> kg)
            </div>
            <div>
                Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="total-row">
        <span>Total Bayar:</span>
        <span>Rp <?= number_format($total_bayar, 0, ',', '.') ?></span>
    </div>
    
    <form method="POST">
        <button type="submit" name="bayar" class="btn-pay">💸 Bayar Sekarang</button>
        <a href="keranjang.php" class="btn-cancel">Kembali ke Keranjang</a>
    </form>
</div>

</body>
</html>