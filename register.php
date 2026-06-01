<?php
session_start();
require 'require/koneksi.php';

$query_faculties = "SELECT * FROM faculties ORDER BY nama_fakultas ASC";
$result_faculties = mysqli_query($koneksi, $query_faculties);

$error_message = $_GET['error'] ?? '';
$success_message = $_GET['success'] ?? '';

// ambil data lama dari sesion
$old = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-sky-600 flex items-center justify-center p-6">

    <div class="bg-amber-50 rounded-2xl shadow-lg p-7 pb-10 w-full max-w-md">
        <div class="flex items-center justify-center gap-2 mb-3">
            <img src="assets/logo-operin-blue.png" alt="Logo Operin" class="max-h-8">
            <h2 class="text-2xl font-bold text-sky-400">OperIn</h2>
        </div>
        <h1 class="text-lg font-semibold text-gray-700 mb-6 text-center">Daftar Akun Baru</h1>

        <?php if (!empty($error_message)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm text-center font-medium"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST" action="prosesRegister.php">
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($old['nama'] ?? '') ?>" placeholder="Nama Lengkap Anda" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="Contoh: nama@gmail.com" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kata Sandi</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required minlength="6"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Ulangi Kata Sandi</label>
                <input type="password" name="confirm_password" placeholder="Masukkan kembali kata sandi" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">No. WhatsApp</label>
                <input type="text" name="whatsapp" value="<?= htmlspecialchars($old['whatsapp'] ?? '') ?>" placeholder="Contoh: 08123456789" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
            </div>

            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Fakultas Asal</label>
                <select name="faculty_id" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700 bg-white">
                    <option value="" disabled <?= !isset($old['faculty_id']) ? 'selected' : '' ?>>-- Pilih Fakultas --</option>
                    <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                        <option value="<?= $f['id'] ?>" <?= (isset($old['faculty_id']) && $old['faculty_id'] == $f['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['nama_fakultas']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="w-full bg-sky-500 text-white py-2 rounded-lg hover:bg-sky-600 transition-all font-semibold cursor-pointer shadow-md">
                Daftar Sekarang
            </button>
        </form>
        </div>
</body>
</html>