<?php
session_start();
include 'koneksi.php';

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = $_POST['role']; // buyer or seller

    // Validation
    if ($role != 'buyer' && $role != 'seller') {
        $error = "Role tidak valid!";
    } else {
        // Check availability
        $check = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $status = ($role == 'seller') ? 'pending' : 'approved';
            
            $sql = "INSERT INTO users (username, password, nama_lengkap, role, status) VALUES ('$username', '$hashed', '$nama', '$role', '$status')";
            
            if (mysqli_query($koneksi, $sql)) {
                $status_msg = ($status == 'pending') ? "Silahkan tunggu verifikasi Admin untuk login." : "Silahkan login.";
                $success = "Registrasi berhasil! $status_msg";
            } else {
                $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Reusing Login Styles for consistency */
        body {
            font-family: 'Outfit', sans-serif;
            background: #F3E5AB;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: rgba(0,0,0,0.5); /* Overlay */
            background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5));
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-align: center;
        }
        h2 { color: #6F4E37; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { display: block; margin-bottom: 5px; color: #444; font-weight: 600; }
        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-family: inherit;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #6F4E37;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.3s;
        }
        button:hover { background: #5A3A26; }
        .error { color: red; margin-bottom: 15px; background: #ffe6e6; padding: 10px; border-radius: 5px;}
        .success { color: green; margin-bottom: 15px; background: #e6ffe6; padding: 10px; border-radius: 5px;}
        .links { margin-top: 15px; font-size: 14px; }
        a { color: #6F4E37; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>📝 Daftar Akun Baru</h2>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>
    <?php if($success) echo "<div class='success'>$success</div>"; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" required placeholder="Nama Anda">
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Username unik">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="******">
        </div>
        <div class="form-group">
            <label>Daftar Sebagai</label>
            <select name="role">
                <option value="buyer">Pembeli (Buyer)</option>
                <option value="seller">Penjual (Seller)</option>
            </select>
            <small style="color: #666; display: block; margin-top: 5px;">*Penjual membutuhkan verifikasi Admin</small>
        </div>
        <button type="submit" name="register">Daftar</button>
    </form>
    <div class="links">
        Sudah punya akun? <a href="login.php">Login disini</a>
    </div>
</div>

</body>
</html>
