<?php
session_start();
require 'require/koneksi.php';
require 'needLogin.php';

$user_id = $_SESSION['user_id'];
$error_msg = $_GET['error'] ?? '';
$success_msg = $_GET['success'] ?? '';

$result_produk = mysqli_query($koneksi, "SELECT id, name FROM products WHERE user_id = $user_id ORDER BY id DESC");
$result_paket = mysqli_query($koneksi, "SELECT * FROM promo_packages ORDER BY price ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Promo - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 flex flex-col antialiased">

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-2xl w-full mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-6">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-2 h-5 bg-amber-500 rounded-xs"></span>
                Pengajuan Promosi
            </h1>
            <a href="produk.php" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Beranda
            </a>
        </div>

        <?php if ($error_msg) : ?><div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold"><?= htmlspecialchars($error_msg) ?></div><?php endif; ?>
        <?php if ($success_msg) : ?><div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-bold"><?= htmlspecialchars($success_msg) ?></div><?php endif; ?>

        <div class="bg-sky-50 border border-sky-200 p-4 rounded-xl mb-6 text-xs text-sky-800">
            <p class="font-bold mb-1">Instruksi Pembayaran:</p>
            <p>Silakan transfer nominal sesuai paket yang dipilih ke rekening <strong>Mandiri 138-000-123456</strong> a.n. Admin OperIn Ganteng.</p>
        </div>

        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-xs">
            <form method="POST" action="prosesAjukanPromo.php" enctype="multipart/form-data" class="space-y-5 text-xs font-bold text-slate-600">
                
                <div>
                    <label class="block mb-1.5 text-slate-500">Pilih Barang Anda</label>
                    <select name="product_id" required class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:border-amber-500 focus:outline-none text-sm font-normal text-slate-700">
                        <option value="" disabled selected>-- Pilih Barang --</option>
                        <?php while ($p = mysqli_fetch_assoc($result_produk)) : ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-1.5 text-slate-500">Pilih Paket Promosi</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <?php while ($paket = mysqli_fetch_assoc($result_paket)) : ?>
                            <label class="relative flex flex-col p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-amber-500 hover:bg-amber-50/50 transition-all text-center">
                                <input type="radio" name="package_id" value="<?= $paket['id'] ?>" required class="absolute top-3 right-3 text-amber-500">
                                <span class="text-sm font-bold text-slate-800 mb-1"><?= htmlspecialchars($paket['package_name']) ?></span>
                                <span class="text-[10px] text-slate-400 font-medium mb-2">Durasi <?= $paket['duration_days'] ?> Hari</span>
                                <span class="mt-auto text-amber-600 font-bold text-sm">Rp<?= number_format($paket['price'], 0, ',', '.') ?></span>
                            </label>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div>
                    <label class="block mb-1.5 text-slate-500">Upload Bukti Transfer (Screenshot)</label>
                    <div class="border border-dashed border-slate-300 rounded-xl p-4 bg-slate-50 flex items-center justify-center">
                        <input type="file" name="bukti_bayar" accept="image/*" required class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200 cursor-pointer">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 rounded-xl shadow-md cursor-pointer text-sm">
                        Kirim Bukti & Ajukan Promosi
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>