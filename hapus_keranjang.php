<?php
session_start();

$id_produk = $_POST['id_produk'];

// Hapus produk dari keranjang
foreach ($_SESSION['keranjang'] as $key => $item) {
    if ($item['id_produk'] == $id_produk) {
        unset($_SESSION['keranjang'][$key]);
        break;
    }
}

// Redirect kembali ke halaman keranjang
header('Location: keranjang.php');
exit;
?>