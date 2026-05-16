<?php
// DOKUMENTASI: Menyertakan konfigurasi database
include 'config.php';

// DOKUMENTASI: Mengambil ID dari URL menggunakan $_GET
$id = $_GET['id'];

// DOKUMENTASI: Mengambil data terbaru untuk item tertentu
$query = "SELECT * FROM items WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// DOKUMENTASI: Memproses pengiriman form untuk memperbarui data
if(isset($_POST['update'])) {
    $name = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $status = $_POST['status'];

    // DOKUMENTASI: Query SQL untuk memperbarui data barang
    $update_query = "UPDATE items SET item_name='$name', quantity='$qty', status='$status' WHERE id=$id";
    
    if(mysqli_query($conn, $update_query)) {
        // DOKUMENTASI: Kembali ke dashboard setelah berhasil memperbarui
        header("Location: index.php");
    } else {
        echo "Error saat memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <!-- DOKUMENTASI: Bootstrap CSS CDN -->
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Item Inventaris</h2>
        
        <form method="POST" action="">
            <div class="mb-3">
                <label for="item_name" class="form-label">Nama Barang</label>
                <!-- DOKUMENTASI: Mengisi form dengan data yang sudah ada dari $row -->
                <input type="text" id="item_name" name="item_name" class="form-control" value="<?php echo $row['item_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $row['quantity']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="Available" <?php if($row['status'] == 'Available') echo 'selected'; ?>>Tersedia</option>
                    <option value="Out of Stock" <?php if($row['status'] == 'Out of Stock') echo 'selected'; ?>>Habis</option>
                    <option value="Ordered" <?php if($row['status'] == 'Ordered') echo 'selected'; ?>>Dipesan</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-warning">Perbarui Barang</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
