<?php
    session_start();
    include '../config/koneksi.php';

    $err = "";
    $scs = "";

    if (isset($_POST['register'])) {
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];


        if (empty($nama_lengkap) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            $err = "Semua field harus diisi!";
        } elseif ($password !== $confirm_password) {
            $err = "Password dan konfirmasi password tidak cocok!";
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


            $query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $result = mysqli_query($koneksi, $query);
            if (mysqli_num_rows($result) > 0) {
                $err = "Username atau email sudah terdaftar!";
            } else {

                $query = "INSERT INTO users (nama_lengkap, username, email, password, role) 
                                VALUES ('$nama_lengkap', '$username', '$email', '$hashed_password', 'user')";
                if (mysqli_query($koneksi, $query)) {

                    $last_id = mysqli_insert_id($koneksi);


                    $update_query = "UPDATE users SET user_id = $last_id WHERE id = $last_id";
                    if (mysqli_query($koneksi, $update_query)) {
                        $scs = "Pendaftaran berhasil! Silakan login.";
                    } else {
                        $err = "Terjadi kesalahan saat memperbarui user_id!";
                    }
                } else {
                    $err = "Terjadi kesalahan saat mendaftar!";
                }
            }
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
    <title>Pendaftaran Pengguna</title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <div class="form-container">
        <h2>Registrasi</h2>

        <?php if ($scs): ?>
            <div class="alert success"><?= $scs; ?></div>
        <?php endif; ?>

        <?php if ($err): ?>
            <div class="alert error"><?= $err; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Konfirmasi Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit" name="register">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>

</html>