<?php
session_start();
include "../config/koneksi.php";

$no = 1;

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$search = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi WHERE nama_lengkap LIKE '%$search%' OR nama_kamar LIKE '%$search%' ORDER BY id_reservasi DESC");
} else {
    $query_reservasi = mysqli_query($koneksi, "SELECT * FROM reservasi ORDER BY id_reservasi DESC");
}

$query_website = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'website_name'");
$website = mysqli_fetch_assoc($query_website);

$website_name = $website['value'];

if (isset($_POST['export_excel'])) {
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data-reservasi.xls");

    echo "No\tNama User\tNama Kamar\tTotal harga\tStatus\n";

    $query_reservasi_excel = mysqli_query($koneksi, "SELECT * FROM reservasi");
    while ($row = mysqli_fetch_assoc($query_reservasi_excel)) {
        echo $no++ . "\t" . $row['nama_lengkap'] . "\t" . $row['nama_kamar'] . "\t" . $row['total_harga'] . "\t" . $row['status'] . "\n";
    }

    exit();
}

if (isset($_POST['export_pdf'])) {
    echo '<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>';
    echo '<h1>Daftar Reservasi</h1>';
    echo '<table>';
    echo '<thead>';
    echo '<tr><th>No</th><th>Nama User</th><th>Nama Kamar</th><th>Total Harga</th><th>Status</th></tr>';
    echo '</thead><tbody>';

    $no = 1;
    while ($row = mysqli_fetch_assoc($query_reservasi)) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . $row['nama_lengkap'] . '</td>';
        echo '<td>' . $row['nama_kamar'] . '</td>';
        echo '<td>' . $row['total_harga'] . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '<script>window.print();</script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Reservasi - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Manajemen Reservasi</h1>
            </header>

            <section class="main-content">
                <h2>Daftar Reservasi</h2>

                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; align-items: center;">
                    <form method="post" style="gap: 10px;">
                        <a href="pdf.php" class="btn-primary">Export PDF</a>
                        <a href="excel.php" class="btn-primary">Export Excel</a>
                    </form>

                    <form method="get" style="gap: 10px;">
                        <input type="text" name="search" placeholder="Cari berdasarkan Nama User atau Nama Kamar"
                            value="<?= $search; ?>" style="padding: 11px; width: 300px;">
                        <button type="submit" class="btn-primary">Cari</button>
                    </form>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-success"><?= $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama User</th>
                            <th>Nama Kamar</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_reservasi) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($reservasi = mysqli_fetch_assoc($query_reservasi)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td style="text-align: left;"><?= $reservasi['nama_lengkap']; ?></td>
                                    <td style="text-align: left;"><?= $reservasi['nama_kamar']; ?></td>
                                    <td style="text-align: left;">Rp.
                                        <?= number_format($reservasi['total_harga'], 0, ',', '.'); ?>
                                    </td>
                                    <td><span
                                            class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                                    </td>
                                    <td class="button-group">
                                        <?php if ($reservasi['status'] == 'dipesan'): ?>
                                            <a href="ubah_status_reservasi.php?id_reservasi=<?= $reservasi['id_reservasi']; ?>&status=konfirmasi"
                                                class="btn primary">Konfirmasi</a>
                                            <a href="ubah_status_reservasi.php?id_reservasi=<?= $reservasi['id_reservasi']; ?>&status=batal"
                                                class="btn danger">Batal</a>
                                        <?php elseif ($reservasi['status'] == 'konfirmasi'): ?>
                                            <a href="ubah_status_reservasi.php?id_reservasi=<?= $reservasi['id_reservasi']; ?>&status=selesai"
                                                class="btn success">Selesai</a>
                                        <?php elseif ($reservasi['status'] == 'batal'): ?>
                                            <span
                                                class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                                        <?php elseif ($reservasi['status'] == 'selesai'): ?>
                                            <span
                                                class="badge <?= strtolower($reservasi['status']); ?>"><?= ucfirst($reservasi['status']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">Tidak ada data <strong><?= $search; ?></strong>
                                    yang ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</body>

</html>