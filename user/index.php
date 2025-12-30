<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
        header("Location: ../index.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $nama_lengkap = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_lengkap FROM users WHERE id='$user_id'"))['nama_lengkap'];
    $query = mysqli_query($koneksi, "SELECT nama_lengkap, username FROM users WHERE id='$user_id'");

    if ($query) {
        if (mysqli_num_rows($query) == 1) {
            $user = mysqli_fetch_assoc($query);
        } else {
            $_SESSION['error'] = "User tidak ditemukan!";
            header("Location: ../index.php");
            exit;
        }
    } else {

        $_SESSION['error'] = "Query gagal: " . mysqli_error($koneksi);
        header("Location: ../index.php");
        exit;
    }

    $query_kamar = mysqli_query($koneksi, "SELECT * FROM kamar LIMIT 3");

    $alertSuccess = "";
    if (isset($_SESSION['success'])) {
        $alertSuccess = $_SESSION['success'];
        unset($_SESSION['success']);
    }


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
    <title>Halaman Utama - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/user.css">
</head>

<style>
    .hero {
        background-image: url('../assets/img/<?= $hero_image_url ?>');
        background-size: cover;
        background-position: center;
        padding: 150px 20px;
        color: white;
        text-align: center;
    }
</style>

<body>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if ($alertSuccess): ?>
        <div class="alert-success"><?= $alertSuccess; ?></div>
    <?php endif; ?>

    <?php include '../includes/header-user.php'; ?>

    <main>
        <section class="hero">
            <div class="container">
                <h1>Selamat Datang, <?= $user['nama_lengkap']; ?></h1>
                <p>Temukan kenyamanan dan kualitas terbaik di penginapan kami. Pilih kamar terbaik untuk pengalaman
                    menginap Anda.</p>
                <a href="reservasi.php" class="btn">Reservasi Sekarang</a>
            </div>
        </section>

        <section class="content">
            <section class="available-rooms">
                <div class="container">
                    <h2>Kamar Tersedia</h2>
                    <div class="room-grid">
                        <?php while ($kamar = mysqli_fetch_assoc($query_kamar)) { ?>
                            <div class="room-card">
                                <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="<?= $kamar['nama_kamar']; ?>"
                                    class="room-img">
                                <div class="room-card-text">
                                    <h3><?= $kamar['nama_kamar']; ?></h3>
                                    <p>Harga: Rp. <?= number_format($kamar['harga'], 0, ',', '.'); ?>/malam</p>
                                    <p><?= substr($kamar['deskripsi'], 0, 100); ?>...</p>
                                    <a href="reservasi.php" class="btn">Reservasi Sekarang</a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>

            <section class="about-us">
                <div class="container">
                    <h2>Tentang <?= $website_name ?></h2>
                    <p><?= $website_name ?> menawarkan pengalaman menginap yang luar biasa dengan berbagai pilihan kamar
                        yang nyaman, fasilitas terbaik, dan pelayanan yang ramah. Apapun kebutuhan Anda, kami siap
                        menyambut Anda dengan layanan yang memadai.</p>
                </div>
            </section>

        </section>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>