<?php 
session_start();
require 'needLogin.php';
require 'require/koneksi.php';

// ambil data fakultas
$query_faculties = "SELECT * FROM faculties ORDER BY nama_fakultas ASC";
$result_faculties = mysqli_query($koneksi, $query_faculties);

if (!$result_faculties) {
    die("Gagal memuat data master fakultas: " . mysqli_error($koneksi));
}

// ambil data kategori
$query_categories = "SELECT * FROM categories ORDER BY id ASC";
$result_categories = mysqli_query($koneksi, $query_categories);

if (!$result_categories) {
    die("Gagal memuat data master kategori: " . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-sky-600 flex items-center justify-center p-6">

    <div class="bg-amber-50 rounded-2xl shadow-lg p-10 w-full max-w-[500px]">
        <div class="flex items-center justify-center gap-2 mb-2">
            <img src="assets/logo-operin-blue.png" alt="Logo Operin" class="max-h-8">
            <h2 class="text-2xl font-bold text-sky-600">OperIn</h2>
        </div>

        <h1 class="text-lg font-semibold text-gray-700 mb-2 text-center">Tambah Produk Baru</h1>

        <form method="POST" action="prosesTambah.php" enctype="multipart/form-data">
            
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Nama Produk</label>
                <input type="text" name="name" placeholder="Contoh: Rice Cooker Tatung" required
                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700">
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Harga (Rp)</label>
                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:border-sky-400 bg-gray-100">
                    <span class="bg-gray-200 px-3 py-2 text-gray-500 text-sm border-r border-gray-300">Rp</span>
                    <input type="number" name="price" placeholder="185000" required
                        class="w-full px-4 py-2 bg-gray-100 focus:outline-none text-gray-700">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kategori Produk</label>
                <select name="category_id" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 bg-gray-100 text-gray-700">
                    <option value="" disabled selected>-- Pilih Kategori Barang --</option>
                    <?php while ($c = mysqli_fetch_assoc($result_categories)) : ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nama_kategori']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Fakultas (Lokasi COD)</label>
                <select name="faculty_id" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 bg-gray-100 text-gray-700">
                    <option value="" disabled selected>-- Pilih Lokasi Fakultas --</option>
                    <?php while ($f = mysqli_fetch_assoc($result_faculties)) : ?>
                        <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nama_fakultas']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kondisi</label>
                <select name="kondisi" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 bg-gray-100 text-gray-700">
                    <option value="Baru">Baru</option>
                    <option value="Bekas">Bekas</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Ceritakan kondisi barang atau alasan dijual..." required
                class="w-full bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-sky-400 text-gray-700"></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-sm text-gray-600 mb-1">Upload Gambar</label>
                <input type="file" name="image" accept="image/*" required
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 cursor-pointer">
            </div>

            <div class="flex gap-3">
                <a href="produk.php"
                class="w-full text-center border bg-gray-100 border-gray-300 text-gray-600 py-2 rounded-lg hover:bg-gray-300 transition-all font-semibold">
                    Batal
                </a>
                <button type="submit"
                class="w-full bg-sky-500 text-white py-2 rounded-lg hover:bg-sky-600 transition-all font-semibold cursor-pointer">
                    Tambah Produk
                </button>
            </div>
        </form>
    </div>
</body>
</html>