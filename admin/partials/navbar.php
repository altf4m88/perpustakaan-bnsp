<!-- DOKUMENTASI: Navbar admin yang disertakan di setiap halaman admin -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/bnsp-preps/admin/index.php"><em>Dusha-Kniga</em> Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navAdmin">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/admin/kategori/index.php">Kategori</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/admin/buku/index.php">Buku</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/admin/users/index.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/admin/pesanan/index.php">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="/bnsp-preps/admin/pesan/index.php">Pesan Kontak</a></li>
            </ul>
            <span class="navbar-text me-3 text-light">Halo, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
            <a href="/bnsp-preps/admin/logout.php" class="btn btn-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
