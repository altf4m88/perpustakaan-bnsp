<?php
// DOKUMENTASI: Halaman tambah buku baru dengan upload cover
require_once '../auth_check.php';
require_once '../../config.php';

// DOKUMENTASI: Ambil semua kategori untuk dropdown
$kategori_result = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori");

$error = '';

// DOKUMENTASI: Proses simpan buku saat form disubmit
if (isset($_POST['simpan'])) {
    $id_kategori  = (int) $_POST['id_kategori'];
    $judul        = trim($_POST['judul']);
    $pengarang    = trim($_POST['pengarang']);
    $penerbit     = trim($_POST['penerbit']);
    $tahun_terbit = (int) $_POST['tahun_terbit'];
    $harga        = (float) $_POST['harga'];
    $stok         = (int) $_POST['stok'];
    $deskripsi    = trim($_POST['deskripsi']);
    $cover_image  = '';

    // DOKUMENTASI: Proses upload cover buku jika ada file yang dikirim
    if (!empty($_FILES['cover_image']['name'])) {
        $ext   = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        } elseif ($_FILES['cover_image']['size'] > 2 * 1024 * 1024) {
            $error = 'Ukuran gambar maksimal 2MB.';
        } else {
            $cover_image = uniqid('cover_') . '.' . $ext;
            if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], UPLOAD_DIR . $cover_image)) {
                $error = 'Gagal menyimpan file. Periksa permission folder uploads/covers/.';
                $cover_image = '';
            }
        }
    }

    if (!$error) {
        $stmt = mysqli_prepare($conn, "INSERT INTO buku (id_kategori, judul, pengarang, penerbit, tahun_terbit, harga, stok, deskripsi, cover_image) VALUES (?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'isssidiss', $id_kategori, $judul, $pengarang, $penerbit, $tahun_terbit, $harga, $stok, $deskripsi, $cover_image);
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
    <title>Tambah Buku — Admin Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include '../partials/navbar.php'; ?>
<div class="container mt-4" style="max-width:700px">
    <h4 class="mb-3">Tambah Buku</h4>
    <?php if ($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($k = mysqli_fetch_assoc($kategori_result)): ?>
                    <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Judul Buku</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required>
            </div>
            <div class="col mb-3">
                <label class="form-label">Penerbit</label>
                <input type="text" name="penerbit" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" class="form-control" min="1900" max="<?= date('Y') ?>">
            </div>
            <div class="col mb-3">
                <label class="form-label">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" min="0" required>
            </div>
            <div class="col mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" min="0" value="0" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Cover Buku (JPG/PNG/WEBP, max 2MB)</label>
            <input type="file" name="cover_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>
</body>
</html>
