<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Menyusun header untuk file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_reservasi_penginapan_" . date('Ymd_His') . ".xls");

// Menambahkan deskripsi di atas tabel
echo "<h2 style='text-align: center; font-family: Arial, sans-serif;'>Laporan Data Reservasi Penginapan\n\n</h2>";
echo "<p style='text-align: center; font-family: Arial, sans-serif; font-size: 14px;'>
        Laporan ini berisi data reservasi penginapan yang telah dilakukan oleh pengguna, mencakup informasi tentang nama user, nama kamar, total harga, dan status reservasi.<br>
        Laporan ini dihasilkan berdasarkan data yang ada di sistem manajemen reservasi penginapan kami.
    </p>";
echo "<br>";

echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
echo "<thead style='background-color: #4CAF50; color: white; font-weight: bold; text-align: center;'>";
echo "<tr>
        <th>No</th>
        <th>Nama User</th>
        <th>Nama Kamar</th>
        <th>Total Harga</th>
        <th>Status</th>
      </tr>";
echo "</thead>";
echo "<tbody>";


// Menarik data reservasi dari database
$query_reservasi_excel = mysqli_query($koneksi, "SELECT * FROM reservasi ORDER BY id_reservasi ASC");

// Mengecek apakah ada data yang ditemukan
if (mysqli_num_rows($query_reservasi_excel) > 0) {
    $no = 1;
    // Menampilkan data per baris
    while ($row = mysqli_fetch_assoc($query_reservasi_excel)) {
        echo "<tr>";
        echo "<td style='text-align: center;'>$no</td>";
        echo "<td>{$row['nama_lengkap']}</td>";
        echo "<td>{$row['nama_kamar']}</td>";
        echo "<td>" . "Rp. " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
        echo "<td>{$row['status']}</td>";
        echo "</tr>";
        $no++;
    }
} else {
    // Jika tidak ada data
    echo "Tidak ada data reservasi yang ditemukan.\n";
}


echo "</tbody>";
echo "</table>";
?>