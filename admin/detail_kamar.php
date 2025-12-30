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

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Detail Kamar</h1>
            </header>

            <section class="main-content">
                <h3><?= $kamar['nama_kamar']; ?></h3>
                <p><strong>Harga:</strong> Rp. <?= number_format($kamar['harga'], 0, ',', '.'); ?>/malam</p>
                <p><strong>Fasilitas:</strong> <?= $kamar['fasilitas']; ?></p>
                <p><strong>Status:</strong> <?= ucfirst($kamar['status']); ?></p>
                <p><strong>Deskripsi:</strong> <?= $kamar['deskripsi']; ?></p>

                <div class="kamar-img">
                    <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="Gambar Kamar" width="300"
                        style="max-width: 100%; height: auto; border-radius: 8px;">
                </div>

                <a href="manajemen_penginapan.php"><button class="btn-primary">Kembali ke Manajemen
                        Penginapan</button></a>
            </section>
        </main>
    </div>
</body>

</html>