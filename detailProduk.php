<?php
session_start();
require 'require/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_logged_in = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;

$query = "SELECT p.*, f.nama_fakultas AS fakultas, u.nama AS nama_penjual, u.whatsapp, c.nama_kategori AS kategori
          FROM products p
          JOIN faculties f ON p.faculty_id = f.id
          JOIN users u ON p.user_id = u.id
          JOIN categories c ON p.category_id = c.id
          WHERE p.id = $id";

$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: produk.php?error=Produk tidak ditemukan");
    exit();
}

$p = mysqli_fetch_assoc($result);

if ($is_logged_in) {
    $button_url = "https://wa.me/" . $p['whatsapp'] . "?text=" . urlencode("Halo " . $p['nama_penjual'] . ", saya tertarik dengan produk " . $p['name'] . " di OperIn.");
} else {
    $button_url = "login.php?error=" . urlencode("Silakan login terlebih dahulu untuk menghubungi penjual.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($p['name']) ?> - OperIn</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <?php include 'components/navbar.php'; ?>

    <main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <p class="text-xs text-slate-400">
                <a href="produk.php" class="hover:text-sky-600 transition-colors">Beranda</a> 
                <span class="mx-2">/</span> 
                <span class="text-slate-600 font-medium truncate"><?= htmlspecialchars($p['name']) ?></span>
            </p>
            <a href="produk.php" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            
            <div class="md:col-span-5 bg-white p-4 rounded-2xl border border-slate-200/60 shadow-xs">
                <div class="aspect-square w-full rounded-xl overflow-hidden bg-slate-100 border border-slate-100">
                    <img src="<?= htmlspecialchars($p['image']) ?>" 
                         alt="<?= htmlspecialchars($p['name']) ?>"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                </div>
            </div>

            <div class="md:col-span-7 space-y-6">
                
                <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-xs space-y-4">
                    <div class="space-y-1">
                        <span class="inline-block bg-sky-50 text-sky-600 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border border-sky-100">
                            <?= htmlspecialchars($p['kategori']) ?>
                        </span>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($p['name']) ?></h1>
                    </div>

                    <p class="text-3xl font-extrabold text-amber-600 tracking-tight">
                        Rp<?= number_format($p['price'], 0, ',', '.') ?>
                    </p>

                    <div class="flex items-center gap-3 pt-2 border-t border-slate-100 text-xs text-slate-500">
                        <div class="flex items-center gap-1">
                            <span class="font-medium text-slate-400">Kondisi:</span>
                            <span class="px-2 py-0.5 font-bold rounded-md <?= $p['kondisi'] === 'Baru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-700 border border-slate-200' ?> border">
                                <?= htmlspecialchars($p['kondisi']) ?>
                            </span>
                        </div>
                        <div class="w-px h-4 bg-slate-200"></div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium text-slate-700"><?= htmlspecialchars($p['fakultas']) ?> (UNS)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-xs space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">Deskripsi Barang</h2>
                    <p class="text-sm text-slate-600 leading-relaxed font-normal">
                        <?= nl2br(htmlspecialchars($p['description'])) ?>
                    </p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-slate-200/60 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-linear-to-r from-white to-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center font-bold text-sm border border-sky-200 shadow-xs">
                            <?= strtoupper(substr($p['nama_penjual'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pemilik Lapak</p>
                            <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($p['nama_penjual']) ?></p>
                        </div>
                    </div>
                    
                    <a href="<?= $button_url ?>" 
                       <?= $is_logged_in ? 'target="_blank"' : '' ?>
                       class="w-full sm:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-600/10 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397 0 11.93 0c3.165.001 6.14 1.233 8.377 3.474 2.237 2.241 3.467 5.223 3.466 8.393-.004 6.582-5.34 11.93-11.873 11.93-1.996-.001-3.957-.502-5.69-1.463L0 24zm6.59-4.846c1.6.95 3.198 1.451 4.743 1.452 5.366 0 9.73-4.322 9.733-9.634.001-2.573-1.001-4