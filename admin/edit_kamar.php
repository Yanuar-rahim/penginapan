<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if (!isset($_GET['id_kamar'])) {
        die("ID kamar tidak ditemukan dalam URL.");
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

    if (isset($_POST['update_kamar'])) {
        $nama_kamar = $_POST['nama_kamar'];
        $harga = $_POST['harga'];
        $fasilitas = $_POST['fasilitas'];
        $status = $_POST['status'];
        $deskripsi = $_POST['deskripsi'];

        if ($_FILES['gambar']['name']) {
            $gambar = $_FILES['gambar']['name'];
            $gambar_tmp = $_FILES['gambar']['tmp_name'];
            $gambar_path = "../assets/uploads/" . $gambar;

            if (move_uploaded_file($gambar_tmp, $gambar_path)) {
                $query_update = "UPDATE kamar SET 
                                    nama_kamar = '$nama_kamar', 
                                    harga = '$harga', 
                                    fasilitas = '$fasilitas', 
                                    status = '$status', 
                                    deskripsi = '$deskripsi', 
                                    gambar = '$gambar' 
                                    WHERE id_kamar = '$id_kamar'";
            } else {
                $query_update = "UPDATE kamar SET 
                                    nama_kamar = '$nama_kamar', 
                                    harga = '$harga', 
                                    fasilitas = '$fasilitas', 
                                    status = '$status', 
                                    deskripsi = '$deskripsi' 
                                    WHERE id_kamar = '$id_kamar'";
            }
        } else {
            $query_update = "UPDATE kamar SET 
                                nama_kamar = '$nama_kamar', 
                                harga = '$harga', 
                                fasilitas = '$fasilitas', 
                                status = '$status', 
                                deskripsi = '$deskripsi' 
                                WHERE id_kamar = '$id_kamar'";
        }

        if (mysqli_query($koneksi, $query_update)) {
            $_SESSION['success'] = "Kamar berhasil diperbarui!";
            header("Location: manajemen_penginapan.php");
            exit;
        } else {
            $error = "Gagal memperbarui kamar!";
        }
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
    <title>Edit Kamar</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Edit Kamar</h1>
            </header>

            <section class="main-content">
                <?php if (isset($error)): ?>
                    <div class="alert-error"><?= $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="form-container">
                    <h2>Form Edit Kamar</h2>

                    <div class="input-field">
                        <label for="nama_kamar">Nama Kamar</label>
                        <input type="text" name="nama_kamar" value="<?= $kamar['nama_kamar']; ?>" required>
                    </div>

                    <div class="input-field">
                        <label for="harga">Harga</label>
                        <input type="number" name="harga" value="<?= $kamar['harga']; ?>" required>
                    </div>

                    <div class="input-field">
                        <label for="fasilitas">Fasilitas</label>
                        <input type="text" name="fasilitas" value="<?= $kamar['fasilitas']; ?>" required>
                    </div>

                    <div class="input-field">
                        <label for="status">Status</label>
                        <select name="status" required>
                            <option value="tersedia" <?= $kamar['status'] == 'tersedia' ? 'selected' : ''; ?>>Tersedia
                            </option>
                            <option value="terisi" <?= $kamar['status'] == 'terisi' ? 'selected' : ''; ?>>Terisi</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" required><?= $kamar['deskripsi']; ?></textarea>
                    </div>

                    <div class="input-field">
                        <label for="gambar">Gambar</label>
                        <input type="file" name="gambar">
                        <small>Gambar Saat Ini:</small>
                        <div class="kamar-img">
                            <img src="../assets/uploads/<?= $kamar['gambar']; ?>" alt="Gambar Kamar" width="150">
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="submit" name="update_kamar" class="btn-primary">Perbarui Kamar</button>
                        <a href="manajemen_penginapan.php" class="btn-primary red">Kembali</a>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>