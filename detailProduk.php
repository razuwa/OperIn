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

                    <p class="text-3xl font-bold text-amber-600 tracking-tight">
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
                            <span class="font-medium text-slate-700"><?= htmlspecialchars($p['fakultas']) ?></span>
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
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M3.50002 12C3.50002 7.30558 7.3056 3.5 12 3.5C16.6944 3.5 20.5 7.30558 20.5 12C20.5 16.6944 16.6944 20.5 12 20.5C10.3278 20.5 8.77127 20.0182 7.45798 19.1861C7.21357 19.0313 6.91408 18.9899 6.63684 19.0726L3.75769 19.9319L4.84173 17.3953C4.96986 17.0955 4.94379 16.7521 4.77187 16.4751C3.9657 15.176 3.50002 13.6439 3.50002 12ZM12 1.5C6.20103 1.5 1.50002 6.20101 1.50002 12C1.50002 13.8381 1.97316 15.5683 2.80465 17.0727L1.08047 21.107C0.928048 21.4637 0.99561 21.8763 1.25382 22.1657C1.51203 22.4552 1.91432 22.5692 2.28599 22.4582L6.78541 21.1155C8.32245 21.9965 10.1037 22.5 12 22.5C17.799 22.5 22.5 17.799 22.5 12C22.5 6.20101 17.799 1.5 12 1.5ZM14.2925 14.1824L12.9783 15.1081C12.3628 14.7575 11.6823 14.2681 10.9997 13.5855C10.2901 12.8759 9.76402 12.1433 9.37612 11.4713L10.2113 10.7624C10.5697 10.4582 10.6678 9.94533 10.447 9.53028L9.38284 7.53028C9.23954 7.26097 8.98116 7.0718 8.68115 7.01654C8.38113 6.96129 8.07231 7.046 7.84247 7.24659L7.52696 7.52195C6.76823 8.18414 6.3195 9.2723 6.69141 10.3741C7.07698 11.5163 7.89983 13.314 9.58552 14.9997C11.3991 16.8133 13.2413 17.5275 14.3186 17.8049C15.1866 18.0283 16.008 17.7288 16.5868 17.2572L17.1783 16.7752C17.4313 16.5691 17.5678 16.2524 17.544 15.9269C17.5201 15.6014 17.3389 15.308 17.0585 15.1409L15.3802 14.1409C15.0412 13.939 14.6152 13.9552 14.2925 14.1824Z" fill="#ffffff"></path> </g></svg>
                        Hubungi via WhatsApp
                    </a>
                </div>

            </div>
        </div>
    </main>

</body>
</html>