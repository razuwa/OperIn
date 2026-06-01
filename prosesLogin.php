<?php
session_start();
require 'require/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Query mencari email di tabel users
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // verifikasi password
        if (password_verify($password, $user['password'])) {
            
            unset($_SESSION['old_login_email']);

            // simpan sesi
            $_SESSION['user_id']      = $user['id'];
            $_SESSION['user_name']    = $user['nama'];
            $_SESSION['role']         = $user['role'];
            $_SESSION['is_logged_in'] = true;

            // cookie 5 menit
            setcookie('operin_last_active', 'true', time() + 300, '/');

            // role
            if ($user['role'] === 'admin') {
                $redirect_url = "dashboardAdmin.php";
                $pesan_redirect = "Mengalihkan Anda ke Dashboard Admin...";
            } else {
                $redirect_url = "produk.php";
                $pesan_redirect = "Mengalihkan Anda ke Etalase Produk...";
            }
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="refresh" content="2;url=<?= $redirect_url ?>">
                <title>Memproses...</title>
                <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
            </head>
            <body class="bg-sky-600 flex items-center justify-center min-h-screen">
                <div class="bg-white p-8 rounded-2xl shadow-xl text-center max-w-sm w-full mx-4">
                    <div class="mb-4 text-green-500">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Halo, <?= htmlspecialchars($user['nama']) ?>!</h1>
                    <p class="text-gray-500 mt-2"><?= $pesan_redirect ?></p>
                    <div class="mt-4 animate-spin inline-block w-6 h-6 border-4 border-sky-500 border-t-transparent rounded-full"></div>
                </div>
            </body>
            </html>
            <?php
            exit();
        }
    }

    $_SESSION['old_login_email'] = $_POST['email'] ?? '';

    header("Location: login.php?error=" . urlencode("Email atau Password Salah"));
    exit();
}
?>