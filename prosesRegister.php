<?php
session_start();
require 'require/koneksi.php';

// Memanggil PHPMailer secara manual dari folder require
require 'require/phpmailer/Exception.php';
require 'require/phpmailer/PHPMailer.php';
require 'require/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $whatsapp = mysqli_real_escape_string($koneksi, $_POST['whatsapp']);
    $faculty_id = (int)$_POST['faculty_id'];

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

    if ($password !== $confirm_password) {
        kirimError("Konfirmasi kata sandi tidak cocok!");
    } 

    if (strlen($password) < 6) {
        kirimError("Kata sandi minimal harus 6 karakter!");
    }

    $check_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        kirimError("Email sudah terdaftar!");
    }

    unset($_SESSION['old_input']);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (str_starts_with($whatsapp, '08')) {
        $whatsapp = '628' . substr($whatsapp, 2);
    }

    // 1. GENERATE OTP & EXPIRED TIME (+5 Menit)
    $otp_code = rand(100000, 999999);
    date_default_timezone_set('Asia/Jakarta');
    $otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

    // 2. PROSES KIRIM EMAIL VIA PHPMAILER
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi Server SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rajwamailer@gmail.com';        // DI SINI: Masukkan Gmail kamu
        $mail->Password   = 'kjyh uqkm fwuc pxok';        // DI SINI: Masukkan 16 Huruf Sandi Aplikasi Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Penerima & Konten
        $mail->setFrom('rajwamailer@gmail.com', 'OperIn UNS');
        $mail->addAddress($email, $nama);

        $mail->isHTML(true);
        $mail->Subject = 'Kode Verifikasi OTP Akun OperIn Anda';
        $mail->Body    = "
            <div style='font-family: sans-serif; padding: 20px; border: 1px solid #e2e8f0; rounded: 12px;'>
                <h2 style='color: #0284c7;'>Halo, $nama!</h2>
                <p>Terima kasih telah mendaftar di OperIn. Berikut adalah kode OTP Anda untuk memverifikasi akun:</p>
                <div style='background-color: #f1f5f9; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #334155; margin: 20px 0; border-radius: 8px;'>
                    $otp_code
                </div>
                <p style='color: #ef4444; font-size: 12px;'>Kode ini berlaku selama 5 menit. Jangan sebarkan kode ini kepada siapapun.</p>
            </div>
        ";

        $mail->send();

        // 3. JIKA EMAIL BERHASIL DIKIRIM, SIMPAN DATA DENGAN STATUS 'unverified'
        $query_insert = "INSERT INTO users (nama, email, password, whatsapp, faculty_id, role, status, otp_code, otp_expiry) 
                         VALUES ('$nama', '$email', '$hashed_password', '$whatsapp', $faculty_id, 'mahasiswa', 'unverified', '$otp_code', '$otp_expiry')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            // Ambil ID user yang baru disimpan untuk sesi verifikasi
            $_SESSION['verifikasi_email'] = $email;
            header("Location: register.php?step=verifikasi");
            exit();
        } else {
            kirimError("Gagal menyimpan data ke database: " . mysqli_error($koneksi));
        }

    } catch (Exception $e) {
        kirimError("Gagal mengirim email verifikasi. Error: {$mail->ErrorInfo}");
    }

} else {
    header("Location: register.php");
    exit();
}
?>