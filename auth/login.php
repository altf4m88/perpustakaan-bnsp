<?php
// DOKUMENTASI: Halaman login user
session_start();
if (!empty($_SESSION['user_id'])) { header('Location: ../index.php'); exit; }

require_once '../config.php';
$error = '';

// DOKUMENTASI: Proses verifikasi login user
if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, nama, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // DOKUMENTASI: Set session user setelah login berhasil
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_nama'] = $user['nama'];
        $redirect = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '../index.php';
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Email atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <em>Dusha-Kniga</em></title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-header text-center fw-bold">Login — <em>Dusha-Kniga</em></div>
        <div class="card-body">
            <?php if (isset($_GET['daftar'])): ?>
                <div class="alert alert-success">Registrasi berhasil! Silakan login.</div>
            <?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="text-center mt-3 mb-0">Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
</div>
</body>
</html>
