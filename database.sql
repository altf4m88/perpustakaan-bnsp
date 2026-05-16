
CREATE DATABASE IF NOT EXISTS bookstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookstore;

-- DOKUMENTASI: Tabel login admin
CREATE TABLE IF NOT EXISTS admin (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DOKUMENTASI: Tabel kategori buku
CREATE TABLE IF NOT EXISTS kategori (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi     TEXT,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DOKUMENTASI: Tabel data buku
CREATE TABLE IF NOT EXISTS buku (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori  INT           NOT NULL,
    judul        VARCHAR(200)  NOT NULL,
    pengarang    VARCHAR(100)  NOT NULL,
    penerbit     VARCHAR(100),
    tahun_terbit YEAR,
    harga        DECIMAL(10,2) NOT NULL DEFAULT 0,
    stok         INT           NOT NULL DEFAULT 0,
    deskripsi    TEXT,
    cover_image  VARCHAR(255)  DEFAULT NULL,
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id) ON DELETE RESTRICT
);

-- DOKUMENTASI: Tabel akun pembeli
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    telepon    VARCHAR(20),
    alamat     TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DOKUMENTASI: Tabel keranjang belanja (sementara sebelum checkout)
CREATE TABLE IF NOT EXISTS keranjang (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_user    INT NOT NULL,
    id_buku    INT NOT NULL,
    jumlah     INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_buku) REFERENCES buku(id)  ON DELETE CASCADE
);

-- DOKUMENTASI: Tabel header pesanan
CREATE TABLE IF NOT EXISTS pesanan (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    id_user           INT           NOT NULL,
    total_harga       DECIMAL(10,2) NOT NULL,
    status            ENUM('pending','diproses','dikirim','selesai','dibatalkan') DEFAULT 'pending',
    alamat_pengiriman TEXT          NOT NULL,
    catatan           TEXT,
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
);

-- DOKUMENTASI: Tabel detail item per pesanan
CREATE TABLE IF NOT EXISTS detail_pesanan (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan   INT           NOT NULL,
    id_buku      INT           NOT NULL,
    jumlah       INT           NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    subtotal     DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (id_buku)    REFERENCES buku(id)    ON DELETE RESTRICT
);

-- DOKUMENTASI: Tabel pesan dari user ke admin
CREATE TABLE IF NOT EXISTS pesan_kontak (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    id_user    INT          DEFAULT NULL,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    subjek     VARCHAR(200),
    pesan      TEXT         NOT NULL,
    status     ENUM('belum_dibaca','sudah_dibaca') DEFAULT 'belum_dibaca',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- DOKUMENTASI: Seed kategori
INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Fiksi',      'Novel dan cerita fiksi karangan'),
('Non-Fiksi',  'Buku berdasarkan fakta dan realita'),
('Teknologi',  'Buku seputar teknologi dan pemrograman'),
('Bisnis',     'Buku tentang bisnis dan kewirausahaan'),
('Pendidikan', 'Buku pelajaran dan referensi pendidikan'),
('Filosofi',   'Buku tentang filsafat, etika, dan pemikiran manusia');

-- DOKUMENTASI: Seed buku contoh - Classic Russian & European Novels
INSERT INTO buku (id_kategori, judul, pengarang, penerbit, tahun_terbit, harga, stok, deskripsi) VALUES
(1, 'Crime and Punishment',      'Fyodor Dostoevsky',     'Penguin Classics',   1866, 145000, 18, 'A gripping psychological novel about morality, guilt, and redemption in Imperial Russia'),
(1, 'War and Peace',             'Leo Tolstoy',           'Oxford World',       1869, 185000, 12, 'Epic masterpiece depicting Russian society during the Napoleonic wars'),
(1, 'The Brothers Karamazov',    'Fyodor Dostoevsky',     'Penguin Classics',   1880, 155000, 15, 'Profound exploration of faith, doubt, and family relationships'),
(1, 'Pride and Prejudice',       'Jane Austen',           'Penguin Classics',   1813, 95000,  25, 'Classic English romance exploring social class and personal growth'),
(1, 'Jane Eyre',                 'Charlotte Brontë',      'Penguin Classics',   1847, 105000, 20, 'Gothic romance and bildungsroman featuring a strong female protagonist'),
(1, 'The Count of Monte Cristo', 'Alexandre Dumas',       'Dover Publications', 1844, 125000, 16, 'Adventure and revenge tale set during the Restoration period in France'),
(1, 'Wuthering Heights',         'Emily Brontë',          'Penguin Classics',   1847, 110000, 14, 'Dark, passionate tale of love and revenge on the English moors'),
(1, 'Les Misérables',            'Victor Hugo',           'Penguin Classics',   1862, 175000, 18, 'Epic saga of justice, love, and social redemption in 19th century France'),
(6, 'Critique of Pure Reason',   'Immanuel Kant',         'Cambridge University Press', 1781, 200000, 8, 'Foundational work of modern philosophy examining the nature of knowledge'),
(6, 'Thus Spoke Zarathustra',    'Friedrich Nietzsche',   'Penguin Classics',   1883, 165000, 12, 'Philosophical novel exploring morality, power, and the übermensch'),
(6, 'Meditations',               'Marcus Aurelius',       'Dover Publications', 170, 125000,  16, 'Stoic wisdom and reflections on life, duty, and virtue by a Roman emperor'),
(6, 'The Republic',              'Plato',                 'Oxford World',       -380, 155000, 10, 'Ancient Greek masterpiece on justice, governance, and the ideal society'),
(6, 'Discourse on Method',       'René Descartes',        'Dover Publications', 1637, 95000,  14, 'Foundational text of modern rationalism and skeptical thinking'),
(6, 'Being and Nothingness',     'Jean-Paul Sartre',      'Routledge',          1943, 210000,  7, 'Comprehensive exploration of existentialism and human freedom');


-- DOKUMENTASI: Akun admin & user dibuat via install.php (password di-hash dengan password_hash PHP)
-- Jalankan: http://localhost/bnsp-preps/install.php
-- Default admin : admin / admin123
-- Default user  : budi@email.com / user123
