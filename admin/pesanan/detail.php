<?php
// DOKUMENTASI: Halaman detail pesanan — menampilkan item-item yang dipesan
require_once '../auth_check.php';
require_once '../../config.php';

$id      = (int) $_GET['id'];
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT p.*, u.nama, u.email, u.telepon FROM pesanan p JOIN users u ON p.id_user = u.id WHERE p.id = $id"));
if (!$pesanan) { header('Location: index.php'); exit; }

// DOKUMENTASI: Ambil semua item dalam pesanan ini
$query_detail = "SELECT dp.*, b.judul FROM detail_pesanan dp JOIN buku b ON dp.id_buku = b.id WHERE dp.id_pesanan = $id";
$result_detail = mysqli_query($conn, $query_detail);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $id ?> — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4" style="max-width:750px">
    <h4 class="mb-3">Detail Pesanan #<?= $id ?></h4>

    <!-- DOKUMENTASI: Info pembeli dan pesanan -->
    <div class="card mb-3">
        <div class="card-body">
            <p class="mb-1"><strong>Pembeli:</strong> <?= htmlspecialchars($pesanan['nama']) ?> (<?= htmlspecialchars($pesanan['email']) ?>)</p>
            <p class="mb-1"><strong>Telepon:</strong> <?= htmlspecialchars($pesanan['telepon']) ?></p>
            <p class="mb-1"><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($pesanan['alamat_pengiriman']) ?></p>
            <p class="mb-1"><strong>Catatan:</strong> <?= htmlspecialchars($pesanan['catatan'] ?: '-') ?></p>
            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-secondary"><?= $pesanan['status'] ?></span></p>
            <p class="mb-0"><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?></p>
        </div>
    </div>

    <!-- DOKUMENTASI: Tabel item pesanan -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Buku</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($result_detail)): ?>
            <tr>
                <td><?= htmlspecialchars($item['judul']) ?></td>
                <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                <td><?= $item['jumlah'] ?></td>
                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="3" class="text-end fw-bold">Total</td><td class="fw-bold">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></td></tr>
        </tfoot>
    </table>
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
</div>
</body>
</html>
