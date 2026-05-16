<?php
// DOKUMENTASI: Halaman login admin
session_start();

// DOKUMENTASI: Jika sudah login, langsung redirect ke dashboard
if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

require_once '../config.php';
$error = '';

// DOKUMENTASI: Proses form login saat tombol ditekan
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM admin WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin  = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        // DOKUMENTASI: Set session admin setelah login berhasil
        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — <em>Dusha-Kniga</em></title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:420px">
    <div class="card shadow-sm">
        <div class="card-header text-center fw-bold">Admin Login — <em>Dusha-Kniga</em></div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
