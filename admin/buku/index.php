<?php
// DOKUMENTASI: Halaman daftar buku dengan pencarian
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Proses hapus buku beserta file cover-nya
if (isset($_GET['hapus'])) {
    $id  = (int) $_GET['hapus'];
    $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT cover_image FROM buku WHERE id = $id"));
    if ($row && $row['cover_image'] && file_exists(UPLOAD_DIR . $row['cover_image'])) {
        unlink(UPLOAD_DIR . $row['cover_image']);
    }
    mysqli_query($conn, "DELETE FROM buku WHERE id = $id");
    header('Location: index.php?sukses=1');
    exit;
}

// DOKUMENTASI: Pencarian buku berdasarkan judul atau pengarang
$cari  = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$where = '';
if ($cari) {
    $safe  = mysqli_real_escape_string($conn, $cari);
    $where = "WHERE b.judul LIKE '%$safe%' OR b.pengarang LIKE '%$safe%'";
}

$query  = "SELECT b.*, k.nama_kategori FROM buku b JOIN kategori k ON b.id_kategori = k.id $where ORDER BY b.created_at ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Manajemen Buku</h4>
        <a href="create.php" class="btn btn-primary">+ Tambah Buku</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success">Operasi berhasil.</div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Form pencarian buku -->
    <form method="GET" class="mb-3 d-flex gap-2">
        <input type="text" name="cari" class="form-control" placeholder="Cari judul atau pengarang..." value="<?= htmlspecialchars($cari) ?>">
        <button type="submit" class="btn btn-secondary">Cari</button>
        <?php if ($cari): ?><a href="index.php" class="btn btn-outline-secondary">Reset</a><?php endif; ?>
    </form>

    <!-- DOKUMENTASI: Tabel daftar buku -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr><th>#</th><th>Cover</th><th>Judul</th><th>Pengarang</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td>
                    <?php if ($row['cover_image']): ?>
                        <img src="<?= UPLOAD_URL . htmlspecialchars($row['cover_image']) ?>" width="40" height="55" style="object-fit:cover">
                    <?php else: ?>
                        <span class="text-muted">-</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['judul']) ?></td>
                <td><?= htmlspecialchars($row['pengarang']) ?></td>
                <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= $row['stok'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus buku ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
