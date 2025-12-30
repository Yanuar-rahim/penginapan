<?php
    session_start();
    include "../config/koneksi.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $query_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
    $user = mysqli_fetch_assoc($query_user);

    if (isset($_POST['update_profile'])) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $email = $_POST['email'];

        $update_query = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email' WHERE id = '$user_id'";

        if (mysqli_query($koneksi, $update_query)) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";
            header("Location: settings.php");
            exit;
        } else {
            $error = "Gagal memperbarui profil!";
        }
    }

    if (isset($_POST['update_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($old_password, $user['password'])) {
            if ($new_password == $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $update_password_query = "UPDATE users SET password = '$new_password_hash' WHERE id = '$user_id'";

                if (mysqli_query($koneksi, $update_password_query)) {
                    $_SESSION['success'] = "Password berhasil diperbarui!";
                    header("Location: settings.php");
                    exit;
                } else {
                    $error = "Gagal memperbarui password!";
                }
            } else {
                $error = "Konfirmasi password tidak sesuai!";
            }
        } else {
            $error = "Password lama tidak cocok!";
        }
    }

    if (isset($_POST['update_website'])) {
        $website_name = $_POST['website_name'];

        $update_website_query = "UPDATE settings SET value = '$website_name' WHERE setting_key = 'website_name'";

        if (mysqli_query($koneksi, $update_website_query)) {
            $_SESSION['success'] = "Pengaturan website berhasil diperbarui!";
            header("Location: settings.php");
            exit;
        } else {
            $error = "Gagal memperbarui pengaturan website!";
        }
    }

    if (isset($_POST['update_hero_image'])) {
        if ($_FILES['hero_image']['error'] == 0) {
            $upload_dir = '../assets/img/';
            $uploaded_file = $upload_dir . basename($_FILES['hero_image']['name']);
            $file_type = $_FILES['hero_image']['type'];
            $file_size = $_FILES['hero_image']['size'];

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; 

            if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
                $query_hero_image = mysqli_query($koneksi, "SELECT value FROM settings WHERE setting_key = 'hero_image'");
                $hero_image = mysqli_fetch_assoc($query_hero_image);
                $old_hero_image = $hero_image['value'] ?? null;

                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploaded_file)) {
                    $image_path = basename($_FILES['hero_image']['name']); 

                    if ($old_hero_image && file_exists($upload_dir . $old_hero_image)) {
                        unlink($upload_dir . $old_hero_image);
                    }

                    $update_hero_query = "UPDATE settings SET value = '$image_path' WHERE setting_key = 'hero_image'";

                    if (mysqli_query($koneksi, $update_hero_query)) {
                        $_SESSION['success'] = "Gambar hero berhasil diperbarui!";
                        header("Location: settings.php");
                        exit;
                    } else {
                        $error = "Gagal memperbarui gambar hero di database!";
                    }
                } else {
                    $error = "Gagal mengupload gambar!";
                }
            } else {
                $error = "Tipe file tidak valid atau ukuran file terlalu besar!";
            }
        } else {
            $error = "Tidak ada file gambar yang diupload!";
        }
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
    <title>Pengaturan Admin - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<style>
    .form-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        width: 400px;
        margin: 0 auto;
    }

    .input-field {
        margin-bottom: 15px;
    }

    .input-field label {
        font-size: 14px;
        margin-bottom: 5px;
        display: block;
    }

    .input-field input {
        width: 97.5%;
        padding: 10px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button.btn-primary {
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button.btn-primary:hover {
        background-color: #0056b3;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 5px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 15px;
        border-radius: 5px;
    }
</style>

<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <main>
            <header>
                <h1>Pengaturan Admin</h1>
            </header>

            <section class="main-content">
                <?php if (isset($error)): ?>
                    <div class="alert-error"><?= $error; ?></div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-success"><?= $_SESSION['success']; ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <h2>Pengaturan Profil Admin</h2>
                <form method="POST">
                    <div class="input-field">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>
                    </div>

                    <div class="input-field">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?= $user['email']; ?>" required>
                    </div>

                    <button type="submit" name="update_profile" class="btn-primary">Perbarui Profil</button>
                </form>

                <h2>Pengaturan Password</h2>
                <form method="POST">
                    <div class="input-field">
                        <label for="old_password">Password Lama</label>
                        <input type="password" name="old_password" required>
                    </div>

                    <div class="input-field">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" required>
                    </div>

                    <div class="input-field">
                        <label for="confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" required>
                    </div>

                    <button type="submit" name="update_password" class="btn-primary">Perbarui Password</button>
                </form>

                <h2>Pengaturan Website</h2>
                <form method="POST">
                    <div class="input-field">
                        <label for="website_name">Nama Website</label>
                        <input type="text" name="website_name" value="<?= $website_name ?>" required>
                    </div>

                    <button type="submit" name="update_website" class="btn-primary">Perbarui Pengaturan</button>
                </form>

                <h2>Pengaturan Gambar Hero</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="input-field">
                        <label for="hero_image">Gambar Hero <br>
                            <img src="../assets/img/<?= $hero_image_url ?>" alt="<?= $hero_image_url ?>"
                                style="width: 200px; height: 120px; margin: 20px 0;">
                        </label>
                    </div>
                    <button type="submit" name="update_hero_image" class="btn-primary">Perbarui Gambar Hero</button>
                </form>
            </section>
        </main>
    </div>
</body>

</html>