<?php
// DOKUMENTASI: Halaman daftar user terdaftar (read-only untuk admin)
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Ambil semua user beserta jumlah pesanan masing-masing
$query  = "SELECT u.*, COUNT(p.id) AS jumlah_pesanan FROM users u LEFT JOIN pesanan p ON u.id = p.id_user GROUP BY u.id ORDER BY u.created_at ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4">
    <h4 class="mb-3">Daftar User Terdaftar</h4>

    <!-- DOKUMENTASI: Tabel daftar user -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>#</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Alamat</th><th>Pesanan</th><th>Terdaftar</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['telepon']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= $row['jumlah_pesanan'] ?></td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
