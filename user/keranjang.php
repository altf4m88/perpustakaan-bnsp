<?php
// DOKUMENTASI: Halaman keranjang belanja user
session_start();

// DOKUMENTASI: Redirect ke login jika belum login, simpan tujuan agar bisa balik setelah login
if (empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = '/bnsp-preps/user/keranjang.php';
    header('Location: ../auth/login.php');
    exit;
}

require_once '../config.php';
$uid = (int) $_SESSION['user_id'];

// DOKUMENTASI: Tambah buku ke keranjang (juga bisa dipanggil dari index.php)
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $id_buku = (int) $_POST['id_buku'];
    // Cek apakah buku sudah ada di keranjang
    $ada = mysqli_fetch_row(mysqli_query($conn, "SELECT id, jumlah FROM keranjang WHERE id_user=$uid AND id_buku=$id_buku"));
    if ($ada) {
        mysqli_query($conn, "UPDATE keranjang SET jumlah=jumlah+1 WHERE id={$ada[0]}");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (id_user, id_buku, jumlah) VALUES ($uid, $id_buku, 1)");
    }
    header('Location: keranjang.php');
    exit;
}

// DOKUMENTASI: Update jumlah item di keranjang
if (isset($_POST['aksi']) && $_POST['aksi'] === 'update') {
    $id_keranjang = (int) $_POST['id_keranjang'];
    $jumlah       = (int) $_POST['jumlah'];
    if ($jumlah < 1) $jumlah = 1;
    mysqli_query($conn, "UPDATE keranjang SET jumlah=$jumlah WHERE id=$id_keranjang AND id_user=$uid");
    header('Location: keranjang.php');
    exit;
}

// DOKUMENTASI: Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id_keranjang = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE id=$id_keranjang AND id_user=$uid");
    header('Location: keranjang.php');
    exit;
}

// DOKUMENTASI: Ambil semua item keranjang milik user beserta data buku
$query  = "SELECT k.*, b.judul, b.pengarang, b.harga, b.stok, b.cover_image FROM keranjang k JOIN buku b ON k.id_buku = b.id WHERE k.id_user=$uid";
$result = mysqli_query($conn, $query);

$total_harga = 0;
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['harga'] * $row['jumlah'];
    $total_harga    += $row['subtotal'];
    $items[]         = $row;
}

$total_keranjang = array_sum(array_column($items, 'jumlah'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar_user.php'; ?>
<div class="container mt-4">
    <h4 class="mb-3">Keranjang Belanja</h4>

    <?php if (empty($items)): ?>
        <p class="text-muted">Keranjang Anda kosong. <a href="../index.php">Belanja sekarang</a></p>
    <?php else: ?>
        <!-- DOKUMENTASI: Tabel item keranjang -->
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr><th>Buku</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th>Hapus</th></tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($item['judul']) ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($item['pengarang']) ?></small>
                    </td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td style="width:160px">
                        <!-- DOKUMENTASI: Form update jumlah item -->
                        <form method="POST" class="d-flex gap-1">
                            <input type="hidden" name="aksi" value="update">
                            <input type="hidden" name="id_keranjang" value="<?= $item['id'] ?>">
                            <input type="number" name="jumlah" class="form-control form-control-sm" value="<?= $item['jumlah'] ?>" min="1" max="<?= $item['stok'] ?>">
                            <button type="submit" class="btn btn-sm btn-secondary">OK</button>
                        </form>
                    </td>
                    <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    <td>
                        <a href="keranjang.php?hapus=<?= $item['id'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus dari keranjang?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end fw-bold">Total</td>
                    <td class="fw-bold text-primary" colspan="2">Rp <?= number_format($total_harga, 0, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between">
            <a href="../index.php" class="btn btn-outline-secondary">← Lanjut Belanja</a>
            <a href="checkout.php" class="btn btn-primary">Checkout →</a>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
