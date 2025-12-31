<?php
session_start();
include "../config/koneksi.php";

$no = 1;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['export_pdf'])) {
    echo '<style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
            }

            .main {
                padding: 20px 30px;
            }

            h1 {
                text-align: center;
                color: #333;
                font-size: 24px;
                margin-bottom: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            table th, table td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                font-size: 14px;
            }
            table th {
                background-color: #4CAF50;
                color: white;
                text-align: center;
            }
            table td {
                background-color: #fff;
            }
            table tr:nth-child(even) {
                background-color: #f2f2f2;
            }
            table td img {
                width: 100px;
                height: 60px;
                object-fit: cover;
            }
            .footer {
                text-align: center;
                margin-top: 20px;
                font-size: 12px;
                color: #777;
            }
          </style>';

    echo '<div class="main">';
    
    echo '<h1>Daftar Kamar Penginapan</h1>';
    echo '<table>';
    echo '<thead>';
    echo '<tr><th>No</th><th>Nama Kamar</th><th>Harga</th><th>Gambar</th></tr>';
    echo '</thead><tbody>';
    
    $query_kamar_result = mysqli_query($koneksi, "SELECT * FROM kamar");
    while ($row = mysqli_fetch_assoc($query_kamar_result)) {
        echo '<tr>';
        echo '<td style="text-align: center;">' . $no++ . '</td>';
        echo '<td>' . $row['nama_kamar'] . '</td>';
        echo '<td>' . "Rp. " . number_format($row['harga'], 0, ',', '.') . '</td>';
        echo '<td><img src="../assets/uploads/' . $row['gambar'] . '" alt="' . $row['nama_kamar'] . '"></td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '<div class="footer"><p>&copy;2025 T-Three Residences. All rights reserved.</p></div>';
    echo '<script>window.print();</script>';
    echo  '</div>';    
    exit();
}
?>
