<?php
// Impor koneksi database
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beranda E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">E-Commerce</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Keranjang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Kontak</a>
                </li>
                <!-- Tambahkan tautan ke halaman admin -->
                <li class="nav-item">
                    <a class="nav-link text-danger" href="admin.php">Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid p-0">
    <div class="banner">
        <img src="https://t3.ftcdn.net/jpg/04/65/46/52/360_F_465465254_1pN9MGrA831idD6zIBL7q8rnZZpUCQTy.jpg" alt="Banner" class="img-fluid w-100">
        <div class="banner-text text-center">
            <!-- <h1 class="text-white fw-bold">Selamat Datang di E-Commerce Kami</h1>
            <p class="text-white">Temukan produk terbaik dengan harga terbaik!</p> -->
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Kolom Filter -->
        <div class="col-md-3">
            <div class="filter-section bg-white p-4 rounded shadow-sm">
                <h5 class="mb-3">Filter</h5>
                <div class="mb-3">
                    <label for="filterKategori" class="form-label">Kategori</label>
                    <select id="filterKategori" class="form-select" onchange="filterProduk()">
                        <option value="">Semua Kategori</option>
                        <?php
                        $kategoriQuery = "SELECT DISTINCT kategori FROM produk";
                        $stmt = $conn->query($kategoriQuery);
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . htmlspecialchars($row['kategori']) . "'>" . htmlspecialchars($row['kategori']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="minHarga" class="form-label">Harga Minimum</label>
                    <input type="number" id="minHarga" class="form-control" placeholder="Masukkan harga minimum" oninput="filterProduk()">
                </div>
                <div class="mb-3">
                    <label for="maxHarga" class="form-label">Harga Maksimum</label>
                    <input type="number" id="maxHarga" class="form-control" placeholder="Masukkan harga maksimum" oninput="filterProduk()">
                </div>
                <button class="btn btn-primary w-100" onclick="resetFilter()">Reset Filter</button>
            </div>
        </div>

        <!-- Kolom Produk -->
        <div class="col-md-9">
            <h2 class="text-center mb-4">Daftar Produk</h2>
            <div class="row g-4" id="produkContainer">
                <?php
                $sql = "SELECT * FROM produk";
                $stmt = $conn->query($sql);
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='col-md-4 produk-item' data-kategori='" . htmlspecialchars($row['kategori']) . "' data-harga='" . htmlspecialchars($row['harga']) . "'>";
                        echo "<div class='card h-100 shadow-sm'>";
                        echo "<img src='" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar Produk'>";
                        echo "<div class='card-body d-flex flex-column'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($row['nama_produk']) . "</h5>";
                        echo "<p class='text-muted'><strong>Kategori:</strong> " . htmlspecialchars($row['kategori']) . "</p>";
                        echo "<p class='text-danger'><strong>Harga:</strong> Rp" . number_format($row['harga'], 0, ',', '.') . "</p>";
                        $shortDeskripsi = strlen($row['deskripsi']) > 100 ? substr($row['deskripsi'], 0, 100) . "..." : $row['deskripsi'];
                        echo "<p class='card-text short-deskripsi'>" . htmlspecialchars($shortDeskripsi) . "</p>";
                        echo "<button class='btn btn-primary mt-auto'>Tambah ke Keranjang</button>";
                        echo "</div></div></div>";
                    }
                } else {
                    echo "<p class='text-center'>Tidak ada produk tersedia.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <h2 class="text-center mb-4">Produk Terbaru</h2>
    <div class="row g-4" id="produkContainer">
        <?php
        $sql = "SELECT * FROM produk";
        $stmt = $conn->query($sql);
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='col-md-4 produk-item' data-kategori='" . htmlspecialchars($row['kategori']) . "' data-harga='" . htmlspecialchars($row['harga']) . "'>";
                echo "<div class='card h-100 shadow-sm'>";
                echo "<img src='" . htmlspecialchars($row['gambar']) . "' class='card-img-top' alt='Gambar Produk'>";
                echo "<div class='card-body d-flex flex-column'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['nama_produk']) . "</h5>";
                echo "<p class='text-muted'><strong>Kategori:</strong> " . htmlspecialchars($row['kategori']) . "</p>";
                echo "<p class='text-danger'><strong>Harga:</strong> Rp" . number_format($row['harga'], 0, ',', '.') . "</p>";
                $shortDeskripsi = strlen($row['deskripsi']) > 100 ? substr($row['deskripsi'], 0, 100) . "..." : $row['deskripsi'];
                echo "<p class='card-text short-deskripsi'>" . htmlspecialchars($shortDeskripsi) . "</p>";
                echo "<button class='btn btn-primary mt-auto'>Tambah ke Keranjang</button>";
                echo "</div></div></div>";
            }
        } else {
            echo "<p class='text-center'>Tidak ada produk tersedia.</p>";
        }
        ?>
    </div>
</div>
<script>
    function resetFilter() {
        document.getElementById("filterKategori").value = "";
        document.getElementById("minHarga").value = "";
        document.getElementById("maxHarga").value = "";
        filterProduk();
    }

    function filterProduk() {
        let kategori = document.getElementById("filterKategori").value;
        let minHarga = parseFloat(document.getElementById("minHarga").value) || 0;
        let maxHarga = parseFloat(document.getElementById("maxHarga").value) || Infinity;
        let produkItems = document.querySelectorAll(".produk-item");

        produkItems.forEach(item => {
            let itemKategori = item.getAttribute("data-kategori");
            let itemHarga = parseFloat(item.getAttribute("data-harga"));

            if (
                (kategori === "" || itemKategori === kategori) &&
                itemHarga >= minHarga &&
                itemHarga <= maxHarga
            ) {
                item.style.display = "block";
            } else {
                item.style.display = "none";
            }
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButtons = document.querySelectorAll(".toggle-deskripsi");

        toggleButtons.forEach(button => {
            button.addEventListener("click", function () {
                const cardBody = this.closest(".card-body");
                const shortDeskripsi = cardBody.querySelector(".short-deskripsi");
                const fullDeskripsi = cardBody.querySelector(".full-deskripsi");

                if (shortDeskripsi.classList.contains("d-none")) {
                    shortDeskripsi.classList.remove("d-none");
                    fullDeskripsi.classList.add("d-none");
                    this.textContent = "Baca Selengkapnya";
                } else {
                    shortDeskripsi.classList.add("d-none");
                    fullDeskripsi.classList.remove("d-none");
                    this.textContent = "Tutup";
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const kategoriLinks = document.querySelectorAll(".kategori-link");
        const produkItems = document.querySelectorAll(".produk-item");

        kategoriLinks.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const kategori = this.getAttribute("data-kategori");

                produkItems.forEach(item => {
                    const itemKategori = item.getAttribute("data-kategori");
                    if (kategori === "" || itemKategori === kategori) {
                        item.style.display = "block";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        });
    });
</script>
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <p>&copy; 2025 E-Commerce. AdiShop.</p>
        <p>Hubungi kami di: <a href="adidarmap@gmail.com" class="text-warning">adidarmap@gmail.com</a></p>
        <div class="social-icons">
            <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>