<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') header("Location: admin/dashboard.php");
    else if ($_SESSION['role'] == 'seller') header("Location: seller/dashboard.php");
    else header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    // Check username
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Check password
        if (password_verify($password, $row['password'])) {
            
            // Check Status
            if ($row['status'] == 'pending') {
                $error = "Akun Anda masih menunggu verifikasi Admin.";
            } elseif ($row['status'] == 'rejected') {
                $error = "Maaf, pendaftaran Anda ditolak.";
            } else {
                // Login Success
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['nama'] = $row['nama_lengkap'];

                // Redirect based on role
                if ($row['role'] == 'admin') {
                    header("Location: admin/dashboard.php");
                } elseif ($row['role'] == 'seller') {
                    header("Location: seller/dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #F3E5AB;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('assets/bg-coffee.jpg'); /* Optional bg if available */
            background-size: cover;
            background-blend-mode: multiply;
            background-color: rgba(0,0,0,0.5);
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
        input {
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
        .error { color: red; margin-bottom: 15px; font-size: 14px; }
        .links { margin-top: 15px; font-size: 14px; }
        a { color: #6F4E37; text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>☕ Login</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="User / Admin">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="******">
        </div>
        <button type="submit" name="login">Masuk</button>
    </form>
    <div class="links">
        Belum punya akun? <a href="register.php">Daftar sekarang</a>
    </div>
</div>

</body>
</html>
