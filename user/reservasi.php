<?php
session_start();
include "../config/koneksi.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);


$alertSuccess = "";
$alertError = "";


if (isset($_SESSION['success'])) {
    $alertSuccess = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    $alertError = $_SESSION['error'];
    unset($_SESSION['error']);
}


$query_tipe_kamar = mysqli_query($koneksi, "SELECT DISTINCT tipe_kamar FROM kamar WHERE status = 'tersedia'");


$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';


$query_kamar = "SELECT * FROM kamar WHERE nama_kamar LIKE '%$search%' AND status = 'tersedia'";

if ($filter) {
    $query_kamar .= " AND tipe_kamar = '$filter'";
}

$query_kamar .= " LIMIT 6";

$kamar_result = mysqli_query($koneksi, $query_kamar);


if (isset($_POST['pesan'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $nama_kamar = $_POST['nama_kamar'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $gambar = $_POST['gambar'];


    if (strtotime($check_in) >= strtotime($check_out)) {
        $_SESSION['error'] = "Tanggal check-out harus lebih besar dari check-in!";
        header("Location: reservasi.php");
        exit;
    }


    $query_harga = "SELECT harga FROM kamar WHERE nama_kamar = '$nama_kamar'";
    $result_harga = mysqli_query($koneksi, $query_harga);
    $harga_kamar = mysqli_fetch_assoc($result_harga)['harga'];


    $total_harga = $harga_kamar * (strtotime($check_out) - strtotime($check_in)) / 86400;


    $query_reservasi = "INSERT INTO reservasi (nama_lengkap, nama_kamar, check_in, check_out, total_harga, gambar, status)
                                VALUES ('$nama_lengkap', '$nama_kamar', '$check_in', '$check_out', '$total_harga', '$gambar', 'dipesan')";

    if (mysqli_query($koneksi, $query_reservasi)) {
        $_SESSION['success'] = "Reservasi berhasil dilakukan!";
    } else {
        $_SESSION['error'] = "Gagal melakukan reservasi.";
    }


    header("Location: reservasi.php");
    exit;
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
    <title>Pemesanan Kamar - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/user.css">
</head>

<body>
    <?php include '../includes/header-user.php'; ?>

    <main>
        <?php if ($alertSuccess): ?>
            <div class="alert-success"><?= $alertSuccess; ?></div>
        <?php endif; ?>

        <?php if ($alertError): ?>
            <div class="alert-error"><?= $alertError; ?></div>
        <?php endif; ?>

        <section class="search-filter">
            <div class="container">
                <h2>Pemesanan Kamar</h2>

                <form method="GET" action="reservasi.php">
                    <input type="text" name="search" placeholder="Cari kamar..." value="<?= $search; ?>">
                    <select name="filter">
                        <option value="">Filter Tipe Kamar</option>
                        <option value="deluxe" <?= $filter == 'deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                        <option value="standard" <?= $filter == 'standard' ? 'selected' : ''; ?>>Standard</option>
                        <option value="suite" <?= $filter == 'suite' ? 'selected' : ''; ?>>Suite</option>
                    </select>
                    <button type="submit">Cari</button>
                </form>
            </div>
        </section>

        <section class="content">
            <section class="available-rooms">
                <div class="container">
                    <h3 align="center">Kamar Tersedia</h3>
                    <div class="room-grid">
                        <?php if (mysqli_num_rows($kamar_result) > 0): ?>
                            <?php while ($kamar = mysqli_fetch_assoc($kamar_result)) { ?>
                                <div class="room-card">
                                    <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="<?= $kamar['nama_kamar']; ?>"
                                        class="room-img">
                                    <h4><?= $kamar['nama_kamar']; ?></h4>
                                    <p>Harga: Rp. <?= number_format($kamar['harga'], 0, ',', '.'); ?>/malam</p>
                                    <p><?= substr($kamar['deskripsi'], 0, 100); ?>...</p>
                                    <form method="POST" action="reservasi.php" class="reservation-form">
                                        <input type="hidden" name="nama_kamar" value="<?= $kamar['nama_kamar']; ?>">
                                        <input type="hidden" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>">
                                        <input type="hidden" name="gambar" value="<?= $kamar['gambar']; ?>">
                                        <div class="form-group">
                                            <label for="check_in">Check-in:</label>
                                            <input type="date" name="check_in" required>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <label for="check_out">Check-out:</label>
                                            <input type="date" name="check_out" required>
                                        </div>
                                        <button type="submit" name="pesan">Pesan Sekarang</button>
                                    </form>
                                </div>
                            <?php } ?>
                        </div>
                        <?php else: ?>
                            <div class="no-data-found">
                                <p>Tidak ada data ditemukan</p>
                            </div>
                        <?php endif; ?>
                </div>
            </section>
        </section>
    </main>
    <?php include '../includes/footer.php'; ?>

</body>

</html>