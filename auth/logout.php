<?php
// DOKUMENTASI: Proses logout user — hapus session lalu redirect ke halaman utama
session_start();
session_destroy();
header('Location: ../index.php');
exit;
?>
