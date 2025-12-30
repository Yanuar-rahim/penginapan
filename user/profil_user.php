<?php
    session_start();
    include '../config/koneksi.php';


    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $query = mysqli_query($koneksi, "SELECT nama_lengkap, email, username FROM users WHERE id='$user_id'");
    $user = mysqli_fetch_assoc($query);

    $err = "";
    $scs = "";


    if (isset($_POST['update_profile'])) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $email = $_POST['email'];


        $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap', email='$email', username='$username' WHERE id='$user_id'";
        if (mysqli_query($koneksi, $update_query)) {
            $scs = "Profil berhasil diperbarui!";
        } else {
            $err = "Gagal memperbarui profil.";
        }
    }


    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        $query_pass = mysqli_query($koneksi, "SELECT password FROM users WHERE id='$user_id'");
        $user_data = mysqli_fetch_assoc($query_pass);

        if (password_verify($current_password, $user_data['password'])) {
            if ($new_password === $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $update_pass_query = "UPDATE users SET password='$new_password_hash' WHERE id='$user_id'";
                if (mysqli_query($koneksi, $update_pass_query)) {
                    $scs = "Password berhasil diperbarui!";
                } else {
                    $err = "Gagal memperbarui password.";
                }
            } else {
                $err = "Password baru dan konfirmasi password tidak cocok.";
            }
        } else {
            $err = "Password lama salah.";
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
    <title>Profil Saya - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/user.css">
</head>
<style>
    .card-profil {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-profil h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }


    .input-field {
        margin-bottom: 15px;
    }

    .input-field label {
        display: block;
        font-size: 1rem;
        color: #333;
        margin-bottom: 5px;
    }

    .input-field input {
        width: 98%;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }


    button.btn-primary {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        font-size: 1rem;
        margin-top: 10px;
    }

    button.btn-primary:hover {
        background-color: #45a049;
    }


    .alert {
        margin: 10px 0 20px;
        padding: 20px;
        border-radius: 5px;
        font-weight: bold;
    }

    .alert.success {
        background-color: #d1fae5;
        color: #065f46;
        border-left: 5px solid #10b981;
    }

    .alert.error {
        background-color: #fef2f2;
        color: #f87171;
        border-left: 5px solid #f87171;
    }
</style>

<body>
    <!-- Navbar User -->
    <?php include '../includes/header-user.php'; ?>

    <!-- Main Content -->
    <main class="content">


        <section class="profil-user">
            <div class="container">
                <h2>Profil Saya</h2>

                <?php if ($err): ?>
                    <div class="alert error"><?= $err; ?></div>
                <?php endif; ?>

                <?php if ($scs): ?>
                    <div class="alert success"><?= $scs; ?></div>
                <?php endif; ?>

                <!-- Form Edit Profil -->
                <section class="card-profil">
                    <h3 style="margin-bottom: 15px;">Informasi Profil</h3>
                    <form method="post" action="profil_user.php">
                        <div class="input-field">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label for="username">Username</label>
                            <input type="text" name="username" value="<?= $user['username']; ?>" required>
                        </div>
                        <div class="input-field">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="<?= $user['email']; ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn-primary">Perbarui Profil</button>
                    </form>
                </section>

                <section class="card-profil">
                    <h3 style="margin-bottom: 15px;">Ganti Password</h3>
                    <form method="post">
                        <div class="input-field">
                            <label for="current_password">Password Lama</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="input-field">
                            <label for="new_password">Password Baru</label>
                            <input type="password" name="new_password" required>
                        </div>
                        <div class="input-field">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn-primary">Ganti Password</button>
                    </form>
                </section>
            </div>
        </section>

    </main>

    <?php include '../includes/footer.php'; ?>

</body>

</html>