<?php
// DOKUMENTASI: Halaman daftar pesan kontak dari user
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Tandai pesan sebagai sudah dibaca
if (isset($_GET['baca'])) {
    $id = (int) $_GET['baca'];
    mysqli_query($conn, "UPDATE pesan_kontak SET status='sudah_dibaca' WHERE id=$id");
    header('Location: index.php');
    exit;
}

// DOKUMENTASI: Hapus pesan
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesan_kontak WHERE id=$id");
    header('Location: index.php');
    exit;
}

$query  = "SELECT * FROM pesan_kontak ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4">
    <h4 class="mb-3">Pesan Kontak dari User</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>#</th><th>Nama</th><th>Email</th><th>Subjek</th><th>Pesan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr class="<?= $row['status'] === 'belum_dibaca' ? 'table-warning' : '' ?>">
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['subjek']) ?></td>
                <td><?= htmlspecialchars(mb_substr($row['pesan'], 0, 80)) ?>...</td>
                <td>
                    <?php if ($row['status'] === 'belum_dibaca'): ?>
                        <span class="badge bg-warning text-dark">Baru</span>
                    <?php else: ?>
                        <span class="badge bg-success">Dibaca</span>
                    <?php endif; ?>
                </td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <?php if ($row['status'] === 'belum_dibaca'): ?>
                        <a href="index.php?baca=<?= $row['id'] ?>" class="btn btn-sm btn-success">Tandai Dibaca</a>
                    <?php endif; ?>
                    <a href="index.php?hapus=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Hapus pesan ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
