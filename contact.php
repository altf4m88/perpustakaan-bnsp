<?php
// DOKUMENTASI: Halaman kontak — user mengirim pesan ke admin
session_start();
require_once 'config.php';

$sukses = false;
$error  = '';

// DOKUMENTASI: Pre-fill nama & email jika user sudah login
$nama_default  = '';
$email_default = '';
if (!empty($_SESSION['user_id'])) {
    $uid  = (int) $_SESSION['user_id'];
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, email FROM users WHERE id=$uid"));
    $nama_default  = $user['nama'];
    $email_default = $user['email'];
}

// DOKUMENTASI: Proses simpan pesan kontak ke database
if (isset($_POST['kirim'])) {
    $nama   = trim($_POST['nama']);
    $email  = trim($_POST['email']);
    $subjek = trim($_POST['subjek']);
    $pesan  = trim($_POST['pesan']);
    $id_user = !empty($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

    $stmt = mysqli_prepare($conn, "INSERT INTO pesan_kontak (id_user, nama, email, subjek, pesan) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'issss', $id_user, $nama, $email, $subjek, $pesan);
    if (mysqli_stmt_execute($stmt)) {
        $sukses = true;
    } else {
        $error = 'Gagal mengirim pesan. Silakan coba lagi.';
    }
}

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
    <title>Kontak — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar_user.php'; ?>
<div class="container mt-5" style="max-width:600px">
    <h4 class="mb-3">Hubungi Kami</h4>
    <p class="text-muted">Punya pertanyaan atau masukan? Kirim pesan kepada admin kami.</p>

    <?php if ($sukses): ?>
        <div class="alert alert-success">Pesan berhasil dikirim! Admin akan segera merespons.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Form kontak -->
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama_default) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email_default) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subjek</label>
            <input type="text" name="subjek" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Pesan</label>
            <textarea name="pesan" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" name="kirim" class="btn btn-primary">Kirim Pesan</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
