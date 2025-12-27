<?php
session_start();
include 'koneksi.php';

// Check Login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle Actions (Add, Delete)
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    if ($action == 'add' && isset($_GET['id'])) {
        $product_id = $_GET['id'];
        
        // Check if item exists in cart
        $check = mysqli_query($koneksi, "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'");
        
        if (!$check) {
            die("Error Database: " . mysqli_error($koneksi) . "<br>Mungkin tabel 'cart' belum dibuat? <a href='setup_db.php'>Klik disini untuk Setup Database</a>");
        }

        if (mysqli_num_rows($check) > 0) {
            // Update Qty
            mysqli_query($koneksi, "UPDATE cart SET qty = qty + 1 WHERE user_id='$user_id' AND product_id='$product_id'");
        } else {
            // Insert New
            mysqli_query($koneksi, "INSERT INTO cart (user_id, product_id, qty) VALUES ('$user_id', '$product_id', 1)");
        }
        header("Location: keranjang.php");
        exit;
    }
    
    if ($action == 'delete' && isset($_GET['id'])) {
        $cart_id = $_GET['id'];
        mysqli_query($koneksi, "DELETE FROM cart WHERE id='$cart_id'");
        header("Location: keranjang.php");
        exit;
    }
}

// Fetch Cart Data
$cart_query = mysqli_query($koneksi, "
    SELECT c.id as cart_id, c.qty, p.* 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = '$user_id'
");

$total_bayar = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #FDF5E6; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        h1 { margin: 0; color: #8B4513; }
        .btn-back { text-decoration: none; color: white; background: #666; padding: 8px 15px; border-radius: 5px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f9f9f9; color: #8B4513; }
        
        .cart-item-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
        .btn-checkout { background: #27ae60; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 18px; display: inline-block; }
        .total-section { text-align: right; margin-top: 30px; }
        .total-label { font-size: 18px; color: #666; }
        .total-amount { font-size: 24px; font-weight: bold; color: #8B4513; margin: 10px 0 20px; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🛒 Keranjang Belanja</h1>
        <a href="index.php" class="btn-back">← Lanjut Belanja</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($cart_query)): 
                $subtotal = $row['harga'] * $row['qty'];
                $total_bayar += $subtotal;
                
                $img_src = !empty($row['gambar']) ? $row['gambar'] : "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&q=80";
                if (!file_exists($img_src) && strpos($img_src, 'http') === false) $img_src = "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&q=80";
            ?>
            <tr>
                <td style="display: flex; gap: 15px; align-items: center;">
                    <img src="<?= $img_src ?>" class="cart-item-img">
                    <div>
                        <strong><?= $row['nama_kopi'] ?></strong><br>
                        <small><?= $row['daerah_asal'] ?></small>
                    </div>
                </td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['qty'] ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                <td><a href="?action=delete&id=<?= $row['cart_id'] ?>" class="btn-delete">Hapus</a></td>
            </tr>
            <?php endwhile; ?>
            
            <?php if(mysqli_num_rows($cart_query) == 0): ?>
                <tr><td colspan="5" style="text-align: center; color: #999; padding: 50px;">Keranjang masih kosong. Yuk belanja!</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if(mysqli_num_rows($cart_query) > 0): ?>
    <div class="total-section">
        <div class="total-label">Total Belanja</div>
        <div class="total-amount">Rp <?= number_format($total_bayar, 0, ',', '.') ?></div>
        <a href="checkout.php" class="btn-checkout">Bayar Sekarang</a>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
