<?php
/**
 * DOKUMENTASI: Konfigurasi koneksi database Dusha-Kniga
 * File ini disertakan di setiap halaman yang membutuhkan akses database.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookstore');

// DOKUMENTASI: Membangun koneksi ke database MySQL
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// DOKUMENTASI: Pengecekan koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

// DOKUMENTASI: Path untuk upload cover buku
define('UPLOAD_DIR', __DIR__ . '/uploads/covers/');
define('UPLOAD_URL', '/bnsp-preps/uploads/covers/');
?>
