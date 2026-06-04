<?php
session_start();
require 'require/koneksi.php';

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "dashboardAdmin.php" : "produk.php"));
    exit();
}

$error_message = $_GET['error'] ?? '';
$success_message = $_GET['success'] ?? '';

$old_email = $_SESSION['old_login_email'] ?? '';
unset($_SESSION['old_login_email']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-sky-600 flex flex-col items-center justify-center p-4 antialiased selection:bg-sky-500 selection:text-white">

    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full border border-slate-100 flex flex-col justify-between">
        
        <div>
            <div class="flex items-center justify-center gap-2 mb-4">
                <img src="assets/logo-operin-blue.png" alt="Logo Operin" class="max-h-8 object-contain">
                <h2 class="text-2xl font-bold text-sky-600 tracking-tight">OperIn</h2>
            </div>

            <h1 class="text-sm font-medium text-slate-400 mb-6 text-center leading-snug">Silakan Menggunakan Email dan Password Anda.</h1>

            <?php if (!empty($error_message)) : ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl mb-4 text-xs text-center font-semibold">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)) : ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2.5 rounded-xl mb-4 text-xs text-center font-semibold">
                    <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="prosesLogin.php" class="space-y-4 text-xs font-bold text-slate-500">
                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Alamat Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($old_email) ?>" placeholder="Masukkan alamat email" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                
                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Kata Sandi Akun</label>
                    <input type="password" name="password" placeholder="Masukkan kata sandi" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>
                
                <div class="pt-2">
                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white py-2.5 rounded-xl font-bold transition-all shadow-md shadow-sky-600/10 cursor-pointer text-sm">
                        Login
                    </button>
                </div>
            </form>

            <p class="text-center text-xs text-slate-400 mt-6 font-medium">
                Belum memiliki akun? <a href="register.php" class="text-sky-600 font-bold hover:text-orange-500 transition-colors">Daftar di sini</a>
            </p>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-center gap-2">
            <img src="assets/logo-uns.png" alt="Logo UNS" class="max-h-20 object-contain opacity-80">
        </div>

    </div>

</body>
</html>