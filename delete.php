<?php
// DOKUMENTASI: Menyertakan konfigurasi database
include 'config.php';

// DOKUMENTASI: Mengambil ID barang yang akan dihapus dari URL
$id = $_GET['id'];

// DOKUMENTASI: Query SQL untuk menghapus data tertentu
$query = "DELETE FROM items WHERE id = $id";

// Mengeksekusi operasi penghapusan
if(mysqli_query($conn, $query)) {
    // DOKUMENTASI: Segera kembali ke dashboard setelah penghapusan berhasil
    header("Location: index.php");
} else {
    // DOKUMENTASI: Menampilkan error jika penghapusan gagal
    echo "Error saat menghapus data: " . mysqli_error($conn);
}
?>
