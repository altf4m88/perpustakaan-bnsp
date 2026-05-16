<?php
// DOKUMENTASI: Proses logout admin — hapus session lalu redirect ke login
session_start();
session_destroy();
header('Location: login.php');
exit;
?>
