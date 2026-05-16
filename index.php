<?php
// DOKUMENTASI: Halaman utama Dusha-Kniga — katalog buku dengan pencarian dan filter kategori
session_start();
require_once 'config.php';

// DOKUMENTASI: Ambil semua kategori untuk filter
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

// DOKUMENTASI: Bangun query pencarian dan filter
$cari      = isset($_GET['cari'])      ? trim($_GET['cari'])       : '';
$id_kat    = isset($_GET['kategori'])  ? (int) $_GET['kategori']   : 0;

$conditions = ["b.stok > 0"];
if ($cari) {
    $safe = mysqli_real_escape_string($conn, $cari);
    $conditions[] = "(b.judul LIKE '%$safe%' OR b.pengarang LIKE '%$safe%')";
}
if ($id_kat) {
    $conditions[] = "b.id_kategori = $id_kat";
}
$where  = "WHERE " . implode(" AND ", $conditions);
$query  = "SELECT b.*, k.nama_kategori FROM buku b JOIN kategori k ON b.id_kategori = k.id $where ORDER BY b.created_at ASC";
$result = mysqli_query($conn, $query);

// DOKUMENTASI: Hitung total item di keranjang user (ditampilkan di navbar)
$total_keranjang = 0;
if (!empty($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    $total_keranjang = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah),0) FROM keranjang WHERE id_user=$uid"))[0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dusha-Kniga — Toko Buku Online</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<!-- DOKUMENTASI: Navbar utama untuk user -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Dusha-Kniga</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="user/keranjang.php" class="btn btn-light btn-sm">
                        Keranjang <?= $total_keranjang > 0 ? "($total_keranjang)" : '' ?>
                    </a>
                    <a href="user/pesanan.php" class="btn btn-light btn-sm">Pesanan Saya</a>
                    <span class="text-light">Halo, <?= htmlspecialchars($_SESSION['user_nama']) ?></span>
                    <a href="auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="auth/login.php" class="btn btn-light btn-sm">Login</a>
                    <a href="auth/register.php" class="btn btn-primary btn-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <!-- DOKUMENTASI: Form pencarian dan filter kategori -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Cari judul atau pengarang..." value="<?= htmlspecialchars($cari) ?>">
        </div>
        <div class="col-md-4">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php
                // Reset pointer kategori
                mysqli_data_seek($kategori_result, 0);
                while ($k = mysqli_fetch_assoc($kategori_result)):
                ?>
                    <option value="<?= $k['id'] ?>" <?= $id_kat == $k['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
    </form>

    <!-- DOKUMENTASI: Grid kartu buku -->
    <div class="row g-3">
    <?php
    $jumlah_buku = 0;
    while ($row = mysqli_fetch_assoc($result)):
        $jumlah_buku++;
    ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100">
                <div style="height:220px;background:#f5f0eb;display:flex;align-items:center;justify-content:center;padding:12px">
                    <?php if ($row['cover_image']): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($row['cover_image']) ?>" style="max-width:100%;max-height:196px;object-fit:contain;box-shadow:2px 4px 12px rgba(0,0,0,0.18)">
                    <?php else: ?>
                        <span class="text-muted" style="font-size:0.85rem">No Cover</span>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title"><?= htmlspecialchars($row['judul']) ?></h6>
                    <small class="text-muted"><?= htmlspecialchars($row['pengarang']) ?></small>
                    <small class="badge bg-light text-dark border mb-2 mt-1 align-self-start"><?= htmlspecialchars($row['nama_kategori']) ?></small>
                    <p class="fw-bold text-primary mt-auto mb-1">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <small class="text-muted mb-2">Stok: <?= $row['stok'] ?></small>
                    <!-- DOKUMENTASI: Tombol tambah ke keranjang -->
                    <form method="POST" action="user/keranjang.php">
                        <input type="hidden" name="id_buku" value="<?= $row['id'] ?>">
                        <input type="hidden" name="aksi" value="tambah">
                        <button type="submit" class="btn btn-primary btn-sm w-100">+ Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    <?php if ($jumlah_buku === 0): ?>
        <div class="col-12"><p class="text-muted text-center">Tidak ada buku ditemukan.</p></div>
    <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
