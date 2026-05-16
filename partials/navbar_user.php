<!-- DOKUMENTASI: Navbar user yang dipakai di about.php, contact.php, dll -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/bnsp-preps/index.php"><em>Dusha-Kniga</em></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/contact.php">Kontak</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="/bnsp-preps/user/keranjang.php" class="btn btn-light btn-sm">
                        Keranjang <?= !empty($total_keranjang) && $total_keranjang > 0 ? "($total_keranjang)" : '' ?>
                    </a>
                    <a href="/bnsp-preps/user/pesanan.php" class="btn btn-light btn-sm">Pesanan Saya</a>
                    <span class="text-light">Halo, <?= htmlspecialchars($_SESSION['user_nama']) ?></span>
                    <a href="/bnsp-preps/auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                <?php else: ?>
                    <a href="/bnsp-preps/auth/login.php" class="btn btn-light btn-sm">Login</a>
                    <a href="/bnsp-preps/auth/register.php" class="btn btn-primary btn-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
