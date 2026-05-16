<?php
// DOKUMENTASI: Halaman riwayat pesanan milik user yang sedang login
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit; }

require_once '../config.php';
$uid = (int) $_SESSION['user_id'];

// DOKUMENTASI: Ambil semua pesanan milik user ini
$query  = "SELECT * FROM pesanan WHERE id_user=$uid ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

$total_keranjang = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah),0) FROM keranjang WHERE id_user=$uid"))[0];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar_user.php'; ?>
<div class="container mt-4">
    <h4 class="mb-3">Pesanan Saya</h4>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success">
            Pesanan #<?= (int)$_GET['sukses'] ?> berhasil dibuat!
            <br><small>Metode: <strong>Bayar di Tempat (COD)</strong>. Pesanan akan segera diproses.</small>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['bayar'])): ?>
        <div class="alert alert-success">
            Pembayaran untuk pesanan #<?= (int)$_GET['bayar'] ?> dikonfirmasi. Kami sedang memverifikasi transfer Anda.
        </div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Tabel riwayat pesanan -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr><th>#</th><th>Total</th><th>Metode</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php
        $ada_pesanan = false;
        while ($row = mysqli_fetch_assoc($result)):
            $ada_pesanan = true;
        ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <?php if (($row['metode_pembayaran'] ?? 'cod') === 'va'): ?>
                        <span class="badge bg-primary">VA</span>
                    <?php else: ?>
                        <span class="badge bg-success">COD</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                    $badge = ['pending'=>'secondary','diproses'=>'primary','dikirim'=>'info','selesai'=>'success','dibatalkan'=>'danger'];
                    $warna = $badge[$row['status']] ?? 'secondary';
                    ?>
                    <span class="badge bg-<?= $warna ?>"><?= $row['status'] ?></span>
                </td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td class="d-flex gap-1">
                    <a href="detail_pesanan.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Lihat</a>
                    <?php if (($row['metode_pembayaran'] ?? 'cod') === 'va' && $row['status'] === 'pending'): ?>
                        <a href="payment_va.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Bayar</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        <?php if (!$ada_pesanan): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada pesanan. <a href="../index.php">Mulai belanja</a></td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
