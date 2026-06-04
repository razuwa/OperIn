<?php
session_start();
require 'require/koneksi.php';

$query_faculties = "SELECT * FROM faculties ORDER BY nama_fakultas ASC";
$result_faculties = mysqli_query($koneksi, $query_faculties);

$error_message = $_GET['error'] ?? '';
$success_message = $_GET['success'] ?? '';

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
<body class="min-h-screen bg-sky-600 flex flex-col items-center justify-center p-4 md:p-6 antialiased selection:bg-sky-500 selection:text-white">

    <div class="bg-white rounded-2xl shadow-xl px-8 py-5 w-full max-w-md border border-slate-100 flex flex-col justify-between my-4">
        
        <div>
            <div class="flex items-center justify-center gap-2 mb-4">
                <img src="assets/logo-operin-blue.png" alt="Logo Operin" class="max-h-8 object-contain">
                <h2 class="text-2xl font-bold text-sky-600 tracking-tight">OperIn</h2>
            </div>
            
            <?php if (!empty($error_message)) : ?>
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl mb-4 text-xs text-center font-semibold">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="prosesRegister.php" class="space-y-4 text-xs font-bold text-slate-500">
                
                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($old['nama'] ?? '') ?>" placeholder="Nama lengkap Anda" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Alamat Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="Masukkan alamat email" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Kata Sandi</label>
                        <input type="password" name="password" placeholder="Minimal 6 karakter" required minlength="6"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Ulangi Kata Sandi</label>
                        <input type="password" name="confirm_password" placeholder="Konfirmasi kata sandi" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" value="<?= htmlspecialchars($old['whatsapp'] ?? '') ?>" placeholder="Contoh: 08123456789" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Fakultas Asal</label>
                    <select name="faculty_id" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-white focus:outline-none focus:border-sky-500 transition-all">
                        <option value="" disabled <?= !isset($old['faculty_id']) ? 'selected' : '' ?>>-- Pilih Fakultas Asal Anda --</option>
                        <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                            <option value="<?= $f['id'] ?>" <?= (isset($old['faculty_id']) && $old['faculty_id'] == $f['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($f['nama_fakultas']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white text-sm py-2.5 rounded-xl font-bold transition-all shadow-md shadow-sky-600/10 cursor-pointer">
                        Daftar Akun Sekarang
                    </button>
                </div>
            </form>

            <p class="text-center text-xs text-slate-400 mt-4 font-medium">
                Sudah memiliki akun? <a href="login.php" class="text-sky-600 font-bold hover:text-orange-500 transition-colors">Login di sini</a>
            </p>
        </div>

        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-center gap-2">
            <img src="assets/logo-uns.png" alt="Logo UNS" class="max-h-20 object-contain opacity-80">
        </div>

    </div>

</body>
</html>