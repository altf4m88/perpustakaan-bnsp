<?php
// DOKUMENTASI: Halaman daftar kategori buku beserta aksi hapus
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Proses hapus kategori jika ada parameter id
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    // Cek apakah kategori masih dipakai buku
    $cek = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM buku WHERE id_kategori = $id"))[0];
    if ($cek > 0) {
        $error = "Kategori tidak bisa dihapus karena masih memiliki $cek buku.";
    } else {
        mysqli_query($conn, "DELETE FROM kategori WHERE id = $id");
        header('Location: index.php?sukses=1');
        exit;
    }
}

// DOKUMENTASI: Ambil semua kategori beserta jumlah buku per kategori
$query  = "SELECT k.*, COUNT(b.id) AS jumlah_buku FROM kategori k LEFT JOIN buku b ON k.id = b.id_kategori GROUP BY k.id ORDER BY k.created_at ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Manajemen Kategori</h4>
        <a href="create.php" class="btn btn-primary">+ Tambah Kategori</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success">Operasi berhasil.</div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Tabel daftar kategori -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td><?= $row['jumlah_buku'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
