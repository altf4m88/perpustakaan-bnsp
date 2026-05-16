<?php
// DOKUMENTASI: Halaman About Us — informasi tentang Dusha-Kniga
session_start();
require_once 'config.php';
$total_keranjang = 0;
if (!empty($_SESSION['user_id'])) {
    $uid = (int) $_SESSION['user_id'];
    $total_keranjang = mysqli_fetch_row(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah),0) FROM keranjang WHERE id_user=$uid"))[0];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
</head>
<body>
<?php include 'partials/navbar_user.php'; ?>

<!-- DOKUMENTASI: Hero section dengan ilustrasi book-lover -->
<div class="container mt-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-3">Tentang <em>Dusha-Kniga</em></h2>
            <p class="lead text-muted"><em>Dusha-Kniga</em> adalah toko buku online yang hadir untuk memudahkan Anda menemukan dan membeli buku favorit dari berbagai kategori — kapan saja, di mana saja.</p>
            <div class="mt-4">
                <a href="index.php" class="btn btn-primary me-2">Lihat Koleksi Buku</a>
                <a href="contact.php" class="btn btn-outline-secondary">Hubungi Kami</a>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <img src="/bnsp-preps/assets/undraw_book-lover_m9n3.svg"
                 alt="Book Lover" style="max-width:380px;width:100%">
        </div>
    </div>

    <hr class="my-5">

    <!-- DOKUMENTASI: Tiga keunggulan dengan ilustrasi SVG -->
    <div class="row g-4 text-center mb-5">
        <div class="col-md-4">
            <img src="/bnsp-preps/assets/undraw_bookshelves_vhu6%20(1).svg"
                 alt="Koleksi Lengkap" style="height:160px;margin-bottom:1.25rem">
            <h5 class="fw-semibold">Koleksi Lengkap</h5>
            <p class="text-muted">Ratusan judul dari berbagai genre dan kategori tersedia untuk Anda pilih setiap saat.</p>
        </div>
        <div class="col-md-4">
            <img src="/bnsp-preps/assets/undraw_reading-time_gcvc.svg"
                 alt="Baca Kapan Saja" style="height:160px;margin-bottom:1.25rem">
            <h5 class="fw-semibold">Baca Kapan Saja</h5>
            <p class="text-muted">Temukan buku yang menginspirasi dan jadikan membaca bagian dari rutinitas harian Anda.</p>
        </div>
        <div class="col-md-4">
            <img src="/bnsp-preps/assets/undraw_book-lover_m9n3.svg"
                 alt="Layanan Pelanggan" style="height:160px;margin-bottom:1.25rem">
            <h5 class="fw-semibold">Layanan Pelanggan</h5>
            <p class="text-muted">Tim kami siap membantu Anda — mulai dari pencarian buku hingga konfirmasi pesanan.</p>
        </div>
    </div>

    <hr class="my-5">

    <!-- DOKUMENTASI: Visi & Misi dengan ilustrasi bookshelves di sisi kanan -->
    <div class="row align-items-center mb-5">
        <div class="col-md-7">
            <h4 class="fw-bold mb-3">Visi</h4>
            <p class="text-muted">Menjadi platform toko buku online terpercaya dan terlengkap di Indonesia yang mendukung budaya literasi.</p>

            <h4 class="fw-bold mt-4 mb-3">Misi</h4>
            <ul class="text-muted ps-3" style="line-height:2">
                <li>Menyediakan koleksi buku berkualitas dari berbagai kategori.</li>
                <li>Memberikan pengalaman belanja buku yang mudah dan menyenangkan.</li>
                <li>Mendukung budaya membaca dan literasi di Indonesia.</li>
                <li>Menghadirkan sistem pembayaran Payment at Delivery yang aman dan terpercaya.</li>
            </ul>
        </div>
        <div class="col-md-5 text-center">
            <img src="/bnsp-preps/assets/undraw_bookshelves_vhu6%20(1).svg"
                 alt="Bookshelves" style="max-width:300px;width:100%">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
