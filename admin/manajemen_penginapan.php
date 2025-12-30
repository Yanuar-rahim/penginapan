<?php
    session_start();
    include "../config/koneksi.php";

    $no = 1;

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';

    $query_kamar = "SELECT * FROM kamar WHERE nama_kamar LIKE '%$search%'";

    $query_kamar_result = mysqli_query($koneksi, $query_kamar);

    $alertSuccess = "";
    if (isset($_SESSION['success'])) {
        $alertSuccess = $_SESSION['success'];
        unset($_SESSION['success']);
    }

    $query_website = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'website_name'");
    $website = mysqli_fetch_assoc($query_website);

    $website_name = $website['value'];  

    if (isset($_POST['export_excel'])) {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=data-kamar.xls");

        echo "No\tNama Kamar\tTipe\tHarga\tGambar\n";

        $query_kamar_excel = mysqli_query($koneksi, "SELECT * FROM kamar");
        while ($row = mysqli_fetch_assoc($query_kamar_excel)) {
            echo $no++ . "\t" . $row['nama_kamar'] . "\t" . $row['tipe_kamar'] . "\t" . $row['harga'] . "\t" . $row['gambar'] . "\n";
        }

        exit();
    }

    if (isset($_POST['export_pdf'])) {
        echo '<style>table { width: 100%; border-collapse: collapse; } table, th, td { border: 1px solid black; padding: 8px; text-align: left; } th { background-color: #f2f2f2; }</style>';
        echo '<h1>Daftar Kamar</h1>';
        echo '<table>';
        echo '<thead>';
        echo '<tr><th>No</th><th>Nama Kamar</th><th>Harga</th><th>Gambar</th></tr>';
        echo '</thead><tbody>';

        while ($row = mysqli_fetch_assoc($query_kamar_result)) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row['nama_kamar'] . '</td>';
            echo '<td>' . $row['harga'] . '</td>';
            echo '<td><img src="../assets/uploads/' . $row['gambar'] . '" alt="' . $row['nama_kamar'] . '" style="width: 200px; height: 120px; margin-bottom: 20px; object-fit: cover;"></td>';
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
    <title>Manajemen Penginapan - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Manajemen Penginapan</h1>
            </header>

            <section class="main-content">
                <?php if ($alertSuccess): ?>
                    <div class="alert-success"><?= $alertSuccess; ?></div>
                <?php endif; ?>

                <h3>Daftar Kamar</h3>

                <form method="GET" action="manajemen_penginapan.php" class="form-search">
                    <div class="search-input">
                        <input type="text" name="search" placeholder="Cari kamar..." value="<?= $search; ?>"
                            style="width: 185px;">
                        <button type="submit" class="search">Cari</button>
                    </div>
                </form>

                <form method="post" class="nav">
                    <div style="display: flex; gap: 5px;">
                        <button type="submit" name="export_pdf" class="btn-primary">Export PDF</button>
                        <button type="submit" name="export_excel" class="btn-primary">Export Excel</button>
                    </div>
                    <a href="tambah_kamar.php" class="btn-primary">Tambah Kamar</a>
                </form>

                <table>
                    <tr>
                        <th>No.</th>
                        <th>Nama Kamar</th>
                        <th>Tipe</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>

                    <?php if (mysqli_num_rows($query_kamar_result) > 0): ?>
                        <?php while ($kamar = mysqli_fetch_assoc($query_kamar_result)) { ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td style="text-align: left;"><?= $kamar['nama_kamar']; ?></td>
                                <td style="text-align: left;"><?= $kamar['tipe_kamar']; ?></td>
                                <td style="text-align: left;"><?= "Rp. " . number_format($kamar['harga'], 0, ',', '.'); ?></td>
                                <td><img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="Gambar Kamar"
                                        style="width: 100px; height: 60px;"></td>
                                <td class="button-group">
                                    <a href="detail_kamar.php?id_kamar=<?= $kamar['id_kamar']; ?>" class="btn detail">Lihat
                                        Detail</a>
                                    <a href="edit_kamar.php?id_kamar=<?= $kamar['id_kamar']; ?>" class="btn edit">Edit</a>
                                    <a href="proses_kamar.php?action=delete&id_kamar=<?= $kamar['id_kamar']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')"
                                        class="btn hapus">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada kamar <strong><?= $search ?></strong> yang
                                ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </table>
            </section>
        </main>
    </div>
</body>

</html>