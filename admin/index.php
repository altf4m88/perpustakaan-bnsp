<?php
// DOKUMENTASI: Dashboard admin — ringkasan statistik toko
require_once 'auth_check.php';
require_once '../config.php';

// DOKUMENTASI: Hitung total masing-masing entitas untuk kartu statistik
$total_buku     = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM buku"))[0];
$total_kategori = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM kategori"))[0];
$total_users    = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users"))[0];
$total_pesanan  = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pesanan"))[0];
$pesan_baru     = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM pesan_kontak WHERE status='belum_dibaca'"))[0];

// DOKUMENTASI: 5 pesanan terbaru untuk ditampilkan di dashboard
$query_pesanan = "SELECT p.id, u.nama, p.total_harga, p.status, p.created_at
                  FROM pesanan p JOIN users u ON p.id_user = u.id
                  ORDER BY p.created_at asc LIMIT 5";
$result_pesanan = mysqli_query($conn, $query_pesanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar.php'; ?>
<div class="container mt-4">
    <h4 class="mb-4">Dashboard</h4>

    <!-- DOKUMENTASI: Kartu statistik ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body"><h6 class="card-title">Total Buku</h6><h2><?= $total_buku ?></h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body"><h6 class="card-title">Kategori</h6><h2><?= $total_kategori ?></h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body"><h6 class="card-title">Total User</h6><h2><?= $total_users ?></h2></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body"><h6 class="card-title">Pesanan</h6><h2><?= $total_pesanan ?></h2></div>
            </div>
        </div>
    </div>

    <?php if ($pesan_baru > 0): ?>
    <div class="alert alert-info">Ada <strong><?= $pesan_baru ?></strong> pesan kontak baru. <a href="pesan/index.php">Lihat sekarang</a></div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Tabel 5 pesanan terbaru -->
    <div class="card">
        <div class="card-header">Pesanan Terbaru</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-dark">
                    <tr><th>#</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_pesanan)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php if ($total_pesanan == 0): ?>
                    <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if ($total_pesanan > 0): ?>
        <div class="card-footer"><a href="pesanan/index.php">Lihat semua pesanan →</a></div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
