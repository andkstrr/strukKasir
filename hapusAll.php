<?php 
session_start();
unset($_SESSION['data_barang']);
header("Location: index.php");
exit;
?>
