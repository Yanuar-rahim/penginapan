-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Des 2025 pada 16.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penginapan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `nama_kamar` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `fasilitas` text DEFAULT NULL,
  `status` enum('tersedia','terisi') DEFAULT 'tersedia',
  `tipe_kamar` enum('deluxe','standard','suite','') NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `nama_kamar`, `harga`, `fasilitas`, `status`, `tipe_kamar`, `gambar`, `created_at`, `deskripsi`) VALUES
(13, 'Deluxe Room', 500000, 'AC, TV, Free Wi-Fi, Breakfast', 'tersedia', 'deluxe', 'deluxe.jpg', '2025-12-27 12:08:48', 'Kamar deluxe dengan fasilitas lengkap, termasuk AC, TV, Wi-Fi gratis, dan sarapan. Cocok untuk pengalaman menginap mewah.'),
(14, 'Standard Room', 300000, 'AC, TV', 'tersedia', 'standard', 'standard.jpg', '2025-12-27 12:08:48', 'Kamar standard dengan fasilitas dasar seperti AC dan TV. Ideal untuk penginapan yang nyaman namun terjangkau.'),
(15, 'Suite Room', 750000, 'AC, TV, Free Wi-Fi, Jacuzzi', 'tersedia', 'suite', 'suite.jpg', '2025-12-27 12:08:48', 'Kamar suite yang luas dengan fasilitas mewah seperti AC, TV, Wi-Fi gratis, dan jacuzzi pribadi. Sempurna untuk relaksasi.');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi`
--

CREATE TABLE `reservasi` (
  `id_reservasi` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_kamar` varchar(100) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` enum('dipesan','selesai','batal','konfirmasi') DEFAULT 'dipesan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi`
--

INSERT INTO `reservasi` (`id_reservasi`, `nama_lengkap`, `nama_kamar`, `check_in`, `check_out`, `total_harga`, `status`, `created_at`) VALUES
(12, 'Hilda Azqiah', 'Suite Room', '2025-12-29', '2025-12-30', 750000, 'dipesan', '2025-12-28 05:51:39'),
(14, 'Hilda Azqiah', 'Deluxe Room', '2026-01-03', '2026-01-04', 500000, 'dipesan', '2025-12-28 05:52:14'),
(15, 'Feti Ariani', 'Deluxe Room', '2025-12-06', '2025-12-07', 500000, 'dipesan', '2025-12-28 05:54:57'),
(16, 'Feti Ariani', 'Standard Room', '2025-12-30', '2025-12-31', 300000, 'dipesan', '2025-12-28 05:55:09'),
(17, 'Feti Ariani', 'Suite Room', '2025-12-02', '2025-12-03', 750000, 'dipesan', '2025-12-28 05:55:38'),
(18, 'Hilda Azqiah', 'Deluxe Room', '2025-12-08', '2025-12-30', 11000000, 'dipesan', '2025-12-28 06:24:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `value`) VALUES
(1, 'website_name', 'T-Three Residences'),
(2, 'hero_image', 'default-hero.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `username`, `email`, `password`, `role`, `user_id`) VALUES
(3, 'Hilda Azqiah', 'admin', 'hildaaaz@gmail.com', '$2y$10$fWNJ63PrY/hqZY6yZeyJEOOtoqiLghXQBrQ.ug4dySG4tC0nyF8mG', 'admin', 3),
(4, 'Hilda Azqiah', 'hilda-azqiah', 'hildaaz@gmail.com', '$2y$10$oliehoOCa1ukmTpdMeafAOMAI2y30IPKoPG5X99PsiUHDOCXrzjMm', 'user', 4),
(8, 'Feti Ariani', 'yani', 'fetiar@gmail.com', '$2y$10$/r54nXNu6n9bSlIJpTiTXuvNV6pLZJp5NbvOQ2GSMdgcTNmhV3e8q', 'user', 8);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`);

--
-- Indeks untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  ADD PRIMARY KEY (`id_reservasi`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `reservasi`
--
ALTER TABLE `reservasi`
  MODIFY `id_reservasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
