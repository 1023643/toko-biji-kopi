<?php
session_start();
include '../koneksi.php';

// Check Admin Access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle Approvals
if (isset($_POST['approve'])) {
    $id = $_POST['user_id'];
    mysqli_query($koneksi, "UPDATE users SET status='approved' WHERE id='$id'");
}
if (isset($_POST['reject'])) {
    $id = $_POST['user_id'];
    mysqli_query($koneksi, "UPDATE users SET status='rejected' WHERE id='$id'");
}

// Get Pending Sellers
$pending = mysqli_query($koneksi, "SELECT * FROM users WHERE role='seller' AND status='pending'");

// Get Active Users
$users = mysqli_query($koneksi, "SELECT * FROM users WHERE status='approved' ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Toko Kopi</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; margin: 0; background: #f4f4f4; }
        .sidebar { width: 250px; background: #2c3e50; color: white; position: fixed; height: 100vh; padding: 20px; }
        .content { margin-left: 290px; padding: 20px; }
        .sidebar h2 { margin-top: 0; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-radius: 5px; margin-bottom: 5px; }
        .sidebar a:hover { background: #34495e; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #f8f9fa; }
        .btn { padding: 5px 10px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-green { background: #27ae60; }
        .btn-red { background: #c0392b; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 12px; }
        .badge-seller { background: #e67e22; color: white; }
        .badge-buyer { background: #3498db; color: white; }
        .badge-admin { background: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>☕ Admin Panel</h2>
    <a href="dashboard.php" style="background: #34495e;">🏠 Dashboard</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="content">
    <h1>Selamat Datang, Admin!</h1>

    <!-- PENDING APPROVALS -->
    <?php if (mysqli_num_rows($pending) > 0): ?>
    <div class="card" style="border-left: 5px solid #e67e22;">
        <h3>⚠️ Menunggu Persetujuan (Seller Baru)</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($pending)): ?>
                <tr>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><span class="badge badge-seller">Seller</span></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="approve" class="btn btn-green">✅ Terima</button>
                            <button type="submit" name="reject" class="btn btn-red">❌ Tolak</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- USERS LIST -->
    <div class="card">
        <h3>👥 Daftar Pengguna Aktif</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama_lengkap'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td>
                        <span class="badge badge-<?= $row['role'] ?>"><?= ucfirst($row['role']) ?></span>
                    </td>
                    <td><?= ucfirst($row['status']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
