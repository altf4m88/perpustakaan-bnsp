<?php
// DOKUMENTASI: Halaman pembayaran Virtual Account — mockup tampilan instruksi VA
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../auth/login.php'); exit; }

require_once '../config.php';
$uid        = (int) $_SESSION['user_id'];
$id_pesanan = (int) $_GET['id'];

// DOKUMENTASI: Pastikan pesanan ini milik user yang login dan metode VA
$pesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pesanan WHERE id=$id_pesanan AND id_user=$uid AND metode_pembayaran='va'"));
if (!$pesanan) { header('Location: pesanan.php'); exit; }

// DOKUMENTASI: Generate nomor VA deterministik dari ID pesanan
function va_number($id, $prefix) {
    $suffix = str_pad(($id * 13 + 4817) % 100000, 5, '0', STR_PAD_LEFT);
    return $prefix . str_pad($id, 4, '0', STR_PAD_LEFT) . $suffix;
}

$banks = [
    'BCA'     => ['prefix' => '80881', 'color' => '#005b97', 'text' => 'BCA Virtual Account'],
    'Mandiri' => ['prefix' => '88908', 'color' => '#003087', 'text' => 'Mandiri Virtual Account'],
    'BNI'     => ['prefix' => '98811', 'color' => '#f26522', 'text' => 'BNI Virtual Account'],
    'BRI'     => ['prefix' => '00341', 'color' => '#00529b', 'text' => 'BRI Virtual Account'],
];

$selected_bank = isset($_GET['bank']) && isset($banks[$_GET['bank']]) ? $_GET['bank'] : 'BCA';
$bank          = $banks[$selected_bank];
$va_number     = va_number($id_pesanan, $bank['prefix']);
$total         = $pesanan['total_harga'];
$expires_at    = date('d M Y H:i', strtotime($pesanan['created_at'] . ' +24 hours'));

// DOKUMENTASI: Tombol konfirmasi pembayaran (mockup — tandai pesanan jadi diproses)
if (isset($_POST['konfirmasi'])) {
    mysqli_query($conn, "UPDATE pesanan SET status='diproses' WHERE id=$id_pesanan");
    header('Location: pesanan.php?bayar=' . $id_pesanan);
    exit;
}

$total_keranjang = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran VA — Dusha-Kniga</title>
    <link href="/bnsp-preps/assets/bootstrap.min%20(2).css" rel="stylesheet">
    <style>
        .bank-tab         { cursor:pointer; border:2px solid #dee2e6; border-radius:8px; padding:10px 16px; text-align:center; transition:.15s; }
        .bank-tab.active  { border-color: var(--bank-color); background:var(--bank-bg); }
        .va-card          { border-radius:16px; overflow:hidden; }
        .va-header        { padding:20px 24px; color:#fff; }
        .va-body          { background:#fff; padding:24px; }
        .va-number        { font-size:1.6rem; font-weight:700; letter-spacing:3px; font-family:monospace; }
        .copy-btn         { cursor:pointer; font-size:.8rem; }
        .step-badge       { width:28px; height:28px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.8rem; flex-shrink:0; }
        #countdown        { font-variant-numeric:tabular-nums; font-family:monospace; }
    </style>
</head>
<body class="bg-light">
<?php include '../partials/navbar_user.php'; ?>

<div class="container mt-4 mb-5" style="max-width:680px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="pesanan.php" class="text-muted text-decoration-none">← Pesanan</a>
        <span class="text-muted">/</span>
        <span>Pembayaran #<?= $id_pesanan ?></span>
    </div>

    <!-- DOKUMENTASI: Pilih bank VA -->
    <p class="fw-semibold mb-2">Pilih Bank</p>
    <div class="d-flex gap-2 mb-4 flex-wrap">
        <?php foreach ($banks as $nama => $b): ?>
            <a href="?id=<?= $id_pesanan ?>&bank=<?= $nama ?>"
               class="bank-tab <?= $selected_bank === $nama ? 'active' : '' ?>"
               style="--bank-color:<?= $b['color'] ?>;--bank-bg:<?= $b['color'] ?>18;color:<?= $selected_bank === $nama ? $b['color'] : '#555' ?>;font-weight:<?= $selected_bank === $nama ? '700' : '400' ?>">
                <?= $nama ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- DOKUMENTASI: Kartu VA utama -->
    <div class="card va-card shadow-sm mb-4">
        <div class="va-header" style="background:<?= $bank['color'] ?>">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:.8rem;opacity:.8">Pesanan #<?= $id_pesanan ?></div>
                    <div class="fw-bold" style="font-size:1.1rem"><?= $bank['text'] ?></div>
                </div>
                <div class="text-end">
                    <div style="font-size:.75rem;opacity:.8">Bayar sebelum</div>
                    <div style="font-size:.85rem;font-weight:600"><?= $expires_at ?></div>
                </div>
            </div>
        </div>

        <div class="va-body">
            <!-- Nomor VA -->
            <div class="mb-4">
                <div class="text-muted mb-1" style="font-size:.8rem">Nomor Virtual Account</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="va-number" id="va-num"><?= $va_number ?></div>
                    <button class="btn btn-outline-secondary btn-sm copy-btn" onclick="copyVA()">Salin</button>
                </div>
            </div>

            <!-- Total -->
            <div class="mb-4">
                <div class="text-muted mb-1" style="font-size:.8rem">Total Pembayaran</div>
                <div style="font-size:1.5rem;font-weight:700;color:<?= $bank['color'] ?>">
                    Rp <?= number_format($total, 0, ',', '.') ?>
                </div>
                <small class="text-danger">Transfer tepat sesuai nominal di atas.</small>
            </div>

            <!-- Countdown -->
            <div class="d-flex align-items-center gap-2 p-3 rounded mb-4" style="background:#fff8e1;border:1px solid #ffe082">
                <span style="font-size:1.2rem">⏳</span>
                <div>
                    <div style="font-size:.75rem;color:#888">Sisa waktu pembayaran</div>
                    <div id="countdown" style="font-size:1rem;font-weight:700;color:#e65100">--:--:--</div>
                </div>
            </div>

            <!-- Konfirmasi -->
            <form method="POST">
                <button type="submit" name="konfirmasi" class="btn btn-success w-100 mb-2"
                        onclick="return confirm('Konfirmasi bahwa Anda sudah melakukan transfer?')">
                    ✓ Saya Sudah Transfer
                </button>
            </form>
            <a href="pesanan.php" class="btn btn-outline-secondary w-100">Lihat Semua Pesanan</a>
        </div>
    </div>

    <!-- DOKUMENTASI: Langkah-langkah pembayaran -->
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Cara Pembayaran — <?= $selected_bank ?> Virtual Account</div>
        <div class="card-body">

            <p class="fw-semibold text-muted mb-2" style="font-size:.85rem">ATM <?= $selected_bank ?></p>
            <?php
            $steps_atm = [
                'Pilih menu <strong>Transaksi Lainnya</strong> → <strong>Transfer</strong>.',
                'Pilih <strong>Ke Rekening ' . $selected_bank . ' Virtual Account</strong>.',
                'Masukkan nomor VA: <code>' . $va_number . '</code>.',
                'Masukkan nominal <strong>Rp ' . number_format($total, 0, ',', '.') . '</strong>.',
                'Ikuti instruksi untuk menyelesaikan pembayaran.',
            ];
            ?>
            <ol class="ps-3 mb-4" style="font-size:.9rem;line-height:2">
                <?php foreach ($steps_atm as $s): ?>
                    <li><?= $s ?></li>
                <?php endforeach; ?>
            </ol>

            <p class="fw-semibold text-muted mb-2" style="font-size:.85rem">Mobile Banking / Internet Banking</p>
            <?php
            $steps_mb = [
                'Login ke aplikasi <strong>' . $selected_bank . '</strong> mobile banking.',
                'Pilih menu <strong>Transfer</strong> → <strong>Virtual Account</strong>.',
                'Masukkan nomor VA: <code>' . $va_number . '</code>.',
                'Cek detail pembayaran dan konfirmasi nominal.',
                'Masukkan PIN / autentikasi untuk menyelesaikan.',
            ];
            ?>
            <ol class="ps-3 mb-0" style="font-size:.9rem;line-height:2">
                <?php foreach ($steps_mb as $s): ?>
                    <li><?= $s ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// DOKUMENTASI: Countdown 24 jam dari waktu pesanan dibuat
const expiresAt = new Date('<?= date('Y-m-d\TH:i:s', strtotime($pesanan['created_at'] . ' +24 hours')) ?>');

function updateCountdown() {
    const diff = expiresAt - new Date();
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'Waktu habis';
        return;
    }
    const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
    const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
    const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
    document.getElementById('countdown').textContent = h + ':' + m + ':' + s;
}

updateCountdown();
setInterval(updateCountdown, 1000);

// DOKUMENTASI: Salin nomor VA ke clipboard
function copyVA() {
    navigator.clipboard.writeText('<?= $va_number ?>').then(() => {
        const btn = document.querySelector('.copy-btn');
        btn.textContent = 'Tersalin!';
        btn.classList.replace('btn-outline-secondary', 'btn-success');
        setTimeout(() => { btn.textContent = 'Salin'; btn.classList.replace('btn-success', 'btn-outline-secondary'); }, 2000);
    });
}
</script>
</body>
</html>
