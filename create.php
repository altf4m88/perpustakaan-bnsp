<?php
// DOKUMENTASI: Menyertakan koneksi database
include 'config.php';

// DOKUMENTASI: Memeriksa apakah form telah disubmit melalui POST
if(isset($_POST['submit'])) {
    // Mengambil data dari form
    $name = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $status = $_POST['status'];

    // DOKUMENTASI: Query SQL untuk memasukkan barang baru ke database
    $query = "INSERT INTO items (item_name, quantity, status) VALUES ('$name', '$qty', '$status')";
    
    // Mengeksekusi query
    if(mysqli_query($conn, $query)) {
        // DOKUMENTASI: Kembali ke dashboard setelah berhasil
        header("Location: index.php");
    } else {
        // DOKUMENTASI: Menampilkan error jika query gagal
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Baru</title>
    <!-- DOKUMENTASI: Bootstrap CSS untuk styling -->
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tambah Item Inventaris Baru</h2>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="item_name" class="form-label">Nama Barang</label>
                <input type="text" id="item_name" name="item_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Available">Tersedia</option>
                    <option value="Out of Stock">Habis</option>
                    <option value="Ordered">Dipesan</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Simpan Barang</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
