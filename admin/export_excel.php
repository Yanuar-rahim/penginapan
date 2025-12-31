<?php
session_start();
include '../config/koneksi.php';

// Pastikan user sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Menyusun header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penginapan_" . date('Ymd_His') . ".xls");

// Menambahkan deskripsi di atas tabel
echo "<h2 style='text-align: center; font-family: Arial, sans-serif;'>Laporan Daftar Kamar Penginapan</h2>";
echo "<p style='text-align: center; font-family: Arial, sans-serif; font-size: 14px;'>
        Laporan ini berisi daftar kamar yang tersedia di penginapan kami, mencakup informasi tentang nama kamar, tipe, harga, dan gambar kamar.<br>
        Laporan ini dihasilkan berdasarkan data kamar yang ada di sistem penginapan kami.
      </p>";
echo "<br>";

// Menambahkan nama kolom untuk file Excel dengan format lebih profesional
echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
echo "<thead style='background-color: #4CAF50; color: white; font-weight: bold; text-align: center;'>";
echo "<tr>
        <th>No</th>
        <th>Nama Kamar</th>
        <th>Tipe</th>
        <th>Harga</th>
        <th>Gambar</th>
      </tr>";
echo "</thead>";
echo "<tbody>";

// Menarik data kamar dari database
$query_kamar_excel = mysqli_query($koneksi, "SELECT * FROM kamar ORDER BY nama_kamar ASC");

// Mengecek apakah ada data yang ditemukan
if (mysqli_num_rows($query_kamar_excel) > 0) {
    $no = 1;
    // Menampilkan data per baris
    while ($row = mysqli_fetch_assoc($query_kamar_excel)) {
        echo "<tr>";
        echo "<td style='text-align: center;'>$no</td>";
        echo "<td>{$row['nama_kamar']}</td>";
        echo "<td>{$row['tipe_kamar']}</td>";
        echo "<td>" . "Rp. " . number_format($row['harga'], 0, ',', '.') . "</td>";
        echo "<td>{$row['gambar']}</td>";
        echo "</tr>";
        $no++;
    }
} else {
    // Jika tidak ada data
    echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data kamar yang ditemukan.</td></tr>";
}

echo "</tbody>";
echo "</table>";
?>
