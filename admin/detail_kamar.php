<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $id_kamar = $_GET['id_kamar'];

    $query_kamar = mysqli_query($koneksi, "SELECT * FROM kamar WHERE id_kamar = '$id_kamar'");

    if (!$query_kamar) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    $kamar = mysqli_fetch_assoc($query_kamar);

    if (!$kamar) {
        die("Data kamar tidak ditemukan untuk ID: " . $id_kamar);
    }

    $query_website = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'website_name'");
    $website = mysqli_fetch_assoc($query_website);

    $website_name = $website['value'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kamar - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<style>
    .row {
        display: flex;
        align-items: center;
    }

    .row p {
        width: 120px;
    }

    .row span {
        width: 70%;
        text-align: left;
    }

    .card-detail {
        margin-top: 20px;
        padding: 5px 30px 30px 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        /* background-color: red; */
    }

    .card-details {
        display: flex;
        justify-content: space-between;
    }

    .deskripsi {
        width: 50%;
    }

    .detail-img {
        width: 50%;
    }

    .detail-img img {
        width: 100%;
    }
</style>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Detail Kamar</h1>
            </header>

            <section class="main-content">
                <div class="card-detail">
                    <h3><?= $kamar['nama_kamar']; ?></h3>

                    <div class="card-details">
                        <div class="deskripsi">
                            <div class="row">
                                <p><strong>Harga</strong></p>
                                <span>: Rp. <?= number_format($kamar['harga'], 0, ',', '.'); ?>/malam</span>
                            </div>
                            <div class="row">
                                <p><strong>Fasilitas</strong></p>
                                <span>: <?= $kamar['fasilitas']; ?></span>
                            </div>
                            <div class="row">
                                <p><strong>Status</strong></p>
                                <span>: <?= ucfirst($kamar['status']); ?></span>
                            </div>
                            <div class="row" style="align-items: baseline;">
                                <p><strong>Deskripsi</strong></p>
                                <span>: <?= $kamar['deskripsi']; ?></span>
                            </div>
                        </div>
                        <div class="detail-img">
                            <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="Gambar Kamar" width="300"
                                style="max-width: 100%; height: auto; border-radius: 8px;">
                        </div>
                    </div>
                    <a href="manajemen_penginapan.php"><button class="btn-primary">Kembali ke Manajemen
                            Penginapan</button></a>
                </div>
            </section>
        </main>
    </div>
</body>

</html>