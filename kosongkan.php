<?php
session_start();
$_SESSION['keranjang'] = [];
header("Location: index.php");
exit;
?>