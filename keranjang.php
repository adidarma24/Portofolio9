<?php
session_start();

// Periksa apakah keranjang sudah ada di session
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Ambil data produk dari form
$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$harga = $_POST['harga'];
$gambar = $_POST['gambar'];

// Periksa apakah produk sudah ada di keranjang
$produk_ada = false;
foreach ($_SESSION['keranjang'] as &$item) {
    if ($item['id_produk'] == $id_produk) {
        $item['jumlah'] += 1; // Tambahkan jumlah jika produk sudah ada
        $produk_ada = true;
        break;
    }
}

// Jika produk belum ada, tambahkan ke keranjang
if (!$produk_ada) {
    $_SESSION['keranjang'][] = [
        'id_produk' => $id_produk,
        'nama_produk' => $nama_produk,
        'harga' => $harga,
        'gambar' => $gambar,
        'jumlah' => 1
    ];
}

// Redirect kembali ke halaman sebelumnya
header('Location: index.php');
exit;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Keranjang Belanja</h2>
    <?php if (!empty($_SESSION['keranjang'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['keranjang'] as $item): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Gambar Produk" style="width: 50px; height: 50px;"></td>
                        <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                        <td>Rp<?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $item['jumlah']; ?></td>
                        <td>Rp<?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?></td>
                        <td>
                            <form method="post" action="hapus_keranjang.php">
                                <input type="hidden" name="id_produk" value="<?php echo htmlspecialchars($item['id_produk']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-primary">Lanjut Belanja</a>
    <?php else: ?>
        <p class="text-center">Keranjang belanja kosong.</p>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Belanja Sekarang</a>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>