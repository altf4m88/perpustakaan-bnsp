<?php
// DOKUMENTASI: Halaman edit kategori
require_once '../auth_check.php';
require_once '../../config.php';

$id = (int) $_GET['id'];

// DOKUMENTASI: Ambil data kategori berdasarkan ID
$result = mysqli_query($conn, "SELECT * FROM kategori WHERE id = $id");
$row    = mysqli_fetch_assoc($result);
if (!$row) { header('Location: index.php'); exit; }

// DOKUMENTASI: Proses update saat form disubmit
if (isset($_POST['update'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    $deskripsi     = trim($_POST['deskripsi']);

    $stmt = mysqli_prepare($conn, "UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'ssi', $nama_kategori, $deskripsi, $id);
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
    <title>Edit Kategori — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4" style="max-width:600px">
    <h4 class="mb-3">Edit Kategori</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($row['nama_kategori']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
