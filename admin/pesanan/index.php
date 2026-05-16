<?php
// DOKUMENTASI: Halaman daftar pesanan + update status
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Update status pesanan
if (isset($_POST['update_status'])) {
    $id_pesanan = (int) $_POST['id_pesanan'];
    $status     = $_POST['status'];
    $allowed    = ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];
    if (in_array($status, $allowed)) {
        mysqli_query($conn, "UPDATE pesanan SET status = '$status' WHERE id = $id_pesanan");
    }
    header('Location: index.php?sukses=1');
    exit;
}

// DOKUMENTASI: Filter berdasarkan status
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$where  = $filter ? "WHERE p.status = '" . mysqli_real_escape_string($conn, $filter) . "'" : '';

$query  = "SELECT p.*, u.nama AS nama_user FROM pesanan p JOIN users u ON p.id_user = u.id $where ORDER BY p.created_at ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4">
    <h4 class="mb-3">Manajemen Pesanan</h4>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success">Status pesanan diperbarui.</div>
    <?php endif; ?>

    <!-- DOKUMENTASI: Filter status pesanan -->
    <div class="mb-3">
        <?php foreach (['', 'pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'] as $s): ?>
            <a href="index.php?status=<?= $s ?>" class="btn btn-sm <?= $filter === $s ? 'btn-dark' : 'btn-outline-secondary' ?> me-1">
                <?= $s === '' ? 'Semua' : ucfirst($s) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- DOKUMENTASI: Tabel daftar pesanan -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>#</th><th>Pembeli</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Detail</th><th>Ubah Status</th></tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td><span class="badge bg-secondary"><?= $row['status'] ?></span></td>
                <td><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                <td><a href="detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Lihat</a></td>
                <td>
                    <!-- DOKUMENTASI: Form inline untuk update status pesanan -->
                    <form method="POST" class="d-flex gap-1">
                        <input type="hidden" name="id_pesanan" value="<?= $row['id'] ?>">
                        <select name="status" class="form-select form-select-sm">
                            <?php foreach (['pending','diproses','dikirim','selesai','dibatalkan'] as $s): ?>
                                <option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-warning btn-sm">OK</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
