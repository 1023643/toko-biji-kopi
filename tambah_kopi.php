<<<<<<< HEAD
<?php
session_start();

// Proses tambah kopi baru
if (isset($_POST['tambah_kopi'])) {
    $nama = $_POST['nama'];
    $asal = $_POST['asal'];
    $roast = $_POST['roast'];
    $acidty = $_POST['acidty'];
    $harga = $_POST['harga'];
    
    // Generate ID baru
    $id_baru = count($_SESSION['kopi']) + 1;
    
    // Tambah kopi baru ke session
    $_SESSION['kopi'][] = [
        "id" => $id_baru,
        "nama" => $nama,
        "asal" => $asal, 
        "roast" => $roast,
        "acidty" => $acidty,
        "harga" => $harga
    ];
    
    header("Location: index.php?pesan=Kopi berhasil ditambahkan!");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kopi Baru</title>
    <style>
        body { 
            font-family: Arial; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            color: #8B4513;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #8B4513;
        }
        input, select { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #D2691E; 
            border-radius: 8px; 
            font-size: 16px;
        }
        .btn { 
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white; 
            padding: 15px 30px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.4);
        }
        .btn-back {
            background: #6c757d;
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Tambah Kopi Baru</h1>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Kopi:</label>
                <input type="text" name="nama" required placeholder="Contoh: Arabica Toraja">
            </div>
            
            <div class="form-group">
                <label>Asal Daerah:</label>
                <input type="text" name="asal" required placeholder="Contoh: Sulawesi">
            </div>
            
            <div class="form-group">
                <label>Tingkat Roast:</label>
                <select name="roast" required>
                    <option value="">Pilih Roast</option>
                    <option value="Light">Light</option>
                    <option value="Medium">Medium</option>
                    <option value="Dark">Dark</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tingkat Acidty:</label>
                <select name="acidty" required>
                    <option value="">Pilih Acidty</option>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga per kg:</label>
                <input type="number" name="harga" required placeholder="Contoh: 120000">
            </div>
            
            <button type="submit" name="tambah_kopi" class="btn">➕ Tambah Kopi</button>
            <a href="index.php" class="btn btn-back">← Kembali ke Beranda</a>
        </form>
    </div>
</body>
=======
<?php
session_start();

// Proses tambah kopi baru
if (isset($_POST['tambah_kopi'])) {
    $nama = $_POST['nama'];
    $asal = $_POST['asal'];
    $roast = $_POST['roast'];
    $acidty = $_POST['acidty'];
    $harga = $_POST['harga'];
    
    // Generate ID baru
    $id_baru = count($_SESSION['kopi']) + 1;
    
    // Tambah kopi baru ke session
    $_SESSION['kopi'][] = [
        "id" => $id_baru,
        "nama" => $nama,
        "asal" => $asal, 
        "roast" => $roast,
        "acidty" => $acidty,
        "harga" => $harga
    ];
    
    header("Location: index.php?pesan=Kopi berhasil ditambahkan!");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kopi Baru</title>
    <style>
        body { 
            font-family: Arial; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 {
            color: #8B4513;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
            color: #8B4513;
        }
        input, select { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #D2691E; 
            border-radius: 8px; 
            font-size: 16px;
        }
        .btn { 
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
            color: white; 
            padding: 15px 30px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 18px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }
        .btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.4);
        }
        .btn-back {
            background: #6c757d;
            display: block;
            text-align: center;
            text-decoration: none;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Tambah Kopi Baru</h1>
        
        <form method="POST">
            <div class="form-group">
                <label>Nama Kopi:</label>
                <input type="text" name="nama" required placeholder="Contoh: Arabica Toraja">
            </div>
            
            <div class="form-group">
                <label>Asal Daerah:</label>
                <input type="text" name="asal" required placeholder="Contoh: Sulawesi">
            </div>
            
            <div class="form-group">
                <label>Tingkat Roast:</label>
                <select name="roast" required>
                    <option value="">Pilih Roast</option>
                    <option value="Light">Light</option>
                    <option value="Medium">Medium</option>
                    <option value="Dark">Dark</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Tingkat Acidty:</label>
                <select name="acidty" required>
                    <option value="">Pilih Acidty</option>
                    <option value="Rendah">Rendah</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Tinggi">Tinggi</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Harga per kg:</label>
                <input type="number" name="harga" required placeholder="Contoh: 120000">
            </div>
            
            <button type="submit" name="tambah_kopi" class="btn">➕ Tambah Kopi</button>
            <a href="index.php" class="btn btn-back">← Kembali ke Beranda</a>
        </form>
    </div>
</body>
>>>>>>> affef6f948abde4afc53376e47d0618066f455c1
</html>