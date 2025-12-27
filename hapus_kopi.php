<?php
session_start();

if (isset($_GET['id'])) {
    $id_hapus = $_GET['id'];
    
    // Cari dan hapus kopi berdasarkan ID
    foreach ($_SESSION['kopi'] as $key => $kopi) {
        if ($kopi['id'] == $id_hapus) {
            unset($_SESSION['kopi'][$key]);
            break;
        }
    }
    
    // Reset array keys
    $_SESSION['kopi'] = array_values($_SESSION['kopi']);
    
    header("Location: index.php?pesan=Kopi berhasil dihapus!");
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>