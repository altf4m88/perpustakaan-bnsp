<?php
// DOKUMENTASI: Guard session admin — sertakan di awal setiap halaman admin
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: ' . str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) . 'admin/login.php');
    exit;
}
?>
