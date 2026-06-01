<?php
session_start();
require 'require/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $whatsapp = mysqli_real_escape_string($koneksi, $_POST['whatsapp']);
    $faculty_id = (int)$_POST['faculty_id'];

    // simpan session lama biar gak ilang
    function kirimError($pesan) {
        $_SESSION['old_input'] = [
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'whatsapp' => $_POST['whatsapp'],
            'faculty_id' => $_POST['faculty_id']
        ];
        header("Location: register.php?error=" . urlencode($pesan));
        exit();
    }

    // validasi passwowrd
    if ($password !== $confirm_password) {
        kirimError("Konfirmasi kata sandi tidak cocok!");
    } 

    // validasi karakter
    if (strlen($password) < 6) {
        kirimError("Kata sandi minimal harus 6 karakter!");
    }

    // validasi email terdaftar
    $check_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        kirimError("Email sudah terdaftar!");
    }

    unset($_SESSION['old_input']);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (str_starts_with($whatsapp, '08')) {
        $whatsapp = '628' . substr($whatsapp, 2);
    }

    $query_insert = "INSERT INTO users (nama, email, password, whatsapp, faculty_id, role) 
                     VALUES ('$nama', '$email', '$hashed_password', '$whatsapp', $faculty_id, 'mahasiswa')";
    
    if (mysqli_query($koneksi, $query_insert)) {
        header("Location: login.php?success=" . urlencode("Registrasi berhasil! Silakan login."));
        exit();
    } else {
        kirimError("Gagal menyimpan data: " . mysqli_error($koneksi));
    }
} else {
    header("Location: register.php");
    exit();
}
?>