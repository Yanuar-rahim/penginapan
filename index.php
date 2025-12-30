<?php
    include 'config/koneksi.php';
    session_start();


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
    <title>Home - <?= $website_name ?></title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<style>
    .hero {
        background-image: url('assets/img/<?= $hero_image_url ?>');
        background-size: cover;
        background-position: center;
        padding: 150px 20px;
        color: white;
        text-align: center;
    }
</style>

<body>
    <?php include 'includes/header-index.php'; ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Selamat Datang di <?= $website_name ?></h1>
            <p>Tempat yang nyaman untuk beristirahat dan menikmati waktu bersama keluarga atau teman.</p>
            <a href="auth/login.php" class="btn">Reservasi Sekarang</a>
        </div>
    </section>

    <section class="content">

        <section class="deskripsi">
            <h2>Mengapa Memilih Kami?</h2>
            <p>Kami menawarkan berbagai fasilitas terbaik untuk memastikan kenyamanan Anda selama menginap bersama kami.
                Dengan lokasi strategis dan pelayanan ramah, kami siap menyambut Anda kapan saja.</p>
        </section>

        <section class="cards">
            <div class="card">
                <h3>Kamar Nyaman</h3>
                <p>Kamar yang bersih dan nyaman dengan fasilitas lengkap untuk kebutuhan Anda.</p>
            </div>
            <div class="card">
                <h3>Fasilitas Lengkap</h3>
                <p>Kolam renang, restoran, dan area rekreasi untuk menambah kenyamanan Anda.</p>
            </div>
            <div class="card">
                <h3>Lokasi Strategis</h3>
                <p>Terletak di pusat kota, memudahkan akses ke berbagai tempat menarik.</p>
            </div>
            <div class="card">
                <h3>Pelayanan Ramah</h3>
                <p>Staf kami siap membantu Anda dengan senyum dan pelayanan terbaik.</p>
            </div>
        </section>

        <section class="cards">
            <div class="card" style="flex-basis: 100%; text-align: center;">
                <h3>Ayo Bergabung dengan Kami!</h3>
                <p>Jangan lewatkan kesempatan untuk merasakan pengalaman menginap yang tak terlupakan. Daftar sekarang
                    dan nikmati penawaran spesial dari kami!</p>
                <a href="auth/register.php" class="btn" style="margin-bottom: 20px;">Daftar Sekarang</a>
            </div>
        </section>

    </section>

    <?php include 'includes/footer.php'; ?>
</body>

</html>