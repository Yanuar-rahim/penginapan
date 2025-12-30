<?php
    session_start();
    include '../config/koneksi.php';

    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if (isset($_GET['id_kamar'])) {
        $id_kamar = $_GET['id_kamar'];

        $query = mysqli_query($koneksi, "SELECT gambar FROM kamar WHERE id_kamar = '$id_kamar'");
        $kamar = mysqli_fetch_assoc($query);

        if ($kamar) {
            $gambar = $kamar['gambar'];
            if ($gambar) {
                $image_path = "../assets/uploads/" . $gambar;
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $delete_query = "DELETE FROM kamar WHERE id_kamar = '$id_kamar'";
            if (mysqli_query($koneksi, $delete_query)) {
                $_SESSION['success'] = 'Bencana berhasil dihapus!';
                header("Location: manajemen_penginapan.php?success=1");
                exit;
            } else {
                header("Location: manajemen_penginapan.php?error=1");
                exit;
            }
        } else {
            header("Location: manajemen_penginapan.php?error=2");
            exit;
        }
    } else {
        header("Location: manajemen_penginapan.php?error=3");
        exit;
    }
?>