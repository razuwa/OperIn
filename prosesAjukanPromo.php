<?php
session_start();
require 'require/koneksi.php';
require 'require/functions.php';

// Pastikan hanya admin yang bisa mengakses
require_admin_session();

if (!isset($_GET['action']) || !isset($_GET['id'])) {
    if (is_ajax_request()) {
        json_response(false, 'Data aksi pengguna tidak lengkap.');
    }
    header("Location: kelolaUser.php"); exit();
}

$action = $_GET['action'];
$target_id = (int)$_GET['id'];
$admin_id = $_SESSION['user_id'];
$msg = '';

// Mencegah admin memanipulasi akunnya sendiri (Kecuali reset password)
if ($target_id === $admin_id && $action !== 'reset') {
    if (is_ajax_request()) {
        json_response(false, "Anda tidak dapat mengubah status atau role akun Anda sendiri.");
    }
    header("Location: kelolaUser.php?msg=" . urlencode("Anda tidak dapat mengubah status atau role akun Anda sendiri."));
    exit();
}

// EKSEKUSI BERDASARKAN AKSI
switch ($action) {
    case 'reset':
        // Enkripsi ulang sandi default menjadi "123456"
        $new_password = password_hash('123456', PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = '$new_password' WHERE id = $target_id";
        if (mysqli_query($koneksi, $query)) {
            $msg = "Password berhasil direset menjadi: 123456";
        }
        break;

    case 'role':
        // Cek role saat ini, lalu balikkan nilainya (mahasiswa -> admin -> mahasiswa)
        $q_role = mysqli_query($koneksi, "SELECT role FROM users WHERE id = $target_id");
        $current_role = mysqli_fetch_assoc($q_role)['role'];
        $new_role = ($current_role === 'admin') ? 'mahasiswa' : 'admin';
        
        $query = "UPDATE users SET role = '$new_role' WHERE id = $target_id";
        if (mysqli_query($koneksi, $query)) {
            $msg = "Role berhasil diubah menjadi " . strtoupper($new_role) . ".";
        }
        break;

    case 'status':
        // Cek status saat ini, lalu balikkan nilainya (active -> banned -> active)
        $q_status = mysqli_query($koneksi, "SELECT status FROM users WHERE id = $target_id");
        $current_status = mysqli_fetch_assoc($q_status)['status'];
        $new_status = ($current_status === 'active') ? 'banned' : 'active';
        
        $query = "UPDATE users SET status = '$new_status' WHERE id = $target_id";
        if (mysqli_query($koneksi, $query)) {
            $msg = "Akun berhasil di-" . ($new_status === 'banned' ? 'blokir' : 'aktifkan kembali') . ".";
        }
        break;

    case 'delete':
        // Hapus permanen (Sangat berbahaya, akan memicu efek domino ON DELETE CASCADE)
        $query = "DELETE FROM users WHERE id = $target_id";
        if (mysqli_query($koneksi, $query)) {
            $msg = "Pengguna berhasil dihapus secara permanen beserta datanya.";
        } else {
            $msg = "Gagal menghapus! " . mysqli_error($koneksi);
        }
        break;

    default:
        $msg = "Aksi tidak dikenali.";
}

if (is_ajax_request()) {
    json_response($msg !== "Aksi tidak dikenali.", $msg, [
        'id' => $target_id,
        'action' => $action,
        'role' => $new_role ?? null,
        'status' => $new_status ?? null
    ]);
}

header("Location: kelolaUser.php?msg=" . urlencode($msg));
exit();
