<?php
session_start();
include 'koneksi.php';

// Fetch Products from DB
$query = "SELECT p.*, u.nama_lengkap as seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.id DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    // Fallback if table doesn't exist yet (e.g. user hasn't run setup)
    $error_msg = "Database belum siap. Silahkan jalankan setup_db.php terlebih dahulu.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Biji Kopi Pilihan</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #8B4513;
            --secondary: #D2691E;
            --bg: #FDF5E6;
            --text: #4A4A4A;
        }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: var(--bg); color: var(--text); }
        
        /* Navbar */
        .navbar {
            background: white; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100;
        }
        .brand { font-size: 24px; font-weight: 800; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .nav-links a { text-decoration: none; color: var(--text); margin-left: 20px; font-weight: 600; transition: color 0.3s; }
        .nav-links a:hover { color: var(--primary); }
        .btn-login { background: var(--primary); color: white !important; padding: 8px 20px; border-radius: 20px; }
        
        /* Hero */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&q=80');
            background-size: cover; background-position: center; color: white; text-align: center; padding: 100px 20px;
        }
        .hero h1 { font-size: 48px; margin-bottom: 10px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        
        /* Products */
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .section-title { text-align: center; margin-bottom: 40px; color: var(--primary); }
        
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px; }
        .card { 
            background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
            transition: transform 0.3s;
        }
        .card:hover { transform: translateY(-5px); }
        .card-img { height: 200px; background: #eee; overflow: hidden; }
        .card-img img { width: 100%; height: 100%; object-fit: cover; }
        .card-body { padding: 20px; }
        .tag { background: #f0f0f0; padding: 4px 10px; border-radius: 5px; font-size: 12px; color: #666; }
        .price { font-size: 18px; font-weight: 800; color: var(--primary); margin: 10px 0; }
        .btn-buy { 
            display: block; width: 100%; background: var(--primary); color: white; text-align: center; 
            padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; 
        }
        .btn-buy:hover { background: var(--secondary); }

        /* Warn */
        .alert { background: #ffe6e6; color: red; padding: 20px; text-align: center; margin: 20px; border-radius: 10px; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="index.php" class="brand">☕ Toko Biji Kopi</a>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'buyer'): ?>
                    <a href="keranjang.php">Keranjang</a>
                    <a href="riwayat.php">Riwayat Belanja</a>
                <?php else: ?>
                    <a href="<?= $_SESSION['role'] ?>/dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="#">Halo, <?= $_SESSION['nama'] ?></a>
                <a href="logout.php" style="color: red;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-login">Login / Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero">
        <h1>Nikmati Kopi Terbaik Nusantara</h1>
        <p>Dipetik langsung dari petani, disangrai dengan segenap hati.</p>
    </div>

    <div class="container">
        <h2 class="section-title">✨ Katalog Pilihan</h2>
        
        <?php if(isset($error_msg)): ?>
            <div class="alert">
                <h3>⚠️ Perhatian</h3>
                <p><?= $error_msg ?></p>
                <a href="setup_db.php">Klik disini untuk Setup Database</a>
            </div>
        <?php else: ?>

            <div class="grid">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <div class="card-img">
                        <?php 
                            $img_src = !empty($row['gambar']) ? $row['gambar'] : "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&q=80";
                            // Basic check if local file exists, otherwise fallback (if using assets/uploads)
                            if (!file_exists($img_src) && strpos($img_src, 'http') === false) {
                                $img_src = "https://images.unsplash.com/photo-1559056199-641a0ac8b55e?auto=format&fit=crop&q=80";
                            }
                        ?>
                        <img src="<?= $img_src ?>" alt="Kopi">
                    </div>
                    <div class="card-body">
                        <div style="display: flex; gap: 5px; margin-bottom: 10px;">
                            <span class="tag"><?= $row['roast_level'] ?></span>
                            <span class="tag"><?= $row['acidity_level'] ?> Acid</span>
                        </div>
                        <h3 style="margin: 0 0 5px 0;"><?= $row['nama_kopi'] ?></h3>
                        <p style="margin: 0; color: #666; font-size: 14px;">Asal: <?= $row['daerah_asal'] ?></p>
                        <p style="margin: 5px 0; font-size: 12px; color: #999;">Petani: <?= $row['seller_name'] ?></p>
                        <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?> /kg</div>
                        <?php if($row['stok'] > 0): ?>
                            <a href="keranjang.php?action=add&id=<?= $row['id'] ?>" class="btn-buy">🛒 + Keranjang</a>
                        <?php else: ?>
                            <button class="btn-buy" style="background: #ccc; cursor: not-allowed;">Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <?php if(mysqli_num_rows($result) == 0): ?>
                <p style="text-align: center; color: #666;">Belum ada produk yang tersedia. Silahkan login sebagai Seller untuk menambahkan produk.</p>
            <?php endif; ?>

        <?php endif; ?>
    </div>

</body>
</html>