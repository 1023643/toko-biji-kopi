<?php
session_start();
if (!isset($_SESSION['pesanan'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Berhasil - Toko Pemilihan Biji Kopi</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 50px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); text-align: center; }
        .success-icon { font-size: 80px; color: #28a745; margin-bottom: 20px; }
        .btn { background: #8B4513; color: white; padding: 15px 30px; border: none; border-radius: 5px; cursor: pointer; font-size: 18px; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn:hover { background: #A0522D; }
        .order-details { text-align: left; background: #e8f4f8; padding: 20px; border-radius: 8px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✅</div>
        <h1>Pesanan Berhasil Diproses!</h1>
        <p>Terima kasih <strong><?= $_SESSION['pesanan']['nama'] ?></strong> telah berbelanja di Toko Pemilihan Biji Kopi</p>
        
        <div class="order-details">
            <h3>📋 Detail Pesanan:</h3>
            <p><strong>📞 Telepon:</strong> <?= $_SESSION['pesanan']['telepon'] ?></p>
            <p><strong>📍 Alamat:</strong> <?= $_SESSION['pesanan']['alamat'] ?></p>
            <p><strong>💰 Total Pembayaran:</strong> Rp <?= number_format($_SESSION['pesanan']['total'], 0, ',', '.') ?></p>
        </div>

        <p>Pesanan Anda akan diproses dan dikirim dalam 1-2 hari kerja. Kami akan menghubungi Anda untuk konfirmasi pengiriman.</p>
        
        <a href="index.php" class="btn">🏠 Kembali ke Beranda</a>
        
        <?php unset($_SESSION['pesanan']); ?>
    </div>
</body>
</html>
