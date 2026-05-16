<?php
// DOKUMENTASI: Halaman detail pesanan milik user — menampilkan item yang dipesan
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit; }

require_once '../config.php';
$uid = (int) $_SESSION['user_id'];
$id  = (int) $_GET['id'];

// DOKUMENTASI: Pastikan pesanan ini milik user yang sedang login
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id AND id_user=$uid"));
if (!$pesanan) { header('Location: pesanan.php'); exit; }

// DOKUMENTASI: Ambil detail item pesanan
$result = mysqli_query($conn, "SELECT dp.*, b.judul, b.pengarang FROM detail_pesanan dp JOIN buku b ON dp.id_buku = b.id WHERE dp.id_pesanan=$id");

$total_keranjang = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah),0) FROM keranjang WHERE id_user=$uid"))[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $id ?> — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar_user.php'; ?>
<div class="container mt-4" style="max-width:700px">
    <h4 class="mb-3">Detail Pesanan #<?= $id ?></h4>
    <div class="card mb-3">
        <div class="card-body">
            <p class="mb-1"><strong>Status:</strong>
                <?php
                $badge = ['pending'=>'secondary','diproses'=>'primary','dikirim'=>'info','selesai'=>'success','dibatalkan'=>'danger'];
                $warna = $badge[$pesanan['status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $warna ?>"><?= $pesanan['status'] ?></span>
            </p>
            <p class="mb-1"><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($pesanan['alamat_pengiriman']) ?></p>
            <p class="mb-1"><strong>Catatan:</strong> <?= htmlspecialchars($pesanan['catatan'] ?: '-') ?></p>
            <p class="mb-1"><strong>Metode Bayar:</strong> Payment at Delivery</p>
            <p class="mb-0"><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($pesanan['created_at'])) ?></p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>Buku</th><th>Harga Satuan</th><th>Jumlah</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
        <?php while ($item = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($item['judul']) ?><br><small class="text-muted"><?= htmlspecialchars($item['pengarang']) ?></small></td>
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
    <a href="pesanan.php" class="btn btn-secondary">← Kembali</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
