<?php
// DOKUMENTASI: Halaman checkout — konfirmasi pesanan dan pilih metode pembayaran
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit; }

require_once '../config.php';
$uid = (int) $_SESSION['user_id'];

// DOKUMENTASI: Tambah kolom metode_pembayaran jika belum ada (one-time migration)
mysqli_query($conn, "ALTER TABLE pesanan ADD COLUMN IF NOT EXISTS metode_pembayaran ENUM('cod','va') NOT NULL DEFAULT 'cod'");

// DOKUMENTASI: Ambil data user untuk pre-fill alamat
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));

// DOKUMENTASI: Ambil item keranjang
$query  = "SELECT k.*, b.judul, b.harga, b.stok FROM keranjang k JOIN buku b ON k.id_buku = b.id WHERE k.id_user=$uid";
$result = mysqli_query($conn, $query);
$items  = [];
$total  = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $row['subtotal'] = $row['harga'] * $row['jumlah'];
    $total          += $row['subtotal'];
    $items[]         = $row;
}

if (empty($items)) { header('Location: keranjang.php'); exit; }

$error = '';

// DOKUMENTASI: Proses konfirmasi pesanan
if (isset($_POST['pesan'])) {
    $alamat_pengiriman = trim($_POST['alamat_pengiriman']);
    $catatan           = trim($_POST['catatan']);
    $metode            = $_POST['metode_pembayaran'] === 'va' ? 'va' : 'cod';

    if (empty($alamat_pengiriman)) {
        $error = 'Alamat pengiriman wajib diisi.';
    } else {
        foreach ($items as $item) {
            if ($item['jumlah'] > $item['stok']) {
                $error = "Stok buku \"" . htmlspecialchars($item['judul']) . "\" tidak mencukupi.";
                break;
            }
        }

        if (!$error) {
            // DOKUMENTASI: Simpan header pesanan beserta metode pembayaran
            $stmt = mysqli_prepare($conn, "INSERT INTO pesanan (id_user, total_harga, alamat_pengiriman, catatan, metode_pembayaran) VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, 'idsss', $uid, $total, $alamat_pengiriman, $catatan, $metode);
            mysqli_stmt_execute($stmt);
            $id_pesanan = mysqli_insert_id($conn);

            // DOKUMENTASI: Simpan detail item dan kurangi stok
            foreach ($items as $item) {
                $subtotal = $item['subtotal'];
                $stmt2    = mysqli_prepare($conn, "INSERT INTO detail_pesanan (id_pesanan, id_buku, jumlah, harga_satuan, subtotal) VALUES (?,?,?,?,?)");
                mysqli_stmt_bind_param($stmt2, 'iiidd', $id_pesanan, $item['id_buku'], $item['jumlah'], $item['harga'], $subtotal);
                mysqli_stmt_execute($stmt2);
                mysqli_query($conn, "UPDATE buku SET stok=stok-{$item['jumlah']} WHERE id={$item['id_buku']}");
            }

            mysqli_query($conn, "DELETE FROM keranjang WHERE id_user=$uid");

            // DOKUMENTASI: Arahkan ke halaman VA jika metode virtual account
            if ($metode === 'va') {
                header('Location: payment_va.php?id=' . $id_pesanan);
            } else {
                header('Location: pesanan.php?sukses=' . $id_pesanan);
            }
            exit;
        }
    }
}

$total_keranjang = count($items);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar_user.php'; ?>
<div class="container mt-4">
    <h4 class="mb-4">Konfirmasi Pesanan</h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <div class="row g-4">
        <!-- DOKUMENTASI: Ringkasan item yang akan dipesan -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header fw-semibold">Ringkasan Pesanan</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light"><tr><th>Buku</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['judul']) ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr><td colspan="2" class="fw-bold text-end">Total</td><td class="fw-bold">Rp <?= number_format($total, 0, ',', '.') ?></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- DOKUMENTASI: Form detail pengiriman dan pilih metode bayar -->
        <div class="col-md-5">
            <form method="POST">
                <!-- Metode Pembayaran -->
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Metode Pembayaran</div>
                    <div class="card-body d-flex flex-column gap-2">

                        <!-- DOKUMENTASI: Pilihan COD -->
                        <label class="d-flex align-items-start gap-3 border rounded p-3" style="cursor:pointer" id="label-cod">
                            <input type="radio" name="metode_pembayaran" value="cod" checked class="mt-1" onchange="togglePaymentInfo()">
                            <div>
                                <div class="fw-semibold">Bayar di Tempat <span class="badge bg-success ms-1">COD</span></div>
                                <small class="text-muted">Bayar tunai saat buku tiba di depan pintu Anda.</small>
                            </div>
                        </label>

                        <!-- DOKUMENTASI: Pilihan Virtual Account -->
                        <label class="d-flex align-items-start gap-3 border rounded p-3" style="cursor:pointer" id="label-va">
                            <input type="radio" name="metode_pembayaran" value="va" class="mt-1" onchange="togglePaymentInfo()">
                            <div>
                                <div class="fw-semibold">Virtual Account <span class="badge bg-primary ms-1">VA</span></div>
                                <small class="text-muted">Transfer ke nomor VA bank pilihan Anda. Pesanan diproses setelah pembayaran dikonfirmasi.</small>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- Alamat Pengiriman -->
                <div class="card mb-3">
                    <div class="card-header fw-semibold">Detail Pengiriman</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea name="alamat_pengiriman" class="form-control" rows="3" required><?= htmlspecialchars($user['alamat']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <input type="text" name="catatan" class="form-control" placeholder="Catatan untuk kurir...">
                        </div>
                    </div>
                </div>

                <button type="submit" name="pesan" class="btn btn-primary w-100">Konfirmasi Pesanan</button>
                <a href="keranjang.php" class="btn btn-outline-secondary w-100 mt-2">← Kembali ke Keranjang</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// DOKUMENTASI: Highlight kartu metode pembayaran yang dipilih
function togglePaymentInfo() {
    const isCod = document.querySelector('input[value="cod"]').checked;
    document.getElementById('label-cod').style.borderColor = isCod ? '#0d6efd' : '';
    document.getElementById('label-cod').style.background  = isCod ? '#f0f5ff' : '';
    document.getElementById('label-va').style.borderColor  = !isCod ? '#0d6efd' : '';
    document.getElementById('label-va').style.background   = !isCod ? '#f0f5ff' : '';
}
togglePaymentInfo();
</script>
</body>
</html>
