<?php
session_start();
require 'needLogin.php'; // Proteksi: Harus login terlebih dahulu
require 'require/koneksi.php';

$user_id = $_SESSION['user_id'];
$error_msg = $_GET['error'] ?? '';

// data default fakultas
$query_user = "SELECT faculty_id FROM users WHERE id = $user_id";
$result_user = mysqli_query($koneksi, $query_user);
$user_data = mysqli_fetch_assoc($result_user);
$default_faculty_id = $user_data['faculty_id'] ?? 0;

// dropdown
$result_faculties = mysqli_query($koneksi, "SELECT * FROM faculties ORDER BY nama_fakultas ASC");
$result_categories = mysqli_query($koneksi, "SELECT * FROM categories ORDER BY nama_kategori ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-3xl w-full mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs text-slate-400">
                <a href="produk.php" class="hover:text-sky-600 transition-colors">Beranda</a> 
                <span class="mx-2">/</span> 
                <span class="text-slate-600 font-medium">Tambah Produk</span>
            </p>
            <a href="produk.php" class="inline-flex items-center gap-1 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="border-b border-slate-200 pb-3 mb-6">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span class="w-1 h-5 bg-sky-500 rounded-xs"></span>
                Tambah Produk Baru
            </h1>
        </div>

        <?php if (!empty($error_msg)) : ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs font-semibold mb-4">
                <?= htmlspecialchars($error_msg) ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border border-slate-200/60 p-6 rounded-2xl shadow-xs">
            
            <form method="POST" action="prosesTambah.php" enctype="multipart/form-data" class="space-y-5 text-xs font-bold text-slate-500">
                
                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Nama Produk / Barang</label>
                    <input type="text" name="name" placeholder="Contoh: Jas Almamater UNS Ukuran L" required 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Harga Barang (Rp)</label>
                        <input type="number" name="price" placeholder="Contoh: 50000" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Keadaan / Kondisi Fisik</label>
                        <select name="kondisi" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-white focus:outline-none focus:border-sky-500 transition-all">
                            <option value="" disabled selected>-- Pilih Kondisi Barang --</option>
                            <option value="Baru">Baru</option>
                            <option value="Bekas">Bekas</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Kategori Produk</label>
                        <select name="category_id" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-white focus:outline-none focus:border-sky-500 transition-all">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <?php while ($c = mysqli_fetch_assoc($result_categories)) : ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nama_kategori']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-slate-400 font-medium">Lokasi COD</label>
                        <select name="faculty_id" required 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-white focus:outline-none focus:border-sky-500 transition-all">
                            <option value="" disabled>-- Pilih Fakultas --</option>
                            <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                                <option value="<?= $f['id'] ?>" <?= $f['id'] == $default_faculty_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($f['nama_fakultas']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Deskripsi Barang</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan secara rinci minus pemakaian, alasan dijual, kelengkapan barang, atau detail penting lainnya" required 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-slate-800 text-sm font-normal bg-slate-50/50 focus:outline-none focus:border-sky-500 focus:bg-white transition-all resize-none"></textarea>
                </div>

                <div>
                    <label class="block mb-1 text-slate-400 font-medium">Foto Barang</label>
                    <div class="border border-dashed border-slate-200 rounded-xl p-4 bg-slate-50/50 flex flex-col items-center justify-center gap-2">
                        <input type="file" name="image" accept="image/*" required 
                        class="text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:bg-white file:text-slate-700 hover:file:bg-slate-50 file:font-bold file:cursor-pointer">
                        <p class="text-[10px] font-medium text-slate-400">file: JPG, JPEG, PNG, atau WEBP.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="produk.php" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-center transition-all">
                        Batalkan
                    </a>
                    <button type="submit" class="px-5 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl transition-all shadow-md shadow-sky-600/10 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>
</html>