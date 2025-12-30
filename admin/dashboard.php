<?php
    session_start();
    include "../config/koneksi.php";

    $no = 1;
    $noo = 1;

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($query_user);

    $query_kamar = mysqli_query($koneksi, "SELECT * FROM kamar");

    $query_users = mysqli_query($koneksi, "SELECT * FROM users WHERE role = 'user'");

    $query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi ORDER BY created_at DESC");

    $alertSuccess = "";
    if (isset($_SESSION['success'])) {
        $alertSuccess = $_SESSION['success'];
        unset($_SESSION['success']);
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
    <title>Dashboard Admin - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Dashboard</h1>
            </header>

            <section class="main-content">
                <?php if ($alertSuccess): ?>
                    <div class="alert-success"><?= $alertSuccess; ?></div>
                <?php endif; ?>

                <p>Selamat datang, <strong><?= $user['nama_lengkap']; ?>!</strong> Ini adalah halaman dashboard admin.
                </p>

                <div class="cards">
                    <div class="card">
                        <h3>Jumlah Kamar</h3>
                        <p><?= mysqli_num_rows($query_kamar); ?> Kamar</p>
                    </div>
                    <div class="card">
                        <h3>Jumlah Pengguna</h3>
                        <p><?= mysqli_num_rows($query_users); ?> Pengguna</p>
                    </div>
                </div>

                <h3>Data Kamar</h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kamar</th>
                        <th>Harga</th>
                        <th>Fasilitas</th>
                        <th>Status</th>
                        <th>Gambar</th>
                    </tr>
                    <?php
                    while ($kamar = mysqli_fetch_assoc($query_kamar)) {
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td style="text-align: left;"><?= $kamar['nama_kamar']; ?></td>
                            <td style="text-align: left;"><?= "Rp. " . number_format($kamar['harga'], 0, ',', '.'); ?></td>
                            <td style="text-align: left;"><?= $kamar['fasilitas']; ?></td>
                            <td style="text-align: center;"><span
                                    class="badge <?= strtolower($kamar['status']); ?>"><?= ucfirst($kamar['status']); ?></span>
                            </td>
                            <td style="text-align: center;">
                                <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="Gambar Kamar" width="100">
                            </td>
                        </tr>
                    <?php } ?>
                </table>

                <h3>Data Reservasi</h3>
                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Pemesan</th>
                        <th>Kamar</th>
                        <th>Tanggal Check-in</th>
                        <th>Tanggal Check-out</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    while ($reservasi = mysqli_fetch_assoc($query_reservasi)) {
                        ?>
                        <tr>
                            <td><?= $noo++ ?></td>
                            <td style="text-align: left;"><?= $reservasi['nama_lengkap']; ?></td>
                            <td style="text-align: left;"><?= $reservasi['nama_kamar']; ?></td>
                            <td style="text-align: center;" width="12%">
                                <?= date('d-m-Y', strtotime($reservasi['check_in'])); ?></td>
                            <td style="text-align: center;" width="12%">
                                <?= date('d-m-Y', strtotime($reservasi['check_out'])); ?></td>
                            <td style="text-align: left;">Rp. <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?>
                            </td>
                            <td><span
                                    class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
        </main>
    </div>
</body>

</html>