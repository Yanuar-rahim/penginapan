<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if (isset($_POST['submit_kamar'])) {
        $nama_kamar = $_POST['nama_kamar'];
        $harga = $_POST['harga'];
        $fasilitas = $_POST['fasilitas'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsi'];

        $gambar = $_FILES['gambar']['name'];
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_path = "../assets/uploads/" . $gambar;

        if (move_uploaded_file($gambar_tmp, $gambar_path)) {
            $query_insert_kamar = "INSERT INTO kamar (nama_kamar, harga, fasilitas, status, deskripsi, gambar)
                                VALUES ('$nama_kamar', '$harga', '$fasilitas', '$status', '$deskripsi', '$gambar')";

            if (mysqli_query($koneksi, $query_insert_kamar)) {
                $_SESSION['success'] = "Data kamar berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan data kamar!";
            }
        } else {
            $_SESSION['error'] = "Gagal mengunggah gambar!";
        }

        header("Location: manajemen_penginapan.php");
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
    <title>Tambah Kamar - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Tambah Kamar</h1>
            </header>

            <section class="main-content">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-error"><?= $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-success"><?= $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="form-container">
                    <h2>Form Tambah Kamar</h2>

                    <div class="input-field">
                        <label for="nama_kamar">Nama Kamar</label>
                        <input type="text" name="nama_kamar" required>
                    </div>

                    <div class="input-field">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" required>
                    </div>

                    <div class="input-field">
                        <label for="fasilitas">Fasilitas</label>
                        <input type="text" name="fasilitas" required>
                    </div>

                    <div class="input-field">
                        <label for="status">Status</label>
                        <select name="status" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" required></textarea>
                    </div>

                    <div class="input-field">
                        <label for="gambar">Gambar</label>
                        <input type="file" name="gambar" required>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="submit" name="submit_kamar" class="btn-primary">Tambah Kamar</button>
                        <a href="manajemen_penginapan.php" class="btn-primary red">Kembali</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>