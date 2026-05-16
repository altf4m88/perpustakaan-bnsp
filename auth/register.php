<?php
// DOKUMENTASI: Halaman registrasi user baru
session_start();
if (!empty($_SESSION['user_id'])) { header('Location: ../index.php'); exit; }

require_once '../config.php';
$error = '';

// DOKUMENTASI: Proses pendaftaran user baru
if (isset($_POST['daftar'])) {
    $nama    = trim($_POST['nama']);
    $email   = trim($_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];
    $telepon = trim($_POST['telepon']);
    $alamat  = trim($_POST['alamat']);

    // DOKUMENTASI: Validasi input registrasi
    if ($password !== $konfirmasi) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        // Cek email sudah terdaftar
        $cek = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'"))[0];
        if ($cek > 0) {
            $error = 'Email sudah terdaftar. Silakan login.';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, password, telepon, alamat) VALUES (?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, 'sssss', $nama, $email, $hash, $telepon, $alamat);
            mysqli_stmt_execute($stmt);
            header('Location: login.php?daftar=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:500px">
    <div class="card shadow-sm">
        <div class="card-header text-center fw-bold">Daftar Akun Baru</div>
        <div class="card-body">
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="konfirmasi" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2"></textarea>
                </div>
                <button type="submit" name="daftar" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="text-center mt-3 mb-0">Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
