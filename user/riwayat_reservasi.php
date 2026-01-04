<?php
session_start();
include "../config/koneksi.php";

$no = 1;

// Pastikan user sudah login dengan role 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit;
}

// Ambil ID pengguna yang sedang login
$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);

// Ambil daftar reservasi berdasarkan nama lengkap pengguna yang sedang login
$query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE nama_lengkap = '$user[nama_lengkap]' ORDER BY check_in DESC");

// Ambil nama website
$query_website = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'website_name'");
$website = mysqli_fetch_assoc($query_website);
$website_name = $website['value'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/user.css">
</head>

<body>
    <?php include '../includes/header-user.php'; ?>

    <main class="main-content">
        <section class="riwayat-reservasi">
            <div class="content">
                <h2>Riwayat Reservasi Anda</h2>

                <p>Selamat datang di halaman <strong>Riwayat Reservasi</strong>! Di sini Anda dapat melihat seluruh
                    reservasi kamar yang telah Anda lakukan. Jika Anda memiliki pertanyaan mengenai status reservasi,
                    Anda dapat menghubungi kami melalui informasi kontak yang tersedia.</p>

                <p>Berikut adalah daftar semua reservasi yang telah Anda lakukan di <?= $website_name ?>:</p>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kamar</th>
                            <th>Tanggal Check-in</th>
                            <th>Tanggal Check-out</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_reservasi) > 0) { ?>
                            <?php while ($reservasi = mysqli_fetch_assoc($query_reservasi)) { ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $reservasi['nama_kamar']; ?></td>
                                    <td><?= date('d-m-Y', strtotime($reservasi['check_in'])); ?></td>
                                    <td><?= date('d-m-Y', strtotime($reservasi['check_out'])); ?></td>
                                    <td>Rp. <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?></td>
                                    <td><span
                                            class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        if ($reservasi['status'] == 'konfirmasi' && $reservasi['nama_lengkap'] == $user['nama_lengkap']) {
                                            echo '<a href="cetak_bukti.php?id_reservasi=' . $reservasi['id_reservasi'] . '" class="btn download" target="_blank">Cetak Bukti</a>';
                                        } else {
                                            echo 'Tidak ada aksi';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7">Belum ada reservasi yang dilakukan. Silakan lakukan pemesanan kamar melalui
                                    halaman <a href="reservasi.php">Pemesanan Kamar</a>.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <p>Jika Anda membutuhkan perubahan atau pembatalan pada reservasi, kami siap membantu. Jangan ragu untuk
                    menghubungi tim kami untuk memastikan pengalaman menginap Anda lebih menyenangkan.</p>
            </div>
        </section>

    </main>
    <?php include '../includes/footer.php'; ?>

</body>

</html>
