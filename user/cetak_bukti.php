<?php
session_start();
include "../config/koneksi.php";

// Pastikan user sudah login dan memiliki role user
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id_reservasi'])) {
    $id_reservasi = $_GET['id_reservasi'];
    // Mengambil data reservasi berdasarkan ID
    $reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE id_reservasi = '$id_reservasi'");
    $data = mysqli_fetch_array($reservasi);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Reservasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .cards {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            background-color: #fff;
        }

        .cards h2 {
            text-align: center;
            color: #333;
        }

        .cards .row {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 15px;
        }

        .cards .row label {
            font-weight: bold;
            width: 180px;
            /* Memberikan lebar tetap pada label untuk memastikan titik dua sejajar */
            text-align: left;
            /* Untuk memastikan label selalu rata kiri */
            margin-right: 10px;
            /* Menambahkan jarak antara label dan nilai */
        }

        .cards .row div {
            flex: 1;
            text-align: left;
        }


        .status {
            font-weight: bold;
            color: green;
        }

        .card {
            display: flex;
            width: 100%;
            justify-content: space-between;
        }

        .card img {
            width: 50%;
            height: 188px;
            border-radius: 10px;
        }

        .cards-footer {
            text-align: center;
            margin-top: 20px;
        }

        .btn-print {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-print:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Bukti Reservasi</h1>

        <div class="cards">
            <h2>Detail Reservasi</h2>
            <div class="card">
                <div class="deskripsi">
                    <div class="row">
                        <label>Nama Pengguna</label>
                        <div><strong>:</strong> <?php echo $data['nama_lengkap']; ?></div>
                    </div>
                    <div class="row">
                        <label>Nama Kamar</label>
                        <div><strong>:</strong> <?php echo $data['nama_kamar']; ?></div>
                    </div>
                    <div class="row">
                        <label>Tanggal Check-in</label>
                        <div><strong>:</strong> <?php echo date('d-m-Y', strtotime($data['check_in'])); ?></div>
                    </div>
                    <div class="row">
                        <label>Tanggal Check-out</label>
                        <div><strong>:</strong> <?php echo date('d-m-Y', strtotime($data['check_out'])); ?></div>
                    </div>
                    <div class="row">
                        <label>Total Harga</label>
                        <div><strong>:</strong> Rp. <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="row">
                        <label>Status</label>
                        <div class="status">: <?php echo ucfirst($data['status']); ?></div>
                    </div>
                </div>
                <img src="../assets/uploads/<?= $data['gambar']; ?>" alt="<?= $data['nama_kamar'] ?>">
            </div>
        </div>

        <div class="cards-footer">
            <a href="javascript:window.print()" class="btn-print">Cetak Bukti</a>
        </div>
    </div>
</body>

</html>