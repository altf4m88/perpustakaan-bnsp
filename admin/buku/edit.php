<?php
// DOKUMENTASI: Halaman edit buku dengan opsi ganti cover
require_once '../auth_check.php';
require_once '../../config.php';

$id     = (int) $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM buku WHERE id = $id");
$row    = mysqli_fetch_assoc($result);
if (!$row) { header('Location: index.php'); exit; }

// DOKUMENTASI: Ambil semua kategori untuk dropdown
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

$error = '';

// DOKUMENTASI: Proses update buku saat form disubmit
if (isset($_POST['update'])) {
    $id_kategori  = (int) $_POST['id_kategori'];
    $judul        = trim($_POST['judul']);
    $pengarang    = trim($_POST['pengarang']);
    $penerbit     = trim($_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $harga        = (float) $_POST['harga'];
    $stok         = (int) $_POST['stok'];
    $deskripsi    = trim($_POST['deskripsi']);
    $cover_image  = $row['cover_image'];

    // DOKUMENTASI: Ganti cover jika ada file baru yang diupload
    if (!empty($_FILES['cover_image']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        } elseif ($_FILES['cover_image']['size'] > 2 * 1024 * 1024) {
            $error = 'Ukuran gambar maksimal 2MB.';
        } else {
            $new_filename = uniqid('cover_') . '.' . $ext;
            if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], UPLOAD_DIR . $new_filename)) {
                $error = 'Gagal menyimpan file. Periksa permission folder uploads/covers/.';
            } else {
                // Hapus cover lama setelah upload baru berhasil
                if ($cover_image && file_exists(UPLOAD_DIR . $cover_image)) {
                    unlink(UPLOAD_DIR . $cover_image);
                }
                $cover_image = $new_filename;
            }
        }
    }

    if (!$error) {
        $stmt = mysqli_prepare($conn, "UPDATE buku SET id_kategori=?, judul=?, pengarang=?, penerbit=?, tahun_terbit=?, harga=?, stok=?, deskripsi=?, cover_image=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'isssidissi', $id_kategori, $judul, $pengarang, $penerbit, $tahun_terbit, $harga, $stok, $deskripsi, $cover_image, $id);
        mysqli_stmt_execute($stmt);
        header('Location: index.php?sukses=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4" style="max-width:700px">
    <h4 class="mb-3">Edit Buku</h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <?php while ($k = mysqli_fetch_assoc($kategori_result)): ?>
                    <option value="<?= $k['id'] ?>" <?= $k['id'] == $row['id_kategori'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($row['judul']) ?>" required>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" value="<?= htmlspecialchars($row['pengarang']) ?>" required>
            </div>
            <div class="col mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control" value="<?= htmlspecialchars($row['penerbit']) ?>">
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" value="<?= $row['tahun_terbit'] ?>">
            </div>
            <div class="col mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
            </div>
            <div class="col mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $row['stok'] ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Buku</label>
            <?php if ($row['cover_image']): ?>
                <div class="mb-2">
                    <img src="<?= UPLOAD_URL . htmlspecialchars($row['cover_image']) ?>" height="80" style="object-fit:cover">
                    <small class="text-muted ms-2">Cover saat ini</small>
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti cover.</small>
        </div>
        <button type="submit" name="update" class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
