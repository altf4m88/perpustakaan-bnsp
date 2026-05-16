<?php
// DOKUMENTASI: Halaman tambah kategori baru
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Proses simpan data saat form disubmit
if (isset($_POST['simpan'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    $deskripsi     = trim($_POST['deskripsi']);

    $stmt = mysqli_prepare($conn, "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'ss', $nama_kategori, $deskripsi);
    mysqli_stmt_execute($stmt);
    header('Location: index.php?sukses=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4" style="max-width:600px">
    <h4 class="mb-3">Tambah Kategori</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
