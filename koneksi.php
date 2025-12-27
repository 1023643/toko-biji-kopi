<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "toko_kopi";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    // If connection fails, check if it's because DB doesn't exist yet
    $koneksi_no_db = mysqli_connect($host, $user, $pass);
    if ($koneksi_no_db) {
        // DB server is up, but DB might not exist. 
        // We will handle this in setup, allowing this file to be included gracefully or error out.
        // For now, die with error.
        die("Gagal terhubung ke database: " . mysqli_connect_error());
    }
    die("Gagal terhubung ke MySQL Server: " . mysqli_connect_error());
}
?>

