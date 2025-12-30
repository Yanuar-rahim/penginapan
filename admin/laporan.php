<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $query_total_pemasukan = mysqli_query($koneksi, "SELECT SUM(total_harga) as total_pemasukan FROM reservasi WHERE status = 'selesai'");
    $total_pemasukan = mysqli_fetch_assoc($query_total_pemasukan)['total_pemasukan'];

    $query_total_kamar_dipesan = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE status = 'dipesan'");
    $total_kamar_dipesan = mysqli_num_rows($query_total_kamar_dipesan);

    $query_total_pengguna = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'user'");
    $total_user = mysqli_num_rows($query_total_pengguna);

    $query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE status = 'dipesan' ORDER BY check_in DESC");

    $query_website = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'website_name'");
    $website = mysqli_fetch_assoc($query_website);

    $query_hero_image = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'hero_image'");
    $hero_image = mysqli_fetch_assoc($query_hero_image);
    $hero_image_url = $hero_image['value'];

    $website_name = $website['value'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemesanan Kamar - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Laporan Pemesanan Kamar</h1>
            </header>

            <section class="main-content">
                <div class="cards">
                    <div class="card">
                        <h3>Total Pemasukan</h3>
                        <p>Rp. <?= number_format($total_pemasukan, 0, ',', '.'); ?></p>
                    </div>

                    <div class="card">
                        <h3>Total Kamar Dipesan</h3>
                        <p><?= $total_kamar_dipesan ?> Kamar</p>
                    </div>

                    <div class="card">
                        <h3>Total Pengguna</h3>
                        <p><?= $total_user; ?> Pengguna</p>
                    </div>
                </div>

                <h2>Daftar Kamar yang Dipesan</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kamar</th>
                            <th>Nama Pengguna</th>
                            <th>Tanggal Check-in</th>
                            <th>Tanggal Check-out</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_reservasi) > 0) { ?>
                            <?php $no = 1;
                            while ($reservasi = mysqli_fetch_assoc($query_reservasi)) { ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td style="text-align: left;"><?= $reservasi['nama_kamar']; ?></td>
                                    <td style="text-align: left;"><?= $reservasi['nama_lengkap']; ?></td>
                                    <td width="12%"><?= date('d-m-Y', strtotime($reservasi['check_in'])); ?></td>
                                    <td width="12%"><?= date('d-m-Y', strtotime($reservasi['check_out'])); ?></td>
                                    <td style="text-align: left;">Rp.
                                        <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?></td>
                                    <td><span
                                            class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7">Belum ada kamar yang dipesan.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>