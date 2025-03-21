<!-- filepath: c:\xampp\htdocs\Tugas8\admin.php -->
<?php
// Impor koneksi database
include 'koneksi.php';

$notification = ""; // Variabel untuk menyimpan pesan notifikasi

// Tambahkan logika untuk CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Tambah produk
        $nama_produk = $_POST['nama_produk'];
        $deskripsi = $_POST['deskripsi'];
        $gambar = $_POST['gambar'];
        $stok = $_POST['stok'];
        $kategori = $_POST['kategori'];
        $harga = $_POST['harga'];

        $stmt = $conn->prepare("INSERT INTO produk (nama_produk, deskripsi, gambar, stok, kategori, harga) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama_produk, $deskripsi, $gambar, $stok, $kategori, $harga]);

        $notification = "Produk berhasil ditambahkan!";
    } elseif (isset($_POST['edit_product'])) {
        // Edit produk
        $id = $_POST['id'];
        $nama_produk = $_POST['nama_produk'];
        $deskripsi = $_POST['deskripsi'];
        $gambar = $_POST['gambar'];
        $stok = $_POST['stok'];
        $kategori = $_POST['kategori'];
        $harga = $_POST['harga'];

        $stmt = $conn->prepare("UPDATE produk SET nama_produk = ?, deskripsi = ?, gambar = ?, stok = ?, kategori = ?, harga = ? WHERE id = ?");
        $stmt->execute([$nama_produk, $deskripsi, $gambar, $stok, $kategori, $harga, $id]);

        $notification = "Produk berhasil diperbarui!";
    } elseif (isset($_POST['delete_product'])) {
        // Hapus produk
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM produk WHERE id = ?");
        $stmt->execute([$id]);

        $notification = "Produk berhasil dihapus!";
    }
}

// Ambil data produk
$stmt = $conn->query("SELECT * FROM produk");
$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Kelola Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Kelola Produk</h2>
    <div class="text-start mb-3">
        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>

    <!-- Form Tambah Produk -->
    <div class="card mb-4">
        <div class="card-header">Tambah Produk</div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="gambar" class="form-label">URL Gambar</label>
                    <input type="text" class="form-control" id="gambar" name="gambar" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required>
                </div>
                <button type="submit" name="add_product" class="btn btn-primary">Tambah Produk</button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Produk -->
    <div class="card">
        <div class="card-header">Daftar Produk</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Stok</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produk as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                            <td><?= htmlspecialchars(substr($item['deskripsi'], 0, 50)) ?>...</td>
                            <td><img src="<?= htmlspecialchars($item['gambar']) ?>" alt="Gambar" width="50"></td>
                            <td><?= htmlspecialchars($item['stok']) ?></td>
                            <td><?= htmlspecialchars($item['kategori']) ?></td>
                            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                <button class="btn btn-warning btn-sm" onclick="editProduct(<?= htmlspecialchars(json_encode($item)) ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama_produk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="edit_nama_produk" name="nama_produk" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gambar" class="form-label">URL Gambar</label>
                        <input type="text" class="form-control" id="edit_gambar" name="gambar" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="edit_stok" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="edit_kategori" name="kategori" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit_product" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050">
    <div id="toastNotification" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <!-- Pesan notifikasi akan diisi melalui JavaScript -->
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function editProduct(product) {
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        document.getElementById('edit_id').value = product.id;
        document.getElementById('edit_nama_produk').value = product.nama_produk;
        document.getElementById('edit_deskripsi').value = product.deskripsi;
        document.getElementById('edit_gambar').value = product.gambar;
        document.getElementById('edit_stok').value = product.stok;
        document.getElementById('edit_kategori').value = product.kategori;
        document.getElementById('edit_harga').value = product.harga;
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function () {
        <?php if (!empty($notification)): ?>
            const toastEl = document.getElementById('toastNotification');
            const toastBody = toastEl.querySelector('.toast-body');
            toastBody.textContent = "<?= htmlspecialchars($notification) ?>";

            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        <?php endif; ?>
    });
</script>
</body>
</html>