<?php
    session_start();
    include "../config/koneksi.php";


    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        header("Location: ../index.php");
        exit;
    }

    if (isset($_GET['id_reservasi']) && isset($_GET['status'])) {
        $id_reservasi = $_GET['id_reservasi'];
        $status = $_GET['status'];

        
        $query_reservasi = mysqli_query($koneksi, "SELECT nama_kamar FROM reservasi WHERE id_reservasi = '$id_reservasi'");
        $reservasi = mysqli_fetch_assoc($query_reservasi);

        if ($reservasi) {
            $nama_kamar = $reservasi['nama_kamar'];

            
            $query_kamar = mysqli_query($koneksi, "SELECT id_kamar FROM kamar WHERE nama_kamar = '$nama_kamar'");
            $kamar = mysqli_fetch_assoc($query_kamar);

            if ($kamar) {
                $id_kamar = $kamar['id_kamar'];

                
                $query_update_reservasi = "UPDATE reservasi SET status = '$status' WHERE id_reservasi = '$id_reservasi'";

                
                if ($status == 'konfirmasi' || $status == 'terisi') {
                    $query_update_kamar = "UPDATE kamar SET status = 'terisi' WHERE id_kamar = '$id_kamar'";
                    mysqli_query($koneksi, $query_update_kamar); 
                } elseif ($status == 'batal') {
                    
                    $query_update_kamar = "UPDATE kamar SET status = 'tersedia' WHERE id_kamar = '$id_kamar'";
                    mysqli_query($koneksi, $query_update_kamar); 
                } elseif ($status == 'selesai') {
                    
                    $query_update_kamar = "UPDATE kamar SET status = 'tersedia' WHERE id_kamar = '$id_kamar'";
                    mysqli_query($koneksi, $query_update_kamar); 
                }

                if (mysqli_query($koneksi, $query_update_reservasi)) {
                    $_SESSION['success'] = 'Status reservasi berhasil diperbarui!';
                    header("Location: reservasi.php");
                    exit;
                } else {
                    $_SESSION['error'] = 'Gagal memperbarui status reservasi!';
                    header("Location: reservasi.php");
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Kamar tidak ditemukan!';
                header("Location: reservasi.php");
                exit;
            }
        } else {
            $_SESSION['error'] = 'Reservasi tidak ditemukan!';
            header("Location: reservasi.php");
            exit;
        }
    } else {
        $_SESSION['error'] = 'Data tidak valid!';
        header("Location: reservasi.php");
        exit;
    }
?>