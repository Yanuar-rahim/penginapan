<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Menarik data reservasi dari database
$query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi");

// Mulai output HTML untuk PDF
echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Reservasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            font-size: 24px;
        }
        p {
            text-align: center;
            font-size: 14px;
        }
        table {
            width: 95.5%;
            border-collapse: collapse;
            margin-top: 20px;
            margin: 0 30px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .status {
            padding: 5px;
            text-align: center;
        }
        .dipesan {
            background-color: yellow;
        }
        .konfirmasi {
            background-color: green;
            color: white;
        }
        .selesai {
            background-color: blue;
            color: white;
        }
        .batal {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Laporan Daftar Reservasi</h1>
    <p>Laporan ini berisi daftar reservasi yang telah dilakukan oleh pengguna, termasuk informasi terkait nama user, nama kamar, total harga, dan status reservasi.</p>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Nama Kamar</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

// Menampilkan data dalam tabel PDF
$no = 1;
while ($row = mysqli_fetch_assoc($query_reservasi)) {
    echo '<tr>';
    echo '<td>' . $no++ . '</td>';
    echo '<td>' . $row['nama_lengkap'] . '</td>';
    echo '<td>' . $row['nama_kamar'] . '</td>';
    echo '<td>Rp. ' . number_format($row['total_harga'], 0, ',', '.') . '</td>';
    echo '<td class="status ' . strtolower($row['status']) . '">' . ucfirst($row['status']) . '</td>';
    echo '</tr>';
}

echo '</tbody></table>';

echo '<script>
        window.print();
        window.onafterprint = function() {
            window.close();
        };
      </script>';
echo '</body></html>';
exit();
?>
