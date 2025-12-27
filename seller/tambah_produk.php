<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'seller') {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $seller_id = $_SESSION['user_id'];
    $nama = $_POST['nama'];
    $asal = $_POST['asal'];
    $roast = $_POST['roast'];
    $acidity = $_POST['acidity'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    // Upload Gambar
    $gambar = "assets/default-coffee.jpg"; // Default if fail
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "../assets/uploads/";
        $file_name = time() . "_" . basename($_FILES["gambar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = "assets/uploads/" . $file_name;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    $sql = "INSERT INTO products (seller_id, nama_kopi, daerah_asal, roast_level, acidity_level, harga, stok, gambar) 
            VALUES ('$seller_id', '$nama', '$asal', '$roast', '$acidity', '$harga', '$stok', '$gambar')";
            
    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location='produk_saya.php';</script>";
    } else {
        echo "<script>alert('Gagal: ".mysqli_error($koneksi)."');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; margin: 0; background: #f4f4f4; }
        .sidebar { width: 250px; background: #8B4513; color: white; position: fixed; height: 100vh; padding: 20px; }
        .content { margin-left: 290px; padding: 20px; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover { background: #A0522D; }
        
        .form-container { background: white; padding: 30px; border-radius: 8px; max-width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #444; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: #8B4513; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>☕ Seller Panel</h2>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="produk_saya.php">📦 Produk Saya</a>
    <a href="tambah_produk.php" style="background: #A0522D;">➕ Tambah Produk</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h1>Tambah Kopi Baru</h1>
    
    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="gambar" accept="image/*" required>
            </div>
            <div class="form-group">
                <label>Nama Kopi</label>
                <input type="text" name="nama" required placeholder="Contoh: Arabica Gayo">
            </div>
            <div class="form-group">
                <label>Daerah Asal</label>
                <input type="text" name="asal" required placeholder="Contoh: Aceh">
            </div>
            <div class="form-group">
                <label>Roast Level</label>
                <select name="roast">
                    <option value="Light">Light</option>
                    <option value="Medium">Medium</option>
                    <option value="Dark">Dark</option>
                </select>
            </div>
            <div class="form-group">
                <label>Acidity Level</label>
                <select name="acidity">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" required placeholder="50000">
            </div>
            <div class="form-group">
                <label>Stok (kg)</label>
                <input type="number" name="stok" required placeholder="10">
            </div>
            <button type="submit" name="tambah">Simpan Produk</button>
        </form>
    </div>
</div>

</body>
</html>
