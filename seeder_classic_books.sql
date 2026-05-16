-- DOKUMENTASI: Additional Classic Books Seeder
-- Database: bookstore
-- Import file ini via phpMyAdmin untuk menambah koleksi buku klasik.
-- Run in an existing bookstore database (tidak perlu create database atau tabel).

-- DOKUMENTASI: Tambahan buku fiksi klasik dari berbagai periode dan negara
INSERT INTO buku (id_kategori, judul, pengarang, penerbit, tahun_terbit, harga, stok, deskripsi) VALUES
(1, 'Moby Dick',                 'Herman Melville',       'Penguin Classics',   1851, 160000, 13, 'Epic tale of Captain Ahab''s obsessive hunt for the great white whale'),
(1, 'Jane of Green Gables',      'Lucy Maud Montgomery',  'Penguin Classics',   1908, 98000,  17, 'Coming-of-age story of an imaginative orphan girl in Prince Edward Island'),
(1, 'The Great Gatsby',          'F. Scott Fitzgerald',   'Penguin Classics',   1925, 105000, 21, 'Jazz Age classic about wealth, love, and the American Dream'),
(1, 'Anna Karenina',             'Leo Tolstoy',           'Oxford World',       1877, 190000, 10, 'Russian masterpiece exploring love, society, and the meaning of life'),
(1, 'Madame Bovary',             'Gustave Flaubert',      'Penguin Classics',   1856, 115000, 12, 'Tale of a provincial woman''s romantic disillusionment in 19th century France'),
(1, 'The Odyssey',               'Homer',                 'Penguin Classics',   -800, 145000, 14, 'Ancient Greek epic of Odysseus'' long journey home after the Trojan War'),
(1, 'Don Quixote',               'Miguel de Cervantes',   'Penguin Classics',   1605, 175000, 11, 'Spanish classic following the adventures of an idealistic hidalgo and his squire'),
(1, 'Frankenstein',              'Mary Shelley',          'Penguin Classics',   1818, 112000, 15, 'Gothic novel exploring science, ambition, and the nature of humanity'),
(1, 'The Picture of Dorian Gray', 'Oscar Wilde',          'Penguin Classics',   1890, 108000, 16, 'Philosophical tale of beauty, corruption, and moral decay'),
(1, 'Great Expectations',        'Charles Dickens',       'Penguin Classics',   1861, 138000, 13, 'Victorian novel of a young man''s journey from obscurity to mysterious fortune'),

-- DOKUMENTASI: Tambahan buku filosofi dan pemikiran
INSERT INTO buku (id_kategori, judul, pengarang, penerbit, tahun_terbit, harga, stok, deskripsi) VALUES
(6, 'Phaedo',                    'Plato',                 'Oxford World',       380, 85000,  9, 'Dialogue exploring the immortality of the soul and the nature of reality'),
(6, 'Phenomenology of Spirit',   'G.W.F. Hegel',          'Oxford University',  1807, 225000, 6, 'Dense but foundational work on consciousness and human history'),
(6, 'An Enquiry Concerning Human Understanding', 'David Hume', 'Dover Publications', 1748, 98000,  11, 'Empiricist examination of knowledge, causality, and human understanding'),
(6, 'The Phenomenology of Perception', 'Maurice Merleau-Ponty', 'Routledge', 1945, 210000, 7, 'Exploration of perception, embodiment, and the nature of consciousness'),
(6, 'The Social Contract',       'Jean-Jacques Rousseau', 'Dover Publications', 1762, 95000,  12, 'Political philosophy examining the origin of civil society and legitimacy of government'),
(6, 'A Treatise of Human Nature', 'David Hume',            'Dover Publications', 1739, 165000, 8, 'Comprehensive empiricist account of human nature and knowledge'),
(6, 'Nicomachean Ethics',        'Aristotle',             'Oxford World',       325, 140000, 10, 'Ancient Greek work on virtue, happiness, and ethical living'),
(6, 'The Phenomenology of the Sacred', 'Rudolf Otto',      'Dover Publications', 1917, 120000, 8, 'Philosophical study of religious experience and the divine'),
(6, 'Critique of Practical Reason', 'Immanuel Kant',      'Cambridge University', 1788, 195000, 6, 'Kant''s exploration of morality, freedom, and the categorical imperative'),
(6, 'The World as Will and Idea', 'Arthur Schopenhauer',  'Dover Publications', 1818, 180000, 7, 'Pessimist philosophy examining the nature of reality and human suffering');

-- DOKUMENTASI: Query untuk verifikasi data yang dimasukkan
-- SELECT COUNT(*) as total_buku FROM buku;
-- SELECT judul, pengarang, kategori.nama_kategori FROM buku 
-- JOIN kategori ON buku.id_kategori = kategori.id 
-- ORDER BY buku.id DESC LIMIT 20;
