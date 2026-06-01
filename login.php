<?php
session_start();

//session
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? "dashboardAdmin.php" : "produk.php"));
    exit();
}

$error_message = $_GET['error'] ?? '';

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
<body class="min-h-screen bg-sky-600 flex items-center justify-center">

    <div class="bg-amber-50 rounded-2xl shadow-lg p-7 pb-10 w-96">
        
        <div class="flex items-center justify-center gap-2 mb-3">
            <img src="assets/logo-operin-blue.png" alt="Logo Operin" class="max-h-8">
            <h2 class="text-2xl font-bold text-sky-400">OperIn</h2>
        </div>

        <h1 class="text-lg font-semibold text-gray-700 mb-6 text-center">Masukkan Email dan Password Anda</h1>

        <?php if (!empty($error_message)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm text-center font-medium">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="prosesLogin.php">
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old_email) ?>" placeholder="Contoh: nama@gmail.com" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700">
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Kata Sandi</label>
                <input type="password" name="password" placeholder="Kata Sandi" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700">
            </div>
            <button type="submit" class="w-full bg-sky-500 text-white py-2 rounded-lg hover:bg-sky-600 transition-all font-semibold cursor-pointer">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6 pb-6">
            Belum punya akun? <a href="register.php" class="text-sky-400 hover:text-orange-500">Daftar di sini</a>
        </p>

        <div class="flex items-center justify-center">
            <img src="assets/logo-uns.png" alt="Logo UNS" class="max-h-20">
            <h2 class="text-2xl font-bold text-sky-400"></h2>
        </div>

    </div>

</body>
</html>