<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "penginapan";

    $koneksi = mysqli_connect($server, $username, $password, $database);

    if ($koneksi) {
        // echo "Koneksi Berhasil";
    } else {
        die("Koneksi Gagal: " . mysqli_connect_error());
    }
?>