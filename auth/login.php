<?php
    session_start();
    include '../config/koneksi.php';

    $err = "";
    $scs = "";

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($koneksi, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['success'] = "Berhasil login! Selamat datang, " . $user['nama_lengkap'] . ".";

            if ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../user/index.php");
            }
            exit;
        } else {
            $err = "Username atau password salah!";
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
    <title>Login - <?= $website_name ?></title>
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
    <div class="form-container">
        <h2>Login</h2>

        <?php if ($err): ?>
            <div class="alert error"><?= $err; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>

</html>